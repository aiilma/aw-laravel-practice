@extends('layouts.sidebar-section')

@section('sidebar')

<div class="aw__sidebar__group cash__io__controls">
    {{-- <button id="CashInputBtn" class="btn aw-btn-smart-v2 disabled w-100" type="button">
        
    </button> --}}
    {{-- <button id="CashOutputBtn" class="btn aw-btn-smart-v2 w-100" type="button">
        
    </button> --}}
</div>

@endsection

@section('section')

    <h3 class="my-4">PayPal (cash-out):</h3>
    {{-- messages --}}
    <div class="aw-gateway-desc">
        @if(session()->has('message'))
            <p class="message">
                {{ session('message') }}
            </p>
        @endif
    </div>
    {{-- form --}}
    <div class="aw-gateway-form my-2">
        <form method="POST" action="{{URL::route('payments-out-paypal')}}">
            {{ csrf_field() }}
            <!-- УКАЗАНИЕ ДАННЫХ ДЛЯ ПЕРЕВОДА -->
            <div class="form-group row aw-payment-data">
                <label class="col-4 text-right">Email:</label>
                <div class="col-4">
                    <input class="aw-common-text-input" type="text" name="_receiver" placeholder="#@#.#">
                </div>
                <div class="col-4"></div>
            </div>
            <div class="form-group row aw-payment-data">
                <label class="col-4 text-right">Amount $:</label>
                <div class="col-4">
                    <input class="aw-common-text-input" type="text" name="_amount" placeholder="0">
                </div>
                <div class="col-4"></div>
            </div>

            <div class="form-group container aw-form-controls">
                <button id="cashInBtn" type="submit" class="btn aw-btn-smart-v3"><i class="fa fa-paypal" aria-hidden="true"></i></button>
            </div>
        </form>
    </div>



@endsection


@push('scripts')
@endpush