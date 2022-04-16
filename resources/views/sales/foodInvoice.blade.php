<x-app-layout>
    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="pe-7s-stopwatch icon-gradient bg-amy-crisp">
                            </i>
                        </div>
                        <div>Sale Invoice
                            <div class="page-title-subheading">Wide selection of cards with multiple styles, borders, actions and hover effects.
                            </div>
                        </div>
                    </div>
                    <div class="page-title-actions">
                        <button type="button" data-toggle="modal" data-target="#exampleModal" class="btn-shadow mr-3 btn btn-dark">
                            <i class="fa fa-star"></i>
                        </button>
                        <div class="d-inline-block dropdown">
                            <button type="button" id="showInvoiceList" data-toggle="modal" data-target="#exampleModal" class="btn-shadow btn btn-info">
                                <!-- <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-business-time fa-w-20"></i>
                                </span> -->
                                Pending Invoice List
                            </button>
                        </div>

                        <!-- <div class="d-inline-block dropdown">
                            <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-info">
                                <span class="btn-icon-wrapper pr-2 opacity-7">
                                    <i class="fa fa-business-time fa-w-20"></i>
                                </span>
                                Buttons
                            </button>
                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="javascript:void(0);" class="nav-link">
                                            <i class="nav-link-icon lnr-inbox"></i>
                                            <span>
                                                Inbox
                                            </span>
                                            <div class="ml-auto badge badge-pill badge-secondary">86</div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void(0);" class="nav-link">
                                            <i class="nav-link-icon lnr-book"></i>
                                            <span>
                                                Book
                                            </span>
                                            <div class="ml-auto badge badge-pill badge-danger">5</div>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="javascript:void(0);" class="nav-link">
                                            <i class="nav-link-icon lnr-picture"></i>
                                            <span>
                                                Picture
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a disabled href="javascript:void(0);" class="nav-link disabled">
                                            <i class="nav-link-icon lnr-file-empty"></i>
                                            <span>
                                                File Disabled
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class=" ps--active-y ps  col-8">

                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        @php
                        $i = 1;
                        @endphp
                        @if(isset($menuWiseItems))
                        @foreach($menuWiseItems as $menu)
                        <!-- <li class="nav-item">
                            <a role="tab" class="nav-link show " id="tab-1" data-toggle="tab" href="#tab-content5">
                                <span>BBQ</span>
                            </a>
                        </li> -->
                        <li class="nav-item">
                            <a role="tab" class="nav-link show {{($i==1) ? 'active' : ''}}" id="tab-1" data-toggle="tab" href="#{{$menu->tabId}}">
                                <span>{{$menu->menuName}}</span>
                            </a>
                        </li>
                        @php
                        $i++;
                        @endphp
                        @endforeach
                        @endif


                    </ul>
                    <div class="tab-content scrollbar-container" style="height: 100vh;">
                        @if(isset($menuWiseItems))
                        @php
                        $i = 1;
                        @endphp
                        @foreach($menuWiseItems as $menuInfo)
                        <div class="tab-pane tabs-animation fade show {{($i==1) ? 'active' : ''}}" id="{{$menuInfo->tabId}}" role="tabpanel">
                            <div class="row dynamic-content no-gutters">
                                @foreach($menuInfo as $item)
                                <div class="col-md-3 px-1 item-info">
                                    <div class="main-card mb-3 card item-cards">
                                        <!-- <img src="{{url('/'). $item->pic}}" alt="Card image cap" class="card-img"> -->
                                        <div class="card-body {{($item->is_booked == 1) ? 'bg-success' : 'bg-warning'}} text-white activeitem">
                                            <h5 class="card-title" style="color: white;">{{$item->name}}</h5>
                                            <div class="text-success font-size-lg font-weight-bold" style="color: white !important;">Rs.<span class="card-price" style="color: white; font-weight: 900; font-size: 25px;">{{$item->sele_price}}</span></div>
                                        </div>
                                        <input type="hidden" name="" class="id" value="{{$item->id}}">
                                    </div>
                                </div>
                                @endforeach
                            </div>


                        </div>
                        @php
                        $i++;
                        @endphp
                        @endforeach
                        @endif

                    </div>
                </div>

                <div class="col-4">
                    <form action="{{route('saveinvoice')}}" class="Q-form">
                        <input type="hidden" name="id" id="invoice_id">
                        <input type="hidden" name="invoice_number" id="invoice_number" value="{{(isset($invoice_number)) ? Config::get('constants.SALE_INVOICE_PREFIX').$invoice_number: ''}}">
                        <div class="mb-2 card">
                            <div class="card-header-tab card-header-tab-animation card-header d-flex justify-content-between">
                                <div class="card-header-title">
                                    <span><span class="invoiceStatus">New</span>&nbsp;invoice</span>
                                </div>
                                <div>
                                    <span> <input name="invoice_date" id="invoice_date" placeholder="" type="date" value="{{(isset($sale)) ? $sale->invoice_date : date('Y-m-d')}}" class="form-control"></span>
                                </div>
                                <div class="card-header-title">
                                    <span><span style="top:1px;font-size: 1.1rem; font-weight:800;" class="position-relative ">#</span> <span id="show-invoice-number" style="color:#d92550;">{{(isset($invoice_number)) ? Config::get('constants.SALE_INVOICE_PREFIX').$invoice_number: ''}}</span>
                                </div>
                            </div>



                            <div class="card-body scroll-set scrollbar-container">
                                <div class="tab-content selected-items">

                                </div>

                            </div>



                            <div class="card-footer-tab card-footer-tab-animation card-footer d-block py-2 px-0">
                                <div class="widget-content row justify-content-center no-gutters p-0">

                                    <div class="form-group col-md-10 row  no-gutters align-items-baseline">
                                        <!-- <div class="col-md-4"></div> -->
                                        <div class="col-3">
                                            <label for=""><b>Customer </b></label>
                                        </div>
                                        <div class="col-9">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <select class="js-example-basic-single form-control" placeholder="Select Customer" name="customer_id" id="customer_id">
                                                        <option value="">Select Customer</option>
                                                        @if(!empty($customers))
                                                        @foreach($customers as $customer)
                                                        <option value="{{$customer->id}}" {{(isset($sale)) ? ($sale->customer_id == $customer->id) ? 'Selected' : '' : ''}}> {{$customer->name}} </option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-9 row  no-gutters align-items-baseline">
                                        <!-- <div class="col-md-4"></div> -->
                                        <div class="col-3">
                                            <label for=""><b> Travel Agents </b></label>
                                        </div>
                                        <div class="col-9">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <select class="js-example-basic-single form-control agent_id" placeholder="Select agents" name="agent_id" id="agent_id" onchange="GetAgentCommission(this);">
                                                        <option value="">Select Agents</option>
                                                        @if(!empty($agentlists))
                                                        @foreach($agentlists as $agent)
                                                        <option  value="{{$agent->id}}" {{(isset($sale)) ? ($sale->agent_id == $agent->id) ? 'Selected' : '' : ''}}> {{$agent->Name}} </option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-10 row  no-gutters align-items-baseline">
                                        <!-- <div class="col-md-4"></div> -->
                                        <div class="col-3">
                                            <label for=""><b>Table No. </b></label>
                                        </div>
                                        <div class="col-9">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control" name="table_no" id="table_no">

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group col-md-10 row  no-gutters align-items-baseline">

                                        <div class="col-4">
                                            <label for=""><b>Gross Total :</b></label>
                                        </div>
                                        <div class="col-md-4"></div>
                                        <div class="col-4">
                                            <span class="d-inline-block" style="color:#f7b924; font-size:1.2rem;">Rs.<span class="gross_amount">0</span></span>

                                            <input type="hidden" name="gross_total" id="gross_total">
                                        </div>
                                        <input type="hidden" class="form-control" value="" id="total_amount">
                                    </div>
                                    <input type="hidden" class="form-control" id="total_qty">
                                    <div class="form-group col-10 row no-gutters align-items-center">
                                        <!-- <div class="col-md-4"></div> -->
                                        <div class="col-3">
                                            <label for=""><b>Discount% </b></label>
                                        </div>
                                        <div class="col-9">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control item_discount" id="discount" oninput="calculateSum();" name="discount">

                                                </div>
                                                <div class="col-md-6">
                                                    <span class="d-inline-block" style="color:#f7b924; font-size:1.2rem;">Rs.<span class="discount_amount">0</span></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group col-10 row no-gutters align-items-center">
                                        <!-- <div class="col-md-4"></div> -->
                                        <div class="col-3">
                                            <label for=""><b>Tax% : </b></label>
                                        </div>
                                        <div class="col-9">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="number" class="form-control item_tax" oninput="calculateSum();" id="tax" name="tax">
                                                </div>
                                                <div class="col-md-6">
                                                    <span class="d-inline-block" style="color:#f7b924; font-size:1.2rem;">Rs.<span class="tax_amount">0</span></span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group col-md-10 row  no-gutters align-items-baseline">
                                        <!-- <div class="col-md-4"></div> -->
                                        <div class="col-4">
                                            <label for=""><b> Commission % </b></label>
                                        </div>
                                        <div class="col-8">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <input type="text" name="commisssion" id="commission_persent" readonly class="form-control commission_persent" onchange="GetAgentCommission();calculateSum();">
                                                   
                                                </div>


                                                <!-- <div class="col-md-7">
                                                    <select class="js-example-basic-single form-control commission_persent" placeholder="Select agents" name="commisssion" id="commission_persent" onchange="calculateSum();GetAgentCommission();">
                                                        <option value="">Select Commission</option>
                                                        @if(!empty($agentlists))
                                                        @foreach($agentlists as $agent)
                                                        <option value="{{$agent->Commission_persent}}" {{(isset($sale)) ? ($sale->agent_id == $agent->id) ? 'Selected' : '' : ''}}> {{$agent->Commission_persent}} </option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-10 row no-gutters mt-3 align-items-baseline">
                                        <div class="col-4">
                                            <label for=""><b>Net Total : </b></label>
                                        </div>
                                        <div class="col-md-4"></div>
                                        <div class="col-4">
                                            <input type="hidden" name="net_total" id="netTotal">
                                            <input type="hidden" name="net_qty" id="netQty">
                                            <b style="font-size: 1.2rem; color:#3ac47d ;">Rs.<span class="net_total">0</span></b>
                                        </div>

                                    </div>
                                    <div class="form-group col-10 row no-gutters mt-3 align-items-baseline">
                                        <!-- <div class="col-md-4"></div> -->
                                        <div class="col-3">
                                            <label for=""><b>Paid Amount : </b></label>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" name="paid_amount" class="form-control paid_amount" id="paid" oninput="calculateSum();">
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-between px-5">
                                        <span class="btn btn-warning add-amount"><b>Rs.</b> <b><span class="">10</span></b></span>
                                        <span class="btn btn-warning add-amount"><b>Rs.</b> <b><span class="">50</span></b></span>
                                        <span class="btn btn-warning add-amount"><b>Rs.</b> <b><span class="">100</span></b></span>
                                        <span class="btn btn-warning add-amount"><b>Rs.</b> <b><span class="">1000</span></b></span>


                                    </div>
                                    <div class="form-group col-10 row no-gutters mt-3 align-items-baseline">
                                        <div class="col-4">
                                            <label for=""><b>Balance : </b></label>
                                        </div>
                                        <div class="col-md-4"></div>
                                        <div class="col-4">
                                            <b style="font-size: 1.2rem;color:#d92550;">Rs.<span class="remainder">0</span></b>
                                            <input type="hidden" name="remiander" id="remainder">
                                        </div>
                                        <div class="form-group col-10 row no-gutters mt-3 align-items-baseline">
                                            <div class="col-md-8">
                                                <label for=""><b>Commission Amount:</b></label>
                                            </div>

                                            <div class="col-4">



                                                <input type="text" name="commission_amount" class="form-control commission_amount" readonly >
                                            </div>


                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="hidden" name="sadqa" class="form-control sadqa" readonly>
                                        <input type="hidden" name="zakat" class="form-control zakat" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right mt-1">
                            <button type="submit" class="btn btn-success btn-block px-4 saveUpdateBtn" style="font-size: 1.1rem; border-radius:0.5rem;box-shadow: rgba(0, 0, 0, 0.16) 0px 5px 20px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="app-wrapper-footer">
            <div class="app-footer">
                <div class="app-footer__inner">
                    <div class="app-footer-left pl-52">
                        &copy; Copyright 2021. Quadacts
                    </div>
                    <div class="app-footer-right">
                        <ul class="nav">
                            <li class="nav-item">
                                <a href="javascript:void(0);" class="nav-link">
                                    <i class="fa fa-user-alt"></i>
                                    <!-- <i class="fab fa-facebook"></i> -->

                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:void(0);" class="nav-link">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade mymodal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="margin-top: 7rem;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Invoice List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row card mx-0 mb-2 pt-1">
                        <div class="col-md-12">
                            <form action="" class="searchInvoice">

                                <!-- <p style="font-size: 1.2rem;" class="mb-1">Search</p> -->
                                <div class="row no-gutters">
                                    <div class="form-group col-4 ml-1">
                                        <label for="from_date" class="form-label" style="font-size: 1rem;">Invoice Number</label>
                                        <input type="text" name="invoice_number" class="form-control" value="" placeholder="Invoice No." id="myInput">
                                    </div>
                                    <div class="form-group col-4 ml-1">
                                        <label for="from_date" class="form-label" style="font-size: 1rem;">Table Number</label>
                                        <input type="text" name="table_no" class="form-control" value="" placeholder="Table no." id="myInput2">
                                    </div>
                                    <div class="col-2 align-self-end ml-2" style="margin-bottom: 1.1rem;">
                                        <div class="page-title-actions">
                                            <a href="">
                                                <button type="submit" data-toggle="tooltip" title="" data-placement="bottom" class="btn-shadow btn btn-dark" data-original-title="Search">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="main-card mb-3 card">
                                <div class="card-header">Sale Invoice List
                                    <div class="btn-actions-pane-right">
                                        <div role="group" class="btn-group-sm btn-group">
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="align-middle mb-0 table table-borderless table-striped table-hover" id="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Invoice #</th>
                                                <th class="text-center">Customer Name</th>
                                                <th class="text-center">Net Total</th>
                                                <th class="text-center">Net PCS</th>
                                                <th class="text-center">Net Qty</th>
                                                <th class="text-center">Invoice Date</th>
                                                <th class="text-center">Table_no</th>
                                                <th class="text-center">Action</th>

                                            </tr>
                                        </thead>
                                        <tbody class="invoiceList" id="tableBody">

                                            <!-- <tr>
                                                <td class="text-center text-muted">sdfa</td>
                                                <td class="text-center text-muted">dsaf</td>
                                                <td class="text-center">sdfa</td>
                                                <td class="text-center">dfsa </td>
                                                <td class="text-center">fdsa </td>
                                                <td class="text-center"> fdsa</td>
                                                <td class="text-center">fds</td>
                                                <td class="text-center">
                                                    <div class="mb-2 mr-2 btn-group">
                                                        <button class="btn btn-outline-success">Edit</button>
                                                        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle-split dropdown-toggle btn btn-outline-success"><span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(68px, 33px, 0px);">
                                                            <a href=""><button type="button" tabindex="0" class="dropdown-item">Edit</button></a>
                                                            <a href=""><button type="button" tabindex="0" class="dropdown-item">Refund</button></a>
                                                      
                                                       
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr> -->


                                            <!-- <tr>
                                                <td colspan="3">Net Totals</td>
                                                <td class="text-center">{{(isset($net_total)) ? $net_total : ''}}</td>
                                                <td class="text-center">{{(isset($net_pcs)) ? $net_pcs : ''}}</td>
                                                <td class="text-center">{{(isset($net_qty)) ? $net_qty : ''}}</td>
                                                <td colspan="2"></td>
                                            </tr> -->
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div> -->
    </div>
    </div>
    </div>



    <!-- <div class=" d-none" id="hidden">

        <div class="added-item">

            <div class="d-flex justify-content-between align-items-center ">
                <div><b>Zinger Burger</b><br>
                    <span class="text-muted d-inline-block m-0 text">sdfkjakljf</span>
                </div>
                <div class="qty-manage  qty-decrease">&#8722;</div>
                <div class="pt-1"><input type="text" value="0" onchange="calculateSum();" class="qty-box"></div>
                <div class="qty-manage qty-increase">+</div>
                <div class="price-against_item"><b>Rs:20000</b></div>
            </div>
            <hr>

        </div>
    </div> -->



</x-app-layout>