<div class="px-4 pt-4">
    @if ($message = session()->has('succes'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <p class="text-white mb-0 fw-bold">{!! session()->get('succes') !!}</p>
{{--            <p class="text-white mb-0 fw-bold">{{ session()->get('succes') }}</p>--}}
        </div>
    @endif
    @if ($message = session()->has('error'))
        <div class="alert alert-danger" role="alert">
            <p class="text-white mb-0 fw-bold">{!! session()->get('error') !!}</p>
{{--            <p class="text-white mb-0 fw-bold">{{ session()->get('error') }}</p>--}}
        </div>
    @endif
    @if ($message = session()->has('warn'))
        <div class="alert alert-warning" role="alert">
            <p class="text-white mb-0 fw-bold">{!! session()->get('warn') !!}</p>
{{--            <p class="text-white mb-0 fw-bold ">{{ session()->get('warn') }}</p>--}}
        </div>
    @endif
    @if ($message = session()->has('primary'))
        <div class="alert alert-primary" role="alert">
            <p class="text-white mb-0 fw-bold">{!! session()->get('primary') !!}</p>
{{--            <p class="text-white mb-0 fw-bold">{{ session()->get('primary') }}</p>--}}
        </div>
    @endif
</div>
