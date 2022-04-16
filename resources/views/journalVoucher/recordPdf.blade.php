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
    
    <div>
        <table style="width: 100%;" collspacing="0" class="" border="0">
            <tr>
                <td class="w-20">
                    <img src="{{$companyinfo->logo}}" alt="" class="mb-4 company-logo">
                </td>
                <td class="w-40">

                    <div class="">
                        <h3>{{$companyinfo->title}}</h3>
                        <p class="mb-1"><b>Address:</b>{{$companyinfo->address}}</p>
                        <p class="mb-1"><b>Phone:</b>{{$companyinfo->phone}}</p>
                        <p class="mb-1"><b>Email:</b>{{$companyinfo->email}}</p>
                        <p class="mb-1"><b>URL:</b>{{$companyinfo->web}}</p>
                    </div>
                </td>
                <td class="w-20">
                    <div class="mb-3">
                    <p class="mb-1"><b>Invoice #:</b> {{$journalVoucher->voucher_number}}</p>
                        <p class="mb-1"><b>Invoice Date:</b> {{$journalVoucher->voucher_date}}</p>
                    </div>
                </td>
            </tr>
       
        </table>
        <hr>
  
    </div>
    <p class="text-right mb-2" style="font-size: 1.4rem;"><b>General Payment Invoice </b></p>
    <table class="table table-bordered">
        <thead>
            <tr class="table-warning">
                <th class="text-center">#</th>
                <th class="text-center">Account</th>
                <th class="text-center">Description</th>
                <th class="text-center">Credit</th>
                <th>Debit</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($journalVoucher))
            @if(!empty(unserialize($journalVoucher->voucher_detail)))
            @php
            $i=1;
            @endphp
            @foreach(unserialize($journalVoucher->voucher_detail) as $invoiceItem)
            <tr>
                <td class="text-center">{{$i}}</td>
                <td class="text-center text-muted">
                    @if(!empty($accounts))
                    @foreach($accounts as $account)
                    {{(isset($journalVoucher)) ? ($account->id == $invoiceItem['general_ledger_account_id']) ? $account->name : '' : ''}}
                    @endforeach
                    @endif
                </td>


                <td class="">{{(isset($journalVoucher)) ? $invoiceItem['note'] : ''}}</td>
                <td class="">{{(isset($journalVoucher)) ? $invoiceItem['credit'] : ''}}</td>
                <td>{{(isset($journalVoucher)) ? $invoiceItem['debit'] : ''}}</td>

            </tr>
            @php
            $i++;
            @endphp
            @endforeach
            @endif
            @endif

        </tbody>
    </table>
</body>

</html>