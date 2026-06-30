/* webroot/js/login.js */
$(function () {
    var $form = $('#login-form');
    var $alert = $('#login-alert');
    var $submitBtn = $('#login-submit');

    function showAlert(message, type) {
        $alert
            .removeClass('d-none alert-success alert-danger')
            .addClass('alert-' + type)
            .text(message);
    }

    $form.on('submit', function (event) {
        event.preventDefault(); // no native form submission, ever

        $submitBtn.prop('disabled', true).text('Logging in...');

        var payload = {
            username: $('#username').val(),
            password: $('#password').val()
        };

        $.ajax({
            url: '/users/api-login',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            dataType: 'json'
        })
            .done(function (response) {
                // Token is the entire session mechanism on the client side.
                // No PHP session / cookie is used.
                localStorage.setItem('auth_token', response.token);
                window.location.href = '/dashboard';
            })
            .fail(function (xhr) {
                var response = xhr.responseJSON || {};
                var message = (response.errors && response.errors.general) ||
                    'Invalid username or password.';
                showAlert(message, 'danger');
            })
            .always(function () {
                $submitBtn.prop('disabled', false).text('Log in');
            });
    });
});
