<!DOCTYPE html>
<html>
    <head>
        <title>Donate App</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <link href="{{ URL::to('/') }}/css/app.css" rel="stylesheet" type="text/css">

    </head>
    <body class="donate user">
        <div class="container">
            <div class="donate-overlay">
                @foreach ($set->squares as $square)
                    <span id="square-{{$square->id}}" class="donate-box {{$square->class}} x-{{str_pad($square->y, 2, '0', STR_PAD_LEFT)}} y-{{str_pad($square->x, 2, '0', STR_PAD_LEFT)}}" ></span>
                @endforeach
            </div>

            <img id="donate-img" src="{{ URL::to('/') }}/img/floorplan.jpg" alt="Unsplashed background img 2" style="width:100%;" />

            <form action="/" method="POST">

                <div class="file-field input-field">
                  <input class="file-path validate" type="text"  />
                  <div class="btn">
                    <span>File</span>
                    <input v-el="image" type="file" v-on="change:upload" />
                  </div>
                </div>
                <div class="clearfix"></div>

                <div id="name-group" class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Bucky Badger">
                </div>

                <div id="email-group" class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" placeholder="bucky@wisc.edu">
                </div>

                <button type="submit" class="btn btn-success">Submit <span class="fa fa-arrow-right"></span></button>

            </form>
        </div>
    </body>
    <script src="{{ URL::to('/') }}/js/all.js"></script>
</html>
