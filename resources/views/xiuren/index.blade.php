@extends('base')
@section('content')
    {{ $items->links() }}
    <div class="card-columns">
        @foreach ($items as $item)
            <div class="card">
                @include('macros.card.cover')
                <div class="card-body">

                </div>
                <div class="card-footer">
                    <small class="text-muted">
                        <span class="badge badge-primary">{{count($item->images)}}</span>
                    </small>
                    @if(config('app.adult.download'))
                        <span class="float-right">
                         <button type="button" class="btn btn-primary btn-sm ajax-pool"
                                 data-ajax-url="{{route('xiuren.download.request', $item->id)}}"
                                 data-ajax-command="download"
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
