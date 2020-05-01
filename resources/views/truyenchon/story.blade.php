@extends('base')
@section('content')
    @include('truyenchon.includes.navbar')
    <h1>{{$story->title}}</h1>
    @foreach ($items as $item)
        <img class="lazy" data-src="{{$item}}"/>
    @endforeach

    <a href="{{route('truyenchon.story.view',['id'=> $story->id, 'chapter'=> $next])}}">Next</a>
@stop
