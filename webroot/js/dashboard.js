/* webroot/js/dashboard.js */
$(function () {
    var token = localStorage.getItem('auth_token');

    var $loading = $('#dashboard-loading');
    var $content = $('#dashboard-content');
    var $errorBox = $('#dashboard-error');
    var $username = $('#dashboard-username');

    function redirectToLogin() {
        localStorage.removeItem('auth_token');
        window.location.href = '/login';
    }

    if (!token) {
        redirectToLogin();
        return;
    }

    $.ajaxSetup({
        headers: { 'X-Auth-Token': token }
    });

    function formatDate(isoString) {
        if (!isoString) {
            return '—';
        }
        var d = new Date(isoString);
        if (isNaN(d.getTime())) {
            return isoString;
        }
        return d.toLocaleString();
    }

    $.ajax({
        url: '/users/api-dashboard',
        method: 'GET',
        dataType: 'json'
    })
        .done(function (response) {
            var mysql = response.mysql || {};
            var mongo = response.mongo || {};

            $username.text(mongo.full_name || mysql.username);

            $('#mysql-id').text(mysql.id != null ? mysql.id : '—');
            $('#mysql-username').text(mysql.username || '—');
            $('#mysql-email').text(mysql.email || '—');
            $('#mysql-created').text(formatDate(mysql.created_at));
            $('#mysql-modified').text(formatDate(mysql.modified_at));

            $('#mongo-fullname').text(mongo.full_name || '—');
            $('#mongo-created').text(formatDate(mongo.created_at));

            $loading.addClass('d-none');
            $content.removeClass('d-none');
        })
        .fail(function (xhr) {
            $loading.addClass('d-none');
            if (xhr.status === 401) {
                redirectToLogin();
                return;
            }
            var response = xhr.responseJSON || {};
            $errorBox
                .removeClass('d-none')
                .text((response.errors && response.errors.general) || 'Could not load dashboard.');
        });

    $('#logout-btn').on('click', function () {
        $.ajax({
            url: '/users/api-logout',
            method: 'POST',
            dataType: 'json'
        }).always(function () {
            redirectToLogin();
        });
    });
});
