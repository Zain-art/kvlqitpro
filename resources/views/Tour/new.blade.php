<x-app-layout>
    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="pe-7s-plus icon-gradient bg-mean-fruit">
                            </i>
                        </div>
                        <div>New Tour
                            <div class="page-title-subheading">This is an example dashboard created using build-in elements and components.
                            </div>

                        </div>
                    </div>
                    <div class="page-title-actions">
                        <a href="{{ route('tourList') }}">
                            <button type="button" data-toggle="tooltip" title="" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark" data-original-title="Tour List">
                                <i class="fa fa-th-list"></i>
                            </button>
                        </a>

                    </div>
                </div>
            </div>
            <!--HTML USED FOR CREATE NEW ROW -->
            <table style="display: none;">
                <tbody class="new_row">
                    <tr>

                        <td class="text-center"><label class="sr_no">1</label></td>
                        <td class="text-center"><input name="detail[]" id="" placeholder="" value="" type="text" class="form-control " ></td>
                        <td class="text-center"><input name="amount[]" id="" placeholder="" value="" type="text" class="form-control " ></td>
                        <td><button class="btn btn-dark" type="button" onclick="removeRow(this);"><i class="fas fa-times"></i></button></td>
                 
                    </tr>
                <tbody>
            </table>

            <div class="main-card mb-3 card">
                <form class="Q-form" action="{{(isset($record)) ? route('updateTour') : route('saveTour')}}" method="post">
                    @csrf
                    <div class="card-body">
                        <h5 class="card-title">Tour Information</h5>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail11" class="">Tour Name</label>
                                    <input name="tour_name" id="tour_name" placeholder="" type="text" value="{{(isset($record)) ? $record->tour_name : ''}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">

                                    <div class="position-relative form-group col-6 ">
                                        <label for="start_date" class="">Start Date</label>
                                        <input name="start_date" id="start_date" placeholder="" type="date" value="{{(isset($record)) ? $record->start_date : ''}}" class="form-control">
                                    </div>
                                    <div class="position-relative form-group col-6 pl-0">
                                        <label for="end_date" class="">End Date</label>
                                        <input name="end_date" id="end_date" placeholder="" value="{{(isset($record)) ? $record->end_date : ''}}" type="date" class="form-control">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="number_of_tourists" class="">Number of Tourists</label>
                                    <input name="number_of_tourists" id="number_of_tourists" placeholder="" value="{{(isset($record)) ? $record->number_of_tourists : ''}}" type="text" class="form-control">
                                    <input type="hidden" name="id" value="{{(isset($record)) ? $record->id : ''}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">

                                    <div class="position-relative form-group col-6 ">
                                        <label for="from" class="">From</label>
                                        <input name="from" id="from" placeholder="" type="text" value="{{(isset($record)) ? $record->from : ''}}" class="form-control">
                                    </div>
                                    <div class="position-relative form-group col-6 pl-0">
                                        <label for="to" class="">To</label>
                                        <input name="to" id="end_date" placeholder="" value="{{(isset($record)) ? $record->to : ''}}" type="text" class="form-control">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                        @if(isset($record))
                            <div class="col-md-6 mt-4 d-flex align-items-center">
                                <label for="name" class="">Status &nbsp; &nbsp;</label>
                                <label class="switch">
                                    <input type="checkbox" {{(isset($record)) ? ($record->is_tour_open==1) ? 'checked' : '' : ''}} value="1" name="status">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            @endif
                        </div>
                        <div class="form-row">
                            <div class="table-responsive col-md-12">
                                <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Detail</th>
                                            <th class="text-center">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($record))
                                        @if(!empty(unserialize($record->expense_details)))
                                        @php
                                        $i=1;
                                        @endphp
                                        @foreach(unserialize($record->expense_details) as $_detail)
                                        <tr>
                                            <td class="text-center">{{$i}}</td>
                                            <td class="text-center"><input name="detail[]" id="item_price" placeholder="" value="{{(isset($record) && array_key_exists("detail",$_detail)) ? $_detail['detail'] : ''}}" type="text" class="form-control"></td>
                                            <td class="text-center"><input name="amount[]" id="item_pcs" placeholder="" value="{{(isset($record) && array_key_exists("amount",$_detail)) ? $_detail['amount'] : ''}}" type="text" class="form-control" ></td>

                                        </tr>
                                        @php
                                        $i++;
                                        @endphp
                                        @endforeach
                                        @endif
                                        @endif

                                        <tr class="btn-add-new">
                                            <td>
                                                <button class="btn btn-primary add_row" type="button"><i class="fas fa-plus"></i></button>
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                    </tbody>
                                </table>





                            </div>
                        </div>

                    </div>
                    <div class="d-block text-center card-footer">
                        <button type="submit" class="mt-2 btn btn-primary">{{(isset($record)) ? 'Update' : 'Save'}}</button>
                    </div>
                </form>
            </div>


        </div>

    </div>

</x-app-layout>