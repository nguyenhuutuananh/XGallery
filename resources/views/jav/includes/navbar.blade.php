<div class="row mb-2">
    <div class="col-12">
        <nav class="navbar navbar-dark bg-dark">
            <form class="form-inline" method="post" action="{{route('jav.search.view')}}">
                @csrf
                <input class="form-control input-sm mr-sm-2" type="text" name="keyword" placeholder="Enter your search keyword" aria-label="Search" value="{{request()->get('keyword')}}">
                <input class="form-control input-sm mr-sm-2" type="text" name="director" placeholder="Enter your search director" aria-label="Search" value="{{request()->get('director')}}">
                <input class="form-control input-sm mr-sm-2" type="text" name="studio" placeholder="Enter your search studio" aria-label="Search" value="{{request()->get('studio')}}">
                <input class="form-control input-sm mr-sm-2" type="text" name="label" placeholder="Enter your search label" aria-label="Search" value="{{request()->get('label')}}">

                <select class="custom-select form-control input-sm mr-sm-2" id="sort-by" name="sort-by">
                    <option @if(request()->get('sort-by','release_date') == 'id') selected @endif value="id">ID</option>
                    <option @if(request()->get('sort-by','release_date') == 'release_date') selected @endif value="release_date">Release date</option>
                    <option @if(request()->get('sort-by','release_date') == 'is_downloadable') selected @endif value="is_downloadable">Downloadable</option>
                </select>
                <select class="custom-select form-control input-sm mr-sm-2" id="sort-dir" name="sort-dir">
                    <option @if(request()->get('sort-dir','desc') == 'asc') selected @endif value="asc">Asc</option>
                    <option @if(request()->get('sort-dir','desc') == 'desc') selected @endif value="desc">Desc</option>
                </select>
                <select class="custom-select form-control input-sm mr-sm-2" id="perPage" name="per-page">
                    <option @if(request()->get('per-page') == 15) selected @endif value="15">15</option>
                    <option @if(request()->get('per-page') == 30) selected @endif value="30">30</option>
                    <option @if(request()->get('per-page') == 60) selected @endif value="60">60</option>
                    <option @if(request()->get('per-page') == 120) selected @endif value="120">120</option>
                </select>

                <button class="btn btn-primary btn-sm my-2 my-sm-0" type="submit">Search</button>
            </form>
        </nav>
    </div>
</div>
