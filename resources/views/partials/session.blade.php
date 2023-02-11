@if (Session::has('success'))
    <div class="success">{{ Session::get('success') }}</div>
    @php
        session()->forget('success');
    @endphp
@endif

@if (Session::has('error'))
    <div class="error">{{ Session::get('error') }}</div>
    @php
        session()->forget('error');
    @endphp
@endif