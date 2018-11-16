@extends('layouts.rackV1')

@section('content')
<div class="container">
            <div class="content">


                <div class="row align-items-start compcard__form__cont">
                        <form action="/compositions/buy" method="POST">
                                @csrf
                        ...
                        </form>
                </div>


            </div>
</div>
@endsection


@push('scripts') <script src="{{asset('js/backgrounds_load.js')}}"></script>@endpush