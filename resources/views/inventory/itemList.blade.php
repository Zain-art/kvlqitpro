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
                        <div>Items
                            <div class="page-title-subheading">This is an example dashboard created using build-in elements and components.
                            </div>
                        </div>
                    </div>

                    <div class="page-title-actions">
                        <a href="{{ route('newitem') }}">
                            <button type="button" data-toggle="tooltip" title="" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark" data-original-title="Add New Item">
                                <i class="fa fa-plus"></i>
                            </button>
                        </a>

                    </div>
                </div>
            </div>
            <div class="row card mx-0 mb-2 p-3">
                <div class="col-md-12">
                    <form action="{{route('searchItems')}}" method="post">
                        @csrf

                        <div class="row no-gutters">

                            <div class="form-group col-2">
                                <label for="from_date" class="form-label" style="font-size: 1rem;">Item Name</label>
                                <input type="text" name="item_name" class="form-control" value="{{(isset($item_name)) ? $item_name : ((isset($_GET['queries']['item_name'])) ? $_GET['queries']['item_name'] : '')}}">
                            </div>
                            <!-- <div class="form-group col-2 ml-3">
                                <label for="from_date" class="form-label" style="font-size: 1rem;">Item Size</label>
                                <input type="text" name="item_size" class="form-control" value="{{(isset($item_size)) ? $item_size : ((isset($_GET['queries']['item_size'])) ? $_GET['queries']['item_size'] : '')}}">
                            </div> -->
                            <div class="form-group col-2 ml-3 mt-1">
                                <label for="branch" class="">
                                    Category
                                </label>
                                <select class="js-example-basic-single form-control" placeholder="Select" name="item_category" onchange="checkCategory(this);">
                                    <option value="">Select</option>
                                    @if(!empty($categories))
                                    @foreach($categories as $category)
                                    <option value="{{$category->id}}" {{(isset($item_category)) ? ($category->id==$item_category) ? 'Selected' : '' : ''}}> {{$category->name}} </option>
                                    @endforeach
                                    @endif
                                </select>
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
                            <div class="form-group col-2 btns" style="margin:2rem  0 0; margin-left: -90px;">
                          
                            <a href="{{route('categorybylist',3)}}">
                                 <button  type="button" class=" btn btn-outline-warning  form-control raw-material tab-item tab-border active"  >Raw Material</button>
                            </a>
                         
                               
                            </div>
                            <div class="form-group col-2 btns" style="margin:2rem  0 0; margin-left: 10px;">
                            <a href="{{route('categorybylist',5)}}">
                                 <button type="button"  class=" btn btn-outline-warning  form-control finish goods tab-item"  >Finish Goods</button>
                            </a>
                               
                            </div>
                            <div class="form-group col-2 btns" style="margin:2rem  0 0; margin-left: 10px;">
                            <a href="{{route('categorybylist',6)}}">
                                 <button type="button"  class=" btn btn-outline-warning  form-control other goods tab-item"  >Other Goods</button>
                            </a>
                               
                            </div>
                            
                           
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="main-card mb-3 card">
                        <div class="card-header">Item List
                            <!-- <div class="btn-actions-pane-right">
                                <div role="group" class="btn-group-sm btn-group">
                                <a href="{{ route('activemenu') }}"><button class="active btn btn-focus">Active</button></a>
                                <a href="{{ route('inactivemenu') }}"><button class="btn btn-focus">InActive</button></a>
                                </div>
                            </div> -->
                        </div>
                        <div class="table-responsive">
                            <table class="align-middle mb-0 table table-borderless table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Code</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Purchase Price</th>
                                        <th class="text-center">Sale Price</th>
                                        <!--  <th class="text-center">Stock</th>-->
                                        <th class="text-center">Category</th>
                                        <th class="text-center">Stock</th>
                                        <th class="text-center">Unit</th>
                                        <!-- <th class="text-center">Address</th>
                                    <th class="text-center">Action</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!empty($items))
                                    @foreach($items as $item)
                                    <tr>
                                        <td class="text-center text-muted">{{$item->id}}</td>
                                        <td class="text-center text-muted">{{$item->code}}</td>
                                        <td>
                                            <div class="widget-content p-0">
                                                <div class="widget-content-wrapper">
                                                    <div class="widget-content-left mr-3">

                                                    </div>
                                                    <div class="widget-content-left flex2">
                                                        <div class="widget-heading">{{$item->name}}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{$item->purchase_price}}</td>
                                        <td class="text-center">{{$item->sele_price}}</td>
                                        <!--<td class="text-center">{{$item->stock}}</td>-->
                                        <td class="text-center show">{{$item->category_name}}</td>
                                        <td class="text-center">{{$item->stock}}</td>
                                        <td class="text-center">{{$item->unit}}</td>

                                        <td class="text-center">
                                            <div class="mb-2 mr-2 btn-group">
                                                <button class="btn btn-outline-success">Edit</button>
                                                <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle-split dropdown-toggle btn btn-outline-success"><span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(68px, 33px, 0px);">
                                                    <a href="{{route('edititem',$item->id)}}"><button type="button" tabindex="0" class="dropdown-item">Edit</button></a>
                                                    <a href="{{route('deleteitem',$item->id)}}"><button type="button" tabindex="0" class="dropdown-item">Delete</button></a>
                                                    <a href="{{route('itemLedgerEntries',$item->id)}}"><button type="button" tabindex="0" class="dropdown-item">Ledger</button></a>
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
                            
                            {{ $itemlist->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-app-layout>