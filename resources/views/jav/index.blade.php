@extends('base')
@section('content')
    <div class="card-columns">
        @foreach ($items as $item)
            @include('jav.includes.movie');
        @endforeach
    </div>
    {{ $items->links() }}
@stop
