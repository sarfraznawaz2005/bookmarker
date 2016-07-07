<!-- delete confirm modal start -->
<div class="modal fade animated rotateIn modal-danger" id="modal-delete-confirm" tabindex="-1" role="dialog" aria-hidden="true">
{{--<div class="modal fade " id="modal-delete-confirm" tabindex="-1" role="dialog" aria-hidden="true">--}}
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title">
                    <b class="glyphicon-4x glyphicon glyphicon-trash"></b>
                    Confirm Delete
                </h4>
            </div>

            <div class="modal-body"></div>

            <div class="modal-footer">
                <button class="btn btn-default col-sm-2 pull-right" data-dismiss="modal">
                    <i class="fa fa-times-circle"></i> Close
                </button>
                <div class="pull-right">&nbsp;</div>
                <form action="#" method="POST" style="display: inline;">
                    {{csrf_field()}}
                    {{method_field('DELETE')}}

                    <button type="submit"
                            class="btn confirm-delete-red-button btn-danger col-sm-2 pull-right"
                            id="frm_submit"><i class="fa fa-trash"></i> Delete
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- delete confirm modal end -->

@push('scripts')
<script>
    $('body').on('click', '.confirm-delete-red-button', function (e) {
        $(this).attr('disabled', true);
        $(this).closest('form')[0].submit();
    });
</script>
@endpush