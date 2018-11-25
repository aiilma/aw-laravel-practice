@extends('layouts.sidebar-section')

{{-- SIDEBAR --}}
@section('sidebar')
    {{-- ACCOUNT SIDEBAR --}}
    @include('layouts.user-sidebar')
@endsection

{{-- ACCOUNT SECTION --}}
@section('section')

<h2 class="text-center">Orders</h2>

<div class="profile-content">


    <div class="aw__user__orders__table">
        <!-- Шапка таблицы. Заголовки столбцов -->
        <div class="aw__user__orders__table__titles">
          <div class="row text-center">
            <div class="col-3 col-xl-3 aw__orders__table__titlecell">
              <h5 class="d-block">Title</h5>
            </div>
            <div class="col-3 col-xl-3 aw__orders__table__titlecell">
              <h5 class="d-block">Your Data</h5>
            </div>
            <div class="col-3 col-xl-3 aw__orders__table__titlecell">
              <h5 class="d-block">Date</h5>
            </div>
            <div class="col-3 col-xl-3 aw__orders__table__titlecell">
              <h5 class="d-block">Status</h5>
            </div>
          </div>
        </div>
        <!-- Тело таблицы. Содержимое столбцов -->
        <div class="aw__user__orders__table__stack">
          <ul class="list-inline row list-inline user__order__dataset">
            {{-- сессионный заказ --}}
            <li class="row col-12 row__user__current__order" data-order-status="cart">
              <div class="col-3 col-xl-3 user__order__cell">
                <a class="aw-link" href="#" role="button">Mountains</a>
              </div>
              <div class="col-3 col-xl-3 user__order__cell">
                <button class="aw-btn" data-toggle="modal" data-target="#orderDataModalWrapper">
                  <span>Check</span>
                </button>
              </div>
              <div class="col-3 col-xl-3 user__order__cell">
                <p class="order__cell__timedate">10.05.2018</p>
                <p class="order__cell__timedate">18:25</p>
              </div>
              <div class="col-3 col-xl-3 user__order__cell">
                    <a id="confirmOrderBtn" href="#" data-link="{{substr(route('acc-orders-confirm'), strlen(url('/')))}}" class="aw-link aw__icon__confirm" role="button" title="Confirm">
                        <img src="{{asset('storage/img/confirm.svg')}}" width="24" alt="Confirm Order">
                    </a>
                    <a id="denyOrderBtn" href="#" data-link="{{substr(route('acc-orders-deny'), strlen(url('/')))}}" class="aw-link aw__icon__deny" role="button" title="Deny">
                        <img src="{{asset('storage/img/deny.svg')}}" width="24" alt="Deny Order">
                    </a>
              </div>
            </li>
            <li class="row col-12 row__user__current__order" data-order-status="wait">
              <div class="col-3 col-xl-3 user__order__cell">
                <a class="aw-link" href="#" role="button">Mountains</a>
              </div>
              <div class="col-3 col-xl-3 user__order__cell">
                <button class="aw-btn" data-toggle="modal" data-target="#orderDataModalWrapper">
                  <span>Check</span>
                </button>
              </div>
              <div class="col-3 col-xl-3 user__order__cell">
                <p class="order__cell__timedate m-0">10.05.2018</p>
                <p class="order__cell__timedate m-0">18:25</p>
              </div>
              <div class="col-3 col-xl-3 user__order__cell">
                <span>Awaiting...</span>
              </div>
            </li>
            <li class="row col-12 row__user__current__order" data-order-status="process">
              <div class="col-3 col-xl-3 user__order__cell">
                <a class="aw-link" href="#" role="button">Mountains</a>
              </div>
              <div class="col-3 col-xl-3 user__order__cell">
                <button class="aw-btn" data-toggle="modal" data-target="#orderDataModalWrapper">
                  <span>Check</span>
                </button>
              </div>
              <div class="col-3 col-xl-3 user__order__cell">
                <p class="order__cell__timedate">10.05.2018</p>
                <p class="order__cell__timedate">18:25</p>
              </div>
              <div class="col-3 col-xl-3 user__order__cell">
                <span>In processing...</span>
              </div>
            </li>
            <li class="row col-12 row__user__current__order" data-order-status="export">
              <div class="col-3 col-xl-3 user__order__cell">
                <a class="aw-link" href="#" role="button">Mountains</a>
              </div>
              <div class="col-3 col-xl-3 user__order__cell">
                <button class="aw-btn" data-toggle="modal" data-target="#orderDataModalWrapper">
                  <span>Check</span>
                </button>
              </div>
              <div class="col-3 col-xl-3 user__order__cell">
                <p class="order__cell__timedate">10.05.2018</p>
                <p class="order__cell__timedate">18:25</p>
              </div>
              <div class="col-3 col-xl-3 user__order__cell">
                <span>Exporting...</span>
              </div>
            </li>
            <li class="row col-12 row__user__current__order" data-order-status="done">
              <div class="col-3 col-xl-3 user__order__cell">
                <a class="aw-link" href="#" role="button">Mountains</a>
              </div>
              <div class="col-3 col-xl-3 user__order__cell">
                <button class="aw-btn" data-toggle="modal" data-target="#orderDataModalWrapper">
                  <span>Check</span>
                </button>
              </div>
              <div class="col-3 col-xl-3 user__order__cell">
                <p class="order__cell__timedate">10.05.2018</p>
                <p class="order__cell__timedate">18:25</p>
              </div>
              <div class="col-3 col-xl-3 user__order__cell">
                <a class="aw-link" href="#" role="button">Download</a>
              </div>
            </li>
          </ul>
        </div>
    </div>

</div>

<!-- Modal -->
<div class="modal fade" id="orderDataModalWrapper" tabindex="-1" role="dialog" aria-labelledby="orderDataModalWrapperLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg order__dataview" role="document">
        <div class="modal-content mdc__order__data">
        <div class="modal-header">
            <h4 class="modal-title mx-auto pl-5" id="modalOrderTitle">Your Data</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

            {{-- default fields --}}
            <fieldset>
                {{-- DEFAULT INPUTS --}}
                <div class="form-row fg__orderdata__default">
                    {{-- VISUALIZTION COMPONENT --}}
                    <div class="form-group aw__visualization__variants col-md-4  ml-auto">
                        <div class="aw__form__label">
                            <label>Visualization:</label>
                        </div>

                        <div class="aw__form__component">
                            <label class="form-check form-check-inline aw__visual__case" id="visualCaseShort">
                                <input class="form-check-input" type="radio" name="_visualization" value="0" disabled>
                                <div class="visualization__image"></div>
                            </label>
                            <label class="form-check form-check-inline aw__visual__case" id="visualCaseLong">
                                <input class="form-check-input" type="radio" name="_visualization" value="1" checked>
                                <div class="visualization__image"></div>
                            </label>
                        </div>
                    </div>
                    {{-- BACKGROUND COMPONENT --}}
                    <div class="form-group aw__user__bg__variant col-md-4 mr-auto">
                        <div class="aw__form__label">
                            <label>Background:</label>
                        </div>

                        <div class="aw__form__component">
                            <div class="order__data__background row m-auto">
                                <img class="img-fluid m-auto locked" src="https://avatars.mds.yandex.net/get-pdb/197794/c41f9fb8-3c6a-4348-b17e-7193bc8d41fe/s1200"
                                    alt="Background" width="96" height="96">
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

        </div>
        <div class="modal-footer p-0">
            <button type="button" class="btn btn-modal-v1" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>

@endsection



@push('scripts')
    <script src="{{asset('js/order_controls.js')}}"></script>
@endpush

{{-- {{dd($orders)}} --}}