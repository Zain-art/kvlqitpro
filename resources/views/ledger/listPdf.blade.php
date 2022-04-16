<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
</head>
<style>
    html {
        font-size: 85%;
    }
</style>

<body>
    <div class="pt-4 d-table" style="width: 100%; vertical-align: middle;">

        <table style="width: 100%;">
            <tr>
                <td style="width: 25%;">
                    <div class="d-inline-block w-25">

                        <img src="{{$companyinfo->logo}}" style="width:100px;  object-fit: contain; " alt="" class="mb-4">
                    </div>
                </td>
                <td style="width: 55%;">
                    <div class="d-inline-block" style="font-size: .9rem; width: 70%;">
                        <h3>{{$companyinfo->title}}</h3>
                        <p class="mb-1"><b>Address:</b>{{$companyinfo->address}} </p>
                        <p class="mb-1"><b>Phone:</b>{{$companyinfo->phone}}</p>
                        <p class="mb-1"><b>Email:</b>{{$companyinfo->email}}</p>
                        <p class="mb-1"><b>URL:</b>{{$companyinfo->web}}</p>
                    </div>
                </td>
                <td style="width: 20%;">
                    <h4>{{$data['account']->account_type}} {{($data['account']->account_type == 'General Ledger') ? '' : 'Ledger'}}</h4>
                    @if(!empty($data['ledger_data']))
                    <p class="mb-1"><b>Name:</b>{{$data['ledger_data']->name}} </p>
                    <p class="mb-1"><b>Phone:</b>{{$data['ledger_data']->phone}} </p>
                    <p class="mb-1"><b>Address:</b>{{isset($data['ledger_data']->address) ? $data['ledger_data']->address : ''}} </p>
                    @else
                    <p>{{$data['account']->name}} Information</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="" style="margin-top: 2rem;">

        <table class="table table-bordered" cellpadding="10px" cellspacing="10px">
            <thead class="">
                <tr class="table-warning">
                    <th class="text-center">#</th>
                    <th class="text-center">Voucher Date</th>
                    <th class="text-center">Voucher Number</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">debit</th>
                    <th class="text-center">credit</th>
                    <th class="text-center">Balance</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-right text-muted" colspan="6">Beginning Balance</td>
                    <td class="text-center" style="background-color: rgb(212, 212, 212);">{{$data['beginningBalance']}} {{$data['journal_entry_rule']}}</td>
                </tr>
                @if(!empty($data['transactions']))
                @php
                $i=1;
                @endphp
                @foreach($data['transactions'] as $transaction)
                <tr>
                    <td class="text-center text-muted">{{$i}}</td>
                    <td class="text-center">{{$transaction->voucher_date}}</td>
                    <td class="text-center">{{$transaction->voucher_number}}</td>
                    <td class="text-center">{{$transaction->note}}</td>
                    <td class="text-center">{{$transaction->debit}}</td>
                    <td class="text-center">{{$transaction->credit}}</td>
                    <td class="text-center">{{($transaction->closingBalance < 0) ? ('('. abs($transaction->closingBalance) . ')') : $transaction->closingBalance }}</td>

                </tr>
                @php
                $i++;
                @endphp
                @endforeach
                @endif
                <tr>
                    <td class="text-right text-muted" colspan="4">Ending Balance</td>
                    <td class="text-center">{{$data['totalDebit']}}</td>
                    <td class="text-center">{{$data['totalCredit']}}</td>
                    <td class="text-center">{{$data['endingBalance']}} {{$data['journal_entry_rule']}}</td>
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