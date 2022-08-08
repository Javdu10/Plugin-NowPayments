
<div class="alert alert-info" role="alert">
    <p>{{ trans('nowpayments::messages.info')}}</p>
    {{ trans('nowpayments::messages.referral')}} <a href="https://nowpayments.io/?link_id=3934140231" target="_blank">{{trans('nowpayments::messages.link')}}</a>. ({{trans('nowpayments::messages.strings')}})
</div>

<div class="mb-3">
    <label for="api-key" class="form-label">API KEY</label>
    <input type="text" class="form-control @error('api-key') is-invalid @enderror" id="api-key" name="api-key" value="{{ old('api-key', $gateway->data['api-key'] ?? '') }}" required>

    @error('api-key')
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
</div>

<div class="mb-3">
    <label for="ipn-secret" class="form-label">IPN SECRET</label>
    <input type="password" class="form-control @error('ipn-secret') is-invalid @enderror" id="ipn-secret" name="ipn-secret" value="{{ old('ipn-secret', $gateway->data['ipn-secret'] ?? '') }}" required>

    @error('ipn-secret')
    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
    @enderror
</div>