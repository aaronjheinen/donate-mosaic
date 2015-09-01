<!DOCTYPE html>
<html>
    <head>
        <title>Donate App</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <link href="{{ URL::to('/') }}/css/app.css" rel="stylesheet" type="text/css">

    </head>
    <body class="donate admin">
        <div class="container"> 
            <div id="name-group" class="form-group">
                <label for="name">Set Name:</label>
                <input type="text" class="form-control" name="name" placeholder="Name" v-model="set.name" />
            </div>
            <div class="donate-overlay">
                @foreach ($set->squares as $square)
                    <span id="square-{{$square->id}}" class="donate-box {{$square->class}} x-{{str_pad($square->y, 2, '0', STR_PAD_LEFT)}} y-{{str_pad($square->x, 2, '0', STR_PAD_LEFT)}}" ></span>
                @endforeach
            </div>

            <img id="donate-img" src="{{ URL::to('/') }}/img/floorplan.jpg" alt="Unsplashed background img 2" style="width:100%;" />

            <form action="/" method="POST">
            
                <h6>Grid is {{$set->rows}} x {{$set->cols}} for a total of {{$set->rows*$set->cols}} spots</h6>
                <h3>@{{set.available}} boxes are currently available at $<input type="number" class="form-control-inline" name="number" placeholder="10.00" v-model="set.price"> per square.</h3>
                <h5>This allows for a total of <strong><span class="green-text">@{{set.available_price | currency}}</span></strong> to be raised.</h5>
                <button type="submit" class="btn btn-success">Update <span class="fa fa-arrow-right"></span></button>

            </form>
        </div>
    </body>
    <script> var rows = {{$set->rows}}; </script>
    <script src="{{ URL::to('/') }}/js/all.js"></script>
</html>
