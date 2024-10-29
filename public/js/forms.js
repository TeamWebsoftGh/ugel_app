/**
 *
 *  ////////////////////////////////////////////////////////////////////////////
 *
 *  NOTE : PLEASE DO NOT MESS WITH THIS PIECE OF CODE. IT CONTROLS ANY FORM ACTION
 *              FROM SAVING RECORDS TO DELETING THEM.
 *
 *  ///////////////////////////////////////////////////////////////////////////
 *
 *
 *  When ever the page first loads, we want the following actions to
 *  happen to the controls.
 *
 *  NEW BUTTON     :=>  ON
 *  EDIT BUTTON    :=>  OFF
 *  CANCEL BUTTON  :=>  OFF
 *  DELETE BUTTON  :=>  OFF
 *  SAVE BUTTON    :=>  OFF
 *
 *  INPUT-BOXES    :=>  OFF
 *  TEXT-AREAS     :=>  OFF
 *  SELECT-OPTIONS :=>  OFF
 *  RADIO-BUTTONS  :=>  OFF
 *  CHECK-BOXES    :=>  OFF
 *
 *  We also want to activate the text-box on the search form.
 */

    // let slash = baseUrl.endsWith('/') ? '' : '/' ;
    // let appUrl = basePath + baseUrl + slash ;

let deletedItems = [];
let ctoken = $('meta[name="csrf-token"]').attr('content');


let leaders_ids ; // Keeps list of selected leaders.

$(function(){
    'use strict';
    $('form input, form textarea, form select, .delete_btn, .save_btn, .edit_btn, .cancel_btn').prop('disabled', true);
    $('form input[name="_token"], form input[name="_method"], input[type="search"], form .ignore,select[name*="DataTables_Table_"]').prop('disabled',false);
    $('#search_form input, input#_id').prop('disabled', false) ;
    $("input[type='search'], select[name*='DataTables_Table_']").prop('disabled', false);

    // $('form input, form textarea, form select').append('<span class="input-note text-danger" id="error-'+$('form input, form textarea, form select').attr('name')+'"> </span>');

    setTimeout(()=>{$('tr.item-details:first').trigger('click');},1500);
});

$(document).on('click', '.new_btn, .edit_btn, .save_btn', function(){hideAlerts();});

// Disable autofill option on input fields
$('input').attr('autocomplete', 'off');

function EditItem(url, target)
{
    $.ajax({
        type: "GET",
        url: url,
        timeout:60000,
        datatype: "json",
        cache: false,
        error: function(XMLHttpRequest, textStatus, errorThrown){
            HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
        },
        success: function (data) {
            $(target).html("");
            console.log(target)
            $(target).html(data);
            $('input, textarea, select, .delete_btn, .cancel_btn, .save_btn').prop('disabled', true);
            $('.edit_btn, .new_btn, form#search_form input,form input[class="show-inactive"]').prop('disabled', false);
            $('form input[name="_token"], form input[name="_method"], input[type="search"], form .ignore,select[name*="DataTables_Table_"]').prop('disabled',false);
            $(target).find('.dropify').each(function(){$(this).dropify()});
            $(target).find('.summernote').each(function(){$(this).summernote({height: 150})});
            $(target).find('.summernote').each(function(){$(this).summernote('disable')});
            $('select').selectpicker('refresh');
            // $('.select2').select2();
        },
    });
}

function DeleteItem(name, url)
{
    bootbox.confirm("<h4>DELETE</h4><hr /><div>This is an irrevisable action that will delete <b>"+name.toUpperCase()+"</b>. Are you sure you want to <b><span style='color:red'>delete </span>"+ name.toUpperCase() + "</b></div>", function (result) {
        if (result === true) {
            $.ajax({
                type: "DELETE",
                url: url,
                data: ({_token:ctoken}),
                timeout:60000,
                datatype: "json",
                cache: false,
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                },
                success: function (data) {
                    bootbox.alert(DetermineIconFromResult(data) + " " + data.Message, function () {
                        if(data.Result === "SUCCESS")
                        {
                            window.location.reload();
                        }
                    });
                },
            });
        }
        else {
        }
    });
}

function DeleteItemDT(name, url)
{
    bootbox.confirm("<h4>DELETE</h4><hr /><div>This is an irrevisable action that will delete <b>"+name.toUpperCase()+"</b>. Are you sure you want to <b><span style='color:red'>delete </span>"+ name.toUpperCase() + "</b></div>", function (result) {
        if (result === true) {
            $.ajax({
                type: "DELETE",
                url: url,
                data: ({_token:ctoken}),
                timeout:60000,
                datatype: "json",
                cache: false,
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                },
                success: function (data) {
                    bootbox.alert(DetermineIconFromResult(data) + " " + data.Message, function () {
                        if(data.Result === "SUCCESS")
                        {
                            window.location.reload();
                        }
                    });
                },
            });
        }
        else {
        }
    });
}

function SaveItem()
{
    let form=$(this).closest('form');
    let data = new FormData(form[0]);
    let url = form.attr("action");
    let id = form.attr("id");
    let method = form.attr("method");

    $.ajax({
        type: method,
        url: url,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        error: function(XMLHttpRequest, textStatus, errorThrown){
            for (control in XMLHttpRequest.responseJSON.errors) {
                $('#error-' + control).html(XMLHttpRequest.responseJSON.errors[control]);
            }
            HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
        },
        success: function (data) {
            bootbox.alert(DetermineIconFromResult(data) + " " + data.Message, function () {
                if(data.Result === "success")
                {
                    $('#' + id + '-table').DataTable().ajax.reload();
                }
            });
        },
    });
}

function ChangeStatus(name, status, url)
{
    let ActiveOrInactive="";
    let selIcon = "";

    if(status == 1){
        ActiveOrInactive = "disable"; selIcon="<i class='fa fa-ban'></i>";
    }
    else{
        ActiveOrInactive = "enable"; selIcon="<i class='fa fa-caret-square-o-right'></i>";
    }

    if(url)
    {
        bootbox.confirm("<h4>"+ActiveOrInactive.toUpperCase()+"</h4><hr/><div> This action will "+ ActiveOrInactive + " " + name.toUpperCase() + "</div> Are you sure you want to <b><span style='color:blue'>"+ ActiveOrInactive +"</span> " + name.toUpperCase() + "</b></div>", function (result) {
            if (result === true) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: ({_token:ctoken}),
                    timeout:60000,
                    datatype: "json",
                    cache: false,
                    error: function(XMLHttpRequest, textStatus, errorThrown){
                        HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                    },
                    success: function (data) {
                        bootbox.alert(DetermineIconFromResult(data) + " " + data.Message, function () {
                            window.location.reload();
                        });
                    },
                });
            }
            else {

            }
        });
    }else{
        alert("No item selected.")
    }
}

function ResetPassword(name, url){
    bootbox.confirm("<h4>RESET PASSWORD</h4><hr /><div>Are you sure you want to reset password for <b>"+name.toUpperCase()+"</b></div>", function (result) {
        if (result === true) {
            $.ajax({
                type: "POST",
                url: url,
                data: ({_token:ctoken}),
                timeout:60000,
                datatype: "json",
                cache: false,
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                },
                success: function (data) {
                    bootbox.alert(DetermineIconFromResult(data) + " " + data.Message, function () {
                        window.location.reload();
                    });
                },
            });
        }
        else {
        }
    });
}

function ConfirmAccount(name, url)
{
    bootbox.confirm("<h4>Confirm Account</h4><hr/><div> This action will confirm account of " + name.toUpperCase() + "</div> Are you sure you want to <b><span style='color:blue'>confirm</span> " + name.toUpperCase() + "</b></div>", function (result) {
        if (result === true) {
            $.ajax({
                type: "POST",
                url: url,
                data: ({_token:ctoken}),
                timeout:60000,
                datatype: "json",
                cache: false,
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                },
                success: function (data) {
                    bootbox.alert(DetermineIconFromResult(data) + " " + data.Message, function () {
                        window.location.reload();
                    });
                },
            });
        }
        else {

        }
    });

}
/**
 * When the items in the list (.item-details) are clicked, we want the following actions
 * to happen to the controls.
 *
 * The details of the selected item will be loaded into the form-fields.
 *
 *  NEW BUTTON     :=>  ON
 *  EDIT BUTTON    :=>  ON
 *  CANCEL BUTTON  :=>  OFF
 *  DELETE BUTTON  :=>  OFF
 *  SAVE BUTTON    :=>  OFF
 *
 *  INPUT-BOXES    :=>  OFF/ON
 *  TEXT-AREAS     :=>  OFF/ON
 *  SELECT-OPTIONS :=>  OFF/ON
 *  RADIO-BUTTONS  :=>  OFF/ON
 *  CHECK-BOXES    :=>  OFF/ON
 */

$(function(){
    $(document).on('click', '.item-details, .partial-details, .modal-item-details', function(e){
        $('.item-details.selected').each(function(){$(this).removeClass('selected');});
        $('.modal-item-details.selected').each(function(){$(this).removeClass('selected');});
        $('.partial-details.selected').each(function(){$(this).removeClass('selected');});
        $(this).addClass('selected');
    });
});

$(function(){
    'use strict';

    $(document).on('click', '.item-details', function(){
        let id = $(this).attr('id') ;
        let url = appUrl+'detail/'+id;
        let target = $(this).attr('data-target') ;
        EditItem(url, target);

        // $.get('/set-session-id',{___item_id___:id},(d)=>{});
        $(document).on('click', '.new_btn', function(){
            let $form = $(this).closest('form') ;
            $form.find('.update_request').each(function(){$(this).remove()});
            let partialIds = ['#__err2__'+getPartialIds()];
            partialIds.forEach((_id_) => { $form.find(_id_).html('')});
            $form.find('#__staff-id__').prop('readonly', false);
            $form.find('input[class="show-inactive"]').prop('disabled',false);
            $form.find('.update_request').val('false');
            $form.find('input, textarea, select').each(function(){
                let name = $(this).attr('name');
                let type = $(this).attr('type') ;
                let status = $(this).attr('data-ignore');
                if( name === 'logo2' || name === '_token' || name === '_method' || status != null){
                }else{$(this).val('');}
                if(type === 'checkbox' || type === 'radio'){$(this).attr('checked', false);}
            });
            // $form.find('.select2').each(function(){$(this).select2()});
            $('select').selectpicker('refresh');
            $form.find('.dropify').each(function(){$(this).dropify()});
            $form.find('.summernote').each(function(){$(this).summernote({height: 150})});
            $form.find('.summernote').each(function(){$(this).summernote('enable')});
            $form.find('.summernote').each(function(){$(this).summernote('reset')});
        });
        /**
         *  SAVE     :=>  ON
         *  CANCEL   :=>  ON
         *  EDIT     :=>  OFF
         *  DELETE   :=>  ON
         */
        $(document).on('click', '.edit_btn', function(){
            let $form = $(this).closest('form');
            $('tr.item-details').removeClass('item-details').addClass('item-details2');
            $form.find('#__staff-id__').prop('readonly', true);
            $form.find('.update_request').each(function(){$(this).remove()});
            $form.append('<input type="hidden" name="update_request" value="true" class="update_request"/>');
            $form.find('.delete_btn, .save_btn, .cancel_btn').each(function(){$(this).prop('disabled', false)});
            $form.find('.new_btn').hide();
            $form.find('.cancel_btn').show();
            $form.find('input, textarea, select').each(function(){$(this).prop('disabled', false)});
            // $form.find('.select').each(function(){$(this).select2()});
            $('select').selectpicker('refresh');
            $form.find('.summernote').each(function(){$(this).summernote({height: 150})});
            $form.find('.summernote').each(function(){$(this).summernote('enable')});
        });
        $(document).on('click','.cancel_btn',function(){
            $('tr.item-details2').removeClass('item-details2').addClass('item-details');
            let $form = $(this).closest('form') ;
            $('#search_form input, input[type="search"], input[class="show-inactive"]').prop('disabled', false);
            $form.find('.update_request').each(function(){$(this).remove()});
            $form.find('.summernote').each(function(){$(this).summernote('disable')});
            $(this).hide();
            $form.find('.new_btn').show();
        });
        //  $("form#calc input, select#selectnav1, form input[name=_token]").prop('disabled', false) ;
    });
    $(document).on('click', '.partial-details',function(){$('.edit_btn').prop('disabled',false)});
});

/**
 *  When the user clicks on the 'Cancel' button
 */
$(document).on('click', '.cancel_btn', function(e){
    let $form = $(this).closest('form') ;
    $form.find('.update_request').each(function(){$(this).remove()});
    $form.find('#__member-id__').prop('readonly', false);
    $form.find('input, textarea, select, .delete_btn, .save_btn, .cancel_btn').each(function(){$(this).prop('disabled',true)});
    $('select').selectpicker('disabled');
    $form.find('.edit_btn').prop('disabled', false);
    $('#search_form input, input[type="search"], input[class="show-inactive"]').prop('disabled', false);
    $form.find('.new_btn').show();
    $('tr.item-details2').removeClass('item-details2').addClass('item-details');
    $form.find('.summernote').each(function(){$(this).summernote('disable')});
    $(this).hide();
});


/**
 *  When the page first loads and the 'New Button' is clicked, we want the following actions
 *  on the controls.
 *
 *
 *  NEW BUTTON     :=>  HIDE
 *  EDIT BUTTON    :=>  OFF
 *  CANCEL BUTTON  :=>  SHOW
 *  DELETE BUTTON  :=>  OFF
 *  SAVE BUTTON    :=>  ON
 *
 *  Enable the Text-Areas, Check-boxes, Select-Options, Input-Boxes, etc...
 *
 */
$(document).on('click', '.new_btn', function(e){
    let $form = $(this).closest('form');

    $form.find('.update_request').each(function(){$(this).remove()});
    $form.find('input, textarea, select, .cancel_btn, .save_btn').each(function(){$(this).prop('disabled',false)});
    $form.find('input, textarea, select').each(function(){$(this).prop('readonly',false)});
    $form.find('.delete_btn, .edit_btn').each(function(){$(this).prop('disabled',true)});
    $form.find('#__staff-id__, input.show-inactive').prop('readonly', false);
    $form.find('input.show-inactive').prop('disabled',false);
    let partialIds = ['#__err2__'+getPartialIds()];
    partialIds.forEach((_id_) => { $form.find(_id_).html('')});
    $form.find('.cancel_btn').show();

    $('tr.item-details').removeClass('item-details').addClass('item-details2');
    //  $("#target").attr("src", "/default_company.png");

    $form.find('input, textarea, select').each(function(){
        let name = $(this).attr('name');
        let type = $(this).attr('type') ;
        let status = $(this).attr('data-ignore');
        if( name === 'logo2' || name === '_token' || name === '_method' || status != null){
        }else{$(this).val('');}
        if(type === 'checkbox' || type === 'radio'){$(this).attr('checked', false);}
    });
    $form.find('.dropify').each(function(){$(this).removeAttr('data-default-file'); $('.dropify-preview img').attr("src", '')});


    $('#search_form input, input[type="search"]').prop('disabled', false) ;

    $(this).hide();
    $(document).on('click', '.item-details', function(event){

        $('input, textarea, select, .delete_btn, .save_btn').prop('disabled', true);
        let id = $(this).attr('data-id') ;

        $('.cancel_btn').hide();
        $('.new_btn').show();

        $('.edit_btn, #search_form input, input[type="search"]').prop('disabled', false);
        $('.update_request').val('false');
        // loadForm(id, logo) ;
    });

    $(document).on('click', '.cancel_btn',function(){
        let $form = $(this).closest('form');
        $form.find('.update_request').each(function(){$(this).remove()});
        $form.find('input.show-inactive').prop('disabled', false);
        $form.find('.edit_btn').prop('disabled', true);
        $('tr.item-details2').removeClass('item-details2').addClass('item-details');
        setTimeout(()=>{$('tr.item-details:first').trigger('click');},3);
        $('#FormModal').modal('hide');
        $('input[type="search"]').prop('disabled', false);
    })
});

$(document).on('click', '.modal_new_btn', function(e){
    let url = $(this).attr('data-url');
    EditItem(url, '#modal_form_content');
    $('#FormModal').modal('show');
});

// Prevent loading of other items when one is being edited or new one is being created.
$(document).on('click', '.item-details2', function(e){
    alert('Please Save/Cancel previous changes.') ;
    e.preventDefault();
    return 0 ;
});

$(function(){
    'use strict';

    $(document).on('click', '.modal-item-details', function(){
        let id = $(this).attr('id') ;
        let url = $(this).attr('data-url');
        EditItem(url, '#modal_form_content');
        $('#FormModal').modal('show');

        // $.get('/set-session-id',{___item_id___:id},(d)=>{});
        $(document).on('click', '.new_btn', function(){
            let $form = $(this).closest('form') ;
            $form.find('.update_request').each(function(){$(this).remove()});
            let partialIds = ['#__err2__'+getPartialIds()];
            partialIds.forEach((_id_) => { $form.find(_id_).html('')});
            $form.find('#__staff-id__').prop('readonly', false);
            $form.find('input[class="show-inactive"]').prop('disabled',false);
            $form.find('.update_request').val('false');
            $form.find('input, textarea, select').each(function(){
                let name = $(this).attr('name');
                let type = $(this).attr('type') ;
                let status = $(this).attr('data-ignore');
                if( name === 'logo2' || name === '_token' || name === '_method' || status != null){
                }else{$(this).val('');}
                if(type === 'checkbox' || type === 'radio'){$(this).attr('checked', false);}
            });
            // $form.find('.select2').each(function(){$(this).select2()});
            $('select').selectpicker('refresh');
            $form.find('.dropify').each(function(){$(this).dropify()});
            $form.find('.summernote').each(function(){$(this).summernote({height: 150})});
            $form.find('.summernote').each(function(){$(this).summernote('enable')});
            $form.find('.summernote').each(function(){$(this).summernote('reset')});
        });
        /**
         *  SAVE     :=>  ON
         *  CANCEL   :=>  ON
         *  EDIT     :=>  OFF
         *  DELETE   :=>  ON
         */
        $(document).on('click', '.edit_btn', function(){
            let $form = $(this).closest('form');
            $('tr.item-details').removeClass('modal-item-details').addClass('modal-item-details2');
            $form.find('#__staff-id__').prop('readonly', true);
            $form.find('.update_request').each(function(){$(this).remove()});
            $form.append('<input type="hidden" name="update_request" value="true" class="update_request"/>');
            $form.find('.delete_btn, .save_btn, .cancel_btn').each(function(){$(this).prop('disabled', false)});
            $form.find('.new_btn').hide();
            $form.find('.cancel_btn').show();
            $form.find('input, textarea, select').each(function(){$(this).prop('disabled', false)});
            // $form.find('.select').each(function(){$(this).select2()});
            $('select').selectpicker('refresh');
            $form.find('.summernote').each(function(){$(this).summernote({height: 150})});
            $form.find('.summernote').each(function(){$(this).summernote('enable')});
        });
        $(document).on('click','.cancel_btn',function(){
            $('tr.modal-item-details2').removeClass('modal-item-details2').addClass('modal-item-details');
            let $form = $(this).closest('form') ;
            $('#search_form input, input[type="search"], input[class="show-inactive"]').prop('disabled', false);
            $form.find('.update_request').each(function(){$(this).remove()});
            $form.find('.summernote').each(function(){$(this).summernote('disable')});
            $(this).hide();
            $form.find('.new_btn').show();
        });
        //  $("form#calc input, select#selectnav1, form input[name=_token]").prop('disabled', false) ;
    });
});



/**
 *  Handle form submissions.
 */
$(document).on('click', '.save_btn', function(e){
    e.preventDefault();
    let form=$(this).closest('form');
    let data = new FormData(form[0]);
    let url = form.attr("action");
    let method = form.attr("method");
    bootbox.confirm("<h4>SAVE</h4><hr /><div>Are you sure you want to save?</div>", function (result) {
        if (result === true) {
            $.ajax({
                type: method,
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                error: function(XMLHttpRequest, textStatus, errorThrown){
                    for (control in XMLHttpRequest.responseJSON.errors) {
                        $('#error-' + control).html(XMLHttpRequest.responseJSON.errors[control]);
                    }
                    HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                },
                success: function (data) {
                    bootbox.alert(DetermineIconFromResult(data) + " " + data.Message, function () {
                        if(data.Result === "SUCCESS")
                        {
                            window.location.reload();
                        }
                    });
                },
            });
        }
        else {
        }
    });
});

/**
 *  Handle the delete action
 */
$(function(){
    'use strict' ;

    $(document).on('click', '.delete_btn', function(e){
        e.preventDefault();
        /**
         * Note: Every form will have a special '_id' field which will hold the primary key
         * value of the record that's currently loaded.
         *
         * @type {jQuery}
         */
        $('#_delete_form_ input').prop('disabled', false);
        let id = $(this).closest('form').find('#_id').val();
        let name = $(this).closest('form').find('#_name').val();
        let url = $(this).closest('form').attr('action') ;

        if(id){
            let delUrl = url + '/' + id;
            DeleteItem(name, delUrl);
        }else{
            alert('Please select the item you wish to delete.');
            e.preventDefault();
        }
    });
    $(document).on('click', '.delete', function(e){
        e.preventDefault();
        /**
         * Note: Every form will have a special '_id' field which will hold the primary key
         * value of the record that's currently loaded.
         *
         * @type {jQuery}
         */
        $('#_delete_form_ input').prop('disabled', false);
        let id = $(this).attr('id');
        let url = appUrl ;
        if(id){
            let delUrl = url + id +'/delete';
            DeleteItem('data', delUrl);
        }else{
            alert('Please select the item you wish to delete.');
            e.preventDefault();
        }
    });
}) ;

/**
 * Delete table row
 */
$(document).on('click', '.delete_r', function(e){

    e.preventDefault() ;
    let $el =  $(this).closest('tr');
    let item_id = $el.attr('data-id') ;
    let leader = $el.attr('data-leader-id');

    $.colorbox({
        initialHeight: '0',
        initialWidth: '0',
        href: "#remove_prompt",
        inline: true,
        opacity: '0.3',
        onComplete: function(){
            $(document).on('click', '.yes_remove', function(ee){
                ee.preventDefault();
                $el.remove() ;
                $.colorbox.close();
                if($.inArray(item_id, deletedItems) === -1){
                    deletedItems.push(item_id)  ;
                }
                if($.inArray(leader, leaders_ids) > -1){
                    removeElement(leaders_ids, leader);
                }
                return 0;
            });
            $(document).on('click','.confirm_no', function(ee){
                ee.preventDefault();
                $.colorbox.close();
                return 0 ;
            });
        }
    });
});

/**
 * Prompt user of error
 * Do not delete this function...
 * @param promptId
 */
function showErrPrompt(promptId){
    $.colorbox({
        initialHeight: '0',
        initialWidth: '0',
        href: "#"+promptId,
        inline: true,
        opacity: '0.3',
        onComplete: function(){
            return 0;
        }
    });
    return 0 ;
}

/**
 *  This action loads partial form details
 *
 */
$(document).on('click', '.partial-details', function(e){
    let itemId = $(this).attr('data-id') ;
    let formId = $(this).attr('data-target_form');
    let table = $(this).attr('data-table_name');
    if($(this).hasClass('ignore')){
    }else{
        loadPartialForm(itemId, table, '/get-partial-record-info', formId) ;
    }
});

/**
 *
 * @returns {string}
 */
function getPartialIds(){
    let sections = ['banks', 'dependents', 'beneficiaries', 'educations', 'objectives', 'skills', 'hobbies',
        'languages', 'awards', 'incidents', 'employment_histories','professional_courses'];
    let ids = '' ;

    sections.forEach((_val_) => {ids+=', #__'+_val_+'__';});
    return ids ;
}

/**
 * This method loads values into the various form fields.
 * @param data
 * @param fields
 */
function  setFormFieldValues(fields, data){
    let keys = [] ;
    for(let k in data) keys.push(k);
    $(fields).each(function() {
        let name = $(this).attr('name');
        let type = $(this).attr('type');
        let index_val = data[name];
        if (name === '_token' || name === 'logo2' || name === '_method' || name === '__search'){
        } else {
            if(keys.includes(name)){
                if (type === 'checkbox' || type === 'radio') {
                    if (index_val === true || index_val === '1' || index_val === 'true') {
                        $(this).attr('checked', true);
                        $(this).val(index_val);
                    } else {
                        $(this).attr('checked', false);
                        $(this).val(index_val);
                    }
                }else {$(this).val(index_val);}
            }
        }
    });
}

/**
 *
 * @param itemId
 * @param logo
 * @param formId
 *
 * Loads form components when the list-items are clicked.
 *
 * We need to modify this function to be able to load partial views as well.
 * For partial component views, we need to pass the section and the item_id
 */
function loadForm(itemId, logo = false, formId = null){
    let formUrl = appUrl + 'details' ;
    if(logo === true){
        let logoUrl = appUrl + 'logo' ;
        $.get(logoUrl, {___item_id___: itemId}, function(data){
            if(!data){
                $("#target").attr("src", "/default_company.png");
            }else{
                $("#target").attr("src", data);
            }
        });
    }
    let fields = 'input, textarea, select';
    $.get(formUrl,{___item_id___:itemId},(data)=>{setFormFieldValues(fields,data);});
}

/**
 * @param itemId
 * @param requestUrl
 * @param formId
 * @param table
 */
function loadPartialForm(itemId, table, requestUrl, formId){
    let fields = 'form#'+formId+ ' input, form#'+formId+' textarea, form#'+formId+' select' ;
    $.get(requestUrl, {___item_id___: itemId, table_name: table},(data)=>{
        setFormFieldValues(fields, data) ;
    });
}

/**
 * This function loads partial tables forms
 *
 * @param itemId
 * @param partialBaseUrlArray : Array of URLs. The number of urls should correspond to the number of sections to scan
 * @param sectionsArray: Sections to scan and populate. This array should match the number of partialBaseUrlArrays.
 *
 * eg. loadPartialComponents('GNC-997',
 *                              ['/human-resource/employees', '/human-resource/employees/banks'],
 *                              [['emp_id1','emp_id2'], ['emp_id3','emp_id4']])
 */
function loadPartialComponents(itemId, partialBaseUrlArray, sectionsArray){
    let i = 0 ;
    partialBaseUrlArray.forEach((_url_) => {
        let firstComponentSections = sectionsArray[i] ;
        firstComponentSections.forEach((_val_) => {
            if(_url_.endsWith('/')){
            }else{ _url_ = _url_ + '/' ;}
            let url = _url_ + _val_;
            let section_id = '#__'+_val_+'__';
            $.get(url, {___item_id___: itemId},(data)=>{
                $(section_id).html(data);
                addPartialClass(section_id);
            });
        });i+= 1 ;
    });
}

/**
 * Add partial class to sections
 * @param section_id
 */
function addPartialClass(section_id){
    $(section_id).find('tr').addClass('partial-details').closest('table').addClass('table-hover').removeClass('table-striped');
}

/**
 * Loads the image data into the target location.
 *
 * Note: The images are saved as base64 encoded data into the database. This is done for obvious reasons.
 *
 * @param input
 */
function readURL(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = (e) => {$('#target').attr('src', e.target.result);};
        reader.readAsDataURL(input.files[0]);
    }
}
$("#logo").change(function(){readURL(this);});
$('#target').on('click',function(){$('#logo').click();});

/**
 * Activate checkboxes when they are clicked.
 * The checkboxes used on the forms are not the regular checkboxes, hence we need this
 * script to set their values.
 */
$(document).on('click', 'form .checkbox', function(e){
    let $checkbox = $(this).find('input[type="checkbox"]') ;
    if($checkbox.is(':checked')){$checkbox.prop('checked', false);
    }else{$checkbox.prop('checked', true); }
    $checkbox.val(!!$checkbox.is(':checked'));
});

/**
 * Handle form logout request
 */
$(document).on('click', 'a#_logout_btn_',function(){
    $.post('/logout',{_token:ctoken},function(){location.reload()});
});

//
// $(document).on('click', '.save_btn', function(e){
//     let x = confirm('Save this record?');
//     if(x!==true){e.preventDefault();return 0;}else{
//         e.preventDefault();
//         let $form = $(this).closest('form');
//         let url = $(this).closest('form').attr('action');
//         let data = {} ;
//         $form.find('#____search_term____').remove();
//         $form.find('input, textarea, select').each(function(ev){data[$(this).attr('name')]=$(this).val();});
//         $.ajax({
//             type: 'POST',
//             url: url,
//             data: data,
//             success: function(data){
//                 // window.location.reload();
//                 console.log(data) ;
//             },
//             error: function(data){
//                 alert('Error! Unable to process your request. Please try again later.');
//                 // window.location.reload();
//                 console.log(data)
//             }
//         });
//     }
// });

/**
 * Search item
 */

$(document).on('keypress', '#____search_term____',function(e){
    let searchTerm = '' ;
    if(e.which === 13){
        searchTerm = $(this).val();
        let searchUrl = appUrl + 'search?_q='+searchTerm ;
        let $form = $(this).closest('form') ;
        $form.find('input[name="_token"]').remove();
        $form.attr('action', searchUrl).attr('method','GET').submit();
    }
});

/**
 * Show in-active items
 */
function showInactiveItems(url, itemId, sectionId, status = null){
    $.get(url, {___item_id___:itemId, inactive:status}, function(data){
        $(sectionId).html('');
        $(sectionId).html(data) ;
        addPartialClass(sectionId);
    });
}
$(document).ready(function(){
    $('input[class="show-inactive"]').click(function(){

        let id = $(this).attr('id');
        let url = appUrl ;
        let components = $(this).attr('data-components');
        if(components != null){
            url += 'partials/'+components+'/'+id ;
        }else{
            url += 'partials/'+id ;
        }
        let section_id = '#__'+ id + '__';
        let item_id = $('#_id').val();
        if($(this).is(":checked")){
            showInactiveItems(url,item_id, section_id, 1);
        }else{
            showInactiveItems(url,item_id,section_id) ;
        }
    });
});


/**
 * Remove element (ele) from an array (arr)
 * @param arr
 * @param ele
 */
function removeElement(arr, ele){
    let index = arr.indexOf(ele) ;
    if(index > -1)arr.splice(index, 1) ;
}

/**
 *
 */
function hideAlerts(){
    $('.alert').each(function(){$(this).parent('div').remove();$(this).parent('div').hide()});
}


/**
 *
 * @param id
 * @returns {boolean}
 */
function validateId(id){
    if(id === null || id === '' || id.length === 0) return true;
    let found = false;
    $('tr.schedule-elements').each(function(){
        let dd = $(this).attr('data-id');
        if(dd === id){found =  true ;}
    });
    return found ;
}


$(function(){

    'use strict';

    /**
     *  UPLOAD DOCUMENTS
     */

    $('#add-files').click(function (e) {
        let $doc = $('#support');
        $doc.html('') ;
        let number = $('#no-documents').val();
        if ($.trim(number) === '') {
            alert('Please enter the number of supporting documents you wish to add.')
        } else {
            for (let i = 1; i <= number; i++) {
                $doc.append("<div class='col-sm-5'>" +
                    "<input type='text' autocomplete='off' class='form-control' name='document_name[]' placeholder='Name/Title of file'>" +
                    "</div>" +
                    "<div class='col-sm-4'>" +
                    "<input type='file' class='form-control  ' name='document_file[]'></div> "
                )
            }
        }
    }) ;

    $('#submit-doc').change(function(e){
        let type = $(this).val();
        let $el = $('.show-div') ;
        if(type === 'electronic'){
            $el.show()
        }else{
            $el.hide();
            $('#no-documents').val('');
            $('#support').html('');
        }
    });

    $(document).on('click', '#resetPassword', function(e){
        let id = $(this).closest('form').find('#_id').val();
        let name = $(this).closest('form').find('#_name').val();
        let url = $(this).closest('form').attr('action') ;
        url = url+'/reset-password/'+id;

        if(id)
        {
            ResetPassword(name, url);
        }else{
            alert("No item selected.")
        }
    });

    $(document).on('click', '#changeStatus', function(e){
        let id = $(this).closest('form').find('#_id').val();
        let status = $(this).closest('form').find('#_status').val();
        let name = $(this).closest('form').find('#_name').val();
        let url = $(this).closest('form').attr('action') ;
        url = url+'/change-status/'+id;

        ChangeStatus(name, status, url);
    });

    $(document).on('click', '.approve_btn', function(e){
        let id = $(this).closest('form').find('#_id').val();
        let name = $(this).closest('form').find('#_name').val();
        let url = $(this).closest('form').attr('action') ;

        url = url+'/confirm-account/'+id;
        if(id)
        {
            ConfirmAccount(name, url);
        }else{
            alert("No item selected.")
        }
    });
});

