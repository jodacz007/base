var Users = function () {

    var initUsers = function () {
        var oTable = GENERAL.initDataTable('user_list_table',
            {
                "processing": true,
                "serverSide": true,
                "ajax": BASE_URL+'/setting/users/data',
                "deferRender": true,
                "columns": [
                    { "data": 'first_name' },
                    { "data": 'middle_name' },
                    { "data": 'last_name' },
                    { "data": 'company_name' },
                    { "data": 'group_name' },
                    { "data": 'status', "render": function(data, type, full){
                        return '<span id="status_field_'+full.user_id+'" class="label label-sm '+(data=='Active'?'label-success':'label-danger')+'">'+data+'</span>';
                    }},
                    { "render": actionLinks, "data": 'user_id', },
                ],
            }
        );
        oTable.delegate('.reset_pwd','click',function(){
            var data_id = $(this).attr('data_id');
            GENERAL.request(
                "/setting/users/update/"+data_id,
                {
                    'actionType':'resetPwd'
                },
                'POST',
                true,
                function(data){
                    bootbox.alert(data.msg);
                },
                function(){
                    bootbox.alert("Unexpected error encounter, Please try again later!");
                }
            );
        });
        oTable.delegate('.users_change_status','click',function(){
            var data_id = $(this).attr('data_id');

            GENERAL.request(
                "/setting/users/update/"+data_id,
                {
                    'actionType':'status'
                },
                'POST',
                true,
                function(data){
                    if (data.status){
                        var statusHndle = $('#status_field_'+data_id);
                        var statusMsg = statusHndle.html();
                        statusHndle.html(statusMsg=='Active'?'Inactive':'Active');
                        statusHndle.removeClass(statusMsg=='Active'?'label-success':'label-danger');
                        statusHndle.addClass(statusMsg=='Active'?'label-danger':'label-success');
                    }
                    else{
                        bootbox.alert('Unable to Update Status, Please try again later!');
                    }
                },
                function(){
                    bootbox.alert("Unexpected error encounter, Please try again later!");
                }
            );
        });
        oTable.delegate('.remove_user','click',function(){
            var data_id = $(this).attr('data_id');
            bootbox.confirm("Are you sure to permanently remove this user?", function(result) {
                if (result) {
                    GENERAL.request(
                        "/setting/users/delete/"+data_id,
                        {},
                        'POST',
                        true,
                        function(data){
                            if (data.status){
                                var row = $('.row_'+data_id).closest('tr');
                                oTable.fnDeleteRow(row[0]);
                            }
                            else{
                                bootbox.alert("Unable to remove user,Please try again!");
                            }
                        },
                        function(){
                            bootbox.alert("Unexpected error encounter, Please try again later!");
                        }
                    );
                }
            });
        });
    }

    function actionLinks(data, type, full) {
        var actionValue = $('#action_str').html();
        actionValue = actionValue.replace(/##NUM##/g, data);
        return actionValue;
    }
    
    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }
            initUsers();
        }
    };

}();