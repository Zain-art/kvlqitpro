<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Image;

class companyController extends Controller
{
    //
    public function comList(){
        $comlist=DB::table('companyinfo')->get();
         return view('company.companyList', array('company' => $comlist));
     }
     public function editCompany($id){
        $com=DB::table('companyinfo')->where('id',$id)->first();
        // return $menus;
        // echo $menus->title;
        // exit;
        return view('company.companyUpdate', array('company' => $com));
    }
    public function updateCompany(Request $request){
        $compinfo=DB::table('companyinfo')->where('id',$request->id)->first();
        $validator = Validator::make($request->all(),[
            'title' => 'required|min:3|max:20',
            'phone' => ['required', 'numeric'],
            'email' =>'required',
            'address' => 'required',
            'web' => 'required'
        ],
            [
                'title.required' => 'The full name field is required.',
                'phone.required' => 'The Phone No field is required.',
                'email.required' => 'The email field is required.',
                'address.required' => 'The address field is required.',
                'web.required' => 'The Web field is required.',
            ]);

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            return response()->json($response,422);
        } else {
            $logo=Config::get('constants.COMPANU_DEFAULT_LOGO');
            $nav_logo=Config::get('constants.COMPANU_DEFAULT_LOGO');
            $report_logo=Config::get('constants.COMPANU_DEFAULT_LOGO');
            $comp = array(
                'title' => $request->title,
                'business_type' => $request->business_type,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'stock_calculation' =>$request->status,
                'web' => $request->web

            );
            if ($files = $request->file('logo')) {
                $destinationPath = public_path('/company_logo/'); // upload path
                $nav_logo = date('YmdHis') . "_nav." . $files->getClientOriginalExtension();
                $report_logo = date('YmdHis') . "_report." . $files->getClientOriginalExtension();
                $logo = date('YmdHis') . "." . $files->getClientOriginalExtension();

                $img = Image::make($files->path());
                $img->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$nav_logo);

                $img->resize(300, 168, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$report_logo);

                $img->resize(300, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$logo);
                if($compinfo->logo!=Config::get('constants.COMPANU_DEFAULT_LOGO')){
                    $this->removeImage($compinfo->logo);
                    $this->removeImage($compinfo->nav_logo);
                    $this->removeImage($compinfo->report_logo);
                }
                $comp['logo']='/company_logo/'.$logo;
                $comp['nav_logo']='/company_logo/'.$nav_logo;
                $comp['report_logo']='/company_logo/'.$report_logo;
            }



            $comp['updated_at']=date('Y-m-d H:i:s');
            $user=DB::table('companyinfo')->where('id',$request->id)->update($comp);
            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $request->id,
                'transaction_action' => 'Updated',
                'transaction_detail' => serialize($comp),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Company Information',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'Company update successfully..', 'redirectUrl' => '/company/comlist'],200);
        }
    }
    public function removeImage($path)
    {
        if(File::exists(public_path($path))){
            File::delete(public_path($path));
        }
    }

    public function addTransactionLog($data)
    {
        DB::table('transactions_log')->insertGetId($data);
    }
    public function clientValidation(Request $request)
    {


        $pos_id = $request->pos_id;
        $license_key = $request->license_key;


            $str = "new hash for license" . rand();

        $checkPosIDandKey = DB::table('client_license_validation')->where('pos_id',$pos_id)->where('license_key',$license_key)->first();

        if(!empty($checkPosIDandKey)){
            $new_license_key = md5($str);
            $data = array(
                'license_key'=>$new_license_key,
                'next_validation_date' => date('Y-m-d',strtotime('+7 days')),
                'last_validation_date' => $request->next_validation_date,
            );
            DB::table('client_license_validation')->where('id',$checkPosIDandKey->id)->update($data);
            return response()->json(['message'=>'success','generated_license_key'=>$new_license_key,'next_validation_date'=>date('Y-m-d',strtotime('+7 days')),'status'=>true,'code'=>200]);
        }
        else{
            return response()->json(['message'=>'error','status'=>false,'code'=>404]);
        }
    }

}
