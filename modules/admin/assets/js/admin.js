$(function () {
    var liSubMenuActive = $('.treeview li.active');
    if (liSubMenuActive.length === 1) {
        liSubMenuActive.parents('.treeview').addClass('menu-open');
        liSubMenuActive.parents('ul.treeview-menu').show(500);
    }

    $('.btn-active').click(function (e) {
        e.preventDefault();
        $.post(this.href);
        $(this).addClass('hide-btn');
        $(this).siblings('.btn-active').removeClass('hide-btn');
    });

    $('#check-all').change(function () {
        var checked = $(this).prop('checked');
        $("form input[type=checkbox]").prop('checked', checked);
    });

    $(document).on('click', '.who-is', function () {
        $.post(this.href, function (data) {
            $('#pre-insert').html(data['data']);
        });
    });
});
