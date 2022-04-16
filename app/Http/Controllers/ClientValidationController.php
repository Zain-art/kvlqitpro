<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Auth;
use Illuminate\Support\Arr;
use PDF;
class ClientValidationController extends Controller
{
    public function list()
    {
        $list = DB::table('client_license_validation')->paginate(20);
        return view('clients.list',['lists'=>$list]);
    }

    public function new()
    {
        $str = "hash for license key".rand();
        $license_key = md5($str);
       return view('clients.new',['license_key'=>$license_key]);
    }

    public function save(Request $request)
    {
        // $compinfo=DB::table('companyinfo')->where('id',$request->id)->first();
        $validator = Validator::make($request->all(),[
            'company_title' => 'required|min:3|max:20',
            'phone_number' => ['required', 'numeric'],
            'license_key' =>'required',
            'address' => 'required',
            'pos_id' => 'required',
            'ntn_no' => 'required',
            'sales_tax_no' => 'required',
            'irs_password' => 'required',
        ],
            [
                'company_title.required' => 'The Comapny field is required.',
                'phone_number.required' => 'The Phone No field is required.',
                'license_key.required' => 'The licene key field is required.',
                'address.required' => 'The address field is required.',
                'pos_id.required' => 'The pos id field is required.',
                'ntn_no.required' => 'The NTN no field is required.',
                'sales_tax_no.required' => 'The sales tax no field is required.',
                'irs_password.required' => 'The Irs password field is required.',
            ]);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            return response()->json($response,422);
        } else {
   
            $client = array(
                'company_name' => $request->company_title,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'license_key' => $request->license_key,
                'pos_id' => $request->pos_id,
                'ntn_no' => $request->ntn_no,
                'irs_password' => $request->irs_password,
                'sales_tax_no' => $request->sales_tax_no,
            );

            $clientId=DB::table('client_license_validation')->insertGetId($client);
            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $clientId,
                'transaction_action' => 'Created',
                'transaction_detail' => serialize($client),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Client Validation',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'Client Info Saved successfully..', 'redirectUrl' => '/clients/list'],200);
        }
    }

public function edit($id)
{
    $client = DB::table('client_license_validation')->where('id',$id)->first();
    return view('clients.new',['client'=>$client]);
}

    public function update(Request $request)
    {
        // $compinfo=DB::table('companyinfo')->where('id',$request->id)->first();
        $validator = Validator::make($request->all(),[
            'company_title' => 'required|min:3|max:20',
            'phone_number' => ['required', 'numeric'],
            'license_key' =>'required',
            'address' => 'required',
            'pos_id' => 'required',
            'ntn_no' => 'required',
            'sales_tax_no' => 'required',
            'irs_password' => 'required',
        ],
            [
                'company_title.required' => 'The Comapny field is required.',
                'phone_number.required' => 'The Phone No field is required.',
                'license_key.required' => 'The licene key field is required.',
                'address.required' => 'The address field is required.',
                'pos_id.required' => 'The pos id field is required.',
                'ntn_no.required' => 'The NTN no field is required.',
                'sales_tax_no.required' => 'The sales tax no field is required.',
                'irs_password.required' => 'The Irs password field is required.',
            ]);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            return response()->json($response,422);
        } else {
   
            $client = array(
                'company_name' => $request->company_title,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'license_key' => $request->license_key,
                'pos_id' => $request->pos_id,
                'ntn_no' => $request->ntn_no,
                'irs_password' => $request->irs_password,
                'sales_tax_no' => $request->sales_tax_no,
            );

            $clientId=DB::table('client_license_validation')->where('id',$request->id)->update($client);
            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $request->id,
                'transaction_action' => 'Updated',
                'transaction_detail' => serialize($client),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Client Validation',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'Client Info Updated successfully..', 'redirectUrl' => '/clients/list'],200);
        }
    }


    public function delete($id)
    {
      
        $client = DB::table('client_license_validation')->where('id',$id)->first();
        DB::table('client_license_validation')->where('id', $id)->delete();
        $log = array(
            'user_id' => Auth::user()->id,
            'voucher_number' => $id,
            'transaction_action' => 'Deleted',
            'transaction_detail' => serialize($client),
            'branch' => Auth::user()->branch,
            'transaction_type' => 'Client Validation',
            'created_at' => date('Y-m-d H:i:s'),
        );
        $this->addTransactionLog($log);
        return response()->json(['success' => true, 'message' => 'Client info deleted successfully..', 'redirectUrl' => '/clients/list'], 200);
    }




    public function addTransactionLog($data)
    {
        DB::table('transactions_log')->insertGetId($data);
    }
}
