var initConfirm = function () {
    $('a.btn-danger').on('click', function(e) {
        e.preventDefault()
        
        var textConfirm = confirm('หากลบข้อมูลแล้วไม่สามารถกู้คืนได้ ยืนยันการทำรายการ ?')
        if (textConfirm) {
            window.location = e.currentTarget.href;
        }
    })
}

$(function () {

    initConfirm();

    $('form:first *:input[type!=hidden]:first').focus();
    
    $('.data-table').DataTable({
        "aaSorting": [],
        "iDisplayLength": 25,
        "columnDefs": [{
            orderable: false,
            targets: 'no-sort'
        }],
        "language": {
            "url": "js/i18n/datatable-thai.json"
        }
    });

    $('.date-time-picker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm'
    });

    $(".select2").select2({
        theme: "bootstrap"
    });

    $(".edit-field").on('blur', function(e) {
        var element = $(this);
        element.LoadingOverlay('show');

        var old_value = $(this).html();
        var table = $(this).attr('data-table');
        var field = $(this).attr('data-field');
        var id = $(this).attr('data-id');
        var value = $(this).html();
        var reload = $(this).attr('data-reload');
        var type = $(this).attr('data-type');
        var current_element = $(this);

        var params = {
            'table': table,
            'field': field,
            'id': id,
            'value': value,
            'type': type
        }

        $.get('admin/edit_field', params, function(res) {
            console.log(res.status)
            element.LoadingOverlay("hide");
            
            current_element.html(res.field_value);

            if (res.status == false) {
                console.log(res)
                current_element.html(old_value);
            }

            if (reload == 'true') {
                window.location.reload()
            }
        })

    });

    
    $(".checkbox-auto-update").on('click', function(e) {
        var element = $(this).closest('td');
        element.LoadingOverlay('show');

        var table = $(this).attr('data-table');
        var field = $(this).attr('data-field');
        var id = $(this).attr('data-id');
        var value = ($(this).is(":checked")) ? 1 : 0;
        var type = $(this).attr('data-type');

        var params = {
            'table': table,
            'field': field,
            'id': id,
            'value': value,
            'type': type
        }

        $.get('admin/edit_field', params, function(res) {
            console.log(res.status)
            element.LoadingOverlay('hide');            
        })
    })

    $('#modal-member #group-submit').on('click', function(e) {
        var group_id = $('#modal-member #group-id').val()

        if (group_id == "" || group_id == null) {
            $('#modal-member #group-id').focus()
        } else {
            window.location = "admin/group/member/create/" + group_id;
        }
    })
})