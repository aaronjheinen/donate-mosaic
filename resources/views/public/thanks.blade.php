<!DOCTYPE html>
<html>
    <head>
        <title>Donate App</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name=viewport content="width=device-width, initial-scale=1">

        <link href="{{ URL::to('/') }}/css/app.css" rel="stylesheet" type="text/css">

    </head>
    <body class="donate user-thanks">
        <div class="cloak">
            <div class="container">
                <h2 class="center-align">Thank you {{$purchase->name}}!</h2>
                <h4 class="center-align">Your donation of <strong><span class="green-text">${{$purchase->price}}</span></strong> was successful!</h4>
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
            </div> <?php /* .container */ ?>
            <div class="container-gray">
                <div class="container padding-bottom">
                    <div class="row">
                        <div class="col s12">
                            <h3 class="center-align">Thank you for your donation to {{$set->name}}!</h3>
                            <h4>Tell your friends about it!</h4>
                            <p>Todo - insert social media here</p>
                        </div>
                    </div>
                </div> <?php /* .container */ ?>
            </div> <?php /* .container-gray */ ?>
        </div>
    </body>
    <script src="{{ URL::to('/') }}/js/all.js"></script>
</html>
