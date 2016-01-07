@extends('content_handler')

@section('breadcrumb')
    
@stop

@section('page_header')
    User Form
@stop

@section('content')
    <div class="portlet box blue-hoki">
        <div class="portlet-title">
            <div class="caption">
                User Form
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form method="post" class="horizontal-form" id="users_form">
                <div class="form-body">
                        <h3 class="form-section">Login Info</h3>
                        <div class="row">
                            <div class="col-md-{{($company_id==1)?'3':'4'}}">
                                <div class="form-group">
                                    <label class="control-label">Username</label>
                                    {!!
                                        Form::text('username',isset($user_data)?$user_data->username:null,
                                        array(
                                            'class'=>'form-control',
                                            'id'=>'username',
                                            'placeholder'=>'Username',
                                            (isset($user_data)?'readonly':'') => ''
                                        ))
                                    !!}
                                    @if (isset($user_data))
                                        <input type="hidden" id="user_id" value="{{$user_data->user_id}}" />
                                        <input type="hidden" id="user_group_id" value="{{$user_data->group_id}}" />
                                    @endif
                                    @if ($company_id != 1)
                                        <input type="hidden" id="company_id" value="{{$company_id}}" />
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-{{($company_id==1)?'3':'4'}}">
                                <div class="form-group">
                                    <label class="control-label">Status</label>
                                    {!!
                                        Form::select('status',
                                            array(
                                                'Active'=>'Active',
                                                'Inactive'=>'Inactive'
                                            ),
                                            isset($user_data)?$user_data->status:null,
                                            array(
                                                'class'=>'select2 form-control',
                                                'id'=>'status',
                                                'data-placeholder'=>'Choose a Status',
                                                'tabindex' => '1'
                                            )
                                        )
                                    !!}
                                </div>
                            </div>
                            <div class="col-md-{{($company_id==1)?'3':'4'}}">
                                <div class="form-group">
                                    <label class="control-label">Group</label>
                                    <select id="group_id" name="group_id" class="select2 form-control" data-placeholder="Choose a Group" tabindex="1">
                                    </select>
                                </div>
                            </div>
                            @if ($company_id == 1)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Company</label>
                                    <select id="company_id" name="company_id" class="select2 form-control" data-placeholder="Choose a Company" tabindex="1">
                                        @foreach(App\Models\Company\Company::all() as $company)
                                            @if (isset($user_data) && ($company->company_id == $user_data->group->company_id))
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
                        <h3 class="form-section">Personal Info</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">First Name</label>
                                    {!!
                                        Form::text('first_name',isset($user_data)?$user_data->userinfo->first_name:null,
                                        array(
                                            'class'=>'form-control',
                                            'id'=>'first_name',
                                            'placeholder'=>'First Name'
                                        ))
                                    !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Middle Name</label>
                                    {!!
                                        Form::text('middle_name',isset($user_data)?$user_data->userinfo->middle_name:null,
                                        array(
                                            'class'=>'form-control',
                                            'id'=>'middle_name',
                                            'placeholder'=>'Middle Name'
                                        ))
                                    !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Last Name</label>
                                    {!!
                                        Form::text('last_name',isset($user_data)?$user_data->userinfo->last_name:null,
                                        array(
                                            'class'=>'form-control',
                                            'id'=>'last_name',
                                            'placeholder'=>'Last Name'
                                        ))
                                    !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Email</label>
                                    {!!
                                        Form::text('email',isset($user_data)?$user_data->userinfo->email:null,
                                        array(
                                            'class'=>'form-control',
                                            'id'=>'email',
                                            'placeholder'=>'Email Address'
                                        ))
                                    !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Contact Number</label>
                                    {!!
                                        Form::text('contact_no',isset($user_data)?$user_data->userinfo->contact_no:null,
                                        array(
                                            'class'=>'form-control',
                                            'id'=>'contact_no',
                                            'placeholder'=>'Contact Number'
                                        ))
                                    !!}
                                </div>
                            </div>
                        </div>
                </div>
                <div class="form-actions right">
                        <a class=" btn default page_switcher" href="{{URL::to('setting/users')}}">
                            Back
                        </a>
                        @if (!isset($user_data))
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
    @if (!isset($user_data))
    <script>
        jQuery(document).ready(function() {       
           UsersForm.init();
        });
    </script>
    @else
    <script>
        jQuery(document).ready(function() {       
           UsersForm.initUpdate();
        });
    </script>
    @endif
@stop
