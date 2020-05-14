<div class="row mb-2">
    <div class="col-12">
        <nav class="navbar navbar-dark bg-dark">
            @php
                $filter = request()->request->get('filter');
            @endphp
            <form class="form-inline" method="post" action="{{route('jav.dashboard.view')}}">
                @csrf
                <input class="form-control input-sm mr-sm-2" type="text" name="keyword"
                       placeholder="Enter your search keyword" aria-label="Search"
                       value="{{request()->request->get('keyword')}}">
                <input class="form-control input-sm mr-sm-2" type="text" name="filter[director]"
                       placeholder="Search by director" aria-label="Search"
                       value="{{$filter['director'] ?? null}}">
                <input class="form-control input-sm mr-sm-2" type="text" name="filter[studio]"
                       placeholder="Search by studio" aria-label="Search"
                       value="{{$filter['studio'] ?? null}}">
                <input class="form-control input-sm mr-sm-2" type="text" name="filter[label]"
                       placeholder="Search by label" aria-label="Search"
                       value="{{$filter['studio'] ?? null}}">

                <select class="custom-select form-control input-sm mr-sm-2" id="sort-by" name="sort-by">
                    <option @if(request()->request->get('sort-by','release_date') == 'id') selected @endif value="id">ID</option>
                    <option @if(request()->request->get('sort-by','release_date') == 'release_date') selected
                            @endif value="release_date">Release date
                    </option>
                    <option @if(request()->request->get('sort-by','release_date') == 'is_downloadable') selected
                            @endif value="is_downloadable">Downloadable
                    </option>
                </select>

                @include('includes.form.pagination')

                <button class="btn btn-primary btn-sm my-2 my-sm-0" type="submit">Search</button>
            </form>
        </nav>
    </div>
</div>
