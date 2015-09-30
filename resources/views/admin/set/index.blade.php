@extends('admin')
@section('title', 'Admin Panel')
@section('body', 'donate donate-admin')
@section('content')
    <div class="container" v-cloak> 
        <p>Select the boxes which should be hidden to the user. This allows you to upload a non-rectangular image and have the grid align with it.</p>
        <form action="/" method="POST">
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


            <h6>Grid is {{$set->rows}} x {{$set->cols}} for a total of {{$set->rows*$set->cols}} spots</h6>
            <button type="submit" class="right btn">Update</button>

        </form>
    </div>
@endsection