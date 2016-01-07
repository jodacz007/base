@extends('content_handler')

@section('breadcrumb')
    
@stop

@section('page_header')
    Group
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet box blue-hoki">
                <div class="portlet-title">
                    <div class="caption">
                        Group List
                    </div>
                    <div class="actions">
                        @if ($action_value&Action::$Add)
                            <a class="btn default btn-xs green page_switcher" href="{{URL::to('setting/group/add')}}">
                                <i class="fa fa-edit"></i>
                                Add
                            </a>
                        @endif
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="group_list_table">
                        <thead>
                            <tr>
                                <th>
                                    Company Name
                                </th>
                                <th>
                                    Group Name
                                </th>
                                <th>
                                    Module Names
                                </th>
                                <th>
                                    Pages
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div id="action_str" style="display:none">
                        <a class=" btn default btn-xs" href="{{URL::to('setting/group/view/##NUM##')}}" data-target="#info_ajax" data-toggle="modal">
                            <i class="fa fa-eye"></i>
                            View
                        </a>
                        @if ($action_value&Action::$Edit)
                        <a class="btn default btn-xs yellow page_switcher" href="{{URL::to('setting/group/update/##NUM##')}}">
                            <i class="fa fa-edit"></i>
                            Edit
                        </a>
                        @endif
                        @if ($action_value&Action::$Delete)
                        <a data_id="##NUM##" class="remove_group btn default btn-xs red">
                            <i class="fa fa-trash"></i>
                            Remove
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
           Group.init();
        });
    </script>
@stop