@extends('admin')
@section('title', 'Admin Panel')
@section('body', 'donate donate-admin')
@section('content')
<div class="container-full" v-cloak> 
    <a href="#" class="btn" v-on="click: generateImage">Generate Image</a>
        <canvas id="canvas" width="2000" height="1500"></canvas>
        <div id="image"></div>
        <div id="donate-overlay-full" class="donate-overlay">
            @foreach ($set->squares as $square)
                @if(count($square->purchase) > 0)
                    @include('square.taken', ['square' => $square])
                @else
                    @include('square.available', ['square' => $square])
                @endif
            @endforeach
        </div>

</div>
@endsection