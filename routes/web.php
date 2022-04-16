<?php

// use App\Http\Controllers\companyInfo;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\companyController;
use App\Http\Controllers\inventoryController;
use App\Http\Controllers\departmentController;
use App\Http\Controllers\employeeController;
use App\Http\Controllers\productionController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerRecController;
use App\Http\Controllers\VendorPaymentController;
use App\Http\Controllers\GeneralLedgerController;
use App\Http\Controllers\purchaseController;
use App\Http\Controllers\generalLedgerAccountsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\generalReceiptsController;
use App\Http\Controllers\generalPaymentController;
use App\Http\Controllers\salesReturnController;
use App\Http\Controllers\purchasesReturnController;
use App\Http\Controllers\employeePaymentController;
use App\Http\Controllers\workinProcessController;
use App\Http\Controllers\spoilageOrLossController;
use App\Http\Controllers\finishedGoodsController;
use App\Http\Controllers\journalVoucherController;
use App\Http\Controllers\toDoController;
use App\Http\Controllers\transactionLogController;
use App\Http\Controllers\advanceReturnController;
use App\Http\Controllers\ClientValidationController;
use App\Http\Controllers\tourController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [DashboardController::class, 'welcome'])->name('welcome');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user/list', [UserController::class, 'list'])->name('userlist');
    Route::get('/user/new', [UserController::class, 'new'])->name('newuser');
    Route::post('/user/save', [UserController::class, 'store'])->name('saveuser');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('edituser');
    Route::post('/user/update', [UserController::class, 'update'])->name('updateuser');
    Route::get('/user/delete/{id}', [UserController::class, 'delete'])->name('deleteuser');
    Route::get('/user/active', [UserController::class, 'active'])->name('activeuser');
    Route::get('/user/inactive', [UserController::class, 'inactive'])->name('inactiveuser');
    // Search user
    Route::any('user/searchResults', [UserController::class, 'search'])->name('searchUser');


    // Route for sales
    Route::get('/sales/list', [SaleController::class, 'saleList'])->name('saleslist');
    Route::get('/sales/new', [SaleController::class, 'newsale'])->name('newsale');
    Route::post('/sales/save', [SaleController::class, 'store'])->name('saveinvoice');
    Route::get('/sales/edit/{id}', [SaleController::class, 'edit'])->name('editinvoice');
    Route::post('/sales/update', [SaleController::class, 'update'])->name('updateinvoice');
    Route::get('/sales/delete/{id}', [SaleController::class, 'delete'])->name('deletinvoice');
    Route::get('/ledger/{general_ledger_account_id}', [GeneralLedgerController::class, 'ledger'])->name('ledger');
    Route::post('/searchledger', [GeneralLedgerController::class, 'searchledger'])->name('searchledger');
    // Sales Search
    Route::any('/sales/searchSales', [SaleController::class, 'searchSales'])->name('searchSales');
    // Record PDF generator
    Route::get('/sales/pdf/{id}', [SaleController::class, 'recordPDF'])->name('recordPDF');
    // Sale Page PDF generator
    Route::get('/sales/pagepdf/{from_date}/{to_date}/{customer_id}/{invoice_number}', [SaleController::class, 'salePagePDF'])->name('salePagePdf');
    Route::get('invoiceList/data', [SaleController::class, 'getInvoiceList'])->name('getData');
    Route::post('invoice/data', [SaleController::class, 'getSingleInvoiceData']);
    Route::get('invoice/getNumber', [SaleController::class, 'getNextInvoiceNumber']);
    Route::get('invoice/refund/{id}', [SaleController::class, 'invoiceRefund']);

    // Route for sales return
    Route::get('/salesReturn/list', [salesReturnController::class, 'saleReturnList'])->name('saleReturnList');
    Route::get('/salesReturn/new', [salesReturnController::class, 'newSaleReturn'])->name('newSaleReturn');
    Route::post('/salesReturn/save', [salesReturnController::class, 'saveSaleReturn'])->name('saveSaleReturn');
    Route::get('/salesReturn/edit/{id}', [salesReturnController::class, 'editSaleReturn'])->name('editSaleReturn');
    Route::post('/salesReturn/update', [salesReturnController::class, 'updateSaleReturn'])->name('updateSaleReturn');
    Route::get('/salesReturn/delete/{id}', [salesReturnController::class, 'deleteSaleReturn'])->name('deleteSaleReturn');
    // Search for sales return
    Route::any('/salesReturn/searchSales', [salesReturnController::class, 'searchSaleReturn'])->name('searchSaleReturn');
    // Record PDF generator
    Route::get('/salesReturn/pdf/{id}', [salesReturnController::class, 'recordPDF'])->name('saleReturnRecordPDF');
    // Sale Return Page pdf generator b
    Route::get('/salesReturn/pagepdf/{from_date}/{to_date}/{customer_id}/{invoice_number}', [salesReturnController::class, 'saleReturnPagePdf'])->name('saleReturnPagePdf');
//
//// travel Agent Crud

Route::get('/travelagentlist',[SaleController::class,'indexAgents'])->name('travelagentlist');
Route::get('/newtravelagent',[SaleController::class,'newTravelAgent'])->name('newtravelagent');
Route::post('/savetravelagent',[SaleController::class,'saveTravelAgent'])->name('savetravelagent');
Route::get('/travelagent/edit/{id}',[SaleController::class,'editTravelAgent'])->name('edittravelagent');
Route::post('/travelagent/update',[SaleController::class,'updateTravelAgent'])->name('updatetravelagent');
Route::get('/travelagent/delete/{id}',[SaleController::class,'deleteTravelAgent'])->name('deletetravelagent');
Route::get('/travelagent/commission/{id}',[SaleController::class,'TravelAgentCommission'])->name('TravelAgentCommission');
// tour details add routes
Route::get('/tourdetailslist',[SaleController::class,'tourDetailList'])->name('tourdetailslist');
Route::get('/newtour',[SaleController::class,'newTour'])->name('newtour');
Route::post('/savetour',[SaleController::class,'saveTour'])->name('savetour');
Route::get('/tourdetail/edit/{id}',[SaleController::class,'EditTourDetail'])->name('edittourdetail');
Route::post('/tourdetail/update',[SaleController::class,'updateTourDetail'])->name('updatetourdetail');
Route::get('/tourdetail/delete/{id}',[SaleController::class,'deleteTourDetail'])->name('deletetourdetail');
    //// travel Agent Crud

    // Route::get('/travelagentlist', [SaleController::class, 'indexAgents'])->name('travelagentlist');
    // Route::get('/newtravelagent', [SaleController::class, 'newTravelAgent'])->name('newtravelagent');
    // Route::post('/savetravelagent', [SaleController::class, 'saveTravelAgent'])->name('savetravelagent');
    // Route::get('/travelagent/edit/{id}', [SaleController::class, 'editTravelAgent'])->name('edittravelagent');
    // Route::post('/travelagent/update', [SaleController::class, 'updateTravelAgent'])->name('updatetravelagent');
    // Route::get('/travelagent/delete/{id}', [SaleController::class, 'deleteTravelAgent'])->name('deletetravelagent');
    // Route::get('/travelagent/commission/{id}', [SaleController::class, 'TravelAgentCommission'])->name('TravelAgentCommission');
    // tour details add routes
    // Route::get('/tourDetailslist', [SaleController::class, 'tourDetailList'])->name('tourDetailslist');
    // Route::get('/newtour', [SaleController::class, 'newTour'])->name('newtour');
    // Route::get('/savetour', [SaleController::class, 'saveTour'])->name('savetour');
    // Route::get('/tourdetail/edit/{id}', [SaleController::class, 'EditTourDetail'])->name('edittourdetail');
    // Route::get('/tourdetail/update', [SaleController::class, 'updateTourDetail'])->name('updatetourdetail');
    // Route::get('/tourdetail/delete/{id}', [SaleController::class, 'deleteTourDetail'])->name('deletetourdetail');



    // Route for Customer
    Route::get('/customer/list', [CustomerController::class, 'customerList'])->name('customerlist');
    Route::get('/customer/new', [CustomerController::class, 'newCustomer'])->name('newcustomer');
    Route::post('/customer/save', [CustomerController::class, 'storeCustomer'])->name('savecustomer');
    Route::get('/customer/edit/{id}', [CustomerController::class, 'editCustomer'])->name('editcustomer');
    Route::post('/customer/update', [CustomerController::class, 'updateCustomer'])->name('updatecustomer');
    Route::get('/customer/delete/{id}', [CustomerController::class, 'deleteCustomer'])->name('deletecustomer');
    // Search for customers
    Route::any('/customer/searchResults', [CustomerController::class, 'searchCustomers'])->name('searchCustomers');
    Route::get('/customer/customerLedgerPdf/{from_date}/{to_date}/{general_ledger_account_id}', [CustomerController::class, 'customerLedgerPdf'])->name('customerLedgerPdf');

    // Route for Vendor
    Route::get('/vendor/list', [VendorController::class, 'vendorList'])->name('vendorlist');
    Route::get('/vendor/new', [VendorController::class, 'newVendor'])->name('newvendor');
    Route::post('/vendor/new', [VendorController::class, 'storeVendor'])->name('savevendor');
    Route::get('/vendor/edit/{id}', [VendorController::class, 'editVendor'])->name('editvendor');
    Route::post('/vendor/update/', [VendorController::class, 'updateVendor'])->name('updatevendor');
    Route::get('/vendor/delete/{id}', [VendorController::class, 'deleteVendor'])->name('deletevendor');
    // Search for vendors
    Route::any('/vendor/searchResults', [VendorController::class, 'searchVendor'])->name('searchVendor');

    // Route for Purchases
    Route::get('/purchases/purchaselist', [purchaseController::class, 'purchaseList'])->name('purchaseList');
    Route::get('/purchasewiseitemlist', [purchaseController::class, 'purchasewiseitemlist'])->name('purchasewiseitemlist');
    Route::get('/purchases/new', [purchaseController::class, 'newPurchase'])->name('newPurchase');
    Route::post('/purchases/save', [purchaseController::class, 'savePurchase'])->name('savePurchase');
    Route::get('/purchases/edit/{id}', [purchaseController::class, 'editPurchase'])->name('editPurchase');
    Route::post('/purchases/update/', [purchaseController::class, 'updatePurchase'])->name('updatePurchase');
    Route::get('/purchases/delete/{id}', [purchaseController::class, 'deletePurchase'])->name('deletePurchase');
    // Search for purchase
    Route::any('/purchases/searchResults', [purchaseController::class, 'searchPurchase'])->name('searchPurchase');
    Route::any('/searchPurchaseitemwise/searchResults', [purchaseController::class, 'searchPurchaseitemwise'])->name('searchPurchaseitemwise');
    // Purchase page pdf
    Route::get('/purchases/purchasePagePdf/{from_date}/{to_date}/{vendor_id}/{invoice_number}', [purchaseController::class, 'purchasePagePdf'])->name('purchasePagePdf');
    Route::get('/purchasePdfitemwise/purchasePagePdf/{from_date}/{to_date}/{vendor_id}/{invoice_number}', [purchaseController::class, 'purchasePdfitemwise'])->name('purchasePdfitemwise');
    // Purchase Pdf
    Route::get('/purchases/purchasePdf/{id}', [purchaseController::class, 'purchasePdf'])->name('purchasePdf');



    // Route for Purchases return
    Route::get('/purchasesReturn/list', [purchasesReturnController::class, 'purchaseReturnList'])->name('purchaseReturnList');
    Route::get('/purchasesReturn/new', [purchasesReturnController::class, 'newPurchaseReturn'])->name('newPurchaseReturn');
    Route::post('/purchasesReturn/save', [purchasesReturnController::class, 'savePurchaseReturn'])->name('savePurchaseReturn');
    Route::get('/purchasesReturn/edit/{id}', [purchasesReturnController::class, 'editPurchaseReturn'])->name('editPurchaseReturn');
    Route::post('/purchasesReturn/update', [purchasesReturnController::class, 'updatePurchaseReturn'])->name('updatePurchaseReturn');
    Route::get('/purchasesReturn/delete/{id}', [purchasesReturnController::class, 'deletePurchaseReturn'])->name('deletePurchaseReturn');
    // Search for purchase return
    Route::any('/purchasesReturn/searchResults', [purchasesReturnController::class, 'searchPurchaseReturn'])->name('searchPurchaseReturn');
    // Purchase Return Page Pdf
    Route::get('/purchasesReturn/purchaseReturnPagePdf/{from_date}/{to_date}/{vendor_id}/{invoice_number}', [purchasesReturnController::class, 'purchaseReturnPagePdf'])->name('purchaseReturnPagePdf');
    // Purchase Return Pdf
    Route::get('/purchasesReturn/purchaseReturnPdf/{id}', [purchasesReturnController::class, 'purchaseReturnPdf'])->name('purchaseReturnPdf');




    // Route for Customer Receipt
    Route::get('customerreceipt/list', [CustomerRecController::class, 'customer_receipt_List'])->name('customer_receiptlist');
    Route::get('customerreceipt/new', [CustomerRecController::class, 'newCustomer_receipt'])->name('newcustomer_receipt');
    Route::post('customerreceipt/save', [CustomerRecController::class, 'store'])->name('savereceipt');
    Route::get('customerreceipt/edit/{id}', [CustomerRecController::class, 'editCustomer_receipt'])->name('editcustomer_receipt');
    Route::post('customerreceipt/update', [CustomerRecController::class, 'updateCustomer_receipt'])->name('updatecustomer_receipt');
    Route::get('customerreceipt/delete/{id}', [CustomerRecController::class, 'deleteCustomer_receipt'])->name('deletecustomer_receipt');
    // Search for customer receipt
    Route::any('customerreceipt/searchResult', [CustomerRecController::class, 'searchCustomerReceipt'])->name('searchCustomerReceipt');
    // Customer Receipt record PDF
    Route::get('customerreceipt/pdf/{id}', [CustomerRecController::class, 'recordPdf'])->name('customerRecieptRecordPdf');
    // Customer Receipt page pdf
    Route::get('customerreceipt/pagepdf/{from_date}/{to_date}/{customer_name}/{invoice_number}', [CustomerRecController::class, 'pagePdf'])->name('customerRecPagePdf');



    // Route for vendor payment
    Route::get('vendorpayment/list', [VendorPaymentController::class, 'venoderPaymentList'])->name('vendorpaymentlist');
    Route::get('vendorpayment/new', [VendorPaymentController::class, 'newVenoderPayment'])->name('newvendorpayment');
    Route::post('vendorpayment/save', [VendorPaymentController::class, 'store'])->name('savevendorpayment');
    Route::get('vendorpayment/edit/{id}', [VendorPaymentController::class, 'editVendorPayment'])->name('editVendorPayment');
    Route::post('vendorpayment/update', [VendorPaymentController::class, 'update'])->name('updatevendorpayment');
    Route::get('vendorpayment/delete/{id}', [VendorPaymentController::class, 'deleteVendorPayment'])->name('deleteVendorPayment');
    // Search for vendor payment
    Route::any('vendorpayment/searchResults', [VendorPaymentController::class, 'searchVendorPayment'])->name('searchVendorPayment');
    // Vendor Payment record pdf
    Route::get('vendorpayment/pdf/{id}', [VendorPaymentController::class, 'recordPdf'])->name('vendorRecordPdf');
    // Vendor Payment page pdf
    Route::get('vendorpayment/pagepdf/{from_date}/{to_date}/{vendor_name}/{invoice_number}', [VendorPaymentController::class, 'pagePdf'])->name('vendorPagePdf');


    // Rout for menu
    Route::get('/menu/menuList', [MenuController::class, 'menuList'])->name('menulist');
    Route::get('/menu/newmenu', [MenuController::class, 'newMenu'])->name('newmenu');
    Route::post('/menu/save', [MenuController::class, 'storeMenu'])->name('savemenu');
    Route::get('/menu/edit/{id}', [MenuController::class, 'editMenu'])->name('editmenu');
    Route::post('/menu/update', [MenuController::class, 'updateMenu'])->name('updatemenu');
    Route::get('/menu/delete/{id}', [MenuController::class, 'deleteMenu'])->name('deletmenu');
    Route::get('/menu/active', [MenuController::class, 'activeMenu'])->name('activemenu');
    Route::get('/menu/inactive', [MenuController::class, 'inactiveMenu'])->name('inactivemenu');
    /////end menu


    // Rout for company
    Route::get('/company/comlist', [companyController::class, 'comList'])->name('companylist');
    Route::get('/company/comedit/{id}', [companyController::class, 'editCompany'])->name('editcompany');
    Route::post('/company/comupdate', [companyController::class, 'updateCompany'])->name('updatecompany');
    // Rout for category
    Route::get('/category/categoryList', [inventoryController::class, 'categoryList'])->name('categorylist');
    Route::get('/category/newcategory', [inventoryController::class, 'newCategory'])->name('newcategory');
    Route::post('/category/save', [inventoryController::class, 'storeCategory'])->name('savecategory');
    Route::get('/category/edit/{id}', [inventoryController::class, 'editCategory'])->name('editcategory');
    Route::post('/category/update', [inventoryController::class, 'updateCategory'])->name('updatecategory');
    Route::get('/category/delete/{id}', [inventoryController::class, 'deleteCategory'])->name('deletecategory');

    // Route for Units Category Inventory Management
    Route::get('/unitlist', [inventoryController::class, 'unitCategory'])->name('unitlist');
    Route::get('/addunit', [inventoryController::class, 'newUnitAdd'])->name('newunit');
    Route::post('/saveunit', [inventoryController::class, 'storeUnit'])->name('saveunit');
    Route::get('/edit/{id}', [inventoryController::class, 'editUnit'])->name('editunit');
    Route::post('/update', [inventoryController::class, 'updateUnit'])->name('updateunit');
    Route::get('/delete/{id}', [inventoryController::class, 'deleteUnit'])->name('deleteunit');
    //////////////////////
    ///// start hotelroom routes
    Route::get('/hotelroom', [inventoryController::class, 'HotelRoom'])->name('hotelroom');
    Route::get('/addhotelroom', [inventoryController::class, 'newHotelRoom'])->name('newhotelroom');
    Route::post('/savehotelroom', [inventoryController::class, 'storeHotelRoom'])->name('savehotelroom');
    Route::get('/edit/{id}', [inventoryController::class, 'editHotelRoom'])->name('edithotelroom');
    Route::post('/update', [inventoryController::class, 'updateHotelRoom'])->name('updatehotelroom');
    Route::get('/delete/{id}', [inventoryController::class, 'deleteHotelRoom'])->name('deletehotelroom');


    // Route for Units Category Inventory Management
    Route::get('/unitlist', [inventoryController::class, 'unitCategory'])->name('unitlist');
    Route::get('/addunit', [inventoryController::class, 'newUnitAdd'])->name('newunit');
    Route::post('/saveunit', [inventoryController::class, 'storeUnit'])->name('saveunit');
    Route::get('/edit/{id}', [inventoryController::class, 'editUnit'])->name('editunit');
    Route::post('/update', [inventoryController::class, 'updateUnit'])->name('updateunit');
    Route::get('/delete/{id}', [inventoryController::class, 'deleteUnit'])->name('deleteunit');
    //////////////////////
    ///// start hotelroom routes
    Route::get('/hotelroomlist', [inventoryController::class, 'HotelRoom'])->name('hotelroomlist');
    Route::get('/addhotelroom', [inventoryController::class, 'newHotelRoom'])->name('newhotelroom');
    Route::post('/savehotelroom', [inventoryController::class, 'storeHotelRoom'])->name('savehotelroom');
    Route::get('/edit/{id}', [inventoryController::class, 'editHotelRoom'])->name('edithotelroom');
    Route::post('/update', [inventoryController::class, 'updateHotelRoom'])->name('updatehotelroom');
    Route::get('/delete/{id}', [inventoryController::class, 'deleteHotelRoom'])->name('deletehotelroom');

    ////////////////////// end hotel room
    // Rout for Items
    Route::get('/item/itemList', [inventoryController::class, 'itemList'])->name('itemlist');
    Route::get('/item/newitem', [inventoryController::class, 'newItem'])->name('newitem');
    Route::post('/item/save', [inventoryController::class, 'storeItem'])->name('saveitem');
    Route::get('/item/edit/{id}', [inventoryController::class, 'editItem'])->name('edititem');
    Route::post('/item/update', [inventoryController::class, 'updateItem'])->name('updateitem');
    Route::get('/item/delete/{id}', [inventoryController::class, 'deleteItem'])->name('deleteitem');
    Route::any('/item/searchResults', [inventoryController::class, 'searchItems'])->name('searchItems');
    Route::any('/getItemlistbycategory/{id}', [inventoryController::class, 'getItemListByCategory'])->name('categorybylist');
    Route::any('/getItemlistbycategory/{id}', [inventoryController::class, 'getItemListByCategory'])->name('categorybylist');
    Route::any('/getItemlistbycategory/{id}', [inventoryController::class, 'getItemListByCategory'])->name('categorybylist');
    Route::any('/rawMaterial', [inventoryController::class, 'rawMaterial'])->name('rawMaterial');
    //    Items ledger entries (get)
    Route::get('/item/ledgerEntries/{id}', [inventoryController::class, 'itemLedgerEntries'])->name('itemLedgerEntries');
    Route::get('/item/itemsLedgerPagePdf/{from_date}/{to_date}/{invoice_number}/{item_ledger_id}', [inventoryController::class, 'itemsLedgerPagePdf'])->name('itemsLedgerPagePdf');
    Route::post('/item/searchItemsledger', [inventoryController::class, 'searchItemsledger'])->name('searchItemsledger');
    //    Route for item menu 
    Route::get('/itemMenu/itemMenulist', [inventoryController::class, 'itemMenuList'])->name('itemMenuList');
    Route::get('/itemMenu/newItemMenu', [inventoryController::class, 'newitemMenu'])->name('newitemMenu');
    Route::post('/itemMenu/saveItemMenu', [inventoryController::class, 'saveItemMenu'])->name('saveItemMenu');
    Route::get('/itemMenu/editItemMenu/{id}', [inventoryController::class, 'editItemMenu'])->name('editItemMenu');
    Route::post('/itemMenu/updateItemMenu', [inventoryController::class, 'updateItemMenu'])->name('updateItemMenu');
    Route::get('/itemMenu/deleteItemMenu/{id}', [inventoryController::class, 'deleteItemMenu'])->name('deleteItemMenu');

    // Branches 
    Route::get('branches/list', [inventoryController::class, 'branchesList'])->name('branchesList');
    Route::get('branches/new', [inventoryController::class, 'newBranch'])->name('newBranch');
    Route::post('branches/store', [inventoryController::class, 'saveBranch'])->name('saveBranch');
    Route::get('branches/edit/{id}', [inventoryController::class, 'editBranch'])->name('editBranch');
    Route::post('branches/update', [inventoryController::class, 'updateBranch'])->name('updateBranch');
    Route::get('branches/delete/{id}', [inventoryController::class, 'deleteBranch'])->name('deleteBranch');

    // Stock Issues Crud 
    Route::get('stockIssue/list', [inventoryController::class, 'stockIssuesList'])->name('stockIssuesList');
    Route::get('stockIssue/new', [inventoryController::class, 'newStockIssue'])->name('newStockIssue');
    Route::post('stockIssue/store', [inventoryController::class, 'saveStockIssue'])->name('saveStockIssue');
    Route::get('stockIssue/edit/{id}', [inventoryController::class, 'editStockIssue'])->name('editStockIssue');
    Route::post('stockIssue/update', [inventoryController::class, 'stockIssues'])->name('stockIssues');
    Route::get('stockIssue/delete/{id}', [inventoryController::class, 'deleteStockIssue'])->name('deleteStockIssue');
    // Search Stock Issue 
    Route::any('stockIssue/searchResult', [inventoryController::class, 'searchStockIssue'])->name('searchStockIssue');
    // Stock Issue Pdf 
    Route::get('/stockIssue/pagepdf/{from_date}/{to_date}/{branch_id}/{invoice_number}', [inventoryController::class, 'stockIssuePdf'])->name('stockIssuePdf');
    Route::get('/stockIssue/pageWholeinfo/{from_date}/{to_date}/{branch_id}/{invoice_number}', [inventoryController::class, 'stockIssuesecondPdf'])->name('stockIssuesecondPdf');
    Route::get('stockIssue/pdf/{id}', [inventoryController::class, 'stockRecordPdf'])->name('stockRecordPdf');


    // Stock Received 
    Route::get('stockReceived/list', [inventoryController::class, 'stockReceivedList'])->name('stockReceivedList');
    Route::get('stockReceived/new', [inventoryController::class, 'newStockReceived'])->name('newStockReceived');
    Route::post('stockReceived/store', [inventoryController::class, 'saveStockReceived'])->name('saveStockReceived');
    Route::get('stockReceived/edit/{id}', [inventoryController::class, 'editStockReceived'])->name('editStockReceived');
    Route::post('stockReceived/update', [inventoryController::class, 'updateStockReceived'])->name('updateStockReceived');
    Route::get('stockReceived/delete/{id}', [inventoryController::class, 'deleteStockReceived'])->name('deleteStockReceived');
    Route::any('stockReceived/searchResult', [inventoryController::class, 'searchStockReceived'])->name('searchStockReceived');

    Route::get('/stockReceived/pagepdf/{from_date}/{to_date}/{branch_id}/{invoice_number}', [inventoryController::class, 'stockReceivedPdf'])->name('stockReceivedPdf');
    Route::get('/stockReceived/pageWholeinfo/{from_date}/{to_date}/{branch_id}/{invoice_number}', [inventoryController::class, 'stockReceivedsecondPdf'])->name('stockReceivedsecondPdf');
    Route::get('/stockReceived/pdf/{id}', [inventoryController::class, 'stockReceivedRecordPdf'])->name('stockReceivedRecordPdf');

    // Rout for department
    Route::get('/department/departmentList', [departmentController::class, 'departmentList'])->name('departmentlist');
    Route::get('/department/newdepartment', [departmentController::class, 'newDepartment'])->name('newdepartment');
    Route::post('/department/save', [departmentController::class, 'storeDepartment'])->name('savedepartment');
    Route::get('/department/edit/{id}', [departmentController::class, 'editDepartment'])->name('editdepartment');
    Route::post('/department/update', [departmentController::class, 'updateDepartment'])->name('updatedepartment');
    Route::get('/department/delete/{id}', [departmentController::class, 'deleteDepartment'])->name('deletedepartment');

    // Rout for employee
    Route::get('/employee/employeeList', [employeeController::class, 'employeeList'])->name('employeelist');
    Route::get('/employee/newemployee', [employeeController::class, 'newEmployee'])->name('newemployee');
    Route::post('/employee/save', [employeeController::class, 'storeEmployee'])->name('saveemployee');
    Route::get('/employee/edit/{id}', [employeeController::class, 'editEmployee'])->name('editemployee');
    Route::post('/employee/update', [employeeController::class, 'updateEmployee'])->name('updateemployee');
    Route::get('/employee/delete/{id}', [employeeController::class, 'deleteEmployee'])->name('deleteemployee');
    Route::get('/getItemEmployeeRates/{id}', [employeeController::class, 'getItemEmployeeRates'])->name('getItemEmployeeRates'); // for get city list
    // Search for employee
    Route::any('/employee/searchResults', [employeeController::class, 'searchEmployee'])->name('searchEmployee');
    // Employee Attendee
    Route::get('/employee/employeeAttendeelist', [employeeController::class, 'employeeAttendeelist'])->name('employeeAttendeeList');
    Route::get('/employee/editAttendeeSheet/{id}', [employeeController::class, 'editAttendeeSheet'])->name('editAttendeeSheet');
    Route::post('/employee/updateAttendee', [employeeController::class, 'updateAttendee'])->name('updateAttendee');
    Route::get('/employee/deleteAttendee/{id}', [employeeController::class, 'deleteAttendee'])->name('deleteAttendee');
    Route::post('/employee/searchAttendeeRecords', [employeeController::class, 'searchAttendeeRecords'])->name('searchAttendeeRecords');
    Route::get('/employee/employeeAttendeeAdd', [employeeController::class, 'employeeAttendee'])->name('employeeAttendeeAdd');
    Route::post('/employee/searchMonthAttendeeList', [employeeController::class, 'searchMonthAttendeeList'])->name('searchMonthAttendeeList');
    Route::post('/employee/saveAttendee', [employeeController::class, 'attendeeSave'])->name('employeeAttendence');
    Route::post('/employee/deleteAllEmployeeAttendee/{date}', [employeeController::class, 'deleteAllEmployeeAttendee'])->name('deleteAllEmployeeAttendee');
    // Attendee PDF
    Route::get('/employee/attendeePdf/{employee_name}/{invoice_number}', [employeeController::class, 'attendeePdf'])->name('attendeePdf');
    Route::get('/employee/singleRecordPdf/{id}', [employeeController::class, 'singleRecordPdf'])->name('singleRecordPdf');
    // Employee Advance
    Route::get('/employee/advance/{id}', [employeeController::class, 'employeeAdvance'])->name('employeeAdvance');

    // Route for employee payments
    Route::get('/employeePayments/list', [employeePaymentController::class, 'employeePaymentList'])->name('employeePaymentList');
    Route::get('/employeePayments/new', [employeePaymentController::class, 'newEmployeePayment'])->name('newEmployeePayment');
    Route::post('/employeePayments/save', [employeePaymentController::class, 'saveEmployeePayment'])->name('saveEmployeePayment');
    Route::get('/employeePayments/edit/{id}', [employeePaymentController::class, 'editEmployeePayment'])->name('editEmployeePayment');
    Route::post('/employeePayments/update', [employeePaymentController::class, 'updateEmployeePayment'])->name('updateEmployeePayment');
    Route::get('/employeePayments/delete/{id}', [employeePaymentController::class, 'deleteEmployeePayment'])->name('deleteEmployeePayment');
    // Search for employee payment
    Route::any('/employeePayments/searchResults', [employeePaymentController::class, 'search'])->name('searchEmployeePayment');
    // Employee payment Record PDF
    Route::get('/employeePayments/pdf/{id}', [employeePaymentController::class, 'recordPdf'])->name('recordPdf');
    // Employee payment page PDF
    Route::get('/employeePayments/pagepdf/{from_date}/{to_date}/{employee_name}/{invoice_number}', [employeePaymentController::class, 'pagePdf'])->name('employeePayPagePdf');


    // Route for Employee Advance Return
    Route::get('/advanceReturn/list', [advanceReturnController::class, 'list'])->name('advanceReturnList');
    Route::get('/advanceReturn/new', [advanceReturnController::class, 'new'])->name('newAdvanceReturn');
    Route::post('/advanceReturn/save', [advanceReturnController::class, 'store'])->name('saveAdvanceReturn');
    Route::get('/advanceReturn/edit/{id}', [advanceReturnController::class, 'edit'])->name('editAdvanceReturn');
    Route::post('/advanceReturn/update', [advanceReturnController::class, 'update'])->name('updateAdvanceReturn');
    Route::get('/advanceReturn/delete/{id}', [advanceReturnController::class, 'delete'])->name('deleteAdvanceReturn');
    Route::post('/advanceReturn/searchResults', [advanceReturnController::class, 'searchAdvance'])->name('searchAdvanceReturn');
    // Employee Advance Page Pdf
    Route::get('/advanceReturn/advanceReturnPagePDf/{from_date}/{to_date}/{employee_name}/{invoice_number}', [advanceReturnController::class, 'advanceReturnPagePDf'])->name('advanceReturnPagePDf');
    // Employee Advance Pdf
    Route::get('/advanceReturn/advanceReturnPdf/{id}', [advanceReturnController::class, 'advanceReturnPdf'])->name('advanceReturnPdf');

    //  Rout for production
    Route::get('/production/productionList', [productionController::class, 'productionList'])->name('productionlist');
    Route::get('/production/newproduction', [productionController::class, 'new'])->name('newproduction');

    Route::get('/getEmployee/{id}', [productionController::class, 'getEmployee'])->name('get'); // for get city list

    Route::get('/getItem/{id}', 'productionController@getItem'); // for get city list

    Route::post('/production/save', [productionController::class, 'store'])->name('saveproduction');
    Route::get('/production/edit/{id}', [productionController::class, 'edit'])->name('editproduction');
    Route::post('/production/update', [productionController::class, 'update'])->name('updateProduction');
    Route::get('/production/delete/{id}', [productionController::class, 'delete'])->name('deleteproduction');
    Route::get('/production/postProductionPdf/{id}', [productionController::class, 'postProductionPdf'])->name('postProductionPdf');

    // Route for Search (production list)
    Route::any('/production/searchResutls', [productionController::class, 'search'])->name('searchProduction');
    Route::get('/production/productionReport', [productionController::class, 'productionReport'])->name('productionReport');
    Route::post('/production/searchproductionReport', [productionController::class, 'searchproductionReport'])->name('searchproductionReport');
    Route::get('/production/groupItemproductionReport', [productionController::class, 'groupItemproductionReport'])->name('groupItemproductionReport');
    Route::post('/production/searchGroupItemproductionReport', [productionController::class, 'searchGroupItemproductionReport'])->name('searchGroupItemproductionReport');
    Route::post('/production/postEmployeeProductionManually', [productionController::class, 'postEmployeeProductionManually'])->name('postEmployeeProductionManually');
    Route::get('/production/deletpostproduction/{id}', [productionController::class, 'deletpostproduction'])->name('deletpostproduction');
    // Route for work in process
    Route::get('/workinprocess/list', [workinProcessController::class, 'list'])->name('workProcessList');
    Route::get('/workinprocess/new', [workinProcessController::class, 'new'])->name('newWorkingProcess');
    Route::post('/workinprocess/save', [workinProcessController::class, 'store'])->name('saveWorkingProcess');
    Route::get('/workinprocess/edit/{id}', [workinProcessController::class, 'edit'])->name('editWorkingProcess');
    Route::post('/workinprocess/update', [workinProcessController::class, 'update'])->name('updateWorkingProcess');
    Route::get('/workinprocess/delete/{id}', [workinProcessController::class, 'delete'])->name('deleteWorkingProcess');
    // Search for working process
    Route::any('/workinprocess/searchResults', [workinProcessController::class, 'search'])->name('searchWorkingProcess');


    // Route for spoilage/loss
    Route::get('/spoilageOrLoss/list', [spoilageOrLossController::class, 'list'])->name('spoilageOrLoss');
    Route::get('/spoilageOrLoss/new', [spoilageOrLossController::class, 'new'])->name('newSpoilageLoss');
    Route::post('/spoilageOrLoss/save', [spoilageOrLossController::class, 'store'])->name('saveSpoilageloss');
    Route::get('/spoilageOrLoss/edit/{id}', [spoilageOrLossController::class, 'edit'])->name('editSpoilageLoss');
    Route::post('/spoilageOrLoss/update', [spoilageOrLossController::class, 'update'])->name('updateSpoilageLoss');
    Route::get('/spoilageOrLoss/delete/{id}', [spoilageOrLossController::class, 'delete'])->name('deleteSpoilageLoss');
    // Search for spoilage/loss
    Route::any('/spoilageOrLoss/searchResults', [spoilageOrLossController::class, 'search'])->name('searchSpoilageLoss');


    // Route for finished goods
    Route::get('/finishedGoods/list', [finishedGoodsController::class, 'list'])->name('finishedGood');
    Route::get('/finishedGoods/new', [finishedGoodsController::class, 'new'])->name('newFinishedGoods');
    Route::post('/finishedGoods/store', [finishedGoodsController::class, 'store'])->name('saveFinishedGoods');
    Route::get('/finishedGoods/edit/{id}', [finishedGoodsController::class, 'edit'])->name('editFinishedGoods');
    Route::post('/finishedGoods/update', [finishedGoodsController::class, 'update'])->name('updateFinishedGoods');
    Route::get('/finishedGoods/delete/{id}', [finishedGoodsController::class, 'delete'])->name('deleteFinishedGoods');
    // Search for finished Goods
    Route::any('/finishedGoods/searchResults', [finishedGoodsController::class, 'search'])->name('searchFinishedGoods');





    // Route for General Ledger Accounts
    Route::get('/ledgerAccounts/list', [GeneralLedgerController::class, 'ledgerAccountsList'])->name('ledgerAccountsList');
    Route::get('/ledgerAccounts/new', [GeneralLedgerController::class, 'newLedgerAccount'])->name('newLedgerAccount');
    Route::post('/ledgerAccounts/save', [GeneralLedgerController::class, 'saveLedgerAccount'])->name('saveLedgerAccount');
    Route::get('/ledgerAccounts/edit/{id}', [GeneralLedgerController::class, 'editLedgerAccount'])->name('editLedgerAccount');
    Route::post('/ledgerAccounts/update', [GeneralLedgerController::class, 'updateLedgerAccount'])->name('updateLedgerAccount');
    Route::get('/ledgerAccounts/delete/{id}', [GeneralLedgerController::class, 'deleteLedgerAccount'])->name('deleteLedgerAccount');
    Route::any('/ledgerAccounts/searchLedgerAccounts', [GeneralLedgerController::class, 'searchGeneralAccounts'])->name('searchGeneralAccounts');
    // balanceSheet
    Route::get('/balanceSheet/list', [GeneralLedgerController::class, 'balanceList'])->name('balanceSheet');
    Route::get('/balanceSheet/search', [GeneralLedgerController::class, 'balanceListSearch'])->name('balanceListSearch');
    // Pdf balance sheet
    Route::get('/balanceSheet/pdf', [GeneralLedgerController::class, 'balancePdf'])->name('balancePdf');

    Route::get('/incomeStatement/list', [GeneralLedgerController::class, 'incomeList'])->name('incomeStatement');
    Route::post('/incomeStatement/search', [GeneralLedgerController::class, 'incomeListSearch'])->name('incomeStatementSearch');
    // Income Statement Pdf
    Route::get('/incomeStatement/pdf', [GeneralLedgerController::class, 'incomePdf'])->name('incomeStatePdf');
    //Account receivable
    Route::get('/generalReports/accountReceivable', [GeneralLedgerController::class, 'accountReceivable'])->name('accountReceivable');
    // Search Account Receiveable
    Route::any('/generalReports/searchReceiveable', [GeneralLedgerController::class, 'searchReceiveable'])->name('searchReceiveable');
    // Account receivable page pdf 
    Route::get('/generalReports/receiveablePagePdf/{searchQuery}', [GeneralLedgerController::class, 'receiveablePagePdf'])->name('receiveablePagePdf');
    Route::get('/generalReports/accountPayable', [GeneralLedgerController::class, 'accountPayable'])->name('accountPayable');
    // Search Account Payable 
    Route::any('/generalReports/searchAccountPayable', [GeneralLedgerController::class, 'searchAccountPayable'])->name('searchAccountPayable');
    // Account payable pdf 
    Route::get('/generalReports/payablePagePdf/{searchQuery}', [GeneralLedgerController::class, 'payablePagePdf'])->name('payablePagePdf');




    // General Receipts List
    Route::get('/generalReciepts/list', [generalReceiptsController::class, 'list'])->name('generalReceiptsList');
    Route::get('/generalReciepts/new', [generalReceiptsController::class, 'new'])->name('newGeneralReceipt');
    Route::post('/generalReciepts/save', [generalReceiptsController::class, 'store'])->name('saveLedgerReceipts');
    Route::get('/generalReciepts/edit/{id}', [generalReceiptsController::class, 'edit'])->name('editGeneralReceipt');
    Route::post('/generalReciepts/update', [generalReceiptsController::class, 'update'])->name('updateGeneralReceipts');
    Route::get('/generalReciepts/delete/{id}', [generalReceiptsController::class, 'delete'])->name('deleteGeneralReceipts');
    // Search for general recreipt
    Route::any('/generalReciepts/searchResults', [generalReceiptsController::class, 'search'])->name('searchGeneralReceipt');
    // General Receipt record pdf
    Route::get('/generalReciepts/pdf/{id}', [generalReceiptsController::class, 'recordPdf'])->name('generalRecRecordPdf');
    // General Receipt page pdf
    Route::get('/generalReciepts/pagepdf/{from_date}/{to_date}/{invoice_number}', [generalReceiptsController::class, 'pagePdf'])->name('generalRecPdf');


    // General Payment List
    Route::get('/generalPayment/list', [generalPaymentController::class, 'list'])->name('generalPaymentList');
    Route::get('/generalPayment/new', [generalPaymentController::class, 'new'])->name('newGeneralPayments');
    Route::post('/generalPayment/save', [generalPaymentController::class, 'store'])->name('saveGeneralPayment');
    Route::get('/generalPayment/edit/{id}', [generalPaymentController::class, 'edit'])->name('editGeneralPayment');
    Route::post('/generalPayment/update', [generalPaymentController::class, 'update'])->name('updateGeneralPayment');
    Route::get('/generalPayment/delete/{id}', [generalPaymentController::class, 'delete'])->name('deleteGeneralPayment');
    // Search for general payments
    Route::any('/generalPayment/searchResults', [generalPaymentController::class, 'search'])->name('searchGeneralPayment');
    // General Payment record pdf
    Route::get('/generalPayment/pdf/{id}', [generalPaymentController::class, 'recordPdf'])->name('generalpayRecordPdf');
    // General Payment page pdf
    Route::get('/generalPayment/page/pdf/{from_date}/{to_date}/{invoice_number}', [generalPaymentController::class, 'pagePdf'])->name('generalPayPdf');

    // Journal Paymentx
    Route::get('/journalVoucher/list', [journalVoucherController::class, 'list'])->name('journalVoucherList');
    Route::get('/journalVoucher/new', [journalVoucherController::class, 'new'])->name('newJournalVoucher');
    Route::post('/journalVoucher/save', [journalVoucherController::class, 'store'])->name('saveJournalPayment');
    Route::get('/journalVoucher/edit/{id}', [journalVoucherController::class, 'edit'])->name('editJournalPayment');
    Route::post('/journalVoucher/update', [journalVoucherController::class, 'update'])->name('updateJournalVoucher');
    Route::get('/journalVoucher/delete/{id}', [journalVoucherController::class, 'delete'])->name('deleteJournalVoucher');
    // pdf
    Route::get('/journalVoucher/pdf/{id}', [journalVoucherController::class, 'recordPdf'])->name('journalVoucherPdf');
    Route::get('/journalVoucher/journalPagePdf/{from_date}/{to_date}/{invoice_number}', [journalVoucherController::class, 'jvoucherPagePdf'])->name('journalVoucherPagePdf');
    // Search
    Route::any('/journalVoucher/searchResult', [journalVoucherController::class, 'searchJVoucher'])->name('journalVoucherSearch');


    // Route for To Do
    Route::get('/todo/list', [toDoController::class, 'list'])->name('toDoList');
    Route::get('/todo/new', [toDoController::class, 'new'])->name('newTodo');
    Route::post('/todo/save', [toDoController::class, 'store'])->name('saveTodo');
    Route::get('/todo/edit/{id}', [toDoController::class, 'edit'])->name('editToDo');
    Route::post('/todo/update', [toDoController::class, 'update'])->name('updateToDo');
    Route::get('/todo/delete/{id}', [toDoController::class, 'delete'])->name('deleteTodo');

    // Route for Transaction Logs
    Route::get('/transactionLog/list', [transactionLogController::class, 'list'])->name('transactionLogList');
    Route::post('/transactionLog/searchResults', [transactionLogController::class, 'logSearch'])->name('transationLogSearch');

       // Route for Tour 
       Route::get('/tour/list', [tourController::class, 'list'])->name('tourList');
       Route::get('/tour/new', [tourController::class, 'new'])->name('newTour');
       Route::post('/tour/store', [tourController::class, 'store'])->name('saveTour');
       Route::get('/tour/edit/{id}', [tourController::class, 'edit'])->name('editTour');
       Route::post('/tour/update', [tourController::class, 'update'])->name('updateTour');
       Route::get('/tour/delete/{id}', [tourController::class, 'delete'])->name('deleteTour');
       Route::any('/tour/searchResults', [tourController::class, 'search'])->name('searchTour');

    // Client Validation 
    Route::get('/clients/list', [ClientValidationController::class, 'list'])->name('clientsList');
    Route::get('/clients/new', [ClientValidationController::class, 'new'])->name('newClient');
    Route::post('/clients/save', [ClientValidationController::class, 'save'])->name('saveClient');
    Route::get('/clients/edit/{id}', [ClientValidationController::class, 'edit'])->name('editClient');
    Route::post('/clients/update', [ClientValidationController::class, 'update'])->name('updateClient');
    Route::get('/clients/delete/{id}', [ClientValidationController::class, 'delete'])->name('deleteClient');


    Route::get('error/invalid', function () {
        return view('errorpages.validationError');
    })->name('errorPage');
});


require __DIR__ . '/auth.php';
