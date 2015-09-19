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
    <nav role="navigation">
      <div class="nav-wrapper container">
        <a id="logo-container" href="http://www.startingblockmadison.org" class="brand-logo"><img src="http://www.startingblockmadison.org/app/themes/sage/dist/images/sb-logo-green.png" alt="SBM"></a>
        <ul class="right hide-on-med-and-down">
          <li><a href="https://www.facebook.com/StartingBlockMadison"><i class="fa fa-facebook-official"></i></a></li>
          <li><a href="https://twitter.com/StartingBlockM"><i class="fa fa-twitter"></i></a></li>
          <li><a href="https://www.youtube.com/channel/UCPN2Cv1gUEPLPcirDZ3HpRg"><i class="fa fa-youtube"></i></a></li>
        </ul>
        <div class="menu-primary-navigation-container"><ul id="menu-primary-navigation" class="nav nav-primary hide-on-med-and-down"><li id="menu-item-125" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-4 current_page_item menu-item-125"><a href="http://www.startingblockmadison.org/">Home</a></li>
            <li id="menu-item-124" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-124"><a href="http://www.startingblockmadison.org/team/">Team</a></li>
            <li id="menu-item-123" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-123"><a href="http://www.startingblockmadison.org/partners/">Partners</a></li>
            <li id="menu-item-425" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-425"><a href="http://www.startingblockmadison.org/news/">News</a></li>
            <li id="menu-item-122" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-122"><a href="http://www.startingblockmadison.org/contact/">Contact</a></li>
            </ul></div>    <div id="nav-mobile" class="menu-mobile-navigation-container"><ul id="menu-mobile-navigation" class="side-nav" style="left: -250px;"><li id="menu-item-50" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-4 current_page_item menu-item-50"><a href="http://www.startingblockmadison.org/">Home</a></li>
            <li id="menu-item-51" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-51"><a href="http://www.startingblockmadison.org/team/">Team</a></li>
            <li id="menu-item-121" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-121"><a href="http://www.startingblockmadison.org/partners/">Partners</a></li>
            <li id="menu-item-426" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-426"><a href="http://www.startingblockmadison.org/news/">News</a></li>
            <li id="menu-item-120" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-120"><a href="http://www.startingblockmadison.org/contact/">Contact</a></li>
            </ul></div>    <a href="#" data-activates="menu-mobile-navigation" class="button-collapse navbar-toggle">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>
      </div>
    </nav>
        <div class="cloak" v-cloak>
            <form action="/" method="POST">
                <div class="container">
                    <h2 class="center-align">Campaign for {{$set->name}}</h2>
                    <h4 class="center-align" v-if="chosen.length == 0">Choose a block to get started. Each Block is worth <strong><span class="green-text">$@{{set.price}}</span></strong></h4>
                    <h4 class="center-align" v-if="chosen.length > 0">You have chosen <strong>@{{chosen.length}}</strong> blocks which costs <strong><span class="green-text">$@{{purchase.price}}</span></strong>.</h4>
                    <div class="row">
                        <div class="col s12 m6 offset-m3">
                            <div id="name-group" class="form-group">
                                <label for="name">Choose a Number of Blocks to be randomly assigned or pick their location below</label>
                                <input type="number" name="blocks" placeholder="1" v-model="purchase.blocks" v-on="change: updateBlocks" />
                            </div>
                        </div>
                    </div>
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
                                    <div class="reward-level" v-class="active: chosen.length >= blocks,inactive: chosen.length < blocks" v-on="click: setReward(blocks)">
                                        <h3>@{{name}}</h3>
                                        <h4 class="green-text">$@{{blocks * set.price}}</h4>
                                        <p>@{{description}}</p>
                                        <p class="small">@{{blocks}} Blocks are needed for this reward level</p>
                                        <p class="small unearned"><strong>@{{blocks - chosen.length}} more blocks and you will earn this level</strong></p>
                                        <p class="small earned"><strong>You have earned this level<span v-if="$index > 0"> and every level below</span></strong></p>
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
                            <div class="row padding-top">
                                <div class="col s12 m7 l4">
                                    <div class='card-wrapper'></div>
                                </div>
                                <div class="col s12 m5 l8">

                                    <div class="row">
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
                                    </div>
                                    <div class="row">
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
                                            <button id="btn_submit" type="submit" class="btn blue right">Purchase</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12">
                                            <p>
                                              <input type="checkbox" id="optin" name="optin" v-model="purchase.optin" />
                                              <label for="optin">Join our listserve</label>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col s12 red-text">
                                        <span class="payment-errors"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <?php /* .container */ ?>
                </div> <?php /* .container-gray */ ?>
            </form>
        <footer class="page-footer">
            <div class="container">
              <div class="row">
                <div class="col l6 s12">
                  <a href="http://www.startingblockmadison.org" class="brand-logo left" style="margin-right:15px;"><img src="http://www.startingblockmadison.org/app/themes/sage/dist/images/sb-logo-green.png" alt="SBM"></a>
           
                  <h5 class="white-text">Starting Block Madison</h5>
                  <p class="grey-text text-lighten-4">An entrepreneurial hub and ecosystem helping Madison’s entrepreneurs succeed.</p>
                </div>
                <div class="col l3 s12">
                  <h5 class="white-text">Menu</h5>
                  <div class="menu-primary-navigation-container"><ul id="menu-primary-navigation-1" class="nav white-text"><li class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-4 current_page_item menu-item-125"><a href="http://www.startingblockmadison.org/">Home</a></li>
        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-124"><a href="http://www.startingblockmadison.org/team/">Team</a></li>
        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-123"><a href="http://www.startingblockmadison.org/partners/">Partners</a></li>
        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-425"><a href="http://www.startingblockmadison.org/news/">News</a></li>
        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-122"><a href="http://www.startingblockmadison.org/contact/">Contact</a></li>
        </ul></div>        </div>
                <div class="col l3 s12">
                  <h5 class="white-text">Connect</h5>
                  <ul>
                    <li><a class="white-text" href="https://www.facebook.com/StartingBlockMadison">Facebook</a></li>
                    <li><a class="white-text" href="https://twitter.com/StartingBlockM">Twitter</a></li>
                    <li><a class="white-text" href="https://www.youtube.com/channel/UCPN2Cv1gUEPLPcirDZ3HpRg">YouTube</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="footer-copyright">
              <div class="container">
              © 2015 StartingBlock Madison      </div>
            </div>
          </footer>

        </div> <?php /* /.v-cloak */ ?>
    </body>
    <script>
        var baseUrl = "{{ url('/') }}";
        Stripe.setPublishableKey('{{env("STRIPE_PUB")}}');
    </script>
    <script src="{{ URL::to('/') }}/js/all.js"></script>
</html>
