<x-app-layout>
    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="pe-7s-users icon-gradient bg-mean-fruit">
                            </i>
                        </div>
                        <div style="text-transform: capitalize;">{{(isset($data['account'])) ? $data['account']->name : ''}}
                            <div class="page-title-subheading">This is an example dashboard created using build-in elements and components.
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="text-right">
                <a href="{{route('customerLedgerPdf',['from_date'=>(isset($from_date)) ? $from_date : date('Y-m-d',strtotime('-1 month')),'to_date'=>(isset($to_date)) ? $to_date : date('Y-m-d'),'general_ledger_account_id'=>(isset($data['account']->id)) ? $data['account']->id : 'none'])}}" target="_blank" class="btn btn-outline-success mb-2 pdf">Download PDF</a>
            </div>
            <div class="row card mx-0 mb-2 pt-1">
                <div class="col-md-12">
                    <form action="{{route('searchledger')}}" method="post">
                        @csrf
                        <input type="hidden" name="general_ledger_account_id" id="general_ledger_account_id" value="{{$data['account']->id}}">
                        <div class="row no-gutters align-items-baseline">
                            <div class="form-group col-2">
                                <label for="from_date" class="form-label" style="font-size: 1rem;">From</label>
                                <input type="date" name="from_date" class="form-control" value="{{(isset($data['from_date'])) ? $data['from_date'] : date('Y-m-d',strtotime('-1 month'))}}">
                            </div>
                            <div class="form-group col-2 mx-2">
                                <label for="to_date" class="form-label" style="font-size: 1rem;">To</label>
                                <input type="date" name="to_date" class="form-control" value="{{(isset($data['to_date'])) ? $data['to_date'] : date('Y-m-d')}}">
                            </div>
                            <div class="col-2 align-self-end ml-2" style="margin-bottom: 1.1rem;">
                                <div class="page-title-actions">
                                    <a href="">
                                        <button type="submit" data-toggle="tooltip" title="" data-placement="bottom" class="btn-shadow btn btn-dark" data-original-title="Search">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="main-card mb-3 card">
                        <div class="px-3 py-3" style="height: auto; display:block;">
                            <div class="d-flex" style="text-transform: capitalize;">
                                @if(!empty($account))
                                <div class="pb-2"><b>{{$account->name}} <span>-</span> &nbsp; </b></div>
                                <div class="pb-2"><b>{{$account->phone}} <span>-</span> &nbsp; </b></div>
                                <div><b>{{isset($account->address) ? $account->address : ''}}</b></div>
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Voucher Date</th>
                                        <th class="text-center">Voucher Number</th>
                                        <th class="text-center">Note</th>
                                        <th class="text-center">debit</th>
                                        <th class="text-center">credit</th>
                                        <th class="text-center">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-right text-muted" colspan="6">Beginning Balance</td>
                                        <td class="text-center">{{$data['beginningBalance']}} {{$data['journal_entry_rule']}}</td>
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
                                        <td class="text-center">{{($transaction->closingBalance < 0) ? ('('. abs($transaction->closingBalance) . ')') : $transaction->closingBalance  }}</td>

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
                                        <td class="text-center">{{($data['endingBalance'] < 0) ? ('('. abs($data['endingBalance']) . ')') : $data['endingBalance'] }} {{$data['journal_entry_rule']}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-block text-center card-footer">
                            <div class="col-lg-12">
                                <nav class="float-right" aria-label="Page navigation example">
                                    <ul class="pagination">
                                        <li class="page-item"><a href="javascript:void(0);" class="page-link" aria-label="Previous"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>
                                        <li class="page-item"><a href="javascript:void(0);" class="page-link">1</a></li>
                                        <li class="page-item active"><a href="javascript:void(0);" class="page-link">2</a></li>
                                        <li class="page-item"><a href="javascript:void(0);" class="page-link">3</a></li>
                                        <li class="page-item"><a href="javascript:void(0);" class="page-link">4</a></li>
                                        <li class="page-item"><a href="javascript:void(0);" class="page-link">5</a></li>
                                        <li class="page-item"><a href="javascript:void(0);" class="page-link" aria-label="Next"><span aria-hidden="true">»</span><span class="sr-only">Next</span></a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>