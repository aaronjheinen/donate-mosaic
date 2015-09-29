@extends('admin')
@section('title', 'Admin Panel')
@section('body', 'donate admin-size')
@section('content')
    <div class="container" v-cloak> 
        <form action="/" method="POST">
            <div class="row">
                <div class="col s12 m6">
                    <div id="name-group" class="form-group">
                        <label for="name">Horizontal Size (Columns):</label>
                        <input type="number" class="form-control" name="name" placeholder="Name" v-model="set.cols" debounce="500" />
                    </div>
                </div>
                <div class="col s12 m6">
                    <div id="name-group" class="form-group">
                        <label for="name">Vertical Size (Rows):</label>
                        <input type="number" class="form-control" name="name" placeholder="Name" v-model="set.rows" debounce="500" />
                    </div>
                </div>
            </div>

            <h6>Grid is @{{set.rows}} x @{{set.cols}} for a total of @{{set.rows*set.cols}} blocks</h6>

            <div class="donate-container">
                <div class="donate-overlay">
                    <span class="donate-box" v-repeat="square in set.squares"></span>
                </div>

                <img id="donate-img" src="{{ URL::to('/') }}/img/floorplan.jpg" alt="Unsplashed background img 2" style="width:100%;" />
            </div>

            <button type="submit" class="right btn">Update</button>

        </form>
    </div>
@endsection