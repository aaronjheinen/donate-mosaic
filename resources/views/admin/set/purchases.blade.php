@extends('admin')
@section('title', 'Admin Panel')
@section('body', 'donate set-purchases')
@section('content')
    <div class="container" v-cloak> 
        <h3>Move purchase positions</h3>
        <p>Click on a Purchase to move it to another position. Click on the new position to save it there.</p>
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

        </form>
    </div>
@endsection