@extends('layouts.sidebar-section')

@section('sidebar')

<div class="aw__sidebar__group cash__io__controls">
    <button class="btn aw-btn-smart-v2 w-100" type="button">
        Payments
    </button>
    <button class="btn aw-btn-smart-v2 w-100" type="button">
        History
    </button>
</div>

@endsection

@section('section')
    @yield('content')
@endsection


@push('scripts')
@endpush