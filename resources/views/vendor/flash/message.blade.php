@if (Session::has('flash_notification.message'))
    @if (Session::has('flash_notification.overlay'))
        @include('flash::modal', ['modalClass' => 'flash-modal', 'title' => Session::get('flash_notification.title'), 'body' => Session::get('flash_notification.message')])
    @else
        <div class="animated shake alert alert-{{ Session::get('flash_notification.level') }}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

            @if(Session::get('flash_notification.level') === 'success')
                <b class="glyphicon glyphicon-ok-sign"></b>&nbsp;
                <strong>Success!</strong>
            @elseif(Session::get('flash_notification.level') === 'danger')
                <b class="glyphicon glyphicon-remove-sign"></b>&nbsp;
                <strong>Error!</strong>
            @elseif(Session::get('flash_notification.level') === 'info')
                <b class="glyphicon glyphicon-ok-sign"></b>&nbsp;
                <strong>Success!</strong>
            @elseif(Session::get('flash_notification.level') === 'warning')
                <b class="glyphicon glyphicon-warning-sign"></b>&nbsp;
                <strong>Attention!</strong>
            @endif

            {!!  Session::get('flash_notification.message')  !!}
        </div>
    @endif
@endif
