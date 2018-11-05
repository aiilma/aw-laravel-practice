@extends('layouts.rackV1')

@section('content')
<div class="container">
            <div class="content">
                <div class="title m-b-md">
                    <pre>
                        {{ print_r($compositionsList) }}
                    </pre>
                </div>

            </div>
</div>
@endsection
