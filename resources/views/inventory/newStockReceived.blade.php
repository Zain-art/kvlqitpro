<x-app-layout>
    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="pe-7s-plus icon-gradient bg-mean-fruit">
                            </i>
                        </div>
                        <div>Stock Received
                            <div class="page-title-subheading">This is an example dashboard created using build-in elements and components.
                            </div>

                        </div>
                    </div>
                    <div class="page-title-actions">
                        <a href="{{ route('stockReceivedList') }}">
                            <button type="button" data-toggle="tooltip" title="" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark" data-original-title="Stock Received List">
                                <i class="fa fa-th-list"></i>
                            </button>
                        </a>

                    </div>
                </div>
            </div>

            <!--HTML USED FOR CREATE NEW ROW -->
            <table style="display: none;">
                <tbody class="new_row">
                    <tr>

                        <td class="text-center"><label class="sr_no">1</label></td>
                        <td class="text-center text-muted">
                            <select class="select-drop-down form-control" name="item_id[]" required>
                                <option value="">Select Item</option>
                                @if(!empty($items))
                                @foreach($items as $item)
                                <option value="{{$item->id}}"> {{$item->name}} </option>
                                @endforeach
                                @endif
                            </select>
                        </td>
                        <td class="text-center"><input name="item_qty[]" id="item_qty" placeholder="" value="" type="text" class="form-control item_qty item_qt" onchange="calculateInvoiceSum(); qtySum();"></td>
                        <td><button class="btn btn-dark" type="button" onclick="removeRow(this);calculateInvoiceSum();"><i class="fas fa-times"></i></button></td>
                    </tr>
                <tbody>
            </table>

            <!--END OF HTML USED FOR CREATE NEW ROW -->



            <div class="main-card mb-3 card">
                <form class="Q-form" action="{{(isset($stock_issue)) ? route('updateStockReceived') : route('saveStockReceived')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" id="id" value="{{(isset($stock_issue)) ? $stock_issue->id : ''}}">
                    <div class="card-body">
                        <h5 class="card-title">Stock Received Information</h5>
                        <div class="form-row">
                            <div class="col-md-2">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail11" class="">Voucher #</label>
                                    <input name="invoice_number" id="invoice_number" placeholder="" type="text" value="{{(isset($stock_issue)) ? $stock_issue->voucher_number : Config::get('constants.STOCK_RECEIVED_VOUCHER_PREFIX').$invoice_number}}" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail11" class="">Voucher Date</label>
                                    <input name="invoice_date" id="invoice_date" placeholder="" type="date" value="{{(isset($stock_issue)) ? $stock_issue->voucher_date : date('Y-m-d')}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="position-relative form-group">
                                    <label for="branch" class="">
                                        Branch
                                        <a href="" title="category List"><i class="fa fa-list"></i></a>
                                    </label>
                                    <select class="js-example-basic-single form-control" placeholder="Select" name="branch_id" id="branch_id" required>
                                        <option value="">Select</option>
                                        @if(!empty($branches))
                                        @foreach($branches as $branch)
                                        <option value="{{$branch->id}}" {{(isset($stock_issue)) ? ($stock_issue->branch_id == $branch->id) ? 'Selected' : '' : ''}}> {{$branch->name}} </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="form-row">
                            <div class="table-responsive col-md-12">
                                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Items</th>
                                            <th class="text-center">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($stock_issue))
                                        @if(!empty(unserialize($stock_issue->items_detail)))
                                        @php
                                        $i=1;
                                        @endphp
                                        @foreach(unserialize($stock_issue->items_detail) as $invoiceItem)
                                        <tr>
                                            <td class="text-center">{{$i}}</td>
                                            <td class="text-center text-muted">
                                                <select class="js-example-basic-single form-control" aria-placeholder="Select Item" name="item_id[]" onchange="calculateInvoice();" required>
                                                    @if(!empty($items))
                                                    @foreach($items as $item)
                                                    <option value="{{$item->id}}" {{(isset($stock_issue)) ? ($item->id == $invoiceItem['item_id']) ? 'Selected' : '' : ''}}> {{$item->name}} </option>
                                                    @endforeach
                                                    @endif

                                                </select>
                                            </td>
                                            <td class="text-center"><input name="item_qty[]" id="item_qty" placeholder="Quantity" value="{{(isset($stock_issue)) ? $invoiceItem['item_qty'] : ''}}" type="text" class="form-control item_qty item_qt" onchange="calculateInvoiceSum(); qtySum();"></td>

                                        </tr>
                                        @php
                                        $i++;
                                        @endphp
                                        @endforeach
                                        @endif
                                        @endif

                                        <tr class="btn-add-new">
                                            <td>
                                                <button class="btn btn-primary add_row" type="button"><i class="fas fa-plus"></i></button>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                    </tbody>
                                </table>





                            </div>
                        </div>


                        <div class="form-row">
                            <div class="col-md-8">
                                <label for="exampleEmail11" class="">Notes</label>

                                <textarea name="note" id="note" placeholder="" type="text" value="" class="form-control">{{(isset($stock_issue)) ? $stock_issue->note : ''}}</textarea>
                            </div>
                            <div class="col-md-2">
                           

                            </div>
                            <div class="col-md-2 mt-3">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail11" class="">Net Qty</label>
                                    <input name="net_qty" id="qty_" placeholder="" type="text" value="{{(isset($stock_issue)) ? $stock_issue->net_qty : ''}}" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-block text-center card-footer">
                        <button type="submit" class="mt-2 btn btn-primary">{{(isset($stock_issue)) ? 'Update' : 'Save'}}</button>
                    </div>
                </form>
            </div>


        </div>

    </div>

</x-app-layout>
