@extends('content_handler')

@section('breadcrumb')
    
@stop

@section('page_header')
    Group
@stop

@section('content')
    <div class="portlet box blue-hoki">
        <div class="portlet-title">
            <div class="caption">
                Group Form
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form method="post" class="horizontal-form" id="group_form">
                <div class="form-body">
                        <h3 class="form-section">Group Info</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Group Name</label>
                                    {!!
                                        Form::text('group_name',isset($group_data)?$group_data->group_name:null,
                                        array(
                                            'class'=>'form-control',
                                            'id'=>'group_name',
                                            'placeholder'=>'Group Name',
                                            (isset($group_data)?'readonly':'') => ''
                                        ))
                                    !!}
                                    @if (isset($group_data))
                                        <input type="hidden" id="group_id" value="{{$group_data->group_id}}" />
                                    @endif
                                    @if ($company_id != 1)
                                        <input type="hidden" id="company_id" value="{{$company_id}}" />
                                    @endif
                                </div>
                            </div>
                            @if ($company_id == 1)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Company</label>
                                    <select id="company_id" name="company_id" {{isset($group_data)?'disabled="true"':'""'}} class="select2 form-control" data-placeholder="Choose a Company" tabindex="1">
                                        @foreach(App\Models\Company\Company::all() as $company)
                                            @if (isset($group_data) && ($company->company_id == $group_data->company_id))
                                            <option selected="selected" value="{{$company->company_id}}">{{$company->company_name}}</option>
                                            @else
                                            <option value="{{$company->company_id}}">{{$company->company_name}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                        </div>
                        <h3 class="form-section">Module List</h3>
                        <div class="row">
                            @foreach (App\Models\ACL\Permission::where('group_id',$group_id)->groupBy('module_id')->get() as $permission)
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>
                                        {!!
                                            Form::checkbox('module[]',$permission->module->module_id,
                                            (isset($group_data)&&count($group_data->permission()->where('module_id',$permission->module->module_id)->get()))?true:false,
                                            array(
                                                'class'=>'modules_btn icheck',
                                                'data-checkbox' => 'icheckbox_square-grey'
                                            ));
                                        !!}
                                        {!!$permission->module->module_name!!}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <h3 class="form-section">Module Pages</h3>
                        @include('content.setting.group.form.group_form_pages')
                </div>
                <div class="form-actions right">
                        <a class="page_switcher btn default" id="group_return_btn" href="{{URL::to('setting/group')}}">
                            Back
                        </a>
                        @if (!isset($group_data))
                        <button type="submit" class="btn blue"><i class="fa fa-check"></i>Save</button>
                        @else
                        <button type="submit" class="btn blue"><i class="fa fa-check"></i>Update</button>
                        @endif
                </div>
            </form>
            <!-- END FORM-->
        </div>
    </div>
@stop

@section('init_js')
    @if (!isset($group_data))
    <script>
        jQuery(document).ready(function() {       
           GroupForm.init();
        });
    </script>
    @else
    <script>
        jQuery(document).ready(function() {       
           GroupForm.initUpdate();
        });
    </script>
    @endif
@stop
