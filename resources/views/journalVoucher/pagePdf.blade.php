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

    <h3 class="text-right"><b>Journal Voucher List</b></h3>
    <table class="table table-bordered">
        <thead>
            <tr class="table-warning">
                <th class="">#</th>
                <th class="">Voucher Number</th>
                <th class="">Voucher Date</th>
                <th class="">Notes</th>
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
                <td class="align-middle" style="max-width: 100px;">{{substr($list->note,0,50)}} {{ strlen($list->note) > 50 ?  "...." : "" }} </td>
            </tr>
            @php
            $i++;
            @endphp
            @endforeach
            @endif
        </tbody>
    </table>
</body>

</html>