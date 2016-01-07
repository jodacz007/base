@extends('content_handler')

@section('breadcrumb')
    
@stop

@section('page_header')
    Company
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue-hoki">
                <div class="portlet-title">
                    <div class="caption">
                        Company List
                    </div>
                    <div class="actions">
                        @if ($action_value&Action::$Add)
					        <a class="btn default btn-xs green" href="{{URL::to('setting/company/add')}}" data-target="#form_ajax" data-toggle="modal">
					            <i class="fa fa-edit"></i>
					            Add
					        </a>
                        @endif
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="company_list_table">
                        <thead>
                            <tr>
                                <th>
                                    Company Name
                                </th>
                                <th>
                                    Owner First Name
                                </th>
                                <th>
                                    Owner Middle Name
                                </th>
                                <th>
                                    Owner Last Name
                                </th>
<!--                                 <th>
                                    Address
                                </th> -->
                                <th>
                                    Email
                                </th>
                                <th>
                                    Contact Number
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
                        <a class=" btn default btn-xs" href="{{URL::to('setting/company/view/##NUM##')}}" data-target="#info_ajax" data-toggle="modal">
                            <i class="fa fa-eye"></i>
                            View
                        </a>
                        @if ($action_value&Action::$Edit)
                        <a data-id="##NUM##" href="{{URL::to('setting/company/update/##NUM##')}}" class="btn default btn-xs green" data-target="#form_ajax" data-toggle="modal">
                            <i class="fa fa-pencil"></i>
                            Update
                        </a>
                        @endif
                        @if ($action_value&Action::$Delete)
                        <a data-id="##NUM##" data-status="##STATUS##" class="remove_btn btn default btn-xs red">
                            <i class="fa fa-trash"></i>
                            Change Status
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('init_js')
    <script>
        jQuery(document).ready(function() {       
           Company.init();
        });
    </script>
@stop