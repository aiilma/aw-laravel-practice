@extends('layouts.sidebar-section')

@section('sidebar')
    @include('systems.payments.sidebar')
@endsection

@section('section')

cashin
<div>
    <div>
        <h4 class="aw-payment-slider-count">
            <span id="current">1</span> of <span id="total"></span>
        </h4>
    </div>
    <ul id="awPaymentSlider">
        <li>
            <h3>Paypal</h3>
        </li>
        <li>
            <h3>Qiwi</h3>
        </li>
        <li>
            <h3>Yandex.Money</h3>
        </li>
        <li>
            <h3>Skrill</h3>
        </li>
        <li>
            <h3>WebMoney</h3>
        </li>
        <li>
            <h3>Alipay</h3>
        </li>
        <li>
            <h3>Robokassa</h3>
        </li>
        <li>
            <h3>Steam</h3>
        </li>
    </ul>
    <button>Apply</button>
</div>

<div>
    form
</div>
@endsection


@push('scripts')
    <script src="{{asset('js/payments.js')}}"></script>
@endpush