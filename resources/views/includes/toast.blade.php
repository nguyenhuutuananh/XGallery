<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <strong class="mr-auto">{{$title ?? null}}</strong>
        <span class="badge badge-{{strtolower($status)}}"><small>{{$status ?? null}}</small></span>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        {!!$message ?? null!!}
    </div>
</div>
