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

    <h3 class="text-right"><b>Item Stock Ledger</b></h3>
    <div class="">

        <table class="table table-bordered" cellpadding="10px" cellspacing="10px">
            <thead class="table-warning">
                <th class="">#</th>
                <th class="">Voucher #</th>
                <th class="">Voucher Date</th>
                <th class="">Item Name</th>
                <th class="">Qty IN</th>
                <th class="">Qty OUT</th>
                <th class="">Balance Quantity</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($lists))
                @php
                $i=1;
                @endphp
                @foreach($lists as $list)
                <tr>
                    <td class="">{{$i}}</td>
                    <td class="">{{$list->voucher_number}}</td>
                    <td class="">{{$list->voucher_date}}</td>
                    <td class="">{{$list->name}}</td>
                    <td class="">{{($list->transaction_type == '+') ? $list->item_qty :''}}</td>
                    <td class="">{{($list->transaction_type == '-') ? $list->item_qty :''}}</td>
                    <td class="">{{$list->netQty}}</td>

                </tr>
                @php
                $i++;
                @endphp
                @endforeach
                @endif
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