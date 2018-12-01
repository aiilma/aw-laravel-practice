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

    <h3 class="my-4">PayPal (cash-in):</h3>
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
        <form method="POST" action="{{URL::route('payments-in-paypal')}}">
            {{ csrf_field() }}
            <!-- УКАЗАНИЕ ДЕНЕЖНОЙ СУММЫ ДЛЯ ПЕРЕВОДА -->
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


    {{-- <h3 class="my-4">Your Story Of Money Transferring:</h3>
    <!-- История переводов денежных средств -->
    <div class="aw-payment-story my-4">
        <!-- Таблица -->
        <div class="aw-payment-story-table">
            <!-- Заголовки столбцов -->
            <div class="aw-payment-story-table-titles">
                <div class="row text-center">
                    <div class="col-3 aw-payment-story-table-titlecell">
                        <h5>Type</h5>
                    </div>
                    <div class="col-3 aw-payment-story-table-titlecell">
                        <h5>With data</h5>
                    </div>
                    <div class="col-3 aw-payment-story-table-titlecell">
                        <h5>Amount</h5>
                    </div>
                    <div class="col-3 aw-payment-story-table-titlecell">
                        <h5>Date</h5>
                    </div>
                </div>
            </div>
            <!-- Содержимое столбцов -->
            <div class="aw-payment-story-table-content">
                <div class="row aw-payment-story-table-content-row">
                    <div class="aw-payment-story-table-content-rowcell col-3">
                        <p title="Lorem ipsum dolor" class="ellipsis">Lorem ipsum dolor</p>
                    </div>
                    <div class="aw-payment-story-table-content-rowcell col-3">
                        <p title="Lorem ipsum dolor" class="ellipsis">Lorem ipsum dolor</p>
                    </div>
                    <div class="aw-payment-story-table-content-rowcell col-3">
                        <p title="Lorem ipsum dolor" class="ellipsis">Lorem ipsum dolor</p>
                    </div>
                    <div class="aw-payment-story-table-content-rowcell col-3">
                        <p title="Lorem ipsum dolor" class="ellipsis">Lorem ipsum dolor</p>
                    </div>
                </div>
                <div class="row aw-payment-story-table-content-row">
                    <div class="aw-payment-story-table-content-rowcell col-3">
                        <p title="Lorem ipsum dolor" class="ellipsis">Lorem ipsum dolor</p>
                    </div>
                    <div class="aw-payment-story-table-content-rowcell col-3">
                        <p title="Lorem ipsum dolor" class="ellipsis">Lorem ipsum dolor</p>
                    </div>
                    <div class="aw-payment-story-table-content-rowcell col-3">
                        <p title="Lorem ipsum dolor" class="ellipsis">Lorem ipsum dolor</p>
                    </div>
                    <div class="aw-payment-story-table-content-rowcell col-3">
                        <p title="Lorem ipsum dolor" class="ellipsis">Lorem ipsum dolor</p>
                    </div>
                </div>
            </div>

        </div>
    </div> --}}

@endsection


@push('scripts')
    {{-- <script src="{{asset('js/pg_transfer.js')}}"></script> --}}
@endpush