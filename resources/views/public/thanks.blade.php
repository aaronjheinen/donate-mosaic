<!DOCTYPE html>
<html>
    <head>
        <title>Thank you!</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name=viewport content="width=device-width, initial-scale=1">

        <meta property="og:url"           content="https://donatetostartingblockmadison.org" />
        <meta property="og:type"          content="website" />
        <meta property="og:title"         content="Help StartingBlock" />
        <meta property="og:description"   content="Haven't heard of StartingBlock yet? We're building a 50,000 square foot community space that will support not just startups, but also spur local innovation, collaboration, creativity and youth education. Thanks to the generous support of our sponsors, American Family Insurance, MGE, the City of Madison, and others, StartingBlock has already raised 85% of its building costs. But to make StartingBlock a reality, we need your support today!

Buy a virtual block for just $25 and help build Madison's next generation of ideas and startups. Pick as many blocks as you want on StartingBlock' s future floorplan. Upload a photo or logo in your block. More blocks = bigger picture . . . plus special gifts!" />
        <meta property="og:image"         content="http://www.startingblockmadison.org/app/uploads/2015/08/night-man.jpeg" />


        <link href="{{ URL::to('/') }}/css/app.css" rel="stylesheet" type="text/css">
        <link rel="canonical" href="{{ URL::to('/') }}">

    </head>
    <body class="donate user-thanks">
    <nav role="navigation">
      <div class="nav-wrapper container">
        <a id="logo-container" href="http://www.startingblockmadison.org" class="brand-logo"><img src="https://donatetostartingblockmadison.org/img/defaults/sb-logo-green.png" alt="SBM"></a>
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
        <div class="cloak">
            <div class="container">
                <h2 class="center-align page-title">Thank you {{$purchase->name}}!</h2>
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
                            <div class="center-align">
                              <p>Want to help even more?  Spread the word to friends and colleagues! <strong>#BuildingBlocks</strong></p>
   
                              <h4>Tell your friends about it!</h4>
                              <p>  
                                <div class="fb-share-button social-button" 
                                    data-href="https://donatetostartingblockmadison.org" 
                                    data-layout="button">
                                </div>
                                <a class="twitter-share-button social-button"
                                  href="https://twitter.com/intent/tweet?text=Help%20StartingBlock%20Madison%20build%20a%20community.&hashtags=BuildingBlocks"
                                  data-count="none">
                                Tweet</a>
                              </p>    
                              <p>Issues with your image? Please contact <a href="mailto:scott@startingblockmadison.org">scott@startingblockmadison.org</a></p>
                            </div>
                        </div>
                    </div>
                </div> <?php /* .container */ ?>
            </div> <?php /* .container-gray */ ?>
        </div>
        <footer class="page-footer">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <a href="http://www.startingblockmadison.org" class="brand-logo left" style="margin-right:15px;"><img src="https://donatetostartingblockmadison.org/img/defaults/sb-logo-green.png" alt="SBM"></a>
   
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
    <script>var baseUrl = "{{ url('/') }}";</script>
    <script src="{{ URL::to('/') }}/js/public.js"></script>
    <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', '{{ $set->tracking_id }}', 'auto');
    ga('send', 'pageview');

  </script>
  <script>window.twttr = (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0],
    t = window.twttr || {};
  if (d.getElementById(id)) return t;
  js = d.createElement(s);
  js.id = id;
  js.src = "https://platform.twitter.com/widgets.js";
  fjs.parentNode.insertBefore(js, fjs);
 
  t._e = [];
  t.ready = function(f) {
    t._e.push(f);
  };
 
  return t;
}(document, "script", "twitter-wjs"));</script>
<div id="fb-root"></div>
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
    </body>
</html>
