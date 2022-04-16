<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use PDF;
use PhpParser\Node\Stmt\Else_;

class SaleController extends Controller
{
    public function saleList()
    {
        $agentlist = DB::table('travel_agents')->get();
        $Queries = array();
        $list = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->join('travel_agents', 'sales.agent_id', '=', 'travel_agents.id')
            ->select(
                'sales.*',
                'customers.name as customer_name',
                'customers.status as customer_status',
                'travel_agents.Name',
                'travel_agents.Commission_persent',
                'sales.commission_amount'
            )

            ->where('sales.branch', Auth::user()->branch)
            ->orderByDesc('sales.id')
            ->paginate(20);
        $net_total = DB::table('sales')->where('branch', Auth::user()->branch)->sum('net_total');
        $net_qty = DB::table('sales')->where('branch', Auth::user()->branch)->sum('net_qty');
        $net_pcs = DB::table('sales')->where('branch', Auth::user()->branch)->sum('net_pcs');
        $customers = DB::table('customers')->where('branch', Auth::user()->branch)->get();
        $commissionsum = DB::table('sales')->sum('commission_amount');
        $zakat = DB::table('sales')->sum('zakat');
        $sadqa = DB::table('sales')->sum('sadqa');
        return view('sales.list', array('salelist' => $list, 'queries' => $Queries, 'customers' => $customers, 'net_total' => $net_total, 'net_pcs' => $net_pcs, 'net_qty' => $net_qty, 'agentlist' => $agentlist, 'commissionsum' => $commissionsum, 'zakat' => $zakat, 'sadqa' => $sadqa));
    }
    public function newsale()
    {
        $customers = DB::table('customers')->where('status', 1)->where('branch', Auth::user()->branch)->get();
        $invoice_number = DB::table('sales')->max('id') + 1;
        $agentlist = DB::table('travel_agents')->get();
        $agentsum = DB::table('sales')->sum('commission_amount');


        $comapany = DB::table('companyinfo')->first();

        if ($comapany->business_type == 0) {
            $items = DB::table('items')->where('branch', Auth()->user()->branch)
                ->whereIn('category', [4, 5, 6])
                ->get();
            return view('sales.new', array('customers' => $customers, 'invoice_number' => $invoice_number, 'items' => $items, 'agentlist' => $agentlist, 'agentsum' => $agentsum));
        } else {
            $item_menus = DB::table('item_menu')->get();
            // for ($i=0; $i < 200; $i++) {
            //     $dummyItems = array(
            //         'code'=>3434,
            //         'name'=>'item'.rand(),
            //         'pic' => '/item_pic/20211129093702.png
            //         ',
            //         'purchase_price'=> rand(100,1000),
            //         'sele_price'=> rand(200,2000),
            //         'stock' => rand(10,100),
            //         'category' => rand(2,7),
            //         'created_at' => date('Y-m-d H:i:s'),
            //         'branch' => Auth::user()->branch,
            //         'item_menu' => 6,
            //         'linked_items' => '',
            //         'item_type' => 3
            //     );
            //     DB::table('items')->insert($dummyItems);
            // }
            // exit;
            $menuWiseItems = [];
           
            
            $i = 1;
            foreach ($item_menus as $menu) {

                $items = DB::table('items')
                    // ->whereIn('category',[4,5,6])
                    ->where('item_menu', $menu->id)
                    ->get();

                if (count($items) > 0) {
                    $items->menuName = $menu->name;
                    $items->tabId = 'tab-content' . $i;
                    $menuWiseItems[] = $items;
                }
                $i++;
            }
            return view('sales.foodInvoice', array('customers' => $customers, 'invoice_number' => $invoice_number, 'menuWiseItems' => $menuWiseItems, 'agentlists' => $agentlist, 'agentsum' => $agentsum));
        }
    }

    public function store(Request $request)
    {

        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        $sale = DB::table('sales')->where('invoice_number', $request->invoice_number)->first();
        $agentName = DB::table('travel_agents')->get();
        $agentsum = DB::table('sales')->sum('commission_amount');
        if (!empty($sale)) {
            return response()->json(['success' => false, 'message' => 'Sale Invoice already exits..', 'redirectUrl' => '/sales/list'], 200);
        }
        // $tablno = DB::table('sales')->where('table_no', $request->table_no)->first();
        // if (!empty($tablno)) {
        //     return response()->json(['success' => false, 'message' => 'Invoice With this table number already exist.', 'redirectUrl' => '/sales/list'], 200);
        // }
        $companyBusinessType = DB::table('companyinfo')->first();
        $validator = [];
        if ($companyBusinessType->business_type == 0) {
            $validator = Validator::make(
                $request->all(),
                [
                    'invoice_number' => 'required',
                    'invoice_date' => 'required',
                    'customer_id' => 'required|numeric',
                    'net_total' => 'required|numeric|min:0|not_in:0',
                    'net_pcs' => 'required|numeric|min:0|not_in:0',
                    'net_qty' => 'required|numeric|min:0|not_in:0',
                ],
                [
                    'invoice_number.required' => 'The Invoice #  is required.',
                    'invoice_date.required' => 'The Invoice Date  is required.',
                    'customer_id.required' => 'The Customer   is required.',
                    'net_total.required' => 'Net Total   is required.',
                    'net_pcs.required' => 'Pcs is required.',
                    'net_qty.required' => 'Qty is required.',
                ]
            );
        } else {
            $validator = Validator::make(
                $request->all(),
                [
                    'invoice_number' => 'required',
                    'invoice_date' => 'required',
                    'net_total' => 'required|numeric|min:0|not_in:0',
                    'net_qty' => 'required|numeric|min:0|not_in:0',
                ],
                [
                    'invoice_number.required' => 'The Invoice #  is required.',
                    'invoice_date.required' => 'The Invoice Date  is required.',
                    'net_total.required' => 'Net Total   is required.',
                    'net_qty.required' => 'Qty is required.',
                ]
            );
        }


        if ($validator->fails()) {
            //$response['message'] = $validator->messages();
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 422);
        } else {
            $status = 0;
            $customer = [];
            if (isset($request->paid_amount)) {
                if ($request->remiander < 0) {
                    return response()->json(['success' => false, 'message' => 'Fill paid amount field properly.'], 200);
                } else {
                    $status = 1;
                }
            }
            $items_detail = array();

            $item_ids = $request->item_id;
            $item_prices = $request->item_price;
            $item_qtys = $request->item_qty;
            $amounts = $request->amount;
            $agent_name = $request->agent_name;
            $commisssion = $request->commisssion;
            $commission_amount = $request->commission_amount;
            $i = 0;
            foreach ($item_ids as $item) {
                $itemid = $item_ids[$i];
                if($status == 0){
                    DB::table('items')->where('id',$itemid)->update(['is_booked'=>1]);
                }
                else{
                    DB::table('items')->where('id',$itemid)->update(['is_booked'=>0]);
                }
                $price = $item_prices[$i];
                $qty = $item_qtys[$i];
                $amount = $amounts[$i];
                $commisssion = $commisssion;
                $commission_amount = $commission_amount;
                if ($amount > 0) {
                    $items_detail[] = array(
                        'item_id' => $itemid,
                        'item_price' => $price,
                        'item_qty' => $qty,
                        'amount' => $amount,
                        'commisssion' => $commisssion,
                        'commission_amount' => $commission_amount,
                    );
                }

                $i++;
            }
            if (isset($request->customer_id)) {
                $customer = DB::table('customers')->where('id', $request->customer_id)->first();
            } else {
                $customer = DB::table('customers')->where('status', '=', 2)->first();
            }

            /**
             * Insert Double entry
             *Cash A/c Debit
             *Customer A/c  Credit
             */
            if (!empty($request->paid_amount)) {
                $debit = array(
                    'voucher_date' => $request->invoice_date,
                    'voucher_number' => $request->invoice_number,
                    'general_ledger_account_id' => Config::get('constants.CASH_ACCOUNT_GENERAL_LEDGER'),
                    'note' => $request->note,
                    'debit' => $request->net_total,
                    'credit' => 0,
                    'branch' => Auth::user()->branch,
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $this->insertDoubleEntry($debit);
                $credit = array(
                    'voucher_date' => $request->invoice_date,
                    'voucher_number' => $request->invoice_number,
                    'general_ledger_account_id' => $customer->general_ledger_account_id,
                    'note' => $request->note,
                    'debit' => 0,
                    'credit' => $request->net_total,
                    'branch' => Auth::user()->branch,
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $this->insertDoubleEntry($credit);
            }


            /**
             * Insert Double entry
             *Customer A/c Debit
             *Sale A/c  Credit
             */
            // $customer = DB::table('customers')->where('id', $request->customer_id)->first();
            $debit = array(
                'voucher_date' => $request->invoice_date,
                'voucher_number' => $request->invoice_number,
                'general_ledger_account_id' => $customer->general_ledger_account_id,
                'note' => $request->note,
                'debit' => $request->net_total,
                'credit' => 0,
                'branch' => Auth::user()->branch,
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->insertDoubleEntry($debit);
            $credit = array(
                'voucher_date' => $request->invoice_date,
                'voucher_number' => $request->invoice_number,
                'general_ledger_account_id' => Config::get('constants.SALE_ACCOUNT_GENERAL_LEDGER'),
                'note' => $request->note,
                'debit' => 0,
                'credit' => $request->net_total,
                'branch' => Auth::user()->branch,
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->insertDoubleEntry($credit);

            /**
             * Insert Stock Entry for each time
             * 1.get category of item
             *2.get linked general ledger account id from category table
             * Stock Amount will be reduced according to purchase price of the item
             */
            foreach ($items_detail as $_detail) {
                $item = DB::table('items')->where('id', $_detail['item_id'])->first();
                $category = DB::table('category')->where('id', $item->category)->first();
                $company = DB::table('companyinfo')->first();
                if ($company->stock_calculation == 0) {
                    $credit = array(
                        'voucher_date' => $request->invoice_date,
                        'voucher_number' => $request->invoice_number,
                        'general_ledger_account_id' => $category->general_ledger_account_id,
                        'note' => $item->name . ' ' . $_detail['item_qty'] . ' @ ' . $_detail['item_price'],
                        'debit' => 0,
                        'credit' => $_detail['amount'],
                        'branch' => Auth::user()->branch,
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    $this->insertDoubleEntry($credit);
                }
                $record = DB::table('items')->where('id', $_detail['item_id'])->first();
                $unseri = unserialize($record->linked_items);
                if (!empty($unseri)) {

                    if (count($unseri) > 0) {
                        foreach ($unseri as $value) {
                            $qty = $value['item_qty'] * $_detail['item_qty'];
                            $stock  = array(
                                'voucher_date' => $request->invoice_date,
                                'voucher_number' => $request->invoice_number,
                                'transaction_type' => '-',
                                'general_ledger_account_id' => $category->general_ledger_account_id,
                                'item_qty' => $qty,
                                'item_id' => $value['item_id'],
                                'branch' => Auth::user()->branch,
                                'created_at' => date('Y-m-d H:i:s'),
                            );
                            $this->stockManagementEntry($stock);
                        }
                    }
                }
                // else {

                $stock  = array(
                    'voucher_date' => $request->invoice_date,
                    'voucher_number' => $request->invoice_number,
                    'transaction_type' => '-',
                    'general_ledger_account_id' => $category->general_ledger_account_id,
                    'item_qty' => $_detail['item_qty'],
                    'item_id' => $_detail['item_id'],
                    'branch' => Auth::user()->branch,
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $this->stockManagementEntry($stock);
                // }
            }
            $sale = array(
                'net_total' => $request->net_total,
                'gross_total' => $request->gross_total,
                'paid_amount' => $request->paid_amount,
                'discount' => $request->discount,
                'tax' => $request->tax,
                'remainder' => $request->remiander,
                'created_at' => date('Y-m-d H:i:s'),
                'customer_id' => $customer->id,
                'items_detail' => serialize($items_detail),
                'invoice_number' => $request->invoice_number,
                'note' => $request->note,
                'net_pcs' => $request->net_pcs,
                'net_qty' => $request->net_qty,
                'status' => $status,
                'invoice_date' => $request->invoice_date,
                'branch' => Auth::user()->branch,
                'table_no' => $request->table_no,
                'agent_id' => $request->agent_id,
                'commisssion' => $request->commisssion,
                'commission_amount' => $request->commission_amount,
                'sadqa' => $request->sadqa,
                'zakat' => $request->zakat,
            );
            $idForPdf = DB::table('sales')->insertGetId($sale);
            /***
             * add entry to transaction log
             *
             */

            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $request->invoice_number,
                'transaction_action' => 'Add',
                'transaction_detail' => serialize($sale),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Sale Invoice',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            $dataBack = ['success' => true, 'message' => 'Sale Invoice added successfully..', 'redirect' => false];
            if (!empty($request->paid_amount)) {
                $dataBack['print'] = "/sales/pdf/{$idForPdf}";
            }
            return response()->json($dataBack, 200);
        }
    }
    public function edit($id)
    {
        $sale = DB::table('sales')->where('id', $id)->first();
        $customers = DB::table('customers')->where('status', 1)->where('branch', Auth::user()->branch)->where('status', '!=', 2)->get();
        $items = DB::table('items')->where('branch', Auth()->user()->branch)->get();
        return view('sales.new', array('sale' => $sale, 'customers' => $customers, 'items' => $items));
    }
    public function update(Request $request)
    {
        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        $companyBusinessType = DB::table('companyinfo')->first();

        $validator = Validator::make(
            $request->all(),
            [
                'invoice_number' => 'required',
                'invoice_date' => 'required',
                'net_total' => 'required|numeric|min:0|not_in:0',
                'net_qty' => 'required|numeric|min:0|not_in:0',
            ],
            [
                'invoice_number.required' => 'The Invoice #  is required.',
                'invoice_date.required' => 'The Invoice Date  is required.',
                'net_total.required' => 'Net Total   is required.',
                'net_qty.required' => 'Qty is required.',
            ]
        );

        if ($validator->fails()) {
            //$response['message'] = $validator->messages();
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 422);
        } else {

            $status = 0;
            $customer = array();
            if (isset($request->paid_amount)) {
                if ($request->remiander < 0) {
                    return response()->json(['success' => false, 'message' => 'Fill paid amount field properly.'], 200);
                } else {
                    $status = 1;
                }
            }
            $items_detail = array();
            $item_ids = $request->item_id;
            $item_prices = $request->item_price;
            $item_qtys = $request->item_qty;
            $amounts = $request->amount;
            $i = 0;
            foreach ($item_ids as $item) {
                $itemid = $item_ids[$i];
                if($status == 0){
                    DB::table('items')->where('id',$itemid)->update(['is_booked'=>1]);
                }
                else{
                    DB::table('items')->where('id',$itemid)->update(['is_booked'=>0]);
                }
                $price = $item_prices[$i];
                $qty = $item_qtys[$i];
                $amount = $amounts[$i];
                if ($amount > 0) {
                    $items_detail[] = array(
                        'item_id' => $itemid,
                        'item_price' => $price,
                        'item_qty' => $qty,
                        'amount' => $amount,
                    );
                }

                $i++;
            }
            if (isset($request->customer_id)) {
                $customer = DB::table('customers')->where('id', $request->customer_id)->first();
            } else {
                $customer = DB::table('customers')->where('status', '=', 2)->first();
            }




            /**Delete first all entries from General Ledger Transactions Table
             * Insert Double entry
             *Customer A/c Debit
             *Sale A/c  Credit
             */
            $this->deleteDoubleEntry($request->invoice_number);
            $this->stockManagementEntryDelete($request->invoice_number);
            /**
             * Insert Double entry
             *Cash A/c Debit
             *Customer A/c  Credit
             */
            if (!empty($request->paid_amount)) {
                $debit = array(
                    'voucher_date' => $request->invoice_date,
                    'voucher_number' => $request->invoice_number,
                    'general_ledger_account_id' => Config::get('constants.CASH_ACCOUNT_GENERAL_LEDGER'),
                    'note' => $request->note,
                    'debit' => $request->net_total,
                    'credit' => 0,
                    'branch' => Auth::user()->branch,
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $this->insertDoubleEntry($debit);
                $credit = array(
                    'voucher_date' => $request->invoice_date,
                    'voucher_number' => $request->invoice_number,
                    'general_ledger_account_id' => $customer->general_ledger_account_id,
                    'note' => $request->note,
                    'debit' => 0,
                    'credit' => $request->net_total,
                    'branch' => Auth::user()->branch,
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $this->insertDoubleEntry($credit);
            }

            // $customer = DB::table('customers')->where('id', $request->customer_id)->first();
            $debit = array(
                'voucher_date' => $request->invoice_date,
                'voucher_number' => $request->invoice_number,
                'general_ledger_account_id' => $customer->general_ledger_account_id,
                'note' => $request->note,
                'debit' => $request->net_total,
                'credit' => 0,
                'branch' => Auth::user()->branch,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->insertDoubleEntry($debit);
            $credit = array(
                'voucher_date' => $request->invoice_date,
                'voucher_number' => $request->invoice_number,
                'general_ledger_account_id' => Config::get('constants.SALE_ACCOUNT_GENERAL_LEDGER'),
                'note' => $request->note,
                'debit' => 0,
                'credit' => $request->net_total,
                'branch' => Auth::user()->branch,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->insertDoubleEntry($credit);
            /**
             * Insert Stock Entry for each time
             * 1.get category of item
             *2.get linked general ledger account id from category table
             */
            foreach ($items_detail as $_detail) {
                $item = DB::table('items')->where('id', $_detail['item_id'])->first();
                $category = DB::table('category')->where('id', $item->category)->first();
                $company = DB::table('companyinfo')->first();
                if ($company->stock_calculation == 0) {
                    $credit = array(
                        'voucher_date' => $request->invoice_date,
                        'voucher_number' => $request->invoice_number,
                        'general_ledger_account_id' => $category->general_ledger_account_id,
                        'note' => $item->name . ' ' . $_detail['item_qty'] . ' @ ' . $_detail['item_price'],
                        'debit' => 0,
                        'credit' => $_detail['amount'],
                        'branch' => Auth::user()->branch,
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $this->insertDoubleEntry($credit);
                }
                $record = DB::table('items')->where('id', $_detail['item_id'])->first();
                $unseri = unserialize($record->linked_items);
                if (!empty($unseri)) {

                    if (count($unseri) > 0) {
                        foreach ($unseri as $value) {
                            $qty = $value['item_qty'] * $_detail['item_qty'];
                            $stock  = array(
                                'voucher_date' => $request->invoice_date,
                                'voucher_number' => $request->invoice_number,
                                'transaction_type' => '-',
                                'general_ledger_account_id' => $category->general_ledger_account_id,
                                'item_qty' => $qty,
                                'item_id' => $value['item_id'],
                                'branch' => Auth::user()->branch,
                                'created_at' => date('Y-m-d H:i:s'),
                            );
                            $this->stockManagementEntry($stock);
                        }
                    }
                }
                $stock  = array(
                    'voucher_date' => $request->invoice_date,
                    'voucher_number' => $request->invoice_number,
                    'transaction_type' => '-',
                    'general_ledger_account_id' => $category->general_ledger_account_id,
                    'item_qty' => $_detail['item_qty'],
                    'item_id' => $_detail['item_id'],
                    'branch' => Auth::user()->branch,
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $this->stockManagementEntry($stock);
            }
            $sale = array(
                'net_total' => $request->net_total,
                'gross_total' => $request->gross_total,
                'paid_amount' => $request->paid_amount,
                'discount' => $request->discount,
                'remainder' => $request->remiander,
                'tax' => $request->tax,
                'net_pcs' => $request->net_pcs,
                'net_qty' => $request->net_qty,
                'updated_at' => date('Y-m-d H:i:s'),
                'customer_id' => $customer->id,
                'items_detail' => serialize($items_detail),
                'invoice_number' => $request->invoice_number,
                'note' => $request->note,
                'status' => $status,
                'invoice_date' => $request->invoice_date,
                'branch' => Auth::user()->branch,
                'table_no' => $request->table_no,

            );
            DB::table('sales')->where('id', $request->id)->update($sale);
            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $request->invoice_number,
                'transaction_action' => 'Update',
                'transaction_detail' => serialize($sale),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Sale Invoice',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            $dataBack = ['success' => true, 'message' => 'Sale Invoice Updated successfully..', 'redirect' => false, 'requestData' => $request->all()];
            if (!empty($request->paid_amount)) {
                $dataBack['print'] = "/sales/pdf/{$request->id}";
            }
            return response()->json($dataBack, 200);
        }
    }

    public function delete($id)
    {
        $sale = DB::table('sales')->where('id', $id)->first();
        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        $this->deleteDoubleEntry($sale->invoice_number);
        DB::table('sales')->where('invoice_number', $sale->invoice_number)->delete();
        $log = array(
            'user_id' => Auth::user()->id,
            'voucher_number' => $sale->invoice_number,
            'transaction_action' => 'Delete',
            'transaction_detail' => serialize($sale),
            'branch' => Auth::user()->branch,
            'transaction_type' => 'Sale Invoice',
            'created_at' => date('Y-m-d H:i:s'),
        );
        $this->addTransactionLog($log);
        $this->stockManagementEntryDelete($sale->invoice_number);
        return response()->json(['success' => true, 'message' => 'Sale deleted successfully..', 'redirectUrl' => '/sales/list'], 200);
    }


    // Search Sales
    public function searchSales(Request $request)
    {
        $Queries = array();
        // if ($request->isMethod('GET')) {

        //     $request->from_date= $request->get('from_date');
        //     $request->to_date=$request->get('to_date');
        //     $request->invoice_number=$request->get('invoice_number');
        //     $request->customer_id=$request->get('customer_id');
        // }

        if (empty($request->from_date) && empty($request->to_date) &&  empty($request->customer_id) && empty($request->invoice_number)) {
            return redirect('sales/list');
        }
        $query = DB::table('sales');
        $query->join('customers', 'sales.customer_id', '=', 'customers.id');
        $query->select('sales.*', 'customers.name as customer_name', 'customers.status as customer_status');

        if (!empty($request->from_date) && !empty($request->to_date)) {
            $Queries['from_date'] = $request->from_date;
            $Queries['to_date'] = $request->to_date;
            $query->whereBetween('sales.invoice_date', [$request->from_date, $request->to_date]);
        }
        if (!empty($request->invoice_number)) {
            $Queries['invoice_number'] = $request->invoice_number;
            $query->where('sales.invoice_number', 'like', "%$request->invoice_number%");
        }
        if (!empty($request->customer_id)) {
            $Queries['customer_id'] = $request->customer_id;
            $query->where('sales.customer_id', '=', $request->customer_id);
        }
        $list = $query->orderByDesc('sales.id')->paginate(20);
        $list->appends($Queries);
        $net_total = $query->sum('net_total');
        $net_qty = $query->sum('net_qty');
        $net_pcs = $query->sum('net_pcs');
        $customers = DB::table('customers')->get();
        return view('sales.list', array('salelist' => $list, 'from_date' => $request->from_date, 'to_date' => $request->to_date, 'customer_id' => $request->customer_id, 'invoice_number' => $request->invoice_number, 'customers' => $customers, 'net_total' => $net_total, 'net_pcs' => $net_pcs, 'net_qty' => $net_qty));
    }



    // PDF generator
    public function recordPDF($id)
    {
        $sale = DB::table('sales')->where('sales.id', $id)
            ->leftJoin('customers', 'sales.customer_id', '=', 'customers.id')
            ->join('travel_agents', 'sales.agent_id', '=', 'travel_agents.id')
            ->select('sales.*', 'customers.name', 'customers.phone', 'customers.email', 'customers.address', 'customers.general_ledger_account_id', 'travel_agents.Commission_persent')
            ->first();

        $items = DB::table('items')->where('branch', Auth()->user()->branch)->get();

        $companyinfo = DB::table('companyinfo')->first();
        $companyinfo->logo = url('/') . $companyinfo->logo;
        $agentlist = DB::table('travel_agents')->get();
        $data =  array('sale' => $sale, 'items' => $items, 'companyinfo' => $companyinfo);
        // $pdf = PDF::loadView('sales.salePdf', $data);
        $customPaper = array(40, 0, 800.00, 280.80);
        $pdf = PDF::loadView('sales.saleThermalPdf', $data)->setPaper($customPaper, 'landscape');
        // return $pdf->download('salePdf.pdf');
        return $pdf->stream('salePdf.pdf');
    }

    public function salePagePDF($from_date, $to_date, $customer_id, $invoice_number)
    {
        $query = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->select('sales.*', 'customers.name as customer_name', 'customers.status as customer_status')->where('sales.branch', Auth::user()->branch);
        $query2 = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->select('sales.*', 'customers.name as customer_name', 'customers.status as customer_status')->where('sales.branch', Auth::user()->branch);
        if ($from_date != 'none' && $to_date != 'none') {
            $query->whereBetween('sales.invoice_date', [$from_date, $to_date]);
            $query2->whereBetween('sales.invoice_date', [$from_date, $to_date]);
        }
        if ($invoice_number != 'none') {
            $query->where('sales.invoice_number', 'like', "%$invoice_number%");
            $query2->where('sales.invoice_number', 'like', "%$invoice_number%");
        }
        if ($customer_id != 'none') {
            $query->where('customers.id', "$customer_id");
            $query2->where('customers.id', "$customer_id");
        }
        $list = $query->orderByDesc('sales.id')->get();
        $net = $query->where('customers.status', '!=', 2)->orderByDesc('sales.id')->sum('net_total');
        $net2 = $query2->where('customers.status', '=', 2)->sum('net_total');
        $companyinfo = DB::table('companyinfo')->first();
        $companyinfo->logo = url('/') . $companyinfo->logo;
        $data = array(
            'salelist' => $list,
            'companyinfo' => $companyinfo,
            'net' => $net,
            'net2' => $net2
        );

        $pdf = PDF::loadView('sales.salePagePdf', $data);
        return $pdf->stream('pagePdf.pdf');
    }
    // Get Invoice list (Modal)
    public function getInvoiceList()
    {
        $list = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->select('sales.*', 'customers.name as customer_name')->where('sales.branch', Auth::user()->branch)->where('sales.status', 0)
            ->get();
        $data = array();
        if (count($list) > 0) {
            $data = array(
                'code' => 200,
                'data' => $list,
                'count' => count($list)
            );
        } else {
            $data = array(
                'code' => 404,
                'message' => 'No record found!',
            );
        }
        return response()->json($data);
    }

    // Get single invoice data (ajax)
    public function getSingleInvoiceData(Request $request)
    {
        $record = DB::table('sales')->where('id', $request->invoiceId)->where('sales.branch', Auth::user()->branch)->first();
        $items_detail = unserialize($record->items_detail);

        $data = array();
        $itemsData = array();
        if (count($items_detail) > 0) {
            foreach ($items_detail as $item) {
                $itemName = DB::table('items')->where('id', $item['item_id'])->first();
                $item['name'] = $itemName->name;
                $itemsData[] = $item;
            }
        }
        if (count($items_detail) > 0) {

            $data = array(
                'code' => 200,
                'data' => $itemsData,
                'invoice_number' => $record->invoice_number,
                'invoice_date' => $record->invoice_date,
                'invoice_id' => $record->id,
                'invoice_discount' => $record->discount,
                'invoice_tax' => $record->tax,
                'invoice_gross_total' => $record->gross_total,
                'invoice_net_total' => $record->net_total,
                'invoice_paid_amount' => $record->paid_amount,
                'invoice_remainder' => $record->remainder,
                'invoice_table_no' => $record->table_no,
            );
        } else {
            $data = array(
                'code' => 404,
                'message' => 'No record found!',
            );
        }
        return response()->json($data);
    }

    // Get Next Invoice Number
    public function getNextInvoiceNumber()
    {
        $invoice_number = DB::table('sales')->max('id') + 1;
        return response()->json(['success' => true, 'invoice_number' => Config::get('constants.SALE_INVOICE_PREFIX') . $invoice_number], 200);
    }

    public function invoiceRefund($id)
    {
        $data = array(
            'status' => 2
        );
        $refund = DB::table('sales')->where('id', $id)->update($data);
        $ref = DB::table('sales')->where('id', $id)->first();
        $data = DB::table('sales')->where('invoice_number', $ref->invoice_number)->first();
        $response = Http::post(Config::get('constants.LIVE_SERVER_DATA_SEND') . '/sendDataToLiveServerRefundInvoice', ['data' => $data]);
        return response()->json(['message' => 'Invoice Refunded!', 'code' => 200]);
    }


    public function customerledger()
    {
        /* $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        DB::table('sales')->where('id',$id)->delete();
        return response()->json(['success' => true, 'message' => 'Sale deleted successfully..', 'redirectUrl' => '/sales/list'],200);*/
    }
    public function insertDoubleEntry($data)
    {
        /**
         * In case of exception,Roll Back whole Entry
         * remove double entry
         *
         */
        try {
            DB::table('general_ledger_transactions')->insertGetId($data);
        } catch (\Exception $e) {
            DB::table('general_ledger_transactions')->where('voucher_number', $data->voucher_number)->delete();
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'redirectUrl' => '/sales/list'], 200);
        }
    }
    public function updateDoubleEntry($data)
    {
        /**
         * In case of exception,no need to
         * remove double entry while updated because of
         * record already exisit in table
         * no mettars if no updated
         */
        try {
            DB::table('general_ledger_transactions')
                ->where('voucher_number', $data['voucher_number'])
                ->where('general_ledger_account_id', $data['general_ledger_account_id'])
                ->update($data);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'redirectUrl' => '/sales/list'], 200);
        }
    }
    public function deleteDoubleEntry($voucher_number)
    {
        try {
            DB::table('general_ledger_transactions')->where('voucher_number', $voucher_number)->delete();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'redirectUrl' => '/sales/list'], 200);
        }
    }


    public function stockManagementEntry($data)
    {
        try {
            DB::table('general_inventory_transactions')->insertGetId($data);
        } catch (\Exception $e) {
            DB::table('general_inventory_transactions')->where('voucher_number', $data->voucher_number)->delete();
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'redirectUrl' => '/sales/list'], 200);
        }
    }

    public function addTransactionLog($data)
    {
        DB::table('transactions_log')->insertGetId($data);
    }

    public function stockManagementEntryDelete($voucher_number)
    {
        try {
            DB::table('general_inventory_transactions')->where('voucher_number', $voucher_number)->delete();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'redirectUrl' => '/sales/list'], 200);
        }
    }
    public function indexAgents()
    {
        $agentlist = DB::table('travel_agents')->get();
        return view('travelAgents.agentlist', ['agentlist' => $agentlist]);
    }
    public function newTravelAgent()
    {
        return view('travelAgents.newAgent');
    }
    public function saveTravelAgent(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'commission' => 'required',

            ],
            [
                'name.required' => 'The name is required.',
                'phone.required' => 'The phone  is required.',
                'address.required' => 'The address   is required.',
                'commssion.required' => 'Net commission   is required.',

            ]
        );
        $agents = DB::table('travel_agents')->insert([
            'Name' => $request->name,
            'Phone' => $request->phone,
            'Address' => $request->address,
            'Commission_persent' => $request->commission,
        ]);


        // return redirect()->route('travelagentlist')->with('success','Agent added successfully');
        return response()->json(['success' => true, 'message' => 'Agent added successfully..', 'redirectUrl' => 'travelagentlist'], 200);
    }
    public function edittravelagent($id)
    {

        $editagent = DB::table('travel_agents')->find($id);
        return view('travelAgents.newAgent', ['agents' => $editagent]);
    }
    public function updatetravelagent(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'commission' => 'required',

            ],
            [
                'name.required' => 'The name is required.',
                'phone.required' => 'The phone  is required.',
                'address.required' => 'The address   is required.',
                'commssion.required' => 'Net commission   is required.',

            ]
        );
        $agents = DB::table('travel_agents')->where('id', $request->id)->update([
            'Name' => $request->name,
            'Phone' => $request->phone,
            'Address' => $request->address,
            'Commission_persent' => $request->commission,
        ]);


        // return redirect()->route('travelagentlist')->with('success','Agent update successfully');;
        return response()->json(['success' => true, 'message' => 'Agent update successfully..', 'redirectUrl' => '/travelagentlist'], 200);
    }
    public function deletetravelagent($id)
    {
        $agent = DB::table('travel_agents')->where('id', $id);
        $agent->delete();

        //   return redirect()->route('travelagentlist')
        //   ->with('success','Agent deleted successfully');
        return response()->json(['success' => true, 'message' => 'Customer deleted successfully..', 'redirectUrl' => '/travelagentlist'], 200);
    }
    public function TravelAgentCommission($id)
    {
        $agent = DB::table('travel_agents')->where('id', $id)->first();


        //   return redirect()->route('travelagentlist')
        //   ->with('success','Agent deleted successfully');
        return response()->json(['success' => true, 'commission_persent' => $agent->Commission_persent], 200);
    }
    public function tourDetailList()
    {
        $tourdetails = DB::table('tour_details')->get();
        return view('tourDetails.tourdetailslist', ['tourdetails' => $tourdetails]);
    }

    public function newTour()
    {
        return view('tourDetails.newtour');
    }
    public function saveTour(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tour_from' => 'required',
                'tour_to' => 'required',
                'date_from' => 'required',
                'date_to' => 'required',
                'tourism' => 'required',
                

            ],
            [
                'tour_from.required' => 'The tour route from is required.',
                'tour_to.required' => 'The tour route to  is required.',
                'date_from.required' => 'The tour date from   is required.',
                'date_to.required' => 'Net tour date to   is required.',
                'tourism.required' => 'Net tourism   is required.',

            ]
        );
        $toursave = DB::table('tour_details')->insert([
            'tour_from'=> $request->tour_from,
            'tour_to'=> $request->tour_to,
            'date_from'=> $request->date_from,
            'date_to'=> $request->date_to,
            'no_of_tourism' => $request->no_of_tourism,
        ]);


        // return redirect()->route('travelagentlist')->with('success','Agent added successfully');
        return response()->json(['success' => true, 'message' => 'Tour added successfully..', 'redirectUrl' => 'tourdetailslist'], 200);
    }
    public function EditTourDetail($id)
    {

        $touredit = DB::table('tour_details')->find($id);
        return view('tourDetails.newtour', ['toursave' => $touredit]);
    }
    public function updateTourDetail(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'tour_from' => 'required',
                'tour_to' => 'required',
                'date_from' => 'required',
                'date_to' => 'required',
                'tourism' => 'required',
                

            ],
            [
                'tour_from.required' => 'The tour route from is required.',
                'tour_to.required' => 'The tour route to  is required.',
                'date_from.required' => 'The tour date from   is required.',
                'date_to.required' => 'Net tour date to   is required.',
                'tourism.required' => 'Net tourism   is required.',

            ]
        );
       
     $toursave = DB::table('tour_details')->where('id', $request->id)->update([
        'tour_from' => $request->tour_from,
        'tour_to' => $request->tour_to,
        'date_from' => $request->date_from,
        'date_to' => $request->date_to,
        'no_of_tourism' => $request->no_of_tourism,

     ]);


        // return redirect()->route('travelagentlist')->with('success','Agent update successfully');;
        return response()->json(['success' => true, 'message' => 'Agent update successfully..', 'redirectUrl' => '/tourList'], 200);
    }
    

    public function deleteTourDetail($id)
    {

        $tourdelete = DB::table('tour_details')->where('id', $id);
        $tourdelete->delete();

        //   return redirect()->route('travelagentlist')
        //   ->with('success','Agent deleted successfully');
        return response()->json(['success' => true, 'message' => 'tour deleted successfully..', 'redirectUrl' => 'tourdetailslist'], 200);

       
 
    }
}
