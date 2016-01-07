var CompanyForm = function () {

    var initPage = function (type) {
        var url = 'add';
        if (type == 'update'){
            var company_id = $('#company_id').val();
            url = 'update/'+company_id;
        }
        var form_name = 'company_form';
        GENERAL.initFormValidate(form_name,
            {
                company_name:{
                    required: true,
                    remote:  {
                        url:BASE_URL+"/setting/company/"+url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data:{
                            'actionType':'checkCompany',
                        },
                        type: "post",
                    }
                },
                address:{
                    required: true,
                },
                owner_first_name:{
                    required: true,
                },
                owner_last_name:{
                    required: true,
                },
                email:{
                    required: true,
                    email: true,
                },
                contact_number:{
                    required: true,
                },
            },
            {
                company_name:{
                    remote: 'Company Name is already in use!',
                }
            },
            function(){
                GENERAL.request(
                    "/setting/company/"+url,
                    {
                        'actionType': type+'Company',
                        'company':JSON.stringify(new Company()),
                        'companyInfo':JSON.stringify(new CompanyInformation()),
                    },
                    'POST',
                    true,
                    function(data){
                        $('#form_ajax').modal('hide');
                        GENERAL.refreshTable();
                        bootbox.alert(data.msg);
                    },
                    function(){
                        bootbox.alert("Unexpected error encounter, Please try again later!");
                    }
                );
            }
        );
    }

    function Company(){
        this.company_name;

        load = function(self){
            self.company_name = $('#company_name').val();
        }
        load(this);
    }

    function CompanyInformation() {
        this.owner_first_name;
        this.owner_middle_name;
        this.owner_last_name;
        this.address;
        this.email;
        this.contact_number;
        
        load = function(self){
            self.owner_first_name = $('#owner_first_name').val();
            self.owner_middle_name = $('#owner_middle_name').val();
            self.owner_last_name = $('#owner_last_name').val();
            self.address = $('#address').val();
            self.email = $('#email').val();
            self.contact_number = $('#contact_number').val();
        }
        load(this);
    }
    
    return {
        //main function to initiate the module
        init: function (type) {
            initPage(type);
        }
    };

}();