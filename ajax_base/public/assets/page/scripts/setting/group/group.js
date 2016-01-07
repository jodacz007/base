var Group = function () {

    var initGroups = function () {
        var oTable = GENERAL.initDataTable('group_list_table',
            {
                "processing": true,
                "serverSide": true,
                "ajax": BASE_URL+'/setting/group/data',
                "deferRender": true,
                "columns": [
                    { "data": 'company_name' },
                    { "data": 'group_name' },
                    { "data": 'modules' },
                    { "data": 'pages' },
                    { "render": actionLinks, "data": 'group_id', },
                ],
            }
        );
        
        $('.remove_group').click(function(event){
            var data_id = $(this).attr('data_id');
            bootbox.confirm("Are you sure to permanently remove this Group?", function(result) {
                if (result) {
                    GENERAL.request(
                        "/setting/group/delete/"+data_id,
                        {},
                        'POST',
                        true,
                        function(data){
                            if (data.status){
                                var row = $('.row_'+data_id).closest('tr');
                                oTable.fnDeleteRow(row[0]);
                            }
                            else{
                                bootbox.alert("Unable to remove Group,Please try again!");
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
            initGroups();
        }
    };

}();