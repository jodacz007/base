<div class="row">
    <div class="col-md-12">
        <!-- BEGIN PORTLET-->
        <div class="portlet box blue" style="margin-bottom:0px">
            <div class="portlet-title">
                <div class="caption">
                    Company Information
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <td>
                                <b>Company Name</b>
                            </td>
                            <td>
                                {{$company_info->company_name}} &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Owner</b>
                            </td>
                            <td>
                                {{$company_info->companyInfo->owner_first_name}}&nbsp;
                                {{$company_info->companyInfo->owner_middle_name}}&nbsp;
                                {{$company_info->companyInfo->owner_last_name}}&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Contact Number</b>
                            </td>
                            <td>
                                {{$company_info->companyInfo->contact_number}} &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Email Address</b>
                            </td>
                            <td>
                                {{$company_info->companyInfo->email}} &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <b>Address</b>
                            </td>
                            <td>
                                {{$company_info->companyInfo->address}} &nbsp;
                            </td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>
