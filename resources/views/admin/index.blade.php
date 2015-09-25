@extends('admin')
@section('title', 'Admin Panel')
@section('body', 'donate admin-edit-content')
@section('content')
    <div class="container"> 
        <form action="/" method="POST">

            <h4>{{$set->name}}</h4>
            <div class="stats row">
                <div class="col s12 m4">
                    <div class="card grey lighten-5">
                        <div class="card-content">
                            <h4 class="center-align">{{count($set->purchases)}}</h4>
                            <p class="center-align">donations have been collected.</p>
                        </div>
                    </div>
                </div>
                <div class="col s12 m4">
                    <div class="card grey lighten-5">
                        <div class="card-content">
                            <h4 class="center-align">{{$purchased_squares}}</h4>
                            <p class="center-align">squares have been purchased.</p>
                        </div>
                    </div>
                </div>
                <div class="col s12 m4">
                    <div class="card grey lighten-5">
                        <div class="card-content">
                            <h4 class="center-align green-text">${{count($squares) * $set->price}}</h4>
                            <p class="center-align">total has been raised.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="donate-container">
                <div class="donate-overlay">
                    @foreach ($set->squares as $square)
                        @if(count($square->purchase) > 0)
                            @if(isset($square->purchase[0]->media))
                                @include('square.taken.media', ['square' => $square])
                            @else
                                @include('square.taken.index', ['square' => $square])
                            @endif
                        @else
                            @include('square.available', ['square' => $square])
                        @endif
                    @endforeach
                </div>

                <img id="donate-img" src="{{ URL::to('/') }}/img/floorplan.jpg" alt="Unsplashed background img 2" style="width:100%;" />
            </div>
            <div data-editable data-name="main-content">
                <blockquote>
                    Always code as if the guy who ends up maintaining your code will be a violent psychopath who knows where you live.
                </blockquote>
                <p>John F. Woods</p>
            </div>
        </form>
    </div>
@endsection