@extends('layouts.sidebar-section')

{{-- SIDEBAR --}}
@section('sidebar')

    {{-- STATS GROUP --}}
    <div class="sidebar-group">
        <div class="panel panel-default">
            <h4 class="sidebar-panel-item">
                <a href="#" class="aw-link">{{$compDataForm->compRequest->title}}</a>
            </h4>
        </div>
        <div class="panel panel-default">
            <p class="sidebar-panel-item">$ 
                <span>{{$compDataForm->compRequest->custom_price}}</span>
            </p>
        </div>
        <div class="panel panel-default">
            <p class="sidebar-panel-item">
                <span>{{$compDataForm->published_at->format('d.m.Y')}}</span>
                <span>{{$compDataForm->published_at->format('H:i')}}</span>
            </p>
        </div>
    </div>

@endsection

{{-- SECTION --}}
@section('section')
    <div class="compcard__form__cont">
        
            {{-- ERRORS --}}
            <div class="compcard__form__cont__errors">
                @if ($messages->any())
                    <div class="aw-alert aw-alert-danger">
                        <h2 class="aw-alert-danger-h">Unavailable action. Reasons:</h2>
                        <ul>
                            @foreach ($messages->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- FORM --}}
            <form action="{{route('compositions-form-buy')}}" method="POST">
                    @csrf
                    <div class="form-group container aw-form-default-inputs">
                        <input id="compositionHash" type="hidden" name="_comphash" value="{{$compDataForm->compRequest->project_token}}">

                        {{-- VISUALIZTION CONTROLLER --}}
                        <div class="form-group aw__visualization__variants">
                            <div class="aw__form__label">
                                <label>Visualization:</label>
                            </div>

                            {{-- Visualization Component --}}
                            <div class="aw__form__component">
                                @if ($compDataForm->compRequest->visualization == 2)
                                    <label class="form-check form-check-inline aw__visual__case" id="visualCaseShort">
                                        <input class="form-check-input" type="radio" name="_visualization" value="0" >
                                        <div class="visualization__image"></div>
                                    </label>
                                    <label class="form-check form-check-inline aw__visual__case" id="visualCaseLong">
                                        <input class="form-check-input" type="radio" name="_visualization" value="1">
                                        <div class="visualization__image"></div>
                                    </label>
                                @else
                                    <label class="form-check form-check-inline aw__visual__case" id="visualCaseShort">
                                        <input class="form-check-input" type="radio" name="_visualization" value="0" @if ($compDataForm->compRequest->visualization == 1) disabled @endif>
                                        <div class="visualization__image"></div>
                                    </label>
                                    <label class="form-check form-check-inline aw__visual__case" id="visualCaseLong">
                                        <input class="form-check-input" type="radio" name="_visualization" value="1" @if ($compDataForm->compRequest->visualization == 0) disabled @endif>
                                        <div class="visualization__image"></div>
                                    </label>
                                @endif
                            </div>
                        </div>

                        {{-- BACKGROUNDS LIST CONTROLLER --}}
                        <div class="form-group user__bg__cont">
                            <div class="aw__form__label">
                                <label>Background:</label>
                            </div>

                            {{-- User Backgrounds Component --}}
                            <div class="aw__form__component">
                                <input id="userBackgroundInput" type="hidden" name="_background">
                                
                                <button class="btn aw-btn-smart-v2" type="button" data-toggle="collapse"
                                        data-target="#collapseUserBackgrounds" aria-expanded="true"
                                        aria-controls="collapseUserBackgrounds"
                                        data-trigger="hover" title=""
                                        data-content="<img src='{{asset('storage/img/question_mark.svg')}}' weight='96' height='96' style='background-color: black;' />"
                                        data-original-title="Current background">Choose Background
                                </button>
    
                                {{-- Interface Of Background List --}}
                                <div class="collapse" id="collapseUserBackgrounds">
                                    <div class="card card-body">
    
                                        <!-- Управление списком -->
                                        <div class="user__bg__controller">
                                            <div class="user-bg-controls-group">
                                                <button type="button" id="refreshUserBackgroundsList" class="btn btn-dark" data-link="{{substr(route('get-steam-backgrounds'), strlen(url('/')))}}">Refresh</button>
                                            </div>
                                        </div>
                                        <!-- Список фонов -->
                                        <div class="user__bg__body">
                                            <div class="user-bg-list-layer">
                                                <ul></ul>
                                            </div>
                                        </div>
    
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group container aw-form-controls">
                        <button class="btn aw-btn-smart-v3" id="buyCompositionBtn" type="button" data-link="{{substr(route('compositions-form-buy'), strlen(url('/')))}}">Buy</button>
                    </div>
            </form>

    </div>
@endsection


@push('scripts')
    <script src="{{asset('js/backgrounds_load.js')}}"></script>
    <script src="{{asset('js/compositions_form.js')}}"></script>
@endpush