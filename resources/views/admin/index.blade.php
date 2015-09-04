@extends('admin')
@section('title', 'Admin Panel')
@section('content')
<div class="container" v-cloak> 
    <form action="/" method="POST">

        <div id="name-group" class="form-group">
            <label for="name">Set Name:</label>
            <input type="text" class="form-control" name="name" placeholder="Name" v-model="set.name" />
        </div>

        <h5>@{{set.available}} boxes are currently available at $<input type="number" class="form-control-inline" name="number" placeholder="10.00" v-model="set.price"> per square.</h5>
        <h4>This allows for a total of <strong><span class="green-text">@{{set.available_price | currency}}</span></strong> to be raised.</h4>

        <div class="donate-overlay">
            @foreach ($set->squares as $square)
                @if(count($square->purchase) > 0)
                    @include('square.taken', ['square' => $square])
                @else
                    @include('square.available', ['square' => $square])
                @endif
            @endforeach
        </div>

        <img id="donate-img" src="{{ URL::to('/') }}/img/floorplan.jpg" alt="Unsplashed background img 2" style="width:100%;" />

        <h6>Grid is {{$set->rows}} x {{$set->cols}} for a total of {{$set->rows*$set->cols}} spots</h6>
        <button type="submit" class="right btn">Update <span class="fa fa-arrow-right"></span></button>

    </form>
</div>
@endsection