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
                    <p class="mb-1"><b>Invoice #:</b> {{$vendorpayment->voucher_number}}</p>
                        <p class="mb-1"><b>Invoice Date:</b> {{$vendorpayment->received_date}}</p>
                    </div>
                </td>
            </tr>
       
        </table>
        <hr>
  
    </div>
    <p class="text-right mb-2" style="font-size: 1.4rem;"><b>Vendor Payment Invoice </b></p>
    <table class="table table-bordered">
        <thead>
            <tr class="table-warning">
                <th>#</th>
                <th>Name</th>
                <th>Voucher No.</th>
                <th>Check Number</th>
                <th>Bank Name</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{$vendorpayment->name}}</td>
                <td>{{$vendorpayment->received_date}}</td>
                <td> {{$vendorpayment->check_number}}</td>&nbsp;&nbsp;
                <td>{{$vendorpayment->about_bank}}</td>&nbsp;&nbsp;
                <td>{{$vendorpayment->amount}}</td>
            </tr>
            <tr>
                <td colspan="5">Net Total</td>
                <td>{{$vendorpayment->amount}}</td>
            </tr>
        </tbody>
    </table>
    <p style="font-size: 1.1rem;"><b>Payment Mode</b></p>
    @if($vendorpayment->payment_mode == 1)
    <p class="mb-1"><b>Cash</b></p>
    @endif
    @if($vendorpayment->payment_mode == 2)
    <p class="mb-1"><b>Online</b></p>
    <span>Check Number: &nbsp; {{$vendorpayment->check_number}}</span> <br> <span>Bank Name: &nbsp; {{$vendorpayment->about_bank}}</span>
    @endif
    @if($vendorpayment->payment_mode == 3)
    <p class="mb-1"><b>Check</b></p>
    <span>Check Number: &nbsp; {{$vendorpayment->check_number}}</span> <br> <span>Bank Name: &nbsp; {{$vendorpayment->bank_name}}</span>
    @endif
    <p class="mb-1 mt-2"><b>Description:</b></p>
    <p>{{$vendorpayment->note}}</p>

    <div class="mt-3">
        <div class="d-inline-block ">
            <p class="mb-0" style="font-size: 1.3rem; font-weight:900">Thank You for Your business!</p>
            <p><span style="font-size: 1.3rem; font-weight:900">Print Date:</span> @php echo date('Y-m-d'); @endphp</p>
        </div>
        <div class="d-inline-block float-right">
            <p style="border-top: 1px solid black;"><span class="" style="font-size: 1.3rem; font-weight:900; ">Prepared by:</span>admin</p>
        </div>
    </div>

</body>
</html>
