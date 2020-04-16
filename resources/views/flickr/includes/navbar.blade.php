<div class="row mb-2">
    <div class="col-12">
        <nav class="navbar navbar-dark bg-dark">
            <form class="form-inline" method="post" action="{{route('flickr.download.request')}}">
                @csrf
                <input class="form-control input-sm mr-sm-2" type="text" name="url" placeholder="" aria-label="Search" value="{{request()->get('keyword')}}">
                <button class="btn btn-primary btn-sm my-2 my-sm-0" type="submit"><i class="fas fa-download"></i> Download</button>
            </form>
        </nav>
    </div>
</div>
