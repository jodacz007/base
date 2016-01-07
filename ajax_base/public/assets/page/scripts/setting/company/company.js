var Company = function () {

    var initPage = function () {
        var oTable = GENERAL.initDataTable('company_list_table',
            {
                "processing": true,
                "serverSide": true,
                "ajax": BASE_URL+'/setting/company/data',
                "deferRender": true,
                "columns": [
                    { "data": 'company_name' },
                    { "data": 'owner_first_name' },
                    { "data": 'owner_middle_name' },
                    { "data": 'owner_last_name' },
                    // { "data": 'address' },
                    { "data": 'email' },
                    { "data": 'contact_number' },
                    { "data": 'company_status' },
                    { "render": actionLinks, "data": 'company_id', },
                ],
            }
        );
        oTable.delegate('.remove_btn','click',function(){
            var data_id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            bootbox.confirm("Are you sure to change company status?", function(result) {
                if (result) {
                    if (status == 'Active'){
                        bootbox.prompt("Inactive Reason?", function(result) {
                            if (result !== null && result != '') {
                                GENERAL.request(
                                    "/setting/company/delete/"+data_id,
                                    {
                                        'reason': result,
                                    },
                                    'POST',
                                    true,
                                    function(data){
                                        if (data.status){
                                            oTable.fnDraw();
                                        }
                                        else{
                                            bootbox.alert(data.msg);
                                        }
                                    },
                                    function(){
                                        bootbox.alert("Unexpected error encounter, Please try again later!");
                                    }
                                );
                            }
                        });
                    }
                    else{
                        GENERAL.request(
                            "/setting/company/delete/"+data_id,
                            {
                                'reason': 'NONE',
                            },
                            'POST',
                            true,
                            function(data){
                                if (data.status){
                                    oTable.fnDraw();
                                }
                                else{
                                    bootbox.alert(data.msg);
                                }
                            },
                            function(){
                                bootbox.alert("Unexpected error encounter, Please try again later!");
                            }
                        );
                    }
                }
            });
        });
    }

    function actionLinks(data, type, full) {
        var actionValue = $('#action_str').html();
        actionValue = actionValue.replace(/##NUM##/g, data);
        actionValue = actionValue.replace(/##STATUS##/g, full.company_status);
        return actionValue;
    }
    
    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
            initPage();
        }
    };

}();