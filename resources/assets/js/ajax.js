import $ from 'jquery';

const toast = document.querySelector('#toast');
const root = $('body').data('root');

$('#timetable').find('form').on('submit', (event) => {
    const data = $(event.currentTarget).serializeArray();
    const action = $(event.currentTarget).attr('action');
    const method = $(event.currentTarget).attr('method').toUpperCase() || 'GET';

    let error = false;

    $.ajax({
        url: action,
        type: method,
        data: data,
        dataType: 'json',
        success: (data) => {
            toast.MaterialSnackbar.showSnackbar({
                message: data.msg
            });

            if (!data.error) {
                const method = $(event.currentTarget).find('input[name="_method"]');

                if (method.val() === 'delete') {
                    $(event.currentTarget).find('a').html('Unbooked');
                    method.val('put');
                } else {
                    $(event.currentTarget).find('a').html($('#username').html());
                    method.val('delete');
                }
            }

            //$('#error-container').html(msg);
        },
        error: () => error = true
    });

    if (!error) {
        return false;
    }
});
