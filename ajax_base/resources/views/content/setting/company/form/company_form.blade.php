@extends('ajax_content_handler')

@section('content')
    <div class="portlet box blue-hoki" style="margin: 0 !important"> 
        <div class="portlet-title">
            <div class="caption">
                Company Form
            </div>
        </div>
        <div class="portlet-body form">
            <!-- BEGIN FORM-->
            <form method="post" class="horizontal-form" id="company_form">
                <div class="form-body">
                        <h3 class="form-section">Company Info</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Company Name</label>
                                    {!!
                                        Form::text('company_name',isset($company_info)?$company_info->company_name:null,
                                        array(
                                            'class'=>'form-control',
                                            'id'=>'company_name',
                                            'placeholder'=>'Company Name',
                                        ))
                                    !!}
                                    @if (isset($company_info))
                                        <input type="hidden" id="company_id" value="{{$company_info->company_id}}" />
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Email Address</label>
                                    {!!
                                        Form::text('email',isset($company_info)?$company_info->companyInfo->email:null,
                                        array(
                                            'class'=>'form-control',
                                            'id'=>'email',
                                            'placeholder'=>'Email Address',
                                        ))
                                    !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Contact Number</label>
                                    {!!
                                        Form::text('contact_number',isset($company_info)?$company_info->companyInfo->contact_number:null,
                                        array(
                                            'class'=>'form-control',
                                            'id'=>'contact_number',
                                            'placeholder'=>'Contact Number',
                                        ))
                                    !!}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Address</label>
                                    {!!
                                        Form::text('address',isset($company_info)?$company_info->companyInfo->address:null,
                                        array(
                                            'class'=>'form-control',
                                            'id'=>'address',
                                            'placeholder'=>'Company Address',
                                        ))
                                    !!}
                                </div>
                            </div>
                        </div>
                        <h3 class="form-section">Owner Info</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">First Name</label>
                                    {!!
                                        Form::text('owner_first_name',isset($company_info)?$company_info->companyInfo->owner_first_name:null,
                                        array(
                                            'class'=>'form-control',
                                            'id'=>'owner_first_name',
                                            'placeholder'=>'First Name',
                                        ))
                                    !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Middle Name</label>
                                    {!!
                                        Form::text('owner_middle_name',isset($company_info)?$company_info->companyInfo->owner_middle_name:null,
                                        array(
                                            'class'=>'form-control',
                                            'id'=>'owner_middle_name',
                                            'placeholder'=>'Middle Name',
                                        ))
                                    !!}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Last Name</label>
                                    {!!
                                        Form::text('owner_last_name',isset($company_info)?$company_info->companyInfo->owner_last_name:null,
                                        array(
                                            'class'=>'form-control',
                                            'id'=>'owner_last_name',
                                            'placeholder'=>'Last Name',
                                        ))
                                    !!}
                                </div>
                            </div>
                        </div>
                </div>
                <div class="form-actions right">
                        <!-- <a class=" btn default page_switcher" href="{{URL::to('setting/company')}}">
                            Back
                        </a> -->
                        <button type="button" data-dismiss="modal" class="btn default">Close</button>
                        @if (!isset($company_info))
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
    <script>
        jQuery(document).ready(function() {       
           CompanyForm.init('{{isset($company_info)?"update":"add"}}');
        });
    </script>
@stop
