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
                        <div>Client Information
                            <div class="page-title-subheading">This is an example dashboard created using build-in elements and components.
                            </div>

                        </div>
                    </div>
                    <div class="page-title-actions">
                        <a href="{{ route('clientsList') }}">
                            <button type="button" data-toggle="tooltip" title="" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark" data-original-title="Client List">
                                <i class="fa fa-th-list"></i>
                            </button>
                        </a>

                    </div>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <form class="Q-form" enctype="multipart/form-data" action="{{(isset($client)) ? route('updateClient') : route('saveClient')}}" method="post">
                    @csrf
                    <div class="card-body">
                        <h5 class="card-title">Company Information</h5>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail11" class="">Company Title</label>
                                    <input name="company_title" id="title" placeholder="company title" type="text" value="{{(isset($client)) ? $client->company_name : ''}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="phone" class="">Phone No</label>
                                    <input name="phone_number" id="phone" placeholder="Phone No" type="text" value="{{(isset($client)) ? $client->phone_number : ''}}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="address" class="">Company Address</label>
                                    <input name="address" id="adress" value="{{(isset($client)) ? $client->address : ''}}" type="text" class="form-control">
                                    <input name="id" id="id" value="{{(isset($client)) ? $client->id : ''}}" type="hidden" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail11" class="">License Key</label>
                                    <input name="license_key" id="" placeholder="with a placeholder" type="text" value="{{(isset($client)) ? $client->license_key : (isset($license_key) ? $license_key : '') }}" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail11" class="">Pos ID</label>
                                    <input name="pos_id" id="email" placeholder="" type="text" value="{{(isset($client)) ? $client->pos_id : '' }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail11" class="">NTN No.</label>
                                    <input name="ntn_no" id="email" placeholder="" type="text" value="{{(isset($client)) ? $client->ntn_no : '' }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail11" class="">Sales Tax No.</label>
                                    <input name="sales_tax_no" id="email" placeholder="" type="text" value="{{(isset($client)) ? $client->sales_tax_no : '' }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail11" class="">IRS Password</label>
                                    <input name="irs_password" id="email" placeholder="" type="text" value="{{(isset($client)) ? $client->irs_password : '' }}" class="form-control">
                                </div>
                            </div>

                        </div>
              
        


            </div>

            <div class="d-block text-center card-footer">
                <button type="submit" class="mt-2 btn btn-primary">{{(isset($client)) ? 'Update' : 'Register'}}</button>
            </div>
            </form>
        </div>

    </div>

    </div>

</x-app-layout>