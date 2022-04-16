<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale Invoice</title>
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
                    <p class="mb-1"><b>Invoice #:</b> {{$record->voucher_number}}</p>
                        <p class="mb-1"><b>Invoice Date:</b> {{$record->voucher_date}}</p>
                    </div>
                </td>
            </tr>
       
        </table>
        <hr>
  
    </div>
    <p class="text-right mb-2" style="font-size: 1.4rem;"><b>Advance Return Invoice </b></p>
    <table class="table table-bordered">
        <thead>
            <tr class="table-warning">
                <th>#</th>
                <th>Employee Name</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
    
            <tr>
                <td>1</td>
                <td>{{$record->name}}</td>
                <td>{{$record->amount}}</td>
            
            </tr>

        </tbody>
    </table>

    <p class="mb-1"><b>Description:</b></p>
    <p>{{$record->note}}</p>

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
