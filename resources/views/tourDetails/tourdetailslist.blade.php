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
                        <div>Tour List
                            <div class="page-title-subheading">This is an example dashboard created using build-in elements and components.
                            </div>

                        </div>
                    </div>
                    <div class="page-title-actions">
                        <a href="{{route('newtour')}}">
                            <button type="button" data-toggle="tooltip" title="" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark" data-original-title="Add New tour">
                                <i class="fa fa-plus"></i>
                            </button>
                        </a>

                    </div>
                </div>
            </div>


           

            <div class="row">
                <div class="col-md-12">
                    <div class="main-card mb-3 card">
                        <div class="card-header">Active tours
                            <div class="btn-actions-pane-right">
                                <div role="group" class="btn-group-sm btn-group">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive p-3">
                            <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                <thead>
                                <tr>
                                    <th class="">#</th>
                                    <th class="">Tour From</th>
                                    <th class="">Tour To</th>
                                    <th class="">Date From</th>
                                    <th class="">Date to</th>
                                    <th class=""> No of Tourism</th>
                                    
                                   
                                 
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($tourdetails))
                                @foreach($tourdetails as $tour)

                                <tr class="">
                                    <td class="text-start text-muted">{{$tour->id}}</td>
                                    <td>
                                        <div class="widget-content p-0">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left">
                                                    <div class="widget-content-left">
                                                        <img width="40" class="rounded-circle" src="" alt="">
                                                    </div>
                                                </div>
                                                <div class="widget-content-left flex2">
                                                    <div class="widget-heading">{{$tour->tour_from}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="">{{$tour->tour_to}}</td>
                                    <td class="">{{$tour->date_from}}</td>
                                    <td class="">{{$tour->date_to}}</td>
                                    <td class="">{{$tour->no_of_tourism}}</td>
                                 
                                   
                                   
                                    <td class="text-center">
                                   
                                            <div class="mb-2 mr-2 btn-group">
                                                <button class="btn btn-outline-success">Edit</button>
                                                <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle-split dropdown-toggle btn btn-outline-success"><span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(68px, 33px, 0px);">
                                                 
                                                    <a href="{{route('edittourdetail',$tour->id)}}"><button type="button" tabindex="0" class="dropdown-item">Edit</button></a>
                                                    <a  href="{{route('deletetourdetail',$tour->id)}}"><button type="button" tabindex="0" class="dropdown-item">Delete</button></a>
                                                </div>
                                            </div>
                                           
                                    </td>
                                </tr>

                                @endforeach
                                @endif

                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mr-3 card-footer">
                            <!-- <div class="col-lg-12">
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
                            </div> -->
                            




                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>
