<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale Invoice</title>
    <link href="{{ asset('css/thermal.css') }}" rel="stylesheet">
</head>
<style>
    html {
        font-size: 70%;
    }

    .thermal-pdf-table {
        text-align: center;

    }

    .thermal-pdf-heading-table__container {
        text-align: center;
        width: 100%;
        padding-left: 2rem;
    }

    .fbr-container {
        padding-left: 2rem;
    }
</style>

<body style=" width:290px;">
    <div class="thermal-pdf-heading-table__container">
        <table class="thermal-pdf-table" collspacing="0" class="" border="0">
            <tr>
                <td class="w-100">
                    <img src="{{$companyinfo->logo}}" alt="" class="mb-4 company-logo">
                </td>
            </tr>
            <tr>
                <td class="w-100">
                    <div class="">
                        <h3>{{$companyinfo->title}}</h3>
                        <p class="mb-1">Address:{{$companyinfo->address}}</p>
                        <p class="mb-1">Phone:{{$companyinfo->phone}}</p>
                        <p class="mb-1">Email:{{$companyinfo->email}}</p>
                        <p class="mb-1">URL:{{$companyinfo->web}}</p>
                        <hr>
                        <p class="mb-1">Invoice Date :{{$sale->invoice_date}}</p>
                        @if(!empty($sale->customer_name))
                        <p class="mb-1">Customer Name: {{$sale->customer_name}}</p>
                        @endif
                        @if(!empty($sale->cnic_no))
                        <p class="mb-1">Cnic #:{{$sale->cnic_no}}</p>
                        @endif
                        @if(!empty($sale->ntn_no))
                        <p class="mb-1">NTN #: {{$sale->ntn_no}}</p>
                        @endif
                        @if(!empty($sale->contact_no))
                        <p class="mb-1">Contact #:{{$sale->contact_no}}</p>
                        @endif
                        <p class="mb-1">Sale Invoice</p>
                    </div>
                </td>
            </tr>
        </table>
        <hr>
    </div>
    <div>
    
    <h3 class="text-center" style=" padding-left:2rem;">{{($sale->status == 0) ? 'Pending' : ($sale->status == 1 ? 'Paid' : '')}}</h3>
        <p class="text-center" style="font-size: 1rem; padding-left:2rem;"><b>Sale Invoice</b></p>
    </div>
    <table class="table table-bordered" style="padding-left: 2rem; width:310px;">
        <thead>
            <tr class="table-warning">
                <th>#</th>
                <th style="min-width: 80px;">Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($sale))
            @if(!empty($sale->items_detail))
            @php
            $i=1;
            @endphp
            @foreach(unserialize($sale->items_detail) as $invoiceItem)

            <tr >
                <td>{{$i}}</td>
                <td style="width:120px;">
                    @if(!empty($items))
                    @foreach($items as $item)
                    {{(isset($sale)) ? ($item->id == $invoiceItem['item_id']) ? $item->name : '' : ''}}
                    @endforeach
                    @endif

                </td>
                &nbsp;&nbsp;
                <td> {{$invoiceItem['item_price']}}</td>&nbsp;&nbsp;
          
                <td>{{$invoiceItem['item_qty']}}</td>&nbsp;&nbsp;
                <td>{{$invoiceItem['amount']}}</td>
            </tr>
            @php
            $i++;
            @endphp
            @endforeach
            @endif
            @endif
            <tr>
                <td colspan="4">Net Total</td>
                <td>{{$sale->net_total}}</td>
            </tr>
        </tbody>
    </table>

    <div class="fbr-container">
        <span><b>Discount: </b> {{$sale->discount}}</span>&nbsp;&nbsp;&nbsp;&nbsp;
        <span><b>Tax: </b> {{$sale->tax}}</span><br>
       
        <span><b>Table no.: </b> {{$sale->table_no}}</span>
        <hr>
        <p>Powerd by Quadacts www.quadacts.com +923167652340</p>
    </div>


</body>

</html>