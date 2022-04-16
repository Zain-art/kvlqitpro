<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class tourController extends Controller
{
    public function list()
    {
        $lists = DB::table('tours')->paginate(20);
        return view('Tour.list', ['lists' => $lists]);
    }

    public function new()
    {
        return view('Tour.new');
    }

    public function store(Request $request)
    {
        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        $validator = Validator::make(
            $request->all(),
            [
                'tour_name' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'number_of_tourists' => 'required',
                'from' => 'required',
                'to' => 'required',
            ],
            [
                'tour_name.required' => 'The Name field is required.',
                'start_date.required' => 'The Start Date field is required.',
                'end_date.required' => 'The End Date field is required.',
                'number_of_tourists.required' => 'The Number of Tourists field is required.',
                'from.required' => 'The From field is required.',
                'to.required' => 'The To field is required.',

            ]
        );

        if ($validator->fails()) {
            //$response['message'] = $validator->messages();
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 422);
        } else {

            $expense_details = array();
            $details = $request->detail;
            $amounts = $request->amount;
            $i = 0;
            foreach ($amounts as $item) {
                $detail = $details[$i];
                $amount = $amounts[$i];
                $expense_details[] = array(
                    'detail' => $detail,
                    'amount' => $amount,
                );

                $i++;
            }
            $tourInfo = array(
                'tour_name' => $request->tour_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'number_of_tourists' => $request->number_of_tourists,
                'from' => $request->from,
                'to' => $request->to,
                'expense_details' => serialize($expense_details),
                'created_at' => date('Y-m-d H:i:s'),
            );
            $tourid = DB::table('tours')->insertGetId($tourInfo);
            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $tourid,
                'transaction_action' => 'Created',
                'transaction_detail' => serialize($tourInfo),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Tour',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'Tour added successfully..', 'redirectUrl' => '/tour/list'], 200);
        }
    }

    public function edit($id)
    {
        $record = DB::table('tours')->where('id', $id)->first();
        return view('Tour.new', ['record' => $record]);
    }

    public function update(Request $request)
    {
        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        $validator = Validator::make(
            $request->all(),
            [
                'tour_name' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'number_of_tourists' => 'required',
                'from' => 'required',
                'to' => 'required',
            ],
            [
                'tour_name.required' => 'The Name field is required.',
                'start_date.required' => 'The Start Date field is required.',
                'end_date.required' => 'The End Date field is required.',
                'number_of_tourists.required' => 'The Number of Tourists field is required.',
                'from.required' => 'The From field is required.',
                'to.required' => 'The To field is required.',

            ]
        );

        if ($validator->fails()) {
            //$response['message'] = $validator->messages();
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 422);
        } else {

            $expense_details = array();
            $details = $request->detail;
            $amounts = $request->amount;
            $i = 0;
            foreach ($amounts as $item) {
                $detail = $details[$i];
                $amount = $amounts[$i];
                $expense_details[] = array(
                    'detail' => $detail,
                    'amount' => $amount,
                );

                $i++;
            }
            $tourInfo = array(
                'tour_name' => $request->tour_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'number_of_tourists' => $request->number_of_tourists,
                'from' => $request->from,
                'to' => $request->to,
                'is_tour_open' => isset($request->status) ? $request->status : 0,
                'expense_details' => serialize($expense_details),
                'created_at' => date('Y-m-d H:i:s'),
            );
            $tourid = DB::table('tours')->where('id', $request->id)->update($tourInfo);
            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $tourid,
                'transaction_action' => 'Updated',
                'transaction_detail' => serialize($tourInfo),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Tour',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'Tour Updated successfully..', 'redirectUrl' => '/tour/list'], 200);
        }
    }

    public function delete($id)
    {
        $tour = DB::table('tours')->where('id', $id)->first();
        DB::table('tours')->where('id', $tour->id)->delete();
        $log = array(
            'user_id' => Auth::user()->id,
            'voucher_number' => $tour->id,
            'transaction_action' => 'Delete',
            'transaction_detail' => serialize($tour),
            'branch' => Auth::user()->branch,
            'transaction_type' => 'Tour',
            'created_at' => date('Y-m-d H:i:s'),
        );
        $this->addTransactionLog($log);
        return response()->json(['success' => true, 'message' => 'Tour deleted successfully..', 'redirectUrl' => '/tour/list'], 200);
    }

    public function search(Request $request)
    {
        $Queries = array();
        if (empty($request->from_date) && empty($request->to_date) &&  empty($request->tour_name) && !isset($request->status)) {
            return redirect('tour/list');
        }
        $query = DB::table('tours');
        if (!empty($request->from_date) && !empty($request->to_date)) {
            $Queries['from_date'] = $request->from_date;
            $Queries['to_date'] = $request->to_date;
            $query->where('start_date', $request->from_date);
            $query->where('end_date', $request->to_date);
        }
        if (!empty($request->tour_name)) {
            $Queries['tour_name'] = $request->tour_name;
            $query->where('tour_name', 'like', "%$request->tour_name%");
        }
        if (isset($request->status)) {
            $Queries['status'] = $request->status;
            $query->where('is_tour_open', '=', $request->status);
        }
        $list = $query->paginate(20);
        $list->appends($Queries);

        return view('Tour.list', array('lists' => $list, 'from_date' => $request->from_date, 'to_date' => $request->to_date, 'status' => $request->status, 'tour_name' => $request->tour_name));
    }




    /**
     * Utilities
     */
    public function addTransactionLog($data)
    {
        DB::table('transactions_log')->insertGetId($data);
    }
}
