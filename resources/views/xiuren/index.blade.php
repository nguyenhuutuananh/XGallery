@extends('base')
@section('content')
    <div class="card-columns">
        @foreach ($items as $item)
            <div class="card">
                @if(!empty($item->cover))
                    <img class="bd-placeholder-img card-img-top" src="{{$item->cover}}"/>
                @endif
                <div class="card-body">

                </div>
                <div class="card-footer">
                    <small class="text-muted">
                         <span class="float-right">
                         <button type="button" class="btn btn-primary btn-sm ajax-pool"
                                 data-ajax-url="{{route('xiuren.download.request', $item->id)}}"
                                 data-ajax-command="download"
                         >
                        <i class="fas fa-download mr-1"></i>Download
                        </button>
                    </span>
                    </small>
                </div>
            </div>
        @endforeach
    </div>
    {{ $items->links() }}
@stop
