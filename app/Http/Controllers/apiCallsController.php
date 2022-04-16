<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Str;


class apiCallsController extends Controller
{


    //  User Login (Api)
    public function userLogin(Request $request)
    {
        // $email = $request->email;
        // $password = $request->password;
        // $token = $request->accessToken;
        // $checkUser = DB::table('users')->where('email', '=', $email)->first();
        // if (!empty($checkUser)) {
        //     if (password_verify($password, $checkUser->password)) {
        //         $apiToken = auth()->user()->createToken('API Token')->plainTextToken;
        //         return response()->json(['message' => 'You have logged in Successfully!', 'state' => true,'token'=>$apiToken]);
        //     } else {
        //         return response()->json(['message' => 'Please enter correct password!', 'state' => false]);
        //     }
        // } else {
        //     return response()->json(['message' => $email . ' email deosn\'t exist!', 'status' => false]);
        // }
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if (!Auth::attempt($attr)) {
            return response()->json(['message' => 'Enter valid credentials', 'state' => false]);
        }
        $apiToken = auth()->user()->createToken('API Token')->plainTextToken;
        return response()->json(['message' => 'You have logged in Successfully!', 'state' => true,'token'=>$apiToken]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have logged out.'
        ];
    }


    public function saleslist($pageNo, $limit, $date)
    {
        $offset = ($pageNo - 1) * $limit;
        $query = DB::table('sales')->join('customers', 'customers.id', '=', 'sales.customer_id')->select('sales.id', 'sales.invoice_date', 'sales.net_total', 'customers.name');
        if ($date != 'none') {
            $query->where('invoice_date', $date);
        }
        $saleslist = $query->offset($offset)->limit($limit)->get();
        $status = false;
        if (count($saleslist) > 0) {
            $status = true;
        }
        return response()->json(compact('saleslist', 'status'));
    }

    public function purchaselist($pageNo, $limit, $date)
    {
        $offset = ($pageNo - 1) * $limit;
        $query = DB::table('purchases')->join('vendors', 'vendors.id', '=', 'purchases.vendor_id')->select('purchases.id', 'purchases.invoice_date', 'purchases.net_total', 'vendors.name');
        if ($date != 'none') {
            $query->where('invoice_date', $date);
        }
        $purchaselist = $query->offset($offset)->limit($limit)->get();
        $status = false;
        if (count($purchaselist) > 0) {
            $status = true;
        }
        return response()->json(compact('purchaselist', 'status'));
    }


    // Top Sale Customers 
    public function getDashboardData()
    {

        /**
         * Calculate Net Sales
         **/
        $Sales = DB::table('general_ledger_transactions')
            ->where('general_ledger_account_id', Config::get('constants.SALE_ACCOUNT_GENERAL_LEDGER'))
            ->sum(\DB::raw('credit'));
        $SalesReturn = DB::table('general_ledger_transactions')
            ->where('general_ledger_account_id', Config::get('constants.SALE_RETURN_ACCOUNT_GENERAL_LEDGER'))
            ->sum(\DB::raw('debit'));
        $netSales = $Sales - $SalesReturn;
        /**
         * Active Customers
         **/
        $customers = DB::table('customers')->where('status', 1)->count('*');
        /**
         * Active Vendors
         **/
        $vendors = DB::table('vendors')->where('status', 1)->count('*');

        /**
         * Sale Invoices
         * Sale A/c Credit
         **/

        /**
         * Last 7 Month
         **/
        $fromdate = Carbon::now()->subMonth(6)->format('Y-m-d');
        $todate = date('Y-m-d');
        $MonthWiseSales = $this->getMontlyAccountBalance(Config::get('constants.SALE_ACCOUNT_GENERAL_LEDGER'), 'credit', $fromdate, $todate, '');
        $MonthWiseSalesReturn = $this->getMontlyAccountBalance(Config::get('constants.SALE_RETURN_ACCOUNT_GENERAL_LEDGER'), 'debit', $fromdate, $todate, '');
        $filterPurchaseMonth = array();
        $filterSalesMonth = array();
        $MonthData = array();
        $SalesData = array();
        $PurchaseData = array();
        $Expensesdata = array();

        if (!empty($MonthWiseSales)) {

            /**
             * create new array set month  as key
             */
            foreach ($MonthWiseSales as $sale) {

                if (property_exists($sale, 'month')) {
                    $filterSalesMonth[$sale->month] = $sale->balance;
                }
            }

            /**
             * subtract the sale return for each month
             */
            if (!empty($MonthWiseSalesReturn)) {
                foreach ($MonthWiseSalesReturn as $return) {
                    if (array_key_exists($return->month, $filterSalesMonth)) {
                        $filterSalesMonth[$return->month] = $filterSalesMonth[$return->month] - $return->balance;
                    }
                }
            }
            foreach ($filterSalesMonth as $key => $sale) {
                //$Month[]=$this->getMonthName($key);
                $MonthData[] = $key;
                $SalesData[] = $this->thousandsCurrencyFormat($sale);
            }


            /**
             * all expenses
             */
            $expenseAccounts = DB::table('general_ledger_accounts')
                ->join('chart_of_accounts', 'general_ledger_accounts.chart_of_account_id', '=', 'chart_of_accounts.id')
                ->where('chart_of_accounts.id', '=', 5)
                ->where('general_ledger_accounts.account_type_id', '=', 4)
                ->whereNotIn('general_ledger_accounts.id',  [4, 10]) //don't include Purchase A/c and Purchase Return A/c
                ->select('general_ledger_accounts.*', 'chart_of_accounts.name as account_type_name')
                ->get();


            $filterExpensesMonth = array();
            $ExpensesData = array();

            foreach ($expenseAccounts as $account) {
                $monthwiseExpense = $this->getMontlyAccountBalance($account->id, 'debit', $fromdate, $todate, '');
                if (!empty($monthwiseExpense)) {
                    foreach ($monthwiseExpense as $expense) {
                        if (property_exists($expense, 'month')) {
                            $filterExpensesMonth[$expense->month] = $expense->balance;
                        }
                    }
                }
            }
            if (!empty($filterExpensesMonth)) {
                foreach ($MonthData as $month) {
                    if (array_key_exists($month, $filterExpensesMonth)) {
                        $ExpensesData[] = $this->thousandsCurrencyFormat($filterExpensesMonth[$month]);
                    }
                }
            }


            $MonthWisePurchases = $this->getMontlyAccountBalance(Config::get('constants.PURCHASE_ACCOUNT_GENERAL_LEDGER'), 'debit', $fromdate, $todate, '');
            $MonthWisePurchasesReturn = $this->getMontlyAccountBalance(Config::get('constants.PURCHASE_RETURN_ACCOUNT_GENERAL_LEDGER'), 'credit', $fromdate, $todate, '');
            foreach ($MonthWisePurchases as $purchase) {
                if (property_exists($purchase, 'month')) {
                    $filterPurchaseMonth[$purchase->month] = $purchase->balance;
                }
            }
            /**
             * subtract the Purchase return for each month
             */
            if (!empty($MonthWisePurchasesReturn)) {
                foreach ($MonthWisePurchasesReturn as $return) {
                    if (array_key_exists($return->month, $filterPurchaseMonth)) {
                        $filterPurchaseMonth[$return->month] = $filterPurchaseMonth[$return->month] - $return->balance;
                    }
                }
            }
            if (!empty($filterPurchaseMonth)) {
                foreach ($MonthData as $month) {
                    if (array_key_exists($month, $filterPurchaseMonth)) {
                        $PurchaseData[] = $filterPurchaseMonth[$month];
                    }
                }
            }
        }


        $top10SalesCustomersBalances = $this->getTopSaleCustomers();
        $top10SalesCustomers = array();
        if (!empty($top10SalesCustomersBalances)) {
            foreach ($top10SalesCustomersBalances as $key => $CustomersSales) {
                $Customer = DB::table('customers')
                    ->where('id', '=', $key)
                    ->first();
                $Customer->sale = $CustomersSales;
                $top10SalesCustomers[] = $Customer;
            }
        }
        // echo "<pre>";
        // print_r($MonthData);
        // exit;
        $months = array();
        foreach ($MonthData as $month) {
            $monthNum  = $month;
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('M');
            $months[] = $monthName;
        }
        $activeCustomers = DB::table('customers')->where('status', 1)->count();
        $activeEmployees = DB::table('employee')->where('status', 1)->count();
        $activeSuppliers = DB::table('vendors')->where('status', 1)->count();
        return response()->json(compact('top10SalesCustomers', 'activeCustomers', 'activeSuppliers', 'activeEmployees', 'netSales', 'MonthData', 'SalesData', 'ExpensesData', 'PurchaseData', 'months'));
    }
    public function getTopSaleCustomers()
    {
        /***
         * Default Begining Date will be last day from today
         * and Ending date will be today date
         */

        $CustomerSales = array();
        $CustomerSalesGroup = DB::table('customers')
            ->join('general_ledger_transactions', 'customers.general_ledger_account_id', '=', 'general_ledger_transactions.general_ledger_account_id')
            ->select('customers.id as customerid', DB::raw('SUM(general_ledger_transactions.debit) As sale'))
            ->groupBy('customerid')
            ->orderBy('sale', 'desc')->limit(10)
            ->get();
        if (!empty($CustomerSalesGroup)) {
            foreach ($CustomerSalesGroup as $SalesGroup) {
                $CustomerSalesReturn = DB::table('general_ledger_transactions')
                    ->where('general_ledger_account_id', '=', $SalesGroup->customerid)
                    ->where('general_ledger_transactions.voucher_number', 'LIKE', '' . Config::get('constants.SALE_INVOICE_RETURN_PREFIX') . '%')
                    ->sum('credit');
                $CustomerSales[$SalesGroup->customerid] = $SalesGroup->sale - $CustomerSalesReturn;
            }
        }
        return $CustomerSales;
    }

    public function cashReceipts($pageNo, $limit, $date)
    {
        $offset = ($pageNo - 1) * $limit;
        $query = DB::table('customers')->rightJoin('customer_receipt', 'customer_receipt.customer', '=', 'customers.id')->select('customer_receipt.*', 'customers.name');
        if ($date != 'none') {
            $query->where('received_date', $date);
        }
        $cashReceipts = $query->offset($offset)->limit($limit)->get();
        $status = false;
        if (count($cashReceipts) > 0) {
            $status = true;
        }
        return response()->json(compact('cashReceipts', 'status'));
    }

    public function ledgerList($pageNo, $limit, $date)
    {
        /**
         * Get Only Accounts thoes have type General Ledger
         * but not default accounts
         */
        $offset = ($pageNo - 1) * $limit;
        $query = DB::table('general_ledger_accounts')
            ->leftjoin('general_ledger_accounts_types', 'general_ledger_accounts.account_type_id', '=', 'general_ledger_accounts_types.id')
            ->leftjoin('chart_of_accounts', 'general_ledger_accounts.chart_of_account_id', '=', 'chart_of_accounts.id')
            ->leftjoin('chart_of_accounts_category', 'general_ledger_accounts.chart_of_accounts_category_id', '=', 'chart_of_accounts_category.id')
            ->select('general_ledger_accounts.*', 'general_ledger_accounts_types.name as ledger_account_name', 'chart_of_accounts.name as chart_name', 'chart_of_accounts_category.name as accounts_category_name', 'general_ledger_accounts_types.id as general_ledger_accounts_types_id')->where('general_ledger_accounts_types.id', 4);
        if ($date != 'none') {
            $query->where('created_at', $date);
        }
        $ledgerlist = $query->offset($offset)->limit($limit)->get();

        return response()->json(compact('ledgerlist'));
    }

    public function ledger($general_ledger_account_id)
    {
        /***
         * Default Begining Date will be last day from today
         * and Ending date will be today date
         */
        $journal_entry_rule = $this->getAccountjournalentryrule($general_ledger_account_id);
        $journal_sum_rule = 'debit - credit';
        if ($journal_entry_rule == 'credit') {
            $journal_sum_rule = 'credit - debit';
        }
        $account = DB::table('general_ledger_accounts')
            ->join('general_ledger_accounts_types', 'general_ledger_accounts.account_type_id', '=', 'general_ledger_accounts_types.id')
            ->where('general_ledger_accounts.id', '=', $general_ledger_account_id)
            ->select('general_ledger_accounts.*', 'general_ledger_accounts_types.name as account_type')
            ->first();
        $beginningBalance = DB::table('general_ledger_transactions')
            ->where('voucher_date', '<', Carbon::now()->format('Y-m-d'))
            ->where('general_ledger_account_id', $general_ledger_account_id)
            ->sum(\DB::raw($journal_sum_rule));
        $allTransactions = DB::table('general_ledger_transactions')
            ->where('general_ledger_account_id', $general_ledger_account_id)
            // ->where('voucher_date', '=', Carbon::now()->format('Y-m-d'))
            ->whereBetween('voucher_date', [date('Y-m-d', strtotime('-1 month')), date('Y-m-d')])
            ->get();
        $transactions = array();
        $balance = $beginningBalance;
        if (!empty($allTransactions)) {
            foreach ($allTransactions as $transaction) {
                if ($journal_entry_rule == 'credit') {
                    $transaction->closingBalance = $balance + $transaction->credit - $transaction->debit;
                } else {
                    $transaction->closingBalance = $balance + $transaction->debit - $transaction->credit;
                }
                $transactions[] = $transaction;
                $balance = $transaction->closingBalance;
            }
        }
        $endingBalance = DB::table('general_ledger_transactions')
            ->where('voucher_date', '<=', Carbon::now()->format('Y-m-d'))
            ->where('general_ledger_account_id', $general_ledger_account_id)
            ->sum(\DB::raw($journal_sum_rule));

        $data = array(
            'beginningBalance' => $beginningBalance,
            'transactions' => $transactions,
            'endingBalance' => $endingBalance,
            'account' => $account,
            'journal_entry_rule' => $journal_entry_rule
        );

        return response()->json(compact('data'));
    }







    public function getAccountjournalentryrule($general_ledger_account_id)
    {
        $account = DB::table('general_ledger_accounts')
            ->where('id', '=', $general_ledger_account_id)
            ->first();
        $chart_of_account = DB::table('chart_of_accounts')
            ->where('id', '=', $account->chart_of_account_id)
            ->first();
        return $chart_of_account->journal_entry_rule;
    }

    public function getMontlyAccountBalance($general_ledger_account_id, $journal_sum_rule, $fromdate, $todate, $voucher_number_prefix)
    {
        /***
         * Default Begining Date will be last day from today
         * and Ending date will be today date
         */

        $groupedBalance = DB::table('general_ledger_transactions')
            ->where('voucher_date', '>=', $fromdate)
            ->where('voucher_date', '<=', $todate)
            ->where('general_ledger_account_id', '=', $general_ledger_account_id)
            ->selectRaw(
                'MONTH(voucher_date) as month,
        SUM(' . $journal_sum_rule . ') AS balance',
            )->groupByRaw('MONTH(voucher_date)')->get();


        return $groupedBalance;
    }
    public function thousandsCurrencyFormat($num)
    {

        if ($num > 1000) {

            $x = round($num);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $x_display = $x;
            $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            // $x_display .= $x_parts[$x_count_parts - 1];

            return $x_display;
        }

        return $num;
    }
}
