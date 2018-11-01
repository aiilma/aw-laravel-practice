            <div class="col-sm-4 col-md-3 aw_acc_sidebar">
                <div id="accordion">
                    {{-- Account --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5 class="panel-title">
                                <a data-toggle="collapse" class="ahead_monofiska" data-parent="#accordion" href="#collapseAccount">Account</a>
                            </h5>
                        </div>
                        <div id="collapseAccount" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table">
                                    <tr>
                                        <td>
                                            <a class="aiteen_monofiska" href="{{route('account.home')}}">My account</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- Production --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5 class="panel-title">
                                <a data-toggle="collapse" class="ahead_monofiska" data-parent="#accordion" href="#collapseProduction">Production</a>
                            </h5>
                        </div>
                        <div id="collapseProduction" class="panel-collapse collapse">
                            <div class="panel-body">
                                <table class="table">
                                    <tr>
                                        <td>
                                            <a class="aiteen_monofiska" href="{{route('account.production.upload_show')}}">Uploader</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <a class="aiteen_monofiska" href="{{route('account.production.requests_show')}}">Requests</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{-- Specification --}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5 class="panel-title">
                                <a class="ahead_monofiska" href="/specification">Specification</a>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>