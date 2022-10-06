function showModal(id,modal_id,url,token,validate){
    $("html, body").animate({
        scrollTop: 0
    }, 600);
    $.ajax({
        url : url,
        data : {'pk_i_id':id,'_token':token},
        type: "POST",
        beforeSend(){
            block_page('Please Wait...');
        },
        success:function(data) {
            if(data.success){
				$('#'+modal_id).Modal.init($('#'+modal_id), {dismissible: false,focus: false, backdrop: 'static', keyboard: false} );
				// $('#'+modal_id).html(data.page).modal('show', {backdrop: 'static', keyboard: false, dismissible: false});
                if (typeof validateForm === "function") {
                    validateForm();
                }
            }else{
                showAlertMessage('alert-danger', 'Fatal error !', 'An unknown error occured !');
            }
            un_block_page();
        },
        error:function(data) {
            un_block_page();
            console.log(data);
        } ,
        statusCode: {
            500: function(data) {
                un_block_page();
                console.log(data);
            }
        }
    });
}

function showDeleteModal(id, type = null) {
    $("html, body").animate({
        scrollTop: 0
    }, 600);
    $('#app_delete_modal #app_delete_input').val(id);
    $('#app_delete_modal #app_delete_input_type').val(type);
    $('#app_delete_modal').modal('show', {backdrop: 'static', keyboard: false});
}

function RefreshTable(tableId, urlData) {
	$(tableId).DataTable().ajax.reload();
}

function block_page(message){
    KTApp.blockPage({
        overlayColor: '#000000',
        type: 'v2',
        state: 'success',
        message: message
    });
}

function un_block_page(){
    KTApp.unblockPage();
}

function block_modal(IDModal,message){
    KTApp.block("#"+IDModal+" .modal-content",{
        message: message
    });
}

function block(target, message) {
    KTApp.block(target, {
        message: message
    });
}

function un_block(target) {
    KTApp.unblock(target);
}

function un_block_modal(IDModal){
    KTApp.unblock("#"+IDModal+" .modal-content");
}

function blockModal(IDModal , message ){
    KTApp.block("#"+IDModal+" .modal-content",{
    message: message
}),setTimeout(function(){
        KTApp.unblock("#"+IDModal+" .modal-content")},3500);
}

function validate_form(form, rules) {
    form.validate({
        // define validation rules
        rules: rules,
        //display error alert on form submit
        invalidHandler: function(event, validator) {
            // var alert = $('#m_form_1_msg');
            // alert.removeClass('m--hide').show();
            mUtil.scrollTop();
        },

        submitHandler: function (form) {
            //form[0].submit(); // submit the form
        }
    });

}

/**
 * Initializes bootstrap tooltip
 */
function initTooltip(el) {
    var skin = el.data('skin') ? 'kt-tooltip--skin-' + el.data('skin') : '';
    var width = el.data('width') === 'auto' ? 'kt-tooltop--auto-width' : '';
    var triggerValue = el.data('trigger') ? el.data('trigger') : 'hover';
    var placement = el.data('placement') ? el.data('placement') : 'left';

    el.tooltip({
        trigger: triggerValue,
        template: '<div class="kt-tooltip ' + skin + ' ' + width + ' tooltip" role="tooltip">\
                                <div class="arrow"></div>\
                                <div class="tooltip-inner"></div>\
                            </div>'
    });
};

/**
 * Initializes bootstrap tooltips
 */
function initTooltips() {
    // init bootstrap tooltips
    $('[data-toggle="kt-tooltip"]').each(function() {
        initTooltip($(this));
    });
}

function ch_st(id, url, token) {
    $.ajax({
        url: url,
        data: {pk_i_id: id, _token: token},
        type: "POST",
        success: function (data, textStatus, jqXHR) {
            if(data.success){
                showAlertMessage('success', data.title, data.message);
            }
            else{
                showAlertMessage('danger', data.title, data.message+'lll', 5000);
            }
            RefreshTable(Table, data_table_url);
        },
        error: function (data, textStatus, jqXHR) {
            console.log(data);
        },
        statusCode: {
            500: function (data) {
                console.log(data);
            }
        }
    });
}
