$(document).ready(function ()
{
    $('.add-task').on('click', function (e)
    {
        e.preventDefault();

        let $form = $('form[name=add_task_form]');
        let formData = $form.serialize();

        $.ajax({
            type: "POST",
            url: "",
            data: formData,
            dataType: 'json',
            beforeSend: function ()
            {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
            },
            success: function (msg)
            {
                if (msg.success === true)
                {
                    location.reload();
                } else
                {
                    if (msg.errors)
                    {
                        for (let errorsKey in msg.errors)
                        {
                            let $element = $('[name=' + errorsKey + ']');

                            $element.addClass('is-invalid');
                            $element.after('<div class="invalid-feedback">' + msg.errors[errorsKey] + '</div>');
                        }
                    } else if (msg.error)
                    {
                        alert(msg.error);
                    }
                }
            }
        });
    });

    $('.edit-task-popup').on('click', function (e)
    {
        e.preventDefault();

        let taskId = $(this).data('task-id');
        let modalId = $(this).data('modal-id');
        let formData = new FormData();

        formData.set('action', 'task_edit_popup');
        formData.set('task_id', taskId);

        $.ajax({
            url: '',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            type: 'POST',
            success: function (data)
            {
                if (data.success === true)
                {
                    if (data.data)
                    {
                        for (let dataKey in data.data)
                        {
                            let $element = $('#editTask [name="' + dataKey + '"]');

                            if (data.data.hasOwnProperty(dataKey))
                            {
                                if (dataKey === 'status')
                                {
                                    if (data.data[dataKey] == 5)
                                    {
                                        $element.prop('checked', true);
                                    }
                                } else
                                {
                                    $element.val(data.data[dataKey]);
                                }
                            }
                        }

                        $(modalId).modal('show');
                    }
                } else
                {
                    if (data.error)
                    {
                        alert(data.error);
                    }
                }
            }
        });
    });

    $('.edit-task').on('click', function (e)
    {
        e.preventDefault();

        let $form = $('form[name=edit_task_form]');
        let formData = $form.serialize();

        $.ajax({
            type: "POST",
            url: "",
            data: formData,
            dataType: 'json',
            beforeSend: function ()
            {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();
                $('.popup_error').remove();
            },
            success: function (msg)
            {
                if (msg.success === true)
                {
                    location.reload();
                } else
                {
                    if (msg.errors)
                    {
                        for (let errorsKey in msg.errors)
                        {
                            if (errorsKey === 'task_id' || errorsKey === 'permission')
                            {
                                $('#editTask .modal-body').prepend('<div class="alert alert-danger popup_error"' +
                                    ' role="alert">\n' +
                                    msg.errors[errorsKey] +
                                    '</div>')
                            } else
                            {
                                let $element = $('[name=' + errorsKey + ']');

                                $element.addClass('is-invalid');
                                $element.after('<div class="invalid-feedback">' + msg.errors[errorsKey] + '</div>');
                            }
                        }
                    } else if (msg.error)
                    {
                        alert(msg.error);
                    }
                }
            }
        });
    });
});