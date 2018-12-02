{{-- Payments --}}
<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-title">
            <a data-toggle="collapse" class="aw-link ahead_monofiska" data-parent="#accordion" href="#collapsePayments">Payments</a>
        </h5>
    </div>
    <div id="collapsePayments" class="panel-collapse collapse">
        <div class="panel-body">
            <table class="table">
                <tr>
                    <td>
                        <a class="aw-link aiteen_monofiska" href="{{route('payments-in-index')}}">Pay In</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a class="aw-link aiteen_monofiska" href="{{route('payments-out-index')}}">Pay Out</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
{{-- History --}}
<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-title">
            <a class="aw-link ahead_monofiska" href="{{route('payments-history')}}">History</a>
        </h5>
    </div>
</div>