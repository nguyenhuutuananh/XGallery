@extends('base')
@section('content')
    <div class="row">
        <div class="col-4">
            {{ $items->links() }}
        </div>
    </div>
    <div class="card mb-3">
        <div class="row no-gutters">
            <div class="col-4">
                <a href="">
                    <img class="bd-placeholder-img card-img"
                         width="100%" src="{{$idol->getCover()}}" alt="{{$idol->name}}"/>
                </a>
            </div>
            <div class="col-8">
                <div class="card-body">
                    <h5 class="card-title mr-1"><strong>{{$idol->name}}</strong></h5>
                    <span class="badge badge-pill badge-primary">{{$items->total()}}</span>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item birthday">
                        <i class="fas fa-birthday-cake mr-1"></i><strong
                            class="mr-1">Birthday</strong>{{$idol->birthday}}
                    </li>
                </ul>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item birthday">
                        <i class="fas fa-tag mr-1"></i><strong class="mr-1">Blood type</strong>{{$idol->blood_type}}
                    </li>
                </ul>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item birthday">
                        <i class="fas fa-tag mr-1"></i><strong class="mr-1">City</strong>{{$idol->city}}
                    </li>
                </ul>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item birthday">
                        <i class="fas fa-tag mr-1"></i><strong class="mr-1">Height</strong>{{$idol->height}}
                    </li>
                </ul>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item birthday">
                        <i class="fas fa-tag mr-1"></i><strong class="mr-1">Breast</strong>{{$idol->breast}}
                    </li>
                </ul>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item birthday">
                        <i class="fas fa-tag mr-1"></i><strong class="mr-1">Waist</strong>{{$idol->waist}}
                    </li>
                </ul>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item birthday">
                        <i class="fas fa-tag mr-1"></i><strong class="mr-1">Hip</strong>{{$idol->hips}}
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row ">
        <div class="col-12">
            <div class="card-columns">
                @foreach ($items as $item)
                    @include('jav.includes.movie')
                @endforeach
            </div>
        </div>
    </div>
    {{ $items->links() }}
@stop
