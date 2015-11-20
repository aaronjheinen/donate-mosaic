@extends('admin')
@section('title', 'Admin Panel')
@section('body', 'donate generate-image')
@section('content')
<div v-cloak> 
    <a href="#" class="btn" v-on:click="generateImage">Generate Image</a>
    <div id="donate-overlay-div" class="donate-container">
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
        <img id="donate-img" src="{{ URL::to('/') }}/img/floorplan.jpg" style="width:100%;" />
    </div>
    <canvas id="canvas" width="2000" height="1500"></canvas>
    <div id="image"></div>

</div>
@endsection