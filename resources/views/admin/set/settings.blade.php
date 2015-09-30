@extends('admin')
@section('title', 'Admin Panel')
@section('body', 'donate set-settings')
@section('content')
    <div class="container" v-cloak> 
        <form action="/" method="POST">

            <div id="name-group" class="form-group">
                <label for="name">Set Name:</label>
                <input type="text" class="form-control" name="name" placeholder="Name" v-model="set.name" />
            </div>

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

            <h6>Grid is @{{set.cols}} x @{{set.rows}} for a total of @{{set.rows*set.cols}} blocks</h6>

            <h5>@{{set.available}} boxes are currently available at $<input type="number" class="form-control-inline" name="number" placeholder="10.00" v-model="set.price"> per square.</h5>
            <h4>This allows for a total of <strong><span class="green-text">@{{set.available_price | currency}}</span></strong> to be raised.</h4>

            <div class="donate-container">
                <div class="donate-overlay">
                    <span class="donate-box" v-repeat="square in set.squares"></span>
                </div>

                <img id="donate-img" src="{{ URL::to('/') }}/img/floorplan.jpg" alt="Unsplashed background img 2" style="width:100%;" />
            </div>

            <button type="submit" class="right btn">Update</button>

            <!-- Modal Structure -->
            <div id="modalConfirm" class="modal">
                <div class="modal-content">
                  <h4>Warning!</h4>
                  <p>You've changed the size of the grid. If there are purchases currently on your grid that are cut off by the new size they will be moved to the first available spot. Is this OK?</p>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-close btn" v-on="click: submitForm">This is what I want to do</a>
                    <a href="#!" class="modal-action modal-close waves-effect waves-red btn-flat">Nevermind</a>
                </div>
            </div>

        </form>
    </div>
@endsection