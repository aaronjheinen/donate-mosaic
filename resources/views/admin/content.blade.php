@extends('admin')
@section('title', 'Admin Panel')
@section('body', 'donate admin-edit-content')
@section('content')
    <div class="container"> 
        <form action="/" method="POST">
            <div data-editable data-name="header-content">
                @if(isset($set->content->header))
                    {!!$set->content->header!!}
                @else
                    <h2 class="page-title">Put your title here!</h2>
                    <p>This is where you description can go.</p>
                    <p><strong>Hey Teacher! Leave them kids alone.</strong> This is where your other descriptive text can go. <strong>This is where your hook can go that will make everyone want to donate!</strong></p>
                @endif
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
            <div class="container-gray">
                    <div class="container padding-bottom">
                        <ul class="tabs">
                            <li class="tab col s6"><a href="#img-choose">Choose an Image</a></li>
                            <li class="tab col s6"><a class="active" href="#img-upload">Upload your own Image</a></li>
                            <li class="tab col s6"><a href="#color-picker">Choose a Color</a></li>
                        </ul>
                        <div id="img-choose" class="tab-content">
                            <h4 class="center-align">Choose an Image to use</h4>
                            <div class="row defaults">
                                <div class="col s12 m4">
                                    <div class="default-media" v-class="active: purchase.media_id == 1" v-on="click: setMedia(1, '{{ url('/img/defaults/flag-of-madison.png') }}')">
                                        <img src="{{ url('/img/defaults/flag-of-madison.png') }}" />
                                    </div>
                                </div>
                                <div class="col s12 m4">
                                    <div class="default-media" v-class="active: purchase.media_id == 2" v-on="click: setMedia(2, '{{ url('/img/defaults/wisconsin.png') }}')">
                                        <img src="{{ url('/img/defaults/wisconsin.png') }}" />
                                    </div>
                                </div>
                                <div class="col s12 m4">
                                    <div class="default-media" v-class="active: purchase.media_id == 3" v-on="click: setMedia(3, '{{ url('/img/defaults/sb-logo-green.png') }}')">
                                        <img src="{{ url('/img/defaults/sb-logo-green.png') }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="img-upload" class="tab-content">
                            <h4 class="center-align">Select an image of yourself to be displayed on the blocks you purchase</h4>
                            <div class="row">
                                <div class="col s6 offset-s3">
                                    <img class="thumbnail left" src="" v-attr="src: img_url" v-if="img_url" />
                                    <img class="thumbnail left" src="https://placehold.it/200x150" v-if="!img_url" />
                                    <div class="file-field input-field">
                                      <input class="file-path validate" type="text"  />
                                      <div class="btn">
                                        <span>File</span>
                                        <input v-el="image" type="file" name="image" v-on="change:upload" />
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="color-picker" class="tab-content">
                            <h4 class="center-align">Select a Color to be displayed on the blocks you purchase</h4>
                            <div class="row">
                                <div class="col s6 offset-s3 m4 offset-m4">
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
                            <h4 class="center-align">Special Gifts</h4>
                            <div class="rewards">
                                @foreach ($set->rewards as $reward)
                                    <div class="col s12 m4">
                                        <div class="reward-level">
                                            <h3>{{$reward->name}}</h3>
                                            <h4 class="green-text">${{$reward->blocks * $set->price}}</h4>
                                            <p>{{$reward->description}}</p>
                                            <p class="small">{{$reward->blocks}} Blocks are needed for this reward level</p>
                                        </div>
                                    </div>
                                @endforeach
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
                            <h4 class="center-align">Choose a block to get started. Each Block is worth <strong><span class="green-text">${{$set->price}}</span></strong></h4>
                            <div class="row padding-top">
                                <div class="col s12 m7 l4">
                                    <div class='card-wrapper'></div>
                                </div>
                                <div class="col s12 m5 l8">

                                    <div class="row">
                                        <div class="col s12 m12 l4">
                                            <div id="name-group" class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="name" v-model="purchase.name" placeholder="Your Name">
                                            </div>
                                        </div>
                                        <div class="col s12 m12 l4">
                                            <div id="email-group" class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" name="email" v-model="purchase.email" placeholder="">
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
                                    <div class="row">
                                        <div class="col s12">
                                            <div data-editable data-name="disclaimer-content">
                                                @if(isset($set->content->disclaimer))
                                                    {!!$set->content->disclaimer!!}
                                                @else
                                                    <p>All donations are tax-deductible. Any other disclaimer information should go here. Maybe put information about who to contact here with an email link to <a href="mailto:app@admin.com">app@admin.com</a>.</p>
                                                @endif
                                            </div>
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
    </div>
@endsection