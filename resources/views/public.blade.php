<!DOCTYPE html>
<html>
    <head>
        <title>Donate App</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name=viewport content="width=device-width, initial-scale=1">

        <link href="{{ URL::to('/') }}/css/app.css" rel="stylesheet" type="text/css">

        <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    </head>
    <body class="donate user">
        <div class="cloak" v-cloak>
            <form action="/" method="POST">
                <div class="container">
                    <h2 class="center-align">Campaign for {{$set->name}}</h2>
                    <h4 class="center-align" v-if="chosen.length == 0">Choose a block to get started. Each Block is worth <strong><span class="green-text">$@{{set.price}}</span></strong></h4>
                    <h4 class="center-align" v-if="chosen.length > 0">You have chosen <strong>@{{chosen.length}}</strong> blocks which costs <strong><span class="green-text">$@{{purchase.price}}</span></strong>.</h4>
                    <div class="donate-container">
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
                    </div>
                </div> <?php /* .container */ ?>
                <div class="container-gray">
                    <div class="container padding-bottom">
                        <div class="row">
                            <div class="col s12">
                                <ul class="tabs">
                                    <li class="tab col s6"><a href="#img-choose">Choose an Image</a></li>
                                    <li class="tab col s6"><a class="active" href="#img-upload">Upload your own Image</a></li>
                                    <li class="tab col s6"><a href="#color-picker">Choose a Color</a></li>
                                </ul>
                                <div id="img-choose" class="tab-content">
                                    <h4 class="center-align">Choose an Image to use</h4>
                                </div>
                                <div id="img-upload" class="tab-content">
                                    <h4 class="center-align">Select an Image to be displayed on the blocks you purchase</h4>
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
                                <div id="color-picker" class="tab-content">
                                    <h4 class="center-align">Select a Color to be displayed on the blocks you purchase</h4>
                                    <div class="minicolors">
                                        <input type="text" class="minicolors-input" value="#4fad2f" v-model="purchase.color">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <?php /* .container */ ?>
                </div> <?php /* .container-gray */ ?>
                <div class="container">    
                    <div class="row">
                        <div class="col s12">
                            <h4 class="center-align">Reward Levels</h4>
                            <div class="rewards">
                                <div class="col s12 m4" v-repeat="set.rewards">
                                    <div class="reward-level" v-class="active: chosen.length >= blocks">
                                        <h3>@{{name}}</h3>
                                        <h4 class="green-text">$@{{blocks * set.price}}</h4>
                                        <p>@{{description}}</p>
                                        <p class="small">@{{blocks}} Blocks are needed for this reward level</p>
                                        <p class="small unearned"><strong>@{{blocks - chosen.length}} more blocks and you will earn this level</strong></p>
                                        <p class="small earned"><strong>You have earned this level<span v-if="$index > 0"> and every level below it!</span></strong></p>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div> <?php /* .container */ ?>
                <div class="container-gray">
                    <div class="container padding-top padding-bottom">    
                        <div class="row">
                            <div class="col s12">
                                <h4 class="center-align">Pay with Stripe</h4>
                            </div>
                            <h4 class="center-align" v-if="chosen.length == 0">Choose a block to get started. Each Block is worth <strong><span class="green-text">$@{{set.price}}</span></strong></h4>
                            <h4 class="center-align" v-if="chosen.length > 0">You have chosen <strong>@{{chosen.length}}</strong> blocks which costs <strong><span class="green-text">$@{{purchase.price}}</span></strong>.</h4>
                            <div class="col s12 m7 l4">
                                <div class='card-wrapper'></div>
                            </div>
                            <div class="col s12 m5 l8">
                                <div class="col s12 m12 l4">
                                    <div id="name-group" class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" v-model="purchase.name" placeholder="Bucky Badger">
                                    </div>
                                </div>
                                <div class="col s12 m12 l4">
                                    <div id="email-group" class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" name="email" v-model="purchase.email" placeholder="bucky@wisc.edu">
                                    </div>
                                </div>
                                <div class="col s12 m12 l4">
                                    <div id="number-group" class="form-group">
                                        <label for="number">Credit Card Number</label>
                                        <input type="text" name="number" />
                                    </div>
                                </div>
                                <div class="col s6 m6 l4">
                                    <div id="expiry-group" class="form-group">
                                        <label for="expiry">Expiration Date</label>
                                        <input type="text" name="expiry" />
                                    </div>
                                </div>
                                <div class="col s6 m6 l4">
                                    <div id="cvc-group" class="form-group">
                                        <label for="cvc">CVC Code</label>
                                        <input type="text" name="cvc" />
                                    </div>
                                </div>
                                <div class="col s12 m12 l4">
                                    <button id="btn_submit" type="submit" class="btn blue right">Purchase <span class="fa fa-arrow-right"></span></button>
                                </div>
                                <div class="col s12 red-text">
                                    <span class="payment-errors"></span>
                                </div>
                            </div>
                        </div>
                    </div> <?php /* .container */ ?>
                </div> <?php /* .container-gray */ ?>
            </form>
        </div>
    </body>
    <script>
        Stripe.setPublishableKey('{{env("STRIPE_PUB")}}');
    </script>
    <script src="{{ URL::to('/') }}/js/all.js"></script>
</html>
