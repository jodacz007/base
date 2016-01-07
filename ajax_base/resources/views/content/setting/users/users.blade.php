@extends('content_handler')

@section('breadcrumb')
    <li>
        <i class="fa fa-user"></i>
        <a class="page_switcher" href="{{URL::to('/')}}">Home</a>
    </li>
@stop

@section('page_header')
    Users
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue-hoki">
                <div class="portlet-title">
                    <div class="caption">
                        User List
                    </div>
                    <div class="actions">
                        @if ($action_value&Action::$Add)
					        <a class="btn default btn-xs green page_switcher" href="{{URL::to('setting/users/add')}}">
					            <i class="fa fa-edit"></i>
					            Add
					        </a>
                        @endif
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="user_list_table">
                        <thead>
                            <tr>
                                <th>
                                    First Name
                                </th>
                                <th>
                                    Middle Name
                                </th>
                                <th>
                                    Last Name
                                </th>
                                <th>
                                    Company Name
                                </th>
                                <th>
                                    Group
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div id="action_str" style="display:none">
                        @if ($action_value&Action::$Edit)
                        <a data_id="##NUM##" class="reset_pwd btn default btn-xs purple">
                            <!--<i class="fa fa-edit"></i>-->
                            Reset Password
                        </a>
                        <a data_id="##NUM##" class="users_change_status btn default btn-xs purple">
                            <!--<i class="fa fa-edit"></i>-->
                            Change Status
                        </a>
                        @endif
                        <a class=" btn default btn-xs" href="{{URL::to('setting/users/view/##NUM##')}}" data-target="#user_info_ajax" data-toggle="modal">
                            <i class="fa fa-eye"></i>
                            View
                        </a>
                        @if ($action_value&Action::$Edit)
                        <a class="btn default btn-xs yellow page_switcher" href="{{URL::to('setting/users/update/##NUM##')}}">
                            <i class="fa fa-edit"></i>
                            Edit
                        </a>
                        @endif
                        @if ($action_value&Action::$Delete)
                        <a data_id="##NUM##" class="remove_user btn default btn-xs red">
                            <i class="fa fa-trash"></i>
                            Remove
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Modal Start-->
    <div class="modal fade" id="user_info_ajax" role="basic" aria-hidden="true">
        <div class="page-loading page-loading-boxed">
            <img src="{{URL::to('/')}}/assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
            <span>
                &nbsp;&nbsp;Loading...
            </span>
        </div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    <!--Modal End-->
@stop

@section('init_js')
    <script>
        jQuery(document).ready(function() {       
           Users.init();
        });
    </script>
@stop