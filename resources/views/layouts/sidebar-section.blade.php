@extends('layouts.rackV1')

@section('content')
<div class="container">
    <div class="content row">
        {{-- SIDEBAR --}}
        <div class="col-xl-2 col-4 aw_sidebar">
            <div id="accordion">
                @yield('sidebar')
            </div>
        </div>

        {{-- SECTION --}}
        <div class="col-xl-10 col-8 aw_section">
            <div>
                @yield('section')
            </div>
        </div>
    </div>
</div>
@endsection