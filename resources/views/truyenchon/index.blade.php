@extends('base')
@section('content')
    @include('truyenchon.includes.navbar')
    <div class="card-columns">
        @foreach ($items as $item)
            <div class="card">
                <img class="bd-placeholder-img card-img-top" src="{{$item->getCover()}}"/>
                <div class="card-body">
                    <a href="">
                        <h5 class="card-title mr-1"><strong>{{$item->title}}</strong></h5>
                    </a>
                </div>
                <div class="card-footer">
                    <small class="text-muted">
                        @foreach($item->chapters as $key => $chapter)
                            <a href="{{route('truyenchon.story.view', ['id'=>$item->id, 'chapter'=>$key])}}"><span
                                    class="badge badge-primary">{{$key}}</span>
                            </a>
                        @endforeach
                    </small>
                    @if(config('app.adult.download'))
                        <span class="float-right">
                         <button type="button" class="btn btn-primary btn-sm ajax-pool"
                                 data-ajax-url="{{route('truyenchon.download.request', $item->id)}}"
                                 data-ajax-command="download"
                         >
                        @include('macros.general.download')
                    </span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    {{ $items->links() }}
@stop
