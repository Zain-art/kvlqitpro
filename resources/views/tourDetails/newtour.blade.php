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
                        <a href="{{route('tourdetailslist')}}">
                            <button type="button" data-toggle="tooltip" title="" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark" data-original-title="toursave List">
                                <i class="fa fa-th-list"></i>
                            </button>
                        </a>

                    </div>
                </div>
            </div>
            <div class="main-card mb-3 card">
                <form class="Q-form" action="{{(isset($toursave)) ? route('updatetourdetail') : route('savetour')}}" method="post">
                    @csrf
                    <div class="card-body">
                        <h5 class="card-title">Tour Details</h5>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="tour route" class="">Tour Route From</label>
                                    <input name="tour_from" id="text" placeholder="tour route from" type="text" value="{{(isset($toursave)) ? $toursave->tour_from : ''}}" class="form-control">
                                    <input name="id" id="id" value="{{(isset($toursave)) ? $toursave->id : ''}}" type="hidden" class="form-control">
                                </div>
                                
                               
                            </div>
                            <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="to" class="">  To</label>
                                <input name="tour_to" id="text" placeholder="tour route to" type="text" value="{{(isset($toursave)) ? $toursave->tour_to : ''}}" class="form-control">
                               
                            </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail11" class="">Tour Date From</label>
                                    <input name="date_from" id="text" placeholder="tour route from" type="date" value="{{(isset($toursave)) ? $toursave->date_from : ''}}" class="form-control">
                                   
                                </div>
                                
                               
                            </div>
                            <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="exampleEmail11" class="">  To</label>
                                <input name="date_to" id="text" placeholder="tour route to" type="date" value="{{(isset($toursave)) ? $toursave->date_to : ''}}" class="form-control">
                               
                            </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="tourism" class="">no. of Tourism</label>
                                    <input name="no_of_tourism" id="address" value="{{(isset($toursave)) ? $toursave->no_of_tourism : ''}}" type="number" class="form-control" placeholder="no of tourism">
                                </div>


                            </div>
                            
                        </div>
                        <div class="row">
                        <div class="col-md-6 ">
                                <div class="position-relative form-group">
                                    <button class="btn btn-primary btn-sm">+</button>
                                </div>


                            </div>





                            <div class="d-flex justify-content-end px-4 pb-3 ">
                                <button type="submit" class="mt-2 btn btn-primary">{{(isset($toursave)) ? 'Update' : 'Save'}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>

    </div>

</x-app-layout>