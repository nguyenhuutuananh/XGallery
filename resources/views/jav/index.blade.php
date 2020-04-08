@extends('base')
@section('content')
    <div class="card-columns">
        @foreach ($items as $item)
            <div class="card">
                @if(!empty($item->cover))
                    <a href="{{route('movie.view',$item->id)}}"><img class="bd-placeholder-img card-img-top"
                                                                     width="100%" src="{{$item->cover}}"/></a>
                @endif
                <div class="card-body">
                    <a href="{{route('movie.view',$item->id)}}">
                        @if(!empty($item->name))<h5 class="card-title mr-1"><strong>{{$item->name}}</strong></h5>@endif
                        <span class="badge badge-primary">{{$item->item_number}}</span>
                    </a>
                    @if(!empty($item->description))
                        <p class="card-text">{{$item->description}}</p>
                    @endif
                    <ul class="list-group list-group-flush">
                        @if(!empty($item->directory))
                            <li class="list-group-item director">
                                <i class="fas fa-user mr-2"></i><strong class="mr-1">Directory</strong><a
                                    href="">{{$item->directory}}</a>
                            </li>
                        @endif
                        @if(!empty($item->studio))
                            <li class="list-group-item studio">
                                <i class="fas fa-tag mr-2"></i><strong class="mr-1">Studio</strong><a
                                    href="">{{$item->studio}}</a>
                            </li>
                        @endif
                        @if(!empty($item->label))
                            <li class="list-group-item label">
                                <i class="fas fa-tag mr-2"></i><strong class="mr-1">Label</strong><a
                                    href="">{{$item->label}}</a>
                            </li>
                        @endif
                        <li class="list-group-item tag">
                            <i class="fas fa-tags"></i>
                            @foreach ($item->genres() as $genre)
                                    <span class="badge badge-pill badge-dark">{{$genre->name}}</span>
                                @endforeach
                        </li>
                        @if($item->idols()->count() > 0)
                            <li class="list-group-item actress">
                                <i class="fas fa-female"></i>
                                @foreach ($item->idols() as $idol)
                                    <span class="badge-pill badge-info">{{$idol->name}}</span>
                               @endforeach
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="card-footer">
                    <small class="text-muted"><i class="far fa-calendar-alt mr-1"></i>{{$item->release_date}}</small>
                    @if($item->is_downloadable == 1)
                        <span class="float-right">
                         <button type="button" class="btn btn-primary btn-sm ajax-pool"
                                 data-ajax-url=""
                                 data-ajax-command="download"
                                 data-movie-id=""
                         >
                        <i class="fas fa-download mr-1"></i>Download
                        </button>
                    </span>
                    @endif
                </div>
            </div>
        @endforeach


    </div>
    {{ $items->links() }}
@stop
