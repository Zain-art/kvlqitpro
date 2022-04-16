<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
</head>

<body>

<div class="pt-4 d-table" style="width: 100%; vertical-align: middle;">

        <table style="width: 100%;">
            <tr>
                <td style="width: 25%;">
                    <div class="d-inline-block w-25">

                        <img src="{{$companyinfo->logo}}" style="width:100px;  object-fit: contain; " alt="" class="mb-4">
                    </div>
                </td>
                <td style="width: 70%;">
                    <div class="d-inline-block" style="font-size: .9rem; width: 70%;">
                        <h3>{{$companyinfo->title}}</h3>
                        <p class="mb-1"><b>Address:</b>{{$companyinfo->address}} </p>
                        <p class="mb-1"><b>Phone:</b>{{$companyinfo->phone}}</p>
                        <p class="mb-1"><b>Email:</b>{{$companyinfo->email}}</p>
                        <p class="mb-1"><b>URL:</b>{{$companyinfo->web}}</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <h3 class="text-right"><b>Stock Received List</b></h3>
    <div class="">

        <table class="table table-bordered" cellpadding="10px" cellspacing="10px">
            <thead class="">
            <tr class="table-warning">
                <th>#</th>
                <th>Voucher No.</th>
                <th>Voucher Date</th>
                <th>Branch Name</th>
                <th>Item Name</th>
                <th>Net Qty</th>
                </tr>
            </thead>
            <tbody>
            @if(!empty($lists))
                @php
                $i=1;
                @endphp
                @foreach($lists as $list)
                @if(!empty(unserialize($list->items_detail)))
                @foreach(unserialize($list->items_detail) as $invoiceItem)
                <tr>
                <td>{{$i}}</td>
                <td>{{$list->voucher_number}}</td>
                <td >{{$list->voucher_date}}</td>
                <td>{{$list->name}}</td>
                <td>
                    @if(!empty($items))
                    @foreach($items as $item)
                    {{(isset($list)) ? ($item->id == $invoiceItem['item_id']) ? $item->name : '' : ''}}
                    @endforeach
                    @endif

                </td>
                <td>{{$invoiceItem['item_qty']}} </td>
                </tr>
                @endforeach
                @endif
                @php
                $i++;
                @endphp
                @endforeach
                @endif
                <tr>
                    <td colspan="5">Net Total</td>
                    <td>{{$net}}</td>

                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <div class="d-inline-block ">
            <p class="mb-0" style="font-size: 1.3rem; font-weight:900">Thanks You for Your business!</p>
            <p><span style="font-size: 1.3rem; font-weight:900">Print Date:</span> @php echo date('Y-m-d'); @endphp</p>
        </div>
        <div class="d-inline-block float-right">
            <p style="border-top: 1px solid black;"><span class="" style="font-size: 1.3rem; font-weight:900; ">Prepared by:</span>admin</p>
        </div>
    </div>

</body>

</html>