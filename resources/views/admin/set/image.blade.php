@extends('admin')
@section('title', 'Admin Panel')
@section('body', 'donate generate-image')
@section('content')
<div v-cloak> 
    <div class="container">
        <a href="#" class="btn pull-right" v-on:click="generateImage" v-if="!generated">Generate Image</a>
        <h3>Generate the Static Image</h3>
        <hr />
        <p>Generating this image allows for a faster loadtime on the front end so only one image has to download, not all the individual larger resolution user images. Those will then lazy-load.</p>
    </div>
    <div id="donate-overlay-div" class="donate-container" v-if="!generated">
        <div class="donate-overlay">
            @foreach ($set->squares as $square)
                @if(count($square->purchase) > 0)
                    @if(isset($square->purchase[0]->media))
                        <span id="square-{{$square->id}}" class="donate-box preload {{$square->class}} {{$square->status}} has-image" style="background-image:url('{{ $square->purchase[0]->media->thumburl }}');" ></span>
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
    <canvas id="canvas" width="1280" height="600" v-if="!generated"></canvas>
    <div id="image"></div>

</div>
@endsection