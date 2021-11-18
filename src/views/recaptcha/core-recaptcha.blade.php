<script src="https://www.google.com/recaptcha/api.js?explicit&hl={{ config('lara-core.language') }}" async defer></script>
<div class="g-recaptcha {{ $hasError ? 'is-invalid' : '' }}" data-sitekey="{{ config('lara-core.site_key') }}"></div>
@error('g-recaptcha-response')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror
