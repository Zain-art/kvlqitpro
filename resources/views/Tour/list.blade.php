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
                        <div>Tours List
                            <div class="page-title-subheading">This is an example dashboard created using build-in elements and components.
                            </div>

                        </div>
                    </div>
                    <div class="page-title-actions">
                        <a href="{{ route('newTour') }}">
                            <button type="button" data-toggle="tooltip" title="" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark" data-original-title="Add New Tour">
                                <i class="fa fa-plus"></i>
                            </button>
                        </a>

                    </div>
                </div>
            </div>
            <div class="row card mx-0 mb-2 pt-1">
                <div class="col-md-12">
                    <form action="{{route('searchTour',['asad'=>'amir'])}}" method="post">
                        @csrf
                        <!-- <p style="font-size: 1.2rem;" class="mb-1">Search</p> -->
                        <div class="row no-gutters">
                            <div class="form-group col-2">
                                <label for="tour_name" class="form-label" style="font-size: 1rem;">Tour Name</label>
                                <input type="text" name="tour_name" class="form-control" value="{{(isset($tour_name)) ? $tour_name : ((isset($_GET['queries']['tour_name'])) ? $_GET['queries']['tour_name'] : '')}}">
                            </div>
                            <div class="form-group col-2 mx-2 mt-1">
                                <div class="form-group">
                                    <label for="branch" class="">
                                        Status
                                    </label>
                                    <select class="js-example-basic-single form-control" placeholder="Select" name="status" id="status">
                                        <option value="">Status</option>
                                        <option value="1" {{isset($status) ? ($status == 1 ? 'selected' : '') : ''}}>Open</option>
                                        <option value="0" {{isset($status) ? ($status == 0 ? 'selected' : '') : ''}}>Closed</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-2 ml-n4">
                                <label for="from_date" class="form-label" style="font-size: 1rem;">From</label>
                                <input type="date" name="from_date" class="form-control" value="{{(isset($from_date)) ? $from_date : ((isset($_GET['queries']['from'])) ? $_GET['queries']['from_date'] : '')}}">
                            </div>
                            <div class="form-group col-2 mx-2">
                                <label for="to_date" class="form-label" style="font-size: 1rem;">To</label>
                                <input type="date" name="to_date" class="form-control" value="{{(isset($to_date)) ? $to_date : ((isset($_GET['queries']['to'])) ? $_GET['queries']['to_date'] : '')}}">
                            </div>
                            <div class="col-2 align-self-end ml-2 pb-3" style="margin-bottom: 1.1rem;">
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
                        <div class="card-header">Tours List
                        </div>
                        <div class="table-responsive p-3">
                            <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Tour Name</th>
                                        <th class="">Start Date</th>
                                        <th class="">End Date</th>
                                        <th class="">No. of Tourists</th>
                                        <th>From</th>
                                        <th class="">To</th>
                                        <th class="">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($lists))
                                    @php
                                    $i = 1;
                                    @endphp
                                    @foreach($lists as $list)
                                    <tr class="">
                                        <td class="text-left">{{$i}}</td>
                                        <td class="text-left">{{$list->tour_name}}</td>
                                        <td>
                                            {{$list->start_date}}
                                        </td>

                                        <td class="">{{$list->end_date}}</td>
                                        <td class="">{{$list->number_of_tourists}}</td>
                                        <td>{{$list->from}}</td>
                                        <td class="">{{$list->to}}</td>
                                        <td class="">{{($list->is_tour_open == 1) ? 'OPEN' : 'CLOSED'}}</td>



                                        <td class="text-center">
                                            <div class="mb-2 mr-2 btn-group">
                                                <button class="btn btn-outline-success">Edit</button>
                                                <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle-split dropdown-toggle btn btn-outline-success"><span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(68px, 33px, 0px);">
                                                    <a href="{{route('editTour',$list->id)}}"><button type="button" tabindex="0" class="dropdown-item">Edit</button></a>
                                                    <a href="#" onclick="deleteRecord('{{route('deleteTour',$list->id)}}');"><button type="button" tabindex="0" class="dropdown-item">Delete</button></a>
                                                </div>

                                            </div>

                                        </td>
                                    </tr>

                                    @php
                                    $i++;
                                    @endphp
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
                            {{ $lists->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>