@extends('base')
@section('content')
    @include('jav.includes.navbar')
    <div class="card-columns">
        @foreach ($items as $item)
            @include('jav.includes.movie')
        @endforeach
    </div>
    {{ $items->links() }}
@stop
