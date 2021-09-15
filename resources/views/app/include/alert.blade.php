@if (session('success'))
<div class="callout callout-success" id="alert-success">
    <p>{{ session('success') }}</p>
</div>
@endif
@if (session('error'))
<div class="callout callout-danger" id="alert-error">
    <p>{{ session('error') }}</p>
</div>
@endif
@if (session('info'))
<div class="callout callout-info" id="alert-info">
    <p>{{ session('info') }}</p>
</div>
@endif
