<div class="modal-dialog">
    <form method="POST" action="{{ route('admin.users.update.password', ['id' => $user->id]) }}" 
        enctype="multipart/form-data" id="edit_pwd_form">
        @method('put')
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    @lang('messages.edit_password')
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="required" for="password">{{ trans('messages.password') }}</label>
                    <input class="form-control" type="password" name="password" id="password" required minlength="5">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    @lang('messages.close')
                </button>
                <button type="button" class="btn btn-primary update-password">
                    @lang('messages.update')
                </button>
            </div>
        </div>
    </form>
</div>