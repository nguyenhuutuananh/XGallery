<div class="row mb-2">
    <div class="col-12">
        <nav class="navbar navbar-dark bg-dark">
            <form class="form-inline" method="get" action="{{route('jav.dashboard.view')}}">
                @csrf
                <input class="form-control input-sm mr-sm-2" type="text" name="keyword"
                       placeholder="Enter keyword" aria-label="Search"
                       value="{{request()->request->get('keyword')}}">
                <input class="form-control input-sm mr-sm-2" type="text" name="director"
                       placeholder="Search by director" aria-label="Search"
                       value="{{request()->request->get('director')}}">
                <input class="form-control input-sm mr-sm-2" type="text" name="studio"
                       placeholder="Search by studio" aria-label="Search"
                       value="{{request()->request->get('studio')}}">
                <input class="form-control input-sm mr-sm-2" type="text" name="label"
                       placeholder="Search by label" aria-label="Search"
                       value="{{request()->request->get('label')}}">

                <label for="sort-by"></label><select class="custom-select form-control input-sm mr-sm-2" id="sort-by"
                                                     name="sort-by">
                    <option @if(request()->request->get('sort-by','release_date') == 'id') selected @endif value="id">
                        ID
                    </option>
                    <option @if(request()->request->get('sort-by','release_date') == 'release_date') selected
                            @endif value="release_date">Release date
                    </option>
                    <option @if(request()->request->get('sort-by','release_date') == 'is_downloadable') selected
                            @endif value="is_downloadable">Downloadable
                    </option>
                </select>

                @include('includes.form.pagination')

                <input type="hidden" name="genre" value="{{request()->request->get('genre')}}">
                <input type="hidden" name="idol" value="{{request()->request->get('idol')}}">
                <button class="btn btn-primary btn-sm my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </nav>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active"
                    aria-current="page"><a href="{{route('jav.dashboard.view')}}"><i class="fas fa-home"></i></a></li>
                @if (request()->request->get('idol'))
                    <li class="breadcrumb-item active"
                        aria-current="page">{{\App\Models\JavIdols::find(request()->request->get('idol'))->name}}</li>
                @endif
                @if (request()->request->get('genre'))
                    <li class="breadcrumb-item active"
                        aria-current="page">{{\App\Models\JavGenres::find(request()->request->get('genre'))->name}}</li>
                @endif
            </ol>
        </nav>


    </div>
</div>
