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
                        <div>Item Menu
                            <div class="page-title-subheading">This is an example dashboard created using build-in elements and components.
                            </div>

                        </div>
                    </div>
                    <div class="page-title-actions">
                        <a href="{{ route('itemMenuList') }}">
                            <button type="button" data-toggle="tooltip" title="" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark" data-original-title="Item Menu list">
                                <i class="fa fa-th-list"></i>
                            </button>
                        </a>

                    </div>
                </div>
            </div>


            <div class="main-card mb-3 card">
                <form class="Q-form" action="{{(isset($record)) ? route('updateItemMenu') : route('saveItemMenu')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" id="id" value="{{(isset($record)) ? $record->id : ''}}">
                    <div class="card-body">
                        <h5 class="card-title">Item Menu Information</h5>
                        <div class="form-row">

                            <div class="col-md-10">
                                <div class="position-relative form-group">
                                    <label for="exampleEmail11" class="">Menu Name</label>
                                    <input name="menu_name" id="invoice_date" placeholder="" type="text" value="{{(isset($record)) ? $record->name : ''}}" class="form-control">
                                </div>
                                <input type="hidden" name="id" value="{{(isset($record)) ? $record->id : ''}}">
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
