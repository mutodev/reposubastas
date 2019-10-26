@extends('frontend.base')

@section('content')
    <div class="bg-light-grey p-4">
        <div class="container">
            <div>
                <h1 class="text-center display-6 text-uppercase m-0">{{ $page->title }}</h1>
            </div>
        </div>
    </div>

    <div class="py-4">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            {!! $page->content !!}
        </div>
    </div>
@endsection

@section('footer_extra_scripts')
    @if ($page->slug_en === 'register-success')
        <!-- Event snippet for RR-subasta-2019-11 conversion page --> <script> gtag('event', 'conversion', {'send_to': 'AW-763964033/LPnHCL_tw7EBEIHVpOwC'}); </script>
    @endif
@endsection
