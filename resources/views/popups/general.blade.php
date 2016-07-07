<!-- general modal start -->
<div id="{{$id}}" class="modal fade modal-info" tabindex="-1" role="dialog" style="z-index: 99999;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header {{$header_class}}">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title">
                    {!! $title_icon !!}
                    {!! $title !!}
                </h4>
            </div>

            <div class="modal-body" style="padding: 10px 10px 0 10px;">
                {!! $content !!}
            </div>

            <div class="modal-footer" style="padding: 7px 10px 5px 10px;">
                {!! $actionbutton !!}
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- general modal end -->