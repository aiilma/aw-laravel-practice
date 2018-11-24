@extends('layouts.sidebar-section')

@section('sidebar')

@endsection

@section('section')
orders


{{dd($session)}}
{{-- @if (session('order'))
    <div class="alert alert-success">
        {{ session('order') }}
    </div>
@endif --}}

@endsection


@push('scripts')
@endpush