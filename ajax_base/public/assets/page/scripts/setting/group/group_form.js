var GroupForm = function () {
    
    var modulelist = [];

    var initForm = function () {
        modulelist = [];
        GENERAL.initFormValidate('group_form',
            {
                group_name: {
                    required: true,
                    remote:  {
                        url:BASE_URL+"/setting/group/add",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{
                            'actionType':'checkGroupName',
                            'company_id':function(){
                                return $('#company_id').val();
                            },
                        },
                        type: "post",
                    }
                },
            },
            {
                group_name: {
                    remote: 'Group already exists!',
                }
            },
            function(){
                var data = getModules();
                
                if (data.length == 0) {
                    bootbox.alert("Please Select Module and Pages");
                    return false;
                }

                GENERAL.request(
                    "/setting/group/add",
                    {
                        'actionType':'addGroup',
                        'group_name': $('#group_name').val(),
                        'company_id': $('#company_id').val(),
                        'data':JSON.stringify(data),
                    },
                    'POST',
                    true,
                    function(data){
                        bootbox.alert(data.msg);
                    },
                    function(){
                        bootbox.alert("Unexpected error encounter, Please try again later!");
                    },
                    function(){
                        GENERAL.requestPage(BASE_URL+'/setting/group/add',{});
                    }
                );
            }

        );
    }
    
    var initUpdateForm = function () {
        modulelist = [];
        GENERAL.initFormValidate('group_form',
            {
                group_name: {
                    required: true,
                },
            },
            {},
            function(){
                var data = getModules();
                
                if (data.length == 0) {
                    bootbox.alert("Please Select Module and Pages");
                    return false;
                }
                var group_id = $('#group_id').val();

                GENERAL.request(
                    "/setting/group/update/"+group_id,
                    {
                        'actionType':'updateGroup',
                        'data':JSON.stringify(data),
                    },
                    'POST',
                    true,
                    function(data){
                        bootbox.alert(data.msg);
                    },
                    function(){
                        bootbox.alert("Unexpected error encounter, Please try again later!");
                    },
                    function(){
                        GENERAL.requestPage(BASE_URL+'/setting/group/update/'+group_id,{});
                    }
                );
            }
        );
    }
    
    var initButton = function(){
        $('#company_id').change(function(){
            $('#group_name').val("");
        });
        $('.select2').select2();
        
        $('input:checkbox.modules_btn').each(function () {
            if (this.checked) {
                modulelist.push($(this).val());
                $('#module_page_container_'+$(this).val()).show();
            }
        });

        $(".modules_btn").change(function() {
            if(this.checked) {
                modulelist.push($(this).val());
                $('#module_page_container_'+$(this).val()).show();
                $('.page_module_'+$(this).val()).prop('checked', false);
                $('.module_page_action_'+$(this).val()).prop('checked', false).attr("disabled", true); 
            }
            else{
                $('#module_page_container_'+$(this).val()).hide();
            }
        });

        $(".module_pages").change(function() {
            if(this.checked) {
                $('.module_page_'+$(this).val()).prop('checked', false).removeAttr("disabled");
            }
            else{
                $('.module_page_'+$(this).val()).prop('checked', false).attr("disabled", true);
            }
        });
    }
    
    function getModules() {
        var data = [];
        for (var i=0;i<modulelist.length;i++) {
            $('#group_form input:checkbox.page_module_'+modulelist[i]).each(function () {
                if (this.checked) {
                    var page_id = $(this).val();
                    var action = 1;
                    $('#group_form input:checkbox.module_page_'+page_id).each(function () {
                        if (this.checked) {
                            action += parseInt($(this).val());
                        }
                    });
                    var info = {module_id:modulelist[i],page_id:page_id,action_value:action};
                    data.push(info);
                }
            });
        }
        return data;
    }
    
    return {
        //main function to initiate the module
        init: function () {
            initForm();
            initButton();
        },
        initUpdate: function() {
            initUpdateForm();
            initButton();
        }
    };

}();