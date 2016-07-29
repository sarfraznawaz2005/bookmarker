$(function () {

    // csrf ajax token setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // expand navbar
    $(".navbar-expand-toggle").click(function () {
        $(".app-container").toggleClass("expanded");
        $(".navbar-expand-toggle").toggleClass("fa-rotate-90");
    });

    // toggle menu
    $(".navbar-right-expand-toggle").click(function () {
        $(".navbar-right").toggleClass("expanded");
        $(".navbar-right-expand-toggle").toggleClass("fa-rotate-90");
    });

    // replace select with select2
    $('select').select2();

    // fancy checkbox/radio
    $('.toggle-checkbox').bootstrapSwitch({
        size: "small"
    });

    // fix height
    $('.match-height').matchHeight();

    // side menu toggle
    $(".side-menu .nav .dropdown").on('show.bs.collapse', function () {
        $(".side-menu .nav .dropdown .collapse").collapse('hide');
    });

    // BTS Tooltips
    $('[data-tooltip]').tooltip({container: 'body'});

    // BTS Popover
    $('[rel="popover"]').addClass('text-primary').popover({"trigger": "hover", "html": true});

    // to close popover when clicking outside
    $('body').on('click', function (e) {
        $('[rel="popover"]').each(function () {
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });

    // hide sidebar if window is smaller
    $(window).resize(function () {
        var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
        var $btn = $('button.navbar-expand-toggle');

        if (w < 780) {
            $(".app-container").removeClass("expanded");
            $(".navbar-expand-toggle").removeClass("fa-rotate-90");
        }
        else {
            $(".app-container").addClass("expanded");
            $(".navbar-expand-toggle").addClass("fa-rotate-90");
        }
    });

    // confirm delete
    $('body').on('click', '.confirm-delete', function (e) {
        var label = $(this).data('label');
        var $dialog = $('#modal-delete-confirm');

        $dialog.find('.modal-body').html('You are about to delete <strong>' + label + '</strong>, continue ?');
        $dialog.find('form').attr('action', this.rel);
        $dialog.modal('show');

        e.preventDefault();
    });

    // automatically apply active class to right navigation link
    $('div.side-menu-container li').removeClass('active');
    $('div.side-menu-container a[rel="' + __controller__ + '"]').closest('li').addClass('active');

    // jQuery datatable for tables
    $('.datatable').DataTable({
        "dom": '<"top"fl<"clear">>rt<"bottom"ip<"clear">>',
        "bPaginate": false,
        "bInfo": false,
        "responsive": true,
        "aaSorting": [] // disable initial sort
    });

    $('.pulsate').pulsate();
});
