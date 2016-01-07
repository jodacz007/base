var UsersForm = function () {

    var initForm = function () {
        GENERAL.initFormValidate('users_form',
            {
                username: {
                    required: true,
                    remote:  { 
                        url:BASE_URL+"/setting/users/add" , 
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{
                            'actionType':'checkUsername',
                        } ,
                        type: "post",
                    }
                },
                status: {
                    required: true,
                },
                group_id: {
                    required: true,
                },
                contact_no: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                },
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
            },
            {
                username: {
                    required: 'Enter a valid username.',
                    remote: 'Username already use!',
                }
            },
            function(){
                GENERAL.request(
                    "/setting/users/add",
                    {
                        'actionType':'addUser',
                        'user':JSON.stringify(new User()),
                        'userinfo':JSON.stringify(new UserInfo()),
                    },
                    'POST',
                    true,
                    function(data){
                        if (data.status) {
                            $('#username').val('');
                            $('#first_name').val('');
                            $('#middle_name').val('');
                            $('#last_name').val('');
                            $('#email').val('');
                            $('#contact_no').val('');
                        }
                        bootbox.alert(data.msg);
                    },
                    function(){
                        bootbox.alert("Unexpected error encounter, Please try again later!");
                    }
                );
            }

        );
    }
    
    var initUpdateForm = function () {
        GENERAL.initFormValidate('users_form',
            {
                status: {
                    required: true,
                },
                group_id: {
                    required: true,
                },
                contact_no: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                },
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
            },
            {},
            function(){
                var user_id = $('#user_id').val();
                GENERAL.request(
                    "/setting/users/update/"+user_id,
                    {
                        'actionType':'updateUser',
                        'user':JSON.stringify(new User()),
                        'userinfo':JSON.stringify(new UserInfo()),
                    },
                    'POST',
                    true,
                    function(data){
                        if (data.status) {
                            bootbox.alert(data.msg);
                        }
                        else{
                            // $('#users_form').trigger("reset");
                            bootbox.alert(data.msg);
                        }
                    },
                    function(){
                        bootbox.alert("Unexpected error encounter, Please try again later!");
                    }
                );
            }
        );
    }

    function initFields(type){
        $('.select2').select2();
        $('#company_id').change(function(){
            checkGroup(type);
        });
        GENERAL.addOnPageDoneEvent(function(){
            checkGroup(type);
        });
    }

    function checkGroup(type){
        var company_id = $('#company_id').val();
        var url = 'add';
        var group_id = 0;
        if (type == 'update'){
            var user_id = $('#user_id').val();
            url = 'update/'+user_id;
            group_id = $('#user_group_id').val();
        }
        GENERAL.request(
            "/setting/users/"+url,
            {
                'actionType':'groupList',
                'company_id': function(){
                    return $('#company_id').val();
                },
            },
            'POST',
            true,
            function(data){
                GENERAL.initSelect2('group_id',data,group_id);
            },
            function(){
                bootbox.alert("Unexpected error encounter, Please try again later!");
            }
        );
    }
    
    function User() {
        this.username;
        this.group_id;
        this.status;
        
        load = function(self){
            self.username = $('#username').val();
            self.group_id = $('#group_id').val();
            self.status = $('#status').val();
        }
        load(this);
    }
    
    function UserInfo() {
        this.first_name;
        this.middle_name;
        this.last_name;
        this.email;
        this.contact_no;
        
        load = function(self){
            self.first_name = $('#first_name').val();
            self.middle_name = $('#middle_name').val();
            self.last_name = $('#last_name').val();
            self.email = $('#email').val();
            self.contact_no = $('#contact_no').val();
        }
        load(this);
    }
    
    return {
        //main function to initiate the module
        init: function () {
            initForm();
            initFields('add');
        },
        initUpdate: function() {
            initUpdateForm();
            initFields('update');
        }
    };

}();