@extends('base')
@section('content')
    <div class="row">
        <div class="col-4">
            {{ $items->links() }}
        </div>
    </div>

    <div class="card-columns">
        @foreach ($items as $item)
            @include('jav.includes.movie');
        @endforeach
    </div>
    {{ $items->links() }}
@stop
