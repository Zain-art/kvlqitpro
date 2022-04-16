<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use PDF;

class inventoryController extends Controller
{
    //
    public function categoryList()
    {
        $categorylist = DB::table('category')->orderByDesc('id')->paginate(20);
        return view('inventory.categorylist', array('category' => $categorylist));
    }
    // units category list

    public function unitCategory()
    {


        $unitlist = DB::table('item_units')->orderByDesc('id')->paginate(20);
        return view('inventory.unitlist', array('units' => $unitlist));

    }
    // new create units category
    public function newUnitAdd()
    {
        return view('inventory.addunitCategory');
    }
    public function newCategory()
    {

        return view('inventory.addcategory');
    }
    //////////////////////////// start add unit
/////////////////////// hotel room list show
    public function HotelRoom(){
        $hotelroomlist = DB::table('hotelroom')->orderByDesc('id')->paginate(20);
        return view('inventory.ListHotelRoom', array('hotelroom' => $hotelroomlist));
    }
    //////////////// hotel room add new hotel room
    public function newHotelRoom()
    {

        return view('inventory.addHotelRoom');
    }
    public function storeHotelRoom(Request $request)
    {
        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:3|max:20',

            ],
            [
                'name.required' => 'The Title of hotel room name field is required unit hotel room.',

            ]
        );

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            return response()->json($response, 422);
        } else {

    $hotelroom = array(
                'name' => $request->name,
                'price'=>$request->price,
                //'branch' => $request->branch,
                'created_at' => date('Y-m-d H:i:s'),
            );

            $categoryID = DB::table('hotelroom')->insertGetId($hotelroom);
            $log = array(
                //'id'=>Auth::user()->id,
                //'unit_name'=>$categoryID,
                'user_id' => Auth::user()->id,
                'voucher_number' => $categoryID,
                'transaction_action' => 'Created',
                'transaction_detail' => serialize($hotelroom),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Category',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'Hotel Room added successfully..', 'redirectUrl' => '/hotelroom
            '], 200);
        }
    }
///////////////////////////// end add new hotel room
////////////////////// start update hotel room

public function updateHotelRoom(Request $request)
{
    $categoryinfo = DB::table('hotelroom')->where('id', $request->id)->first();
    $validator = Validator::make(
        $request->all(),
        [
            'name' => 'required|min:3|max:20',
        ],
        [
            'name.required' => 'The Title of hotel room field is required.',
        ]
    );

    if ($validator->fails()) {
        $response['message'] = $validator->messages();
        return response()->json($response, 422);
    } else {
        $hotelroom = array(
            'name' => $request->name,
            'price' => $request->price,


        );


        $hotelroom['updated_at'] = date('Y-m-d H:i:s');
        $hotelroom = DB::table('hotelroom')->where('id', $request->id)->update($hotelroom);
        $log = array(
            'user_id' => Auth::user()->id,
            'voucher_number' => $request->id,
            'transaction_action' => 'Updated',
            'transaction_detail' => serialize($hotelroom),
            'branch' => Auth::user()->branch,
            'transaction_type' => 'Category',
            'created_at' => date('Y-m-d H:i:s'),
        );
        $this->addTransactionLog($log);
        return response()->json(['success' => true, 'message' => 'hotel room update successfully..', 'redirectUrl' => '/hotelroom'], 200);

    }

}
//////////////////////////end hotel room update
 ////////////////////// start edit hotel room
 public function editHotelRoom($id)
 {
     $hotelroom = DB::table('hotelroom')->where('id', $id)->first();
     // return $menus;
     // echo $menus->title;
     // exit;
     return view('inventory.addHotelRoom', array('hotelroom' => $hotelroom));
 }
 ////////////////////////// end edit hotelroom
 /////////////////////// delete unit data
 public function deleteHotelRoom($id)
 {
     $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
     $hotelroomdel = DB::table('hotelroom')->where('id', $id)->first();
     $room = DB::table('hotelroom')->where('id', $id)->delete();
     $log = array(
         'user_id' => Auth::user()->id,
         'voucher_number' => $id,
         'transaction_action' => 'Deleted',
         'transaction_detail' => serialize($hotelroomdel),
         'branch' => Auth::user()->branch,
         'transaction_type' => 'Category',
         'created_at' => date('Y-m-d H:i:s'),
     );
     $this->addTransactionLog($log);
      return redirect('/hotelroom');
    }
    public function storeUnit(Request $request)
    {
        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        $validator = Validator::make(
            $request->all(),
            [
                'unit_name' => 'required|min:3|max:20',

            ],
            [
                'unit_name.required' => 'The Title of category field is required unit categoryssss.',

            ]
        );

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            return response()->json($response, 422);
        } else {

            $category = array(
                'unit_name' => $request->unit_name,
                //'branch' => $request->branch,
                'created_at' => date('Y-m-d H:i:s'),
            );

            $categoryID = DB::table('item_units')->insertGetId($category);
            $log = array(
                //'id'=>Auth::user()->id,
                //'unit_name'=>$categoryID,
                'user_id' => Auth::user()->id,
                'voucher_number' => $categoryID,
                'transaction_action' => 'Created',
                'transaction_detail' => serialize($category),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Category',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'Category added successfully..', 'redirectUrl' => '/unitlist'], 200);
        }
    }



    //////////////////////////// end add unit

    //////////////////// update unit list

    public function updateUnit(Request $request)
    {
        $categoryinfo = DB::table('item_units')->where('id', $request->id)->first();
        $validator = Validator::make(
            $request->all(),
            [
                'unit_name' => 'required|min:3|max:20',
            ],
            [
                'unit_name.required' => 'The Title of category field is required.',
            ]
        );

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            return response()->json($response, 422);
        } else {
            $category = array(
                'unit_name' => $request->unit_name,


            );


            $category['updated_at'] = date('Y-m-d H:i:s');
            $category = DB::table('item_units')->where('id', $request->id)->update($category);
            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $request->id,
                'transaction_action' => 'Updated',
                'transaction_detail' => serialize($category),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Category',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'unit update successfully..', 'redirectUrl' => '/unitlist'], 200);
        }
    }


    /////////// end update unit list
    ////////////////////start edit unit
    public function editUnit($id)
    {
        $category = DB::table('item_units')->where('id', $id)->first();
        // return $menus;
        // echo $menus->title;
        // exit;
        return view('inventory.addunitCategory', array('category' => $category));
    }


    //////////////// end edit unit
    /////////////////////// delete unit data
    public function deleteUnit($id)
    {
        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        $categoryData = DB::table('item_units')->where('id', $id)->first();
        $menu = DB::table('item_units')->where('id', $id)->delete();
        $log = array(
            'user_id' => Auth::user()->id,
            'voucher_number' => $id,
            'transaction_action' => 'Deleted',
            'transaction_detail' => serialize($categoryData),
            'branch' => Auth::user()->branch,
            'transaction_type' => 'Category',
            'created_at' => date('Y-m-d H:i:s'),
        );
        $this->addTransactionLog($log);
        return redirect('/unitlist');
        // return redirect()->back()->with('message', 'IT afafaaWORKS!','reirectUrl','/unitlist');



        //    return redirect(menuList());

        // return response()->redirect('/unilist')->with('success','deleted successfully');
        //return response()->json(['success' => true, 'message' => 'unit is delete successfully..', 'redirectUrl' => '/unitlist'], 200);

        // return response()->json(['success' => true, 'message' => 'Menue update successfully..', 'redirectUrl' => '/menu/menuList'],200);

    }


    ///////////////////// delete unit data end
    public function storeCategory(Request $request)
    {
        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:3|max:20',

            ],
            [
                'name.required' => 'The Title of category field is required.',

            ]
        );

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            return response()->json($response, 422);
        } else {

            $category = array(
                'name' => $request->name,
                'branch' => $request->branch,
                'created_at' => date('Y-m-d H:i:s'),
            );

            $categoryID = DB::table('category')->insertGetId($category);
            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $categoryID,
                'transaction_action' => 'Created',
                'transaction_detail' => serialize($category),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Category',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'Category added successfully..', 'redirectUrl' => '/category/categoryList'], 200);
        }
    }
    public function editCategory($id)
    {
        $category = DB::table('category')->where('id', $id)->first();
        // return $menus;
        // echo $menus->title;
        // exit;
        return view('inventory.addcategory', array('category' => $category));
    }
    public function updateCategory(Request $request)
    {
        $categoryinfo = DB::table('category')->where('id', $request->id)->first();
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:3|max:20',
            ],
            [
                'name.required' => 'The Title of category field is required.',
            ]
        );

        if ($validator->fails()) {
            $response['message'] = $validator->messages();
            return response()->json($response, 422);
        } else {
            $category = array(
                'name' => $request->name,
                'branch' => $request->branch

            );

            $category['updated_at'] = date('Y-m-d H:i:s');
            $category = DB::table('category')->where('id', $request->id)->update($category);
            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $request->id,
                'transaction_action' => 'Updated',
                'transaction_detail' => serialize($category),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Category',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'category update successfully..', 'redirectUrl' => '/category/categoryList'], 200);
        }
    }
    public function deleteCategory($id)
    {
        $categoryData = DB::table('category')->where('id', $id)->first();
        $menu = DB::table('category')->where('id', $id)->delete();
        $log = array(
            'user_id' => Auth::user()->id,
            'voucher_number' => $id,
            'transaction_action' => 'Deleted',
            'transaction_detail' => serialize($categoryData),
            'branch' => Auth::user()->branch,
            'transaction_type' => 'Category',
            'created_at' => date('Y-m-d H:i:s'),
        );
        $this->addTransactionLog($log);

        //    return redirect(menuList());
        return redirect('category/categoryList');
        // return response()->json(['success' => true, 'message' => 'Menue update successfully..', 'redirectUrl' => '/menu/menuList'],200);

    }

    // items crud(methods)
    public function itemList()
    {
        $itemlist = DB::table('items')
            ->join('category', 'items.category', '=', 'category.id')
            ->select('items.*', 'category.name as category_name')->where('items.branch', Auth::user()->branch)
            ->orderByDesc('items.id')
            ->paginate(20);
        $items = array();
        foreach ($itemlist as $item) {
            $stockPlus = DB::table('general_inventory_transactions')->where('item_id', $item->id)->where('transaction_type', '+')->sum('item_qty');
            $stockMinus = DB::table('general_inventory_transactions')->where('item_id', $item->id)->where('transaction_type', '-')->sum('item_qty');
            $total_stock = $stockPlus - $stockMinus;
            $item->stock = $total_stock;
            $item_unit = DB::table('item_units')->where('id', $item->unit)->first();
            $unit_name = $item_unit->unit_name;
            $item->unit = $unit_name;
            $items[] = $item;
        }
        $category = DB::table('category')->where('branch', Auth::user()->branch)->get();
        return view('inventory.itemList', array('items' => $items, 'itemlist' => $itemlist,'categories'=>$category));
    }
    public function newItem()
    {
        $categorylist = DB::table('category')->where('branch', Auth::user()->branch)->get();
        $item_number = DB::table('items')->max('id') + 1;
        $item_menus = DB::table('item_menu')->get();
        $items = DB::table('items')->whereIn('category', [3,4, 5, 6])->where('item_type', 0)->get();
        $units = DB::table('item_units')->get();
        return view('inventory.addItem', array('itemMenus' => $item_menus, 'category' => $categorylist, 'item_number' => $item_number, 'itemss' => $items, 'units' => $units));
    }
    //////// minimum stock raw material
    public function MinimumStockRawMaterial()
    {
        $ministock = DB::Table('items')->select('stock')->get();  
       
        //$ministock = DB::table('items')->select('items.stock');
        
        return view('dashboard', ['stock' => $ministock]);
    }
    public function storeItem(Request $request)
    {
        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        $validator = Validator::make(
            $request->all(),
            [
                'code' => 'required',
                'name' => 'required|min:3|max:500',
                'purchase_price' => ['required', 'numeric'],
                'sele_price' => ['required', 'numeric'],
                'item_type' => ['required', 'numeric'],
            ],
            [
                'code.required' => 'The code field is required.',
                'name.required' => 'The item name field is required.',
                'purchase_price.required' => 'The purchase Price field is required.',
                'sele_price.required' => 'The sale price field is required.',
                'item_type.required' => 'The item type field is required.',

            ]
        );

        if ($validator->fails()) {
            //$response['message'] = $validator->messages();
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 422);
        } else {
            $file_path = Config::get('constants.ITEM_DEFAULT_PIC');
            if ($files = $request->file('pic')) {
                $destinationPath = public_path('/item_pic/'); // upload path
                $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
                $files->move($destinationPath, $profileImage);
                $file_path = '/item_pic/' . $profileImage;
            }

            $item_detail = array();
            if (isset($request->item_qty)) {
                $item_id = $request->item_id;
                $item_qtys = $request->item_qty;
                $i = 0;
                foreach ($item_id as $item) {
                    $general_ledger_account_id = $item_id[$i];
                    $item_qty = $item_qtys[$i];
                    if ($item_qty > 0) {
                        $item_detail[] = array(
                            'item_id' => $general_ledger_account_id,
                            'item_qty' => $item_qty,
                        );
                    }
                    $i++;
                }
            }

            $item = array(
                'code' => $request->code,
                'name' => $request->name,
                'pic' => $file_path,
                'purchase_price' => $request->purchase_price,
                'sele_price' => $request->sele_price,
                'stock' => 0,
                'item_type' => $request->item_type,
                'linked_items' => serialize($item_detail),
                'item_menu' => $request->itemMenu,
                'category' => $request->category,
                'stock' => $request->stock,
                'branch' => Auth::user()->branch,
                'created_at' => date('Y-m-d H:i:s'),
                'unit' => $request->units,
            );
            $itemId = DB::table('items')->insertGetId($item);
            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $itemId,
                'transaction_action' => 'Created',
                'transaction_detail' => serialize($item),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Items',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'item added successfully..', 'redirectUrl' => '/item/itemList'], 200);
        }
    }
    public function editItem($id)
    {
        $item = DB::table('items')->where('id', $id)->first();
        $categorylist = DB::table('category')->get();
        $item_menus = DB::table('item_menu')->get();
        $items = DB::table('items')->whereIn('category', [3,4, 5, 6])->where('item_type', 0)->get();
        $units = DB::table('item_units')->get();
        return view('inventory.addItem', array('itemMenus' => $item_menus, 'item' => $item, 'category' => $categorylist, 'itemss' => $items, 'units' => $units));
    }
    public function updateItem(Request $request)
    {
        $compinfo = DB::table('companyinfo')->where('id', $request->id)->first();
        // $emailexist=DB::table('users')->where('email',$request->email)->where('id','!=',$request->id)->first();
        // $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        // if(!empty($emailexist)){
        //     return response()->json(['success' => false, 'message' => 'The email has already been taken.Please try another one.', 'redirectUrl' => ''],200);
        // }
        $validator = Validator::make(
            $request->all(),
            [
                'code' => 'required',
                'name' => 'required|min:3|max:500',
                'purchase_price' => ['required', 'numeric'],
                'sele_price' => ['required', 'numeric'],
                'item_type' => ['required', 'numeric'],
            ],
            [
                'code.required' => 'The code field is required.',
                'name.required' => 'The item name field is required.',
                'purchase_price.required' => 'The purchase Price field is required.',
                'sele_price.required' => 'The sale price field is required.',


            ]
        );

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 422);
        } else {
            $file_path = Config::get('constants.ITEM_DEFAULT_PIC');
            if ($files = $request->file('pic')) {
                $destinationPath = public_path('/item_pic/'); // upload path
                $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
                $files->move($destinationPath, $profileImage);
                $file_path = '/item_pic/' . $profileImage;
            }

            $item_detail = array();
            if (isset($request->item_qty)) {
                $item_id = $request->item_id;
                $item_qtys = $request->item_qty;
                $i = 0;
                foreach ($item_id as $item) {
                    $general_ledger_account_id = $item_id[$i];
                    $item_qty = $item_qtys[$i];
                    if ($item_qty > 0) {
                        $item_detail[] = array(
                            'item_id' => $general_ledger_account_id,
                            'item_qty' => $item_qty,
                        );
                    }
                    $i++;
                }
            }
            $item = array(
                'code' => $request->code,
                'name' => $request->name,
                'pic' => $file_path,
                'purchase_price' => $request->purchase_price,
                'sele_price' => $request->sele_price,
                'stock' => 0,
                'item_menu' => $request->itemMenu,
                'item_type' => $request->item_type,
                'linked_items' => serialize($item_detail),
                'category' => $request->category,
                'stock' => $request->stock,
                'branch' => Auth::user()->branch,
                'unit' => $request->units,
            );

            $item['updated_at'] = date('Y-m-d H:i:s');
            $item = DB::table('items')->where('id', $request->id)->update($item);
            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $request->id,
                'transaction_action' => 'Updated',
                'transaction_detail' => serialize($item),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Items',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'Item update successfully..', 'redirectUrl' => '/item/itemList'], 200);
        }
    }
    public function deleteItem($id)
    {
        $itemData = DB::table('items')->where('id', $id)->first();
        $item = DB::table('items')->where('id', $id)->delete();
        $log = array(
            'user_id' => Auth::user()->id,
            'voucher_number' => $itemData->id,
            'transaction_action' => 'Deleted',
            'transaction_detail' => serialize($itemData),
            'branch' => Auth::user()->branch,
            'transaction_type' => 'Items',
            'created_at' => date('Y-m-d H:i:s'),

        );
        $this->addTransactionLog($log);
        return redirect('item/itemList');
    }

    public function searchItems(Request $request)
    {
        $Queries = [];
        if (empty($request->item_name) && empty($request->item_category)) {
            return redirect('item/itemList');
        }
        $query  = DB::table('items')
        ->join('category', 'items.category', '=', 'category.id')
        ->select('items.*', 'category.name as category_name')->where('items.branch', Auth::user()->branch);
        $rw_material=DB::table("category")->where('id',3)->get();
        $items = array();

        // if (!empty($request->item_size)) {
        //     $Queries['item_size'] = $request->item_size;
        //     $query->where('item_size', 'like', "%$request->item_size%");
        // }
        if (!empty($request->item_name)) {
            $Queries['item_name'] = $request->item_name;
            $query->where('items.name', 'like', "%$request->item_name%");
        }
        if (!empty($request->item_category)) {
            $Queries['item_category'] = $request->item_category;
            $query->where('category.id', $request->item_category);
        }
        $list = $query->orderByDesc('id')->paginate(20);
        foreach ($list as $item) {
            $stockPlus = DB::table('general_inventory_transactions')->where('item_id', $item->id)->where('transaction_type', '+')->sum('item_qty');
            $stockMinus = DB::table('general_inventory_transactions')->where('item_id', $item->id)->where('transaction_type', '-')->sum('item_qty');
            $total_stock = $stockPlus - $stockMinus;
            $item->stock = $total_stock;
            $item_unit = DB::table('item_units')->where('id', $item->unit)->first();
            $unit_name = $item_unit->unit_name;
            $item->unit = $unit_name;
            $items[] = $item;
        }
        $list->appends($Queries);
        $category = DB::table('category')->where('branch', Auth::user()->branch)->get();
        $categorylist = DB::table('category')->where('branch', Auth::user()->branch)->get();
        return view('inventory.itemList', array('items' => $items,'itemlist'=>$list, 'item_name' => $request->item_name, 'item_category' => $request->item_category, 'categories' => $category,'rw_material'=>$rw_material));
    }
    // Item Menu Crud 
///////  get list by category items
function getItemListByCategory($id){
    // $categorylist=DB::table('category')->where('id',$id)->get();
    // $categorylist=DB::table('items')->join('category','items.category','=','category.id')
    // ->select('items.id,items.code,items.name,items.pic,items.purchase_price,items.sele_price.items.stock,items.created_at,items.updated_at,category.category')
    // ->get();
   
    // return view('inventory.itemlist',['categorylist'=>$categorylist]);

    $itemlist = DB::table('items')
    ->join('category', 'items.category', '=', 'category.id')
    ->select('items.*', 'category.name as category_name')->where('items.category',$id)
    ->orderByDesc('items.id')
    ->paginate(20);
$items = array();
foreach ($itemlist as $item) {
    $stockPlus = DB::table('general_inventory_transactions')->where('item_id', $item->id)->where('transaction_type', '+')->sum('item_qty');
    $stockMinus = DB::table('general_inventory_transactions')->where('item_id', $item->id)->where('transaction_type', '-')->sum('item_qty');
    $total_stock = $stockPlus - $stockMinus;
    $item->stock = $total_stock;
    $item_unit = DB::table('item_units')->where('id', $item->unit)->first();
    $unit_name = $item_unit->unit_name;
    $item->unit = $unit_name;
    $items[] = $item;
}
$category = DB::table('category')->where('branch', Auth::user()->branch)->get();

return view('inventory.itemList', array('items' => $items, 'itemlist' => $itemlist,'categories'=>$category));


}

//////

    function itemMenuList()
    {
        $list = DB::table('item_menu')->get();
        return view('itemMenu/list', array('lists' => $list));
    }

    public function newitemMenu()
    {
        return view('itemMenu/new');
    }
    public function saveItemMenu(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'menu_name' => 'required|min:3|max:20',
            ],
            [
                'menu_name.required' => 'The name field is required.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 422);
        } else {
            $data = array(
                'name' => $request->menu_name,
            );
            DB::table('item_menu')->insert($data);
            return response()->json(['success' => true, 'message' => 'Item Menu added successfully..', 'redirectUrl' => '/itemMenu/itemMenulist'], 200);
        }
    }
    public function editItemMenu($id)
    {
        $record = DB::table('item_menu')->where('id', $id)->first();

        return view('itemMenu/new', array('record' => $record));
    }
    public function updateItemMenu(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'menu_name' => 'required|min:3|max:20',
            ],
            [
                'menu_name.required' => 'The name field is required.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 422);
        } else {
            $data = array(
                'name' => $request->menu_name,
            );
            DB::table('item_menu')->where('id', $request->id)->update($data);
            return response()->json(['success' => true, 'message' => 'Item Menu updated successfully..', 'redirectUrl' => '/itemMenu/itemMenulist'], 200);
        }
    }
    public function deleteItemMenu($id)
    {
        DB::table('item_menu')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Item Menu deleted successfully..', 'redirectUrl' => '/itemMenu/itemMenulist'], 200);
    }
    public function itemLedgerEntries($id)
    {
        $list = DB::table('general_inventory_transactions')->leftJoin('items', 'general_inventory_transactions.item_id', '=', 'items.id')->where('item_id', $id)->paginate(20);
        $allTransactions = array();
        $netQuantity = 0;
        foreach ($list as $singleItem) {
            if ($singleItem->transaction_type == '+') {
                $netQuantity += $singleItem->item_qty;
                $singleItem->netQty = $netQuantity;
                $allTransactions[] = $singleItem;
            }
            if ($singleItem->transaction_type == '-') {
                $netQuantity -= $singleItem->item_qty;
                $singleItem->netQty = $netQuantity;
                $allTransactions[] = $singleItem;
            }
        }

        return view('inventory.itemLedger', array('lists' => $list, 'net' => $netQuantity,'item_ledger_id'=>$id));
    }


    // Search Items Ledger 
    public function searchItemsledger(Request $request)
    {
        $Queries = [];
        if (empty($request->from_date) && empty($request->to_date) && empty($request->invoice_number)) {
            return redirect()->back();
        }
        $query = DB::table('general_inventory_transactions')->leftJoin('items', 'general_inventory_transactions.item_id', '=', 'items.id')->where('general_inventory_transactions.item_id', $request->item_ledger_id);
        if (!empty($request->from_date) && !empty($request->to_date)) {
            $Queries['from_date'] = $request->from_date;
            $Queries['to_date'] = $request->to_date;
            $query->whereBetween('general_inventory_transactions.voucher_date', [$request->from_date, $request->to_date]);
        }
        if (!empty($request->invoice_number)) {
            $Queries['invoice_number'] = $request->invoice_number;
            $query->where('general_inventory_transactions.voucher_number','like',"%$request->invoice_number%");
        }
        $list = $query->orderByDesc('general_inventory_transactions.id')->paginate(20);
        $netQuantity = 0;
        foreach ($list as $singleItem) {
            if ($singleItem->transaction_type == '+') {
                $netQuantity += $singleItem->item_qty;
                $singleItem->netQty = $netQuantity;
                $allTransactions[] = $singleItem;
            }
            if ($singleItem->transaction_type == '-') {
                $netQuantity -= $singleItem->item_qty;
                $singleItem->netQty = $netQuantity;
                $allTransactions[] = $singleItem;
            }
        }
        $list->appends($Queries);
        return view('inventory.itemLedger', array('lists' => $list,'from_date' => $request->from_date, 'to_date' => $request->to_date,'invoice_number'=>$request->invoice_number,'net' => $netQuantity,'item_ledger_id'=>$request->item_ledger_id));
    
    }
    // Items Ledger Page Pdf 
    public function itemsLedgerPagePdf($from_date,$to_date,$invoice_number,$item_ledger_id)
    {
        $query = DB::table('general_inventory_transactions')->leftJoin('items', 'general_inventory_transactions.item_id', '=', 'items.id')->where('general_inventory_transactions.item_id', $item_ledger_id);
        if ($from_date != 'none' && $to_date != 'none') {
            $query->whereBetween('general_inventory_transactions.voucher_date', [$from_date, $to_date]);
        }
        if ($invoice_number != 'none') {
            $query->where('general_inventory_transactions.voucher_number','like',"%$invoice_number%");
        }
        $list = $query->orderByDesc('general_inventory_transactions.id')->paginate(20);
        $netQuantity = 0;
        foreach ($list as $singleItem) {
            if ($singleItem->transaction_type == '+') {
                $netQuantity += $singleItem->item_qty;
                $singleItem->netQty = $netQuantity;
                $allTransactions[] = $singleItem;
            }
            if ($singleItem->transaction_type == '-') {
                $netQuantity -= $singleItem->item_qty;
                $singleItem->netQty = $netQuantity;
                $allTransactions[] = $singleItem;
            }
        }
        $companyinfo = DB::table('companyinfo')->first();
        $companyinfo->logo = url('/') . $companyinfo->logo;
        $data = array(
            'lists' => $list,
            'companyinfo' => $companyinfo
        );

        $pdf = PDF::loadView('inventory.itemsLedgerPagePdf', $data);
        return $pdf->stream('pagePdf.pdf');

        // return view('inventory.itemsLedgerPagePdf', array());
    }
    // Branches Crud 
    public function branchesList()
    {
        $branchesList = DB::table('branches')->paginate(20);
        return view('inventory.branchesList', ['lists' => $branchesList]);
    }

    public function newBranch()
    {
        return view('inventory.newBranch');
    }
    public function saveBranch(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:3|max:20',
            ],
            [
                'name.required' => 'The name field is required.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 422);
        } else {
            $data = array(
                'name' => $request->name,
                'branch' => Auth::user()->branch,
                'created_at' => date('Y-m-d H:i:s')
            );
            DB::table('branches')->insert($data);
            return response()->json(['success' => true, 'message' => 'Branch added successfully..', 'redirectUrl' => '/branches/list'], 200);
        }
    }

    public function editBranch($id)
    {
        $branch = DB::table('branches')->where('id', $id)->first();
        return view('inventory.newBranch', ['branch' => $branch]);
    }


    public function updateBranch(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:3|max:20',
            ],
            [
                'name.required' => 'The name field is required.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 422);
        } else {
            $data = array(
                'name' => $request->name,
                'branch' => Auth::user()->branch,
                'updated_at' => date('Y-m-d H:i:s')
            );
            DB::table('branches')->where('id', $request->id)->update($data);
            return response()->json(['success' => true, 'message' => 'Branch updated successfully..', 'redirectUrl' => '/branches/list'], 200);
        }
    }

    public function deleteBranch($id)
    {
        DB::table('branches')->where('id', $id)->delete();
        return response()->json(['success' => true, 'message' => 'Branch deleted successfully..', 'redirectUrl' => '/branches/list'], 200);
    }

    // Stock Issues
    function stockIssuesList()
    {
        $lists = DB::table('branch_stock_transaction')->join('branches', 'branches.id', '=', 'branch_stock_transaction.branch_id')->select('branch_stock_transaction.*', 'branches.name')->where('branch_stock_transaction.branch', Auth::user()->branch)->paginate(20);
        $net_qty = DB::table('branch_stock_transaction')->join('branches', 'branches.id', '=', 'branch_stock_transaction.branch_id')->select('branch_stock_transaction.*', 'branches.name')->where('branch_stock_transaction.branch', Auth::user()->branch)->sum('net_qty');
        $branches = DB::table('branches')->get();
        return view('inventory.stockIssueList', ['lists' => $lists, 'branches' => $branches, 'net_qty' => $net_qty]);
    }

    public function newStockIssue()
    {
        $branches = DB::table('branches')->get();
        $invoice_number = DB::table('branch_stock_transaction')->max('id') + 1;
        $items = DB::table('items')->where('branch', Auth()->user()->branch)->whereIn('category', [3,4, 5, 6])->get();
        return view('inventory.newStockIssue', ['items' => $items, 'invoice_number' => $invoice_number, 'branches' => $branches]);
    }
    public function saveStockIssue(Request $request)
    {
        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        $saleReturn = DB::table('sales_return')->where('invoice_number', $request->invoice_number)->first();
        if (!empty($saleReturn)) {
            return response()->json(['success' => false, 'message' => 'Sale Invoice already exits..', 'redirectUrl' => '/salesReturn/list'], 200);
        }


        $validator = Validator::make(
            $request->all(),
            [
                'invoice_number' => 'required',
                'invoice_date' => 'required',
                'branch_id' => 'required|numeric',
                'net_qty' => 'required|numeric|min:0|not_in:0',
            ],
            [
                'invoice_number.required' => 'The Invoice #  is required.',
                'invoice_date.required' => 'The Invoice Date  is required.',
                'branch_id.required' => 'The Branch is required.',
                'net_qty.required' => 'Net Qty   is required.',
            ]
        );
        if ($validator->fails()) {
            //$response['message'] = $validator->messages();
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 422);
        } else {

            $items_detail = array();
            $item_ids = $request->item_id;
            $item_qtys = $request->item_qty;
            $i = 0;
            foreach ($item_ids as $item) {
                $itemid = $item_ids[$i];
                $qty = $item_qtys[$i];
                if ($qty > 0) {
                    $items_detail[] = array(
                        'item_id' => $itemid,
                        'item_qty' => $qty
                    );
                }
                $i++;
            }
            /**
             * Insert Double entry
             *Sale Return A/c Debit
             *Customer A/c  Credit
             */

            $debit = array(
                'voucher_date' => $request->invoice_date,
                'voucher_number' => $request->invoice_number,
                'general_ledger_account_id' => Config::get('constants.STOCK_ISSUE_VOUCHER_PREFIX'),
                'note' => $request->note,
                'debit' => $request->net_total,
                'credit' => 0,
                'branch' => Auth::user()->branch,
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->insertDoubleEntry($debit);
            $customer = DB::table('branches')->where('id', $request->branch_id)->first();
            $credit = array(
                'voucher_date' => $request->invoice_date,
                'voucher_number' => $request->invoice_number,
                'general_ledger_account_id' => $customer->id,
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
             */
            foreach ($items_detail as $_detail) {
                $item = DB::table('items')->where('id', $_detail['item_id'])->first();
                $category = DB::table('category')->where('id', $item->category)->first();
                $company = DB::table('companyinfo')->first();
                if ($company->stock_calculation == 0) {
                    $debit = array(
                        'voucher_date' => $request->invoice_date,
                        'voucher_number' => $request->invoice_number,
                        'general_ledger_account_id' => $category->general_ledger_account_id,
                        'note' => $item->name . ' ' . $_detail['item_qty'],
                        'debit' => $_detail['item_qty'],
                        'credit' => 0,
                        'branch' => Auth::user()->branch,
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    $this->insertDoubleEntry($debit);
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
            $stock_issue = array(
                'net_qty' => $request->net_qty,
                'created_at' => date('Y-m-d H:i:s'),
                'branch_id' => $request->branch_id,
                'items_detail' => serialize($items_detail),
                'voucher_number' => $request->invoice_number,
                'note' => $request->note,
                'voucher_date' => $request->invoice_date,
                'branch' => Auth::user()->branch,
            );
            $sale = DB::table('branch_stock_transaction')->insert($stock_issue);
            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $request->invoice_number,
                'transaction_action' => 'Created',
                'transaction_detail' => serialize($stock_issue),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Stock Issue',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'Stock Issue added successfully..', 'redirectUrl' => '/stockIssue/list'], 200);
        }
    }

    public function editStockIssue($id)
    {
        $stock_issue = DB::table('branch_stock_transaction')->where('id', $id)->first();
        $branches = DB::table('branches')->where('branch', Auth::user()->branch)->get();
        $items = DB::table('items')->where('branch', Auth()->user()->branch)->get();
        return view('inventory.newStockIssue', array('stock_issue' => $stock_issue, 'branches' => $branches, 'items' => $items));
    }


    public function updateSaleReturn(Request $request)
    {
        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');


        $validator = Validator::make(
            $request->all(),
            [
                'invoice_number' => 'required',
                'invoice_date' => 'required',
                'branch_id' => 'required|numeric',
                'net_qty' => 'required|numeric|min:0|not_in:0',
            ],
            [
                'invoice_number.required' => 'The Invoice #  is required.',
                'invoice_date.required' => 'The Invoice Date  is required.',
                'branch_id.required' => 'The Branch is required.',
                'net_qty.required' => 'Net Qty   is required.',
            ]
        );
        if ($validator->fails()) {
            //$response['message'] = $validator->messages();
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 422);
        } else {

            $items_detail = array();
            $item_ids = $request->item_id;
            $item_qtys = $request->item_qty;
            $i = 0;
            foreach ($item_ids as $item) {
                $itemid = $item_ids[$i];
                $qty = $item_qtys[$i];
                if ($qty > 0) {
                    $items_detail[] = array(
                        'item_id' => $itemid,
                        'item_qty' => $qty
                    );
                }

                $i++;
            }
            /**
             * Insert Double entry
             *Sale Return A/c Debit
             *Customer A/c  Credit
             */
            $this->deleteDoubleEntry($request->invoice_number);
            $this->stockManagementEntryDelete($request->invoice_number);
            $debit = array(
                'voucher_date' => $request->invoice_date,
                'voucher_number' => $request->invoice_number,
                'general_ledger_account_id' => Config::get('constants.SALE_RETURN_ACCOUNT_GENERAL_LEDGER'),
                'note' => $request->note,
                'debit' => $request->net_total,
                'credit' => 0,
                'branch' => Auth::user()->branch,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->insertDoubleEntry($debit);
            $branches = DB::table('branches')->where('id', $request->branch_id)->first();
            $credit = array(
                'voucher_date' => $request->invoice_date,
                'voucher_number' => $request->invoice_number,
                'general_ledger_account_id' => $branches->id,
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
                        'note' => $item->name . ' ' . $_detail['item_qty'],
                        'debit' => $_detail['item_qty'],
                        'credit' => 0,
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

            $stock_issue = array(
                'net_qty' => $request->net_qty,
                'updated_at' => date('Y-m-d H:i:s'),
                'branch_id' => $request->branch_id,
                'items_detail' => serialize($items_detail),
                'voucher_number' => $request->invoice_number,
                'note' => $request->note,
                'voucher_date' => $request->invoice_date,
                'branch' => Auth::user()->branch,
            );
            $sale = DB::table('branch_stock_transaction')->where('id', $request->id)->update($stock_issue);
            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $request->invoice_number,
                'transaction_action' => 'Updated',
                'transaction_detail' => serialize($sale),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Stock Issue',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'Stock Issue updated successfully..', 'redirectUrl' => '/stockIssue/list'], 200);
        }
    }
    public function deleteStockIssue($id)
    {
        $stock_issue = DB::table('branch_stock_transaction')->where('id', $id)->first();
        $log = array(
            'user_id' => Auth::user()->id,
            'voucher_number' => $stock_issue->voucher_number,
            'transaction_action' => 'Deleted',
            'transaction_detail' => serialize($stock_issue),
            'branch' => Auth::user()->branch,
            'transaction_type' => 'Stock Issue',
            'created_at' => date('Y-m-d H:i:s'),
        );
        $this->addTransactionLog($log);
        $this->stockManagementEntryDelete($stock_issue->voucher_number);
        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        $this->deleteDoubleEntry($stock_issue->voucher_number);
        DB::table('branch_stock_transaction')->where('voucher_number', $stock_issue->voucher_number)->delete();
        return response()->json(['success' => true, 'message' => 'Stock Issue deleted successfully..', 'redirectUrl' => '/stockIssue/list'], 200);
    }

    public function searchStockIssue(Request $request)
    {
        $Queries = array();
        if (empty($request->from_date) && empty($request->to_date) && empty($request->branch_id) && empty($request->invoice_number)) {
            return redirect('stockIssue/list');
        }
        $query = DB::table('branch_stock_transaction');
        $query->join('branches', 'branch_stock_transaction.branch_id', '=', 'branches.id');
        $query->select('branch_stock_transaction.*', 'branches.name');
        if (isset($request->invoice_number) && !empty($request->invoice_number)) {
            $Queries['invoice_number'] = $request->invoice_number;
            $query->where('branch_stock_transaction.voucher_number', 'like', "%$request->invoice_number%");
        }

        if (isset($request->branch_id)) {
            $Queries['branch_id'] = $request->branch_id;
            $query->where('branch_stock_transaction.branch_id', '=', $request->branch_id);
        }
        if (isset($request->from_date) && isset($request->to_date)) {
            $Queries['from_date'] = $request->from_date;
            $Queries['to_date'] = $request->to_date;
            $query->whereBetween('branch_stock_transaction.voucher_date', [$request->from_date, $request->to_date]);
        }

        $result = $query->where('branch_stock_transaction.branch', Auth::user()->branch)->orderByDesc('branch_stock_transaction.id')->paginate(20);
        $result->appends($Queries);
        $net_qty = $query->sum('net_qty');
        $branches = DB::table('branches')->get();
        return view('inventory.stockIssueList', array('lists' => $result, 'from_date' => $request->from_date, 'to_date' => $request->to_date, 'branch_id' => $request->branch_id, 'invoice_number' => $request->invoice_number, 'queries' => $Queries, 'net_qty' => $net_qty, 'branches' => $branches));
    }
    public function stockIssuePdf($from_date, $to_date, $branch_id, $invoice_number)
    {
        $query = DB::table('branch_stock_transaction')
            ->join('branches', 'branch_stock_transaction.branch_id', '=', 'branches.id')
            ->select('branch_stock_transaction.*', 'branches.name')->where('branch_stock_transaction.branch', Auth::user()->branch);
        if ($from_date != 'none' && $to_date != 'none') {
            $query->whereBetween('branch_stock_transaction.voucher_date', [$from_date, $to_date]);
        }
        if ($invoice_number != 'none') {
            $query->where('branch_stock_transaction.voucher_number', $invoice_number);
        }
        if ($branch_id != 'none') {
            $query->where('branches.id', $branch_id);
        }
        $list = $query->orderByDesc('branch_stock_transaction.id')->get();
        $net = $query->sum('net_qty');
        $companyinfo = DB::table('companyinfo')->first();
        $companyinfo->logo = url('/') . $companyinfo->logo;
        $data = array('lists' => $list, 'net' => $net, 'companyinfo' => $companyinfo);
        $pdf = PDF::loadView('inventory.stockIssuePdf', $data);
        return $pdf->stream('pagePdf.pdf');
    }
    public function stockIssuesecondPdf($from_date, $to_date, $branch_id, $invoice_number)
    {
        $query = DB::table('branch_stock_transaction')
            ->join('branches', 'branch_stock_transaction.branch_id', '=', 'branches.id')
            ->select('branch_stock_transaction.*', 'branches.name')->where('branch_stock_transaction.branch', Auth::user()->branch);
        if ($from_date != 'none' && $to_date != 'none') {
            $query->whereBetween('branch_stock_transaction.voucher_date', [$from_date, $to_date]);
        }
        if ($invoice_number != 'none') {
            $query->where('branch_stock_transaction.voucher_number', $invoice_number);
        }
        if ($branch_id != 'none') {
            $query->where('branches.id', $branch_id);
        }
        $list = $query->orderByDesc('branch_stock_transaction.id')->get();  
        $items = DB::table('items')->where('branch', Auth()->user()->branch)->get();
        $net = $query->sum('net_qty');
        $companyinfo = DB::table('companyinfo')->first();
        $companyinfo->logo = url('/') . $companyinfo->logo;
        $data = array('lists' => $list, 'net' => $net, 'companyinfo' => $companyinfo,'items'=>$items);
        $pdf = PDF::loadView('inventory.stockIssueSecondPdf', $data);
        return $pdf->stream('pagePdf.pdf');
    }
    public function stockRecordPdf($id)
    {

        $stock_issue = DB::table('branch_stock_transaction')
            ->leftJoin('branches', 'branch_stock_transaction.branch_id', '=', 'branches.id')
            ->where('branch_stock_transaction.id', $id)
            ->first();
        $items = DB::table('items')->where('branch', Auth()->user()->branch)->get();
        $companyinfo = DB::table('companyinfo')->first();
        $companyinfo->logo = url('/') . $companyinfo->logo;
        $data = array('stock_issue' => $stock_issue, 'items' => $items, 'companyinfo' => $companyinfo);
        $pdf = PDF::loadView('inventory.stockRecordPdf', $data);
        return $pdf->stream('recordPdf.pdf');
    }


    // Stock Received
    public function stockReceivedList()
    {
        $lists = DB::table('branch_stock_received')->join('branches', 'branches.id', '=', 'branch_stock_received.branch_id')->select('branch_stock_received.*', 'branches.name')->where('branch_stock_received.branch', Auth::user()->branch)->paginate(20);
        $net_qty = DB::table('branch_stock_received')->join('branches', 'branches.id', '=', 'branch_stock_received.branch_id')->select('branch_stock_received.*', 'branches.name')->where('branch_stock_received.branch', Auth::user()->branch)->sum('net_qty');
        $branches = DB::table('branches')->get();
        return view('inventory.stockReceivedList', ['lists' => $lists, 'branches' => $branches, 'net_qty' => $net_qty]);
    }
    public function newStockReceived()
    {
        $branches = DB::table('branches')->get();
        $invoice_number = DB::table('branch_stock_received')->max('id') + 1;
        $items = DB::table('items')->where('branch', Auth()->user()->branch)->whereIn('category', [3,4, 5, 6])->get();
        return view('inventory.newStockReceived', ['items' => $items, 'invoice_number' => $invoice_number, 'branches' => $branches]);
    }

    public function saveStockReceived(Request $request)
    {
        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        $saleReturn = DB::table('branch_stock_received')->where('voucher_number', $request->invoice_number)->first();
        if (!empty($saleReturn)) {
            return response()->json(['success' => false, 'message' => 'Invoice already exits..', 'redirectUrl' => '/salesReturn/list'], 200);
        }


        $validator = Validator::make(
            $request->all(),
            [
                'invoice_number' => 'required',
                'invoice_date' => 'required',
                'branch_id' => 'required|numeric',
                'net_qty' => 'required|numeric|min:0|not_in:0',
            ],
            [
                'invoice_number.required' => 'The Invoice #  is required.',
                'invoice_date.required' => 'The Invoice Date  is required.',
                'branch_id.required' => 'The Branch is required.',
                'net_qty.required' => 'Net Qty   is required.',
            ]
        );
        if ($validator->fails()) {
            //$response['message'] = $validator->messages();
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 422);
        } else {

            $items_detail = array();
            $item_ids = $request->item_id;
            $item_qtys = $request->item_qty;
            $i = 0;
            foreach ($item_ids as $item) {
                $itemid = $item_ids[$i];
                $qty = $item_qtys[$i];
                if ($qty > 0) {
                    $items_detail[] = array(
                        'item_id' => $itemid,
                        'item_qty' => $qty
                    );
                }
                $i++;
            }
            /**
             * Insert Double entry
             *Sale Return A/c Debit
             *Customer A/c  Credit
             */

            $debit = array(
                'voucher_date' => $request->invoice_date,
                'voucher_number' => $request->invoice_number,
                'general_ledger_account_id' => Config::get('constants.STOCK_RECEIVED_VOUCHER_PREFIX'),
                'note' => $request->note,
                'debit' => $request->net_total,
                'credit' => 0,
                'branch' => Auth::user()->branch,
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->insertDoubleEntry($debit);
            $customer = DB::table('branches')->where('id', $request->branch_id)->first();
            $credit = array(
                'voucher_date' => $request->invoice_date,
                'voucher_number' => $request->invoice_number,
                'general_ledger_account_id' => $customer->id,
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
             */
            foreach ($items_detail as $_detail) {
                $item = DB::table('items')->where('id', $_detail['item_id'])->first();
                $category = DB::table('category')->where('id', $item->category)->first();
                $company = DB::table('companyinfo')->first();
                if ($company->stock_calculation == 0) {
                    $debit = array(
                        'voucher_date' => $request->invoice_date,
                        'voucher_number' => $request->invoice_number,
                        'general_ledger_account_id' => $category->general_ledger_account_id,
                        'note' => $item->name . ' ' . $_detail['item_qty'],
                        'debit' => $_detail['item_qty'],
                        'credit' => 0,
                        'branch' => Auth::user()->branch,
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    $this->insertDoubleEntry($debit);
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
                                'transaction_type' => '+',
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
                    'transaction_type' => '+',
                    'general_ledger_account_id' => $category->general_ledger_account_id,
                    'item_qty' => $_detail['item_qty'],
                    'item_id' => $_detail['item_id'],
                    'branch' => Auth::user()->branch,
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $this->stockManagementEntry($stock);
            }
            $stock_received = array(
                'net_qty' => $request->net_qty,
                'created_at' => date('Y-m-d H:i:s'),
                'branch_id' => $request->branch_id,
                'items_detail' => serialize($items_detail),
                'voucher_number' => $request->invoice_number,
                'note' => $request->note,
                'voucher_date' => $request->invoice_date,
                'branch' => Auth::user()->branch,
            );
            $sale = DB::table('branch_stock_received')->insert($stock_received);
            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $request->invoice_number,
                'transaction_action' => 'Created',
                'transaction_detail' => serialize($stock_received),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Stock Received',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'Stock Received added successfully..', 'redirectUrl' => '/stockReceived/list'], 200);
        }
    }


    public function editStockReceived($id)
    {
        $stock_issue = DB::table('branch_stock_received')->where('id', $id)->first();
        $branches = DB::table('branches')->where('branch', Auth::user()->branch)->get();
        $items = DB::table('items')->where('branch', Auth()->user()->branch)->get();
        return view('inventory.newStockReceived', array('stock_issue' => $stock_issue, 'branches' => $branches, 'items' => $items));
    }

    public function updateStockReceived(Request $request)
    {
        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');


        $validator = Validator::make(
            $request->all(),
            [
                'invoice_number' => 'required',
                'invoice_date' => 'required',
                'branch_id' => 'required|numeric',
                'net_qty' => 'required|numeric|min:0|not_in:0',
            ],
            [
                'invoice_number.required' => 'The Invoice #  is required.',
                'invoice_date.required' => 'The Invoice Date  is required.',
                'branch_id.required' => 'The Branch is required.',
                'net_qty.required' => 'Net Qty   is required.',
            ]
        );
        if ($validator->fails()) {
            //$response['message'] = $validator->messages();
            return response()->json(array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()

            ), 422);
        } else {

            $items_detail = array();
            $item_ids = $request->item_id;
            $item_qtys = $request->item_qty;
            $i = 0;
            foreach ($item_ids as $item) {
                $itemid = $item_ids[$i];
                $qty = $item_qtys[$i];
                if ($qty > 0) {
                    $items_detail[] = array(
                        'item_id' => $itemid,
                        'item_qty' => $qty
                    );
                }

                $i++;
            }
            /**
             * Insert Double entry
             *Sale Return A/c Debit
             *Customer A/c  Credit
             */
            $this->deleteDoubleEntry($request->invoice_number);
            $this->stockManagementEntryDelete($request->invoice_number);
            $debit = array(
                'voucher_date' => $request->invoice_date,
                'voucher_number' => $request->invoice_number,
                'general_ledger_account_id' => Config::get('constants.SALE_RETURN_ACCOUNT_GENERAL_LEDGER'),
                'note' => $request->note,
                'debit' => $request->net_total,
                'credit' => 0,
                'branch' => Auth::user()->branch,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->insertDoubleEntry($debit);
            $branches = DB::table('branches')->where('id', $request->branch_id)->first();
            $credit = array(
                'voucher_date' => $request->invoice_date,
                'voucher_number' => $request->invoice_number,
                'general_ledger_account_id' => $branches->id,
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
                        'note' => $item->name . ' ' . $_detail['item_qty'],
                        'debit' => $_detail['item_qty'],
                        'credit' => 0,
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
                                'transaction_type' => '+',
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
                    'transaction_type' => '+',
                    'general_ledger_account_id' => $category->general_ledger_account_id,
                    'item_qty' => $_detail['item_qty'],
                    'item_id' => $_detail['item_id'],
                    'branch' => Auth::user()->branch,
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $this->stockManagementEntry($stock);
            }

            $stock_rec = array(
                'net_qty' => $request->net_qty,
                'updated_at' => date('Y-m-d H:i:s'),
                'branch_id' => $request->branch_id,
                'items_detail' => serialize($items_detail),
                'voucher_number' => $request->invoice_number,
                'note' => $request->note,
                'voucher_date' => $request->invoice_date,
                'branch' => Auth::user()->branch,
            );
            $sale = DB::table('branch_stock_received')->where('id', $request->id)->update($stock_rec);
            $log = array(
                'user_id' => Auth::user()->id,
                'voucher_number' => $request->invoice_number,
                'transaction_action' => 'Updated',
                'transaction_detail' => serialize($sale),
                'branch' => Auth::user()->branch,
                'transaction_type' => 'Stock Received',
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->addTransactionLog($log);
            return response()->json(['success' => true, 'message' => 'Stock Received updated successfully..', 'redirectUrl' => '/stockReceived/list'], 200);
        }
    }

    public function deleteStockReceived($id)
    {
        $stock_issue = DB::table('branch_stock_received')->where('id', $id)->first();
        $log = array(
            'user_id' => Auth::user()->id,
            'voucher_number' => $stock_issue->voucher_number,
            'transaction_action' => 'Deleted',
            'transaction_detail' => serialize($stock_issue),
            'branch' => Auth::user()->branch,
            'transaction_type' => 'Stock Received',
            'created_at' => date('Y-m-d H:i:s'),
        );
        $this->addTransactionLog($log);
        $this->stockManagementEntryDelete($stock_issue->voucher_number);
        $response = array('success' => false, 'message' => '', 'redirectUrl' => '');
        $this->deleteDoubleEntry($stock_issue->voucher_number);
        DB::table('branch_stock_received')->where('voucher_number', $stock_issue->voucher_number)->delete();
        return response()->json(['success' => true, 'message' => 'Stock Received deleted successfully..', 'redirectUrl' => '/stockReceived/list'], 200);
    }

    public function searchStockReceived(Request $request)
    {
        $Queries = array();
        if (empty($request->from_date) && empty($request->to_date) && empty($request->branch_id) && empty($request->invoice_number)) {
            return redirect('stockReceived/list');
        }
        $query = DB::table('branch_stock_received');
        $query->join('branches', 'branch_stock_received.branch_id', '=', 'branches.id');
        $query->select('branch_stock_received.*', 'branches.name');
        if (isset($request->invoice_number) && !empty($request->invoice_number)) {
            $Queries['invoice_number'] = $request->invoice_number;
            $query->where('branch_stock_received.voucher_number', 'like', "%$request->invoice_number%");
        }

        if (isset($request->branch_id)) {
            $Queries['branch_id'] = $request->branch_id;
            $query->where('branch_stock_received.branch_id', '=', $request->branch_id);
        }
        if (isset($request->from_date) && isset($request->to_date)) {
            $Queries['from_date'] = $request->from_date;
            $Queries['to_date'] = $request->to_date;
            $query->whereBetween('branch_stock_received.voucher_date', [$request->from_date, $request->to_date]);
        }

        $result = $query->where('branch_stock_received.branch', Auth::user()->branch)->orderByDesc('branch_stock_received.id')->paginate(20);
        $result->appends($Queries);
        $net_qty = $query->sum('net_qty');
        $branches = DB::table('branches')->get();
        return view('inventory.stockReceivedList', array('lists' => $result, 'from_date' => $request->from_date, 'to_date' => $request->to_date, 'branch_id' => $request->branch_id, 'invoice_number' => $request->invoice_number, 'queries' => $Queries, 'net_qty' => $net_qty, 'branches' => $branches));
    }
    public function stockReceivedPdf($from_date, $to_date, $branch_id, $invoice_number)
    {
        $query = DB::table('branch_stock_received')
            ->join('branches', 'branch_stock_received.branch_id', '=', 'branches.id')
            ->select('branch_stock_received.*', 'branches.name')->where('branch_stock_received.branch', Auth::user()->branch);
        if ($from_date != 'none' && $to_date != 'none') {
            $query->whereBetween('branch_stock_received.voucher_date', [$from_date, $to_date]);
        }
        if ($invoice_number != 'none') {
            $query->where('branch_stock_received.voucher_number', $invoice_number);
        }
        if ($branch_id != 'none') {
            $query->where('branches.id', $branch_id);
        }
        $list = $query->orderByDesc('branch_stock_received.id')->get();
        $net = $query->sum('net_qty');
        $companyinfo = DB::table('companyinfo')->first();
        $companyinfo->logo = url('/') . $companyinfo->logo;
        $data = array('lists' => $list, 'net' => $net, 'companyinfo' => $companyinfo);
        $pdf = PDF::loadView('inventory.stockReceivedPdf', $data);
        return $pdf->stream('pagePdf.pdf');
    }
    public function stockReceivedsecondPdf($from_date, $to_date, $branch_id, $invoice_number)
    {
        $query = DB::table('branch_stock_received')
            ->join('branches', 'branch_stock_received.branch_id', '=', 'branches.id')
            ->select('branch_stock_received.*', 'branches.name')->where('branch_stock_received.branch', Auth::user()->branch);
        if ($from_date != 'none' && $to_date != 'none') {
            $query->whereBetween('branch_stock_received.voucher_date', [$from_date, $to_date]);
        }
        if ($invoice_number != 'none') {
            $query->where('branch_stock_received.voucher_number', $invoice_number);
        }
        if ($branch_id != 'none') {
            $query->where('branches.id', $branch_id);
        }
        $list = $query->orderByDesc('branch_stock_received.id')->get();
        $net = $query->sum('net_qty');
        $companyinfo = DB::table('companyinfo')->first();
        $companyinfo->logo = url('/') . $companyinfo->logo;
        $items = DB::table('items')->where('branch', Auth()->user()->branch)->get();
        $data = array('lists' => $list, 'net' => $net, 'companyinfo' => $companyinfo,'items'=>$items);
        $pdf = PDF::loadView('inventory.stockReceivedsecondPdf', $data);
        return $pdf->stream('pagePdf.pdf');
    }

    public function stockReceivedRecordPdf($id)
    {
        $stock_issue = DB::table('branch_stock_received')
        ->leftJoin('branches', 'branch_stock_received.branch_id', '=', 'branches.id')
        ->where('branch_stock_received.id', $id)
        ->first();
    $items = DB::table('items')->where('branch', Auth()->user()->branch)->get();
    $companyinfo = DB::table('companyinfo')->first();
    $companyinfo->logo = url('/') . $companyinfo->logo;
    $data = array('stock_issue' => $stock_issue, 'items' => $items, 'companyinfo' => $companyinfo);
    $pdf = PDF::loadView('inventory.stockReceivedRecord', $data);
    return $pdf->stream('recordPdf.pdf');
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
            DB::table('general_ledger_transactions')->where('voucher_number', $data['voucher_number'])->delete();
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

    public function stockManagementEntryDelete($voucher_number)
    {
        try {
            DB::table('general_inventory_transactions')->where('voucher_number', $voucher_number)->delete();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), 'redirectUrl' => '/sales/list'], 200);
        }
    }

    public function addTransactionLog($data)
    {
        DB::table('transactions_log')->insertGetId($data);
    }

}
