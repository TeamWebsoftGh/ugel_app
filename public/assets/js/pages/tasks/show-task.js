let token = $('meta[name="csrf-token"]').attr('content');

function SubmitForm(url, data, method)
{
    $.ajax({
        type:method,
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
                    $('#activityModal').modal('hide');
                    $("#task-details").load(location.href+" #task-details>*","");
                    $("#updateForm").load(location.href+" #updateForm>*","");
                }
            });
        },
    });
}
// this is the id of the form
$(document).on('submit', '#messageForm, #fileUploadForm, #activityForm, #objectiveForm, #updateForm', function(e){
    e.preventDefault(); // avoid to execute the actual submit of the form.
    let form = $(this);
    let data = new FormData(form[0]);
    let url = form.attr("action");
    let method = form.attr("method");

    SubmitForm(url, data, method);
});
$(document).on('click', '#activityModalBtn', function(e){
    let $form = $('Form#activityForm');
    $form.find('input, textarea, select').each(function(){
        let name = $(this).attr('name');
        let type = $(this).attr('type') ;
        let status = $(this).attr('data-ignore');
        if( name === '_token' || name === '_method' || status != null){
        }else{$(this).val('');}
        if(type === 'checkbox' || type === 'radio'){$(this).attr('checked', false);}
    });
    $('select').selectpicker('refresh');
    $('#activityModal').modal('show');
  //  $('select').selectpicker('refresh');
});
function DeleteItem(name, url)
{
    bootbox.confirm("<h4>DELETE</h4><hr /><div>This is an irrevisable action that will delete <b>"+name.toUpperCase()+"</b>. Are you sure you want to <b><span style='color:red'>delete </span>"+ name.toUpperCase() + "</b></div>", function (result) {
        if (result === true) {
            $.ajax({
                type: "DELETE",
                url: url,
                data: ({_token:token}),
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
                            $("#task-details").load(location.href+" #task-details>*","");
                        }
                    });
                },
            });
        }
        else {
        }
    });
}
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
            $(target).find('.dropify').each(function(){$(this).dropify()});
            $(target).find('.summernote').each(function(){$(this).summernote({height: 150})});
            $(target).find('.summernote').each(function(){$(this).summernote('disable')});
            $('select').selectpicker('refresh');
            // $('.select2').select2();
        },
    });
}

function EditActivity(taskId, id)
{
    let target=$('#activity_container1');
    $.ajax({
        type: "GET",
        url: '/tasks/activities/'+taskId+'/'+id,
        timeout:60000,
        datatype: "json",
        cache: false,
        error: function(XMLHttpRequest, textStatus, errorThrown){
            HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
        },
        success: function (data) {
            $('target').html("");
            console.log(target)
            $(target).html(data);
            $('select').selectpicker('refresh');
            $('#activityModal').modal('show');
        },
    });
}
gh();
$('#has_budget').on('click', function(){
    gh();
});

function gh(){
    if($('#has_budget').is(':checked')){
        $('.budget_container').show();
        $('#stage').val('budget');
        $('#accept_btn').html('Submit');
    } else {
        $('.budget_container').hide();
        $('#stage').val('employee');
        $('#accept_btn').html('Accept Task');
    }
}
function ChangeTaskStatus(name, status, url)
{
    let ActiveOrInactive="";

    if(url)
    {
        bootbox.confirm("<h4>"+name.toUpperCase()+"</h4><hr/><div> This action will " + name.toUpperCase() + "</div> Are you sure you want to " + name.toUpperCase() + "</b></div>", function (result) {
            if (result === true) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: ({_token:token, status:status}),
                    timeout:60000,
                    datatype: "json",
                    cache: false,
                    error: function(XMLHttpRequest, textStatus, errorThrown){
                        HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
                    },
                    success: function (data) {
                        bootbox.alert(DetermineIconFromResult(data) + " " + data.Message, function () {
                            $("#updateForm").load(location.href+" #updateForm>*","");
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


