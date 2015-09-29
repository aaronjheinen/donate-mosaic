@extends('admin')
@section('title', 'Admin Panel')
@section('body', 'donate admin-size')
@section('content')
    <div class="container"> 
        <form action="/" method="POST">
            <div class="row">
                <div class="col s12 m6">
                    <div id="name-group" class="form-group">
                        <label for="name">Horizontal Size (Columns):</label>
                        <input type="text" class="form-control" name="name" placeholder="Name" v-model="set.cols" />
                    </div>
                </div>
                <div class="col s12 m6">
                    <div id="name-group" class="form-group">
                        <label for="name">Vertical Size (Rows):</label>
                        <input type="text" class="form-control" name="name" placeholder="Name" v-model="set.cols" />
                    </div>
                </div>
            </div>

            <h6>Grid is {{$set->rows}} x {{$set->cols}} for a total of {{$set->rows*$set->cols}} spots</h6>

            <div class="donate-container">
                <div class="donate-overlay">
                    @foreach ($set->squares as $square)
                        @include('square.available', ['square' => $square])
                    @endforeach
                </div>

                <img id="donate-img" src="{{ URL::to('/') }}/img/floorplan.jpg" alt="Unsplashed background img 2" style="width:100%;" />
            </div>

            <button type="submit" class="right btn">Update</button>

        </form>
    </div>
@endsection