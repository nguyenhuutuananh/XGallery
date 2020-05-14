@extends('base')
@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-female"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Idols</span>
                    <span class="info-box-number">
                  {{\App\Models\JavIdols::count()}}
                </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-tags"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Genres</span>
                    <span class="info-box-number">{{\App\Models\JavGenres::count()}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-video"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">JAV</span>
                    <span class="info-box-number">{{\App\Models\JavMovies::count()}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-download"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Downloadable</span>
                    <span class="info-box-number">{{\App\Models\JavMovies::where(['is_downloadable'=>1])->count()}}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <div class="row">
        @foreach($endpoints as $endpoint)
            <div class="col-md-4">
                <!-- Info Boxes Style 2 -->
                <div class="info-box mb-3 bg-gray-light">
                    <span class="info-box-icon"><i class="fas fa-list-ol"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">{{$endpoint->crawler}}</span>
                        <span class="info-box-number">{{$endpoint->page ?? 1}}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                    {{$endpoint->url}}
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
        @endforeach
    </div>
@stop
