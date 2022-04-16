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
              
            </tr>
       
        </table>
        <hr>
  
    </div>
    <table class="table table-bordered">
        <thead>
            <tr class="table-warning">
                <th class="">#</th>
                <th class="">Voucher #</th>
                <th class="text-center">From Date</th>
                <th class="text-center">To Date</th>
                <th class="">Employee Name</th>
                <th class="">Total Present</th>
                <th class="">Total Absent</th>
                <th class="">Total Leave</th>
                <th class="">Half Days</th>
                <th class="">Holiday</th>
                <th class="">Basic Salary</th>
                <th class="">Net Working Days</th>
                <th class="text-center">Gross Salary</th>
                <th class="text-center">Deduction</th>
                <th class="">Net Salary</th>
                <th class="text-center">Salary Paid</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($lists))
            @php
            $i=1;
            @endphp
            @foreach($lists as $list)
            <tr>
                <td class=" text-muted">{{$i}}</td>
                <td class=" text-muted">{{$list->voucher_number}}</td>
                <td class="">{{$list->from_date}}</td>
                <td class=>{{$list->to_date}}</td>
                <td class="">{{$list->name}}</td>
                <td class="">{{$list->total_present}} </td>
                <td class="">{{$list->total_absent}} </td>
                <td class="">{{$list->total_leave}} </td>
                <td class="">{{$list->half_days}} </td>
                <td class="">{{$list->holiday}} </td>
                <td class="">{{$list->basic_salary}} </td>
                <td class="">{{$list->net_working_days}} </td>
                <td class="">{{$list->gross_salary}} </td>
                <td class="">{{$list->total_deduction}} </td>
                <td class="">{{$list->net_salary}}</td>
                <td class="text-center">{{$list->salary_paid}}</td>
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