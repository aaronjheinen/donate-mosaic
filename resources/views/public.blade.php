<!DOCTYPE html>
<html>
    <head>
        <title>Donate App</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <link href="{{ URL::to('/') }}/css/app.css" rel="stylesheet" type="text/css">

        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    </head>
    <body class="donate user">
        <div class="container">
            <div class="donate-overlay">
                @foreach ($set->squares as $square)
                    <span id="square-{{$square->id}}" class="donate-box {{$square->class}} {{$square->status}} x-{{str_pad($square->y, 2, '0', STR_PAD_LEFT)}} y-{{str_pad($square->x, 2, '0', STR_PAD_LEFT)}}" ></span>
                @endforeach
            </div>

            <img id="donate-img" src="{{ URL::to('/') }}/img/floorplan.jpg" alt="Unsplashed background img 2" style="width:100%;" />

            <form action="/" method="POST">
            <div class="row">
                <div class="col s12 m6 l6">
                    <img class="thumbnail left" src="" v-attr="src: img_url" v-if="img_url" />
                    <img class="thumbnail left" src="http://placehold.it/200x150" v-if="!img_url" />
                    <div class="file-field input-field">
                      <input class="file-path validate" type="text"  />
                      <div class="btn">
                        <span>File</span>
                        <input v-el="image" type="file" name="image" v-on="change:upload" />
                      </div>
                    </div>
                </div>
                <div class="col s12 m6 l6">
                    <h4 v-if="chosen.length == 0">Choose a box to get started. Each Box is worth <strong><span class="green-text">@{{set.price | currency}}</span></strong></h4>
                    <h4 v-if="chosen.length > 0">You have chosen <strong>@{{chosen.length}}</strong> boxes which correlates to <strong><span class="green-text">@{{purchase.price | currency}}</span></strong>.</h4>
                    <div id="email-group" class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" v-model="purchase.email" placeholder="bucky@wisc.edu">
                    </div>
                </div>
            </div>
                
            <div class='card-wrapper'></div>
            <div class="row">

                <span class="payment-errors"></span>
                <div class="col s12 m6 l6">
                    <div id="name-group" class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" v-model="purchase.name" placeholder="Bucky Badger">
                    </div>
                </div>
                <div class="col s12 m6 l6">
                    <div id="number-group" class="form-group">
                        <label for="number">Credit Card Number</label>
                        <input type="text" name="number" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col s6 m4 l4">
                    <div id="expiry-group" class="form-group">
                        <label for="expiry">Expiration Date</label>
                        <input type="text" name="expiry" />
                    </div>
                </div>
                <div class="col s6 m4 l4">
                    <div id="cvc-group" class="form-group">
                        <label for="cvc">CVC Code</label>
                        <input type="text" name="cvc" />
                    </div>
                </div>
                <div class="col s6 m4 l4">
                    <button id="btn_submit" type="submit" class="btn right">Purchase <span class="fa fa-arrow-right"></span></button>
                </div>
            </div>
            </form>
        </div>
    </body>
    <script>
        Stripe.setPublishableKey('{{env("STRIPE_PUB")}}');
    </script>
    <script src="{{ URL::to('/') }}/js/all.js"></script>
</html>
