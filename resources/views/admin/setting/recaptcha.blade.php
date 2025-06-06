
<h4 class="title mt-4 mb-3">{{ get_phrase('Recaptcha settings') }}</h4>
<form class="required-form" action="{{ route('admin.website.settings.update') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="type" value="recaptcha_settings">
    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label">{{ get_phrase('Recaptcha status') }}<span class="required">*</span></label><br>
        <input class="form-check-input" type="radio" id="recaptcha_active" value="1" name="recaptcha_status" @if (get_frontend_settings('recaptcha_status') == 1) checked @endif>
        <label class="form-label ol-form-label" for="recaptcha_active">{{ get_phrase('Active') }}</label>
        &nbsp;&nbsp;
        <input class="form-check-input" type="radio" id="recaptcha_inactive" value="0" name="recaptcha_status"@if (get_frontend_settings('recaptcha_status') == 0) checked @endif>
        <label class="form-label ol-form-label" for="recaptcha_inactive">{{ get_phrase('Inactive') }}</label>
    </div>

    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="recaptcha_sitekey">{{ get_phrase('Recaptcha sitekey') }} (v3)<span class="text-danger ms-1">*</span></label>
        <input type="text" name = "recaptcha_sitekey" id = "recaptcha_sitekey" class="form-control ol-form-control" value="{{ get_frontend_settings('recaptcha_sitekey') }}" required>
    </div>

    <div class="fpb-7 mb-3">
        <label class="form-label ol-form-label" for="recaptcha_secretkey">{{ get_phrase('Recaptcha secretkey') }}(v3)<span class="text-danger ms-1">*</span></label>
        <input type="text" name = "recaptcha_secretkey" id = "recaptcha_secretkey" class="form-control ol-form-control" value="{{ get_frontend_settings('recaptcha_secretkey') }}" required>
    </div>

    <div class="fpb-7 mb-3">
        <button type="submit" class="btn ol-btn-primary">{{ get_phrase('Save changes') }}</button>
    </div>
</form>
