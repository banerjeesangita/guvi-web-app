/* webroot/js/register.js */
$(function () {
    var $form = $('#register-form');
    var $alert = $('#register-alert');
    var $submitBtn = $('#register-submit');

    function showAlert(message, type) {
        $alert
            .removeClass('d-none alert-success alert-danger')
            .addClass('alert-' + type)
            .text(message);
    }

    function fieldErrorsToMessage(errors) {
        var messages = [];
        $.each(errors, function (field, fieldErrors) {
            $.each(fieldErrors, function (rule, message) {
                messages.push(message);
            });
        });
        return messages.length ? messages.join(' ') : 'Please check the form and try again.';
    }

    $form.on('submit', function (event) {
        event.preventDefault(); // never let the browser submit the form natively

        $submitBtn.prop('disabled', true).text('Creating account...');

        var payload = {
            full_name: $('#full_name').val(),
            username: $('#username').val(),
            email: $('#email').val(),
            password: $('#password').val()
        };

        $.ajax({
            url: '/users/api-register',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            dataType: 'json'
        })
            .done(function (response) {
                showAlert(response.message || 'Account created successfully.', 'success');
                $form.trigger('reset');
                setTimeout(function () {
                    window.location.href = '/login';
                }, 1200);
            })
            .fail(function (xhr) {
                var response = xhr.responseJSON || {};
                var message = response.errors
                    ? fieldErrorsToMessage(response.errors)
                    : 'Something went wrong. Please try again.';
                showAlert(message, 'danger');
            })
            .always(function () {
                $submitBtn.prop('disabled', false).text('Create account');
            });
    });
});
