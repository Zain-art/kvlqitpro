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
                        <div>New Agent
                            <div class="page-title-subheading">This is an example dashboard created using build-in elements and components.
                            </div>
                        </div>
                    </div>
                    <div class="page-title-actions">
                        <a href="{{route('travelagentlist')}}">
                            <button type="button" data-toggle="tooltip" title="" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark" data-original-title="Agents List">
                                <i class="fa fa-th-list"></i>
                            </button>
                        </a>

                    </div>
                </div>
            </div>
            <div class="main-card mb-3 card">
                <form class="Q-form"  action="{{(isset($agents)) ? route('updatetravelagent') : route('savetravelagent')}}" method="post">
                    @csrf
                    <div class="card-body">
                        <h5 class="card-title">Add Agent</h5>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail11" class="">Name</label>
                                    <input name="name" id="text" placeholder="Enter Name" type="text" value="{{(isset($agents)) ? $agents->Name : ''}}" class="form-control">
                                    <input  name="id" id="id" value="{{(isset($agents)) ? $agents->id : ''}}" type="hidden" class="form-control">
                                </div>
                                <div class="position-relative form-group">
                                    <label for="phone" class="">Phone No</label>
                                    <input name="phone" id="phone" value="{{(isset($agents)) ? $agents->Phone : ''}}" type="text" class="form-control" placeholder="Enter Phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                 <div class="position-relative form-group">
                                    <label for="address" class="">Address</label>
                                    <input name="address" id="address" value="{{(isset($agents)) ? $agents->Address : ''}}" type="text" class="form-control" placeholder="Address">
                                </div>
                             <div class="position-relative form-group">
                                    <label for="commission" class="">Commission_%</label>
                                    <input name="commission" id="commission" value="{{(isset($agents)) ? $agents->Commission_persent : ''}}" type="text" class="form-control" placeholder="Enter commission">
                                </div>
                            </div>
                            
                        </div>
                        
                       
                            
                            

                    <div class="d-flex justify-content-end px-4 pb-3">
                        <button type="submit" class="mt-2 btn btn-primary">{{(isset($agents)) ? 'Update' : 'Save'}}</button>
                    </div>
                </form>
            </div>

        </div>

    </div>

</x-app-layout>
