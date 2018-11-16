@extends('layouts.rackV1')

@section('content')
<div class="container">
            <div class="content">


                {{-- {{ dd($compositions) }} --}}

                {{-- Composition Card --}}

                {{-- Compositions Main List Container --}}
                <div class="row align-items-start compcard__list__cont">
                    @foreach($compositions as $composition)
                        {{-- Composition  Area --}}
                        <div class="col-6 col-xl-4 comparea">
                            <div class="card">
                                {{-- Composition Card Place Freeze Picture --}}
                                <img class="card-img-top compcard-extlook-freeze" src="{{asset('storage/img/compositions') . '/' . $composition->compRequest->project_token . '.png'}}" alt="Composition" height="524">
                                {{-- Composition Card Place Preview Picture --}}
                                <img class="card-img-top compcard-extlook-preview" src="{{asset('storage/img/compositions') . '/' . $composition->compRequest->project_token . '.gif'}}" alt="Composition" height="524">
                                {{-- Composition Card Place Info Box Area --}}
                                <div class="card-block compcard-placer-infobox">
                                    <p class="compcard-ib-title">
                                        <a class="aw-link" href="#">{{$composition->compRequest->title}}</a>
                                    </p>
                                    <p class="compcard-ib-price">
                                        <span>
                                            $ {{$composition->compRequest->custom_price}}
                                        </span>
                                    </p>
                                    <p class="compcard-ib-author-nickname">
                                        <span>{{ $composition->compRequest->user->username }}</span>
                                    </p>
                                    <p class="compcard-ib-date-published">
                                        <span title="{{$composition->published_at->format('H:i:s')}}">{{$composition->published_at->format('d.m.Y')}}</span>
                                    </p>
                                </div>
                                {{-- Composition Card Links --}}
                                <div class="card-block compcard-placer-links">
                                    <a class="comp-link-req" href="{{ route('compositions-fbuy', $composition->id) }}"><span class="oi oi-cart" aria-hidden="true"></span></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>



                {{-- Pagination --}}
                {{ $compositions->links('compositions.pagination') }}
                    

            </div>
</div>
@endsection