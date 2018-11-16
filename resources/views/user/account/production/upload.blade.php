@extends('layouts.rackV1')

@section('content')


    <div class="container">

        <div class="row">

            {{-- ACCOUNT SIDEBAR --}}
            @include('user.account.production.sidebar')

            {{-- ACCOUNT SECTION --}}
            <div class="col-sm-8 col-md-9 aw_acc_section">
                <div>
                    <h2 class="text-center">Uploader</h2>

                    <div class="profile-content">
                        {{-- messages --}}
                        <div class="col-md-10 offset-md-1">
                            {{-- note --}}
                            {{-- <div class="alert alert-info" role="alert">
                                Hey! Please read a <a href="#">specification</a> before you upload your artwork template, otherwise, in case of errors, it'll may have to be declined.
                            </div> --}}
                            {{-- errors --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        {{-- uploader --}}
                        <div class="col-md-8 offset-md-2">
                            <form action="{{ route('acc-prod-sendcomprequest') }}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="FCUploaderProject">Your project: </label>
                                    <input name="_project" type="file" accept="application/zip" class="form-control-file" id="FCUploaderProject">
                                </div>
                                <div class="form-group">
                                    <label for="FCUploaderReceive">You receive: </label>
                                    <input name="_receive" type="text" class="form-control" id="FCUploaderReceive" placeholder="Price...">
                                </div>
                                <button type="submit" class="btn btn-dark justify-content-end">Upload</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>





        </div>

    </div>





@endsection