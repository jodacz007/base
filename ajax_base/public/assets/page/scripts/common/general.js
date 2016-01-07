GENERAL = function(){

    var lock_key = false;
    var pageDoneEventList = [];
    var oTable = null;

    //reload Page
    function getPage(url,data){
        if (lock_key){
            return;
        }
        lock_key = true;
        var request = $.ajax({
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            data: data,
            // dataType: 'json',
            beforeSend : function() {
                $('#loading_indicator').show();
            },
            complete: function(){
                $('#loading_indicator').hide();
            },
        })
        .done(function(data){
            $('#page-content').empty();
            $('#page-content').html(data);
            lock_key = false;
            if (pageDoneEventList.length > 0){
                entry = pageDoneEventList.pop();
                entry();
            }
        })
        .error(function(){
            bootbox.alert("Unexpected error encounter, Please try again later!");
            lock_key = false;
        });;
    }

    //get Request
    function getRequest(url,data,type,syncStatus,donefunction,errorfunction,afterDoneFunction){
        if (lock_key){
            return;
        }
        lock_key = true;
        var request = $.ajax({
            type: type,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function(){
                $('#loading_indicator').show();
            },
            complete: function(){
                $('#loading_indicator').hide();
            },
            url: BASE_URL+url,
            data: data,
            async: syncStatus,
            dataType: 'json'
        })
        .done(function(data){
            donefunction(data);
            lock_key = false;
            if (afterDoneFunction != null)
                afterDoneFunction();
        })
        .error(function(){
            errorfunction();
            lock_key = false;
        });
    }

    function initPage(){
        $('body').delegate('.page_switcher','click',function(){
            var url = $(this).attr('href');
            if (url != undefined && url != null && url != ''){
                var data = $(this).attr('data')!=undefined?eval($(this).attr('data')):{};
                getPage(url,data);
            }
            return false;
        });
        // getPage(BASE_URL,{});
    }

    function initTable(table_name,config){
        var table = $('#'+table_name);
        oTable = table.dataTable(config);
        var tableWrapper = $('#'+table_name+'_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
        tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown
        return oTable;
    }

    function reloadTable(){
        if (oTable != null){
            oTable.fnDraw();
        }
    }

    function initForm(formName,rulesList,messageList,submitHandlerFunc){
        var form = $('#'+formName);
        var error = $('.alert-danger', form);
        var success = $('.alert-success', form);
        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            messages: messageList,
            rules: rulesList,

            invalidHandler: function (event, validator) { //display error alert on form submit              
                success.hide();
                error.show();
                Metronic.scrollTo(error, -200);
            },
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },
            success: function (label) {
                label
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },
            submitHandler: function (form) {
                submitHandlerFunc();
                return false;
            }
        });
        return form;
    }

    function initSelect(name,data,selected){
        $('#'+name).html("");
        $('#'+name).append('<option value=""></option>');
        if (data.length > 0){
            data.forEach(function(entry){
                if (selected == entry.id){
                    $('#'+name).append('<option selected="selected" value="'+entry.id+'">'+entry.text+'</option>');
                }
                else{
                    $('#'+name).append('<option value="'+entry.id+'">'+entry.text+'</option>');
                }
            });
        }
        $('#'+name).select2();
    }

    return {
        init: function(){
            initPage();
        },
        request: function (url,data,type,syncStatus,donefunction,errorfunction) {
            getRequest(url,data,type,syncStatus,donefunction,errorfunction,null);
        },
        request: function (url,data,type,syncStatus,donefunction,errorfunction,afterDoneFunction) {
            getRequest(url,data,type,syncStatus,donefunction,errorfunction,afterDoneFunction);
        },
        requestPage: function (url,data){
            getPage(url,data);
        },
        initDataTable: function (tableName,configuration){
            return initTable(tableName,configuration);
        },
        refreshTable: function(){
            reloadTable();
        },
        initFormValidate: function (formName,rules,messages,submitFunction){
            return initForm(formName,rules,messages,submitFunction);
        },
        initSelect2: function (name,data,selected){
            initSelect(name,data,selected);
        },
        addOnPageDoneEvent: function(event){
            pageDoneEventList.push(event);
        },
        getState: function(){
            return lock_key;
        },
    };
}();