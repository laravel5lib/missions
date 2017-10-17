@extends('site.layouts.default')

@section('content')
    @component('carousel', ['id' => 'carousel-example-generic', 'class' => 'campaign-carousel' ])
        <div class="item active">
            <img src="../images/advance/adv-2018-header.jpg" alt="Advance 2018">
            <div class="carousel-caption">
                <h6 class="text-uppercase">Advance 2018</h6>
                <h3>3 Nations 1 Generation</h3>
                <p>On July 21-29, 2018 we will take the next step in discipling nations by returning to all 3 1Nation1Day nations.</p>
            </div><!-- end carousel -->
        </div><!-- end item -->
    @endcomponent

    <hr class="divider inv xlg">

    <campaigns></campaigns>

    <hr class="divider inv xlg">

    @component('section', ['class' => 'white-bg'])
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1 text-center">
                <h2 class="text-primary">Missions.Me provides a custom opportunity. We create experiences that maximize your gifts and are tailored to your passion.</h2>
            </div><!-- end col -->
        </div><!-- end row -->
    @endcomponent

    @component('section-full-width', ['class' => 'white-bg'])
        <div class="row row-no-margin">
            <div class="col-sm-2 col-xs-4 col-no-padding">
                <img class="img-responsive" src="images/why-mm/collage/collage1.jpg" alt="">
            </div>
            <div class="col-sm-2 col-xs-4 col-no-padding">
                <img class="img-responsive" src="images/why-mm/collage/collage3.jpg" alt="">
            </div>
            <div class="col-sm-2 col-xs-4 col-no-padding">
                <img class="img-responsive" src="images/why-mm/collage/collage4.jpg" alt="">
            </div>
            <div class="col-sm-2 col-xs-4 col-no-padding">
                <img class="img-responsive" src="images/why-mm/collage/collage5.jpg" alt="">
            </div>
            <div class="col-sm-2 col-xs-4 col-no-padding">
                <img class="img-responsive" src="images/why-mm/collage/collage8.jpg" alt="">
            </div>
            <div class="col-sm-2 col-xs-4 col-no-padding">
                <img class="img-responsive" src="images/why-mm/collage/collage9.jpg" alt="">
            </div>
        </div>
    @endcomponent

    @component('section-full-width', ['class' => 'white-bg'])
        <div class="row row-no-margin">
            <div class="col-sm-2 col-xs-4 col-no-padding">
                <img class="img-responsive" src="images/why-mm/collage/collage10.jpg" alt="">
            </div>
            <div class="col-sm-2 col-xs-4 col-no-padding">
                <img class="img-responsive" src="images/why-mm/collage/collage12.jpg" alt="">
            </div>
            <div class="col-sm-2 col-xs-4 col-no-padding">
                <img class="img-responsive" src="images/why-mm/collage/collage13.jpg" alt="">
            </div>
            <div class="col-sm-2 col-xs-4 col-no-padding">
                <img class="img-responsive" src="images/why-mm/collage/collage14.jpg" alt="">
            </div>
            <div class="col-sm-2 col-xs-4 col-no-padding">
                <img class="img-responsive" src="images/why-mm/collage/collage15.jpg" alt="">
            </div>
            <div class="col-sm-2 col-xs-4 col-no-padding">
                <img class="img-responsive" src="images/why-mm/collage/collage18.jpg" alt="">
            </div>
        </div>
    @endcomponent

    @component('section', ['class' => 'gray-lighter-bg'])
        <div class="row">
            <div class="col-xs-12 col-xs-offset-0">
                <div class="row">
                    <div class="col-xs-12">
                        <h6 class="text-uppercase text-center">A Trip For Everyone</h6>
                        <h1 class="text-center">Choose A Role</h1>
                        <hr class="divider red-small lg">
                        <hr class="divider inv lg">
                    </div><!-- end col -->
                </div><!-- end row -->
                <div class="row" style="display:flex; flex-wrap: wrap; flex-direction: row;">
                    <div class="col-sm-3 col-xs-12" style="display:flex">
                        <div class="panel">
                            <div style="position: absolute;left: 25px;top: 15px;z-index: 999;">
                                <a class="btn btn-primary-hollow btn-sm launch-modal pull-right" data-toggle="modal" data-target="#video-modal-one"><i class="fa fa-play"></i></a>
                            </div>
                            <img class="img-responsive" src="images/why-mm/missionary.jpg" alt="">
                            <div class="panel-body">
                                <h5>Missionary</h5>
                                <p class="small">Anyone age 13+ can be an M.M missionary. Set out on the journey
                                    with your friend or family to bring a message of love and hope along with an
                                    unforgettable cultural experience.</p>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col-sm-3 col-xs-12" style="display:flex">
                        <div class="panel">
                            <div style="position: absolute;left: 25px;top: 15px;z-index: 999;">
                                <a class="btn btn-primary-hollow btn-sm launch-modal pull-right" data-toggle="modal" data-target="#video-modal-two"><i class="fa fa-play"></i></a>
                            </div>
                            <img class="img-responsive" src="images/why-mm/medical-missionary.jpg" alt="">
                            <div class="panel-body">
                                <h5>Medical Missionary</h5>
                                <p class="small">Treat, diagnose, prescribe, and share the love of Jesus. Anyone
                                    in a medical field can serve, including students currently enrolled in
                                    school. Physicians, Dentists, Nurses, and all assistants needed.</p>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col-sm-3 col-xs-12" style="display:flex">
                        <div class="panel">
                            <div style="position: absolute;left: 25px;top: 15px;z-index: 999;">
                                <a class="btn btn-primary-hollow btn-sm launch-modal pull-right" data-toggle="modal" data-target="#video-modal-three"><i class="fa fa-play"></i></a>
                            </div>
                            <img class="img-responsive" src="images/why-mm/influencer.jpg" alt="">
                            <div class="panel-body">
                                <h5>Influencer</h5>
                                <p class="small">Missionaries with extensive ministry experience, ready to share their gifts of leadership and influence one on one with school faculty, at university forums, or in local churches.</p>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col-sm-3 col-xs-12" style="display:flex">
                        <div class="panel">
                            <div style="position: absolute;left: 25px;top: 15px;z-index: 999;">
                                <a class="btn btn-primary-hollow btn-sm launch-modal pull-right" data-toggle="modal" data-target="#video-modal-four"><i class="fa fa-play"></i></a>
                            </div>
                            <img class="img-responsive" src="images/why-mm/speaker.jpg" alt="">
                            <div class="panel-body">
                                <h5>Pastor/Speaker</h5>
                                <p class="small">Share your gift of leadership with the team by leading a
                                    morning devo then impart spiritual wisdom into local church leadership. Let
                                    us create a custom schedule that makes the most of your valuable time.</p>
                            </div><!-- end panel-body -->
                        </div><!-- end panel -->
                    </div><!-- end col -->
                </div><!-- end row -->
                <div class="row" style="display:flex; flex-wrap: wrap; flex-direction: row;">
                    <div class="col-sm-3 col-xs-12 col-sm-offset-1" style="display:flex">
                        <div class="panel">
                            <div style="position: absolute;left: 25px;top: 15px;z-index: 999;">
                                <a class="btn btn-primary-hollow btn-sm launch-modal pull-right" data-toggle="modal" data-target="#video-modal-five"><i class="fa fa-play"></i></a>
                            </div>
                            <img class="img-responsive" src="images/why-mm/business-person.jpg" alt="">
                            <div class="panel-body">
                                <h5>Business Leader</h5>
                                <p class="small">A shortened “Business Class” trip is available on select trips.
                                    You will experience conferences, stadium outreaches, street ministry, and
                                    connect with God in a whole new way. All trips will have you back on Sunday
                                    night, ready for Monday.</p>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col-sm-3 col-xs-12" style="display:flex">
                        <div class="panel">
                            <div style="position: absolute;left: 25px;top: 15px;z-index: 999;">
                                <a class="btn btn-primary-hollow btn-sm launch-modal pull-right" data-toggle="modal" data-target="#video-modal-six"><i class="fa fa-play"></i></a>
                            </div>
                            <img class="img-responsive" src="images/why-mm/media-missionary.jpg" alt="">
                            <div class="panel-body">
                                <h5>Media Missionary</h5>
                                <p class="small">Tell the story of life change! M.M needs you to film, shoot,
                                    interview, edit, export, and be creative! Experience a whole new world
                                    through the lens of a camera while capturing the most exciting moments of
                                    the trip.</p>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col-sm-3 col-xs-12" style="display:flex">
                        <div class="panel">
                            <div style="position: absolute;left: 25px;top: 15px;z-index: 999;">
                                <a class="btn btn-primary-hollow btn-sm launch-modal pull-right" data-toggle="modal" data-target="#video-modal-seven"><i class="fa fa-play"></i></a>
                            </div>
                            <img class="img-responsive" src="images/why-mm/sports-missionary.jpg" alt="">
                            <div class="panel-body">
                                <h5>Athlete</h5>
                                <p class="small">An athlete missionary is ready to reach people with the life-changing message of Jesus through sports outreach.  Each day play an organized game of soccer or baseball with a local team then spend some time ministering to your opponents during a time of sharing and prayer.</p>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->
            </div><!-- end col -->
        </div><!-- end row -->
    @endcomponent

    @component('section', ['class' => 'white-bg'])
        <div class="row">
            <div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <h6 class="text-uppercase text-primary">It's So Easy</h6>
                <h1 class="text-primary dash-trailing">We've Got You</h1>
                <p class="large-type">Logistics, we got it covered. Missions.Me makes missions simple by taking
                    care of all of your transportation, hotel, food, training, translators and ministry schedule
                    needs.</p>
                <hr class="divider inv">
                <a class="btn btn-primary" href="/contact">Speak To A Rep</a>
                <hr class="divider inv visible-xs visible-sm">
            </div><!-- end col -->
            <div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <img class="img-responsive img-rounded" src="images/why-mm/staff.jpg"/>
            </div><!-- end col -->
        </div><!-- end row -->
    @endcomponent

    @component('section-full-width', ['class' => 'white-bg'])
        <div class="row row-no-margin">
            <div class="col-sm-6 col-no-padding">
                <img class="img-responsive" src="images/why-mm/miami-lawn.jpg" alt="">
            </div>
            <div class="col-sm-6 col-no-padding">
                <img class="img-responsive" src="images/why-mm/miami-conf.jpg" alt="">
            </div>
        </div>
    @endcomponent

    @component('section', ['class' => 'bg-primary'])
        <div class="row">
            <div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <h1 class="text-primary-darker dash-trailing-dark">Level Of Impact</h1>
                <p class="large-type">We are interested in the transformation of individuals, communities, cities, and nations. Every outreach we produce is measured through the lens of measurable transformation.  In one week's time, you and your team will speak to entire schools, impact neighborhoods, empower churches, and even fill stadiums. We believe a short-term team must serve a long-term, sustainable goal. That’s why when your team leaves our international partners continue to serve the communities you impacted, so that we make disciples.</p>
                <hr class="divider inv visible-xs visible-sm">
            </div><!-- end col -->
            <div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <img class="img-responsive img-rounded" src="images/why-mm/stadium-impact.jpg"/>
            </div><!-- end col -->
        </div><!-- end row -->
    @endcomponent

    @component('section', ['class' => 'gray-lighter-bg'])
        <div class="row">
            <div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <img class="img-responsive img-rounded" src="images/why-mm/group-security.jpg"/>
            </div><!-- end col -->
            <div class="col-sm-6 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <h1 class="text-primary dash-trailing">Safety and Security</h1>
                <p>Missions.Me's first priority is safety. Our partners have successfully hosted thousands of
                    missionaries for over 25 years. Our projects are managed in cooperation with the
                    local police and hired security so that each ministry context is safe.</p>
                <blockquote>"I have traveled with Missions.Me in many countries and have witnessed first-hand
                    their organization and safety measures. I am completely confident recommending a Missions.Me experience to families."
                    <footer>Sue, Mother from Lancaster, PA</footer>
                </blockquote>
            </div><!-- end col -->
        </div><!-- end row -->
    @endcomponent

    @component('section', ['class' => 'gray-light-bg'])
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                <h1 class="text-center">Trip Difficulty Ratings</h1>
                <hr class="divider red-small lg">
                <hr class="divider inv lg">
            </div><!-- end col -->
        </div><!-- end row -->
        <div class="row text-center">
            <div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <img class="img-lg" src="images/why-mm/level1.png" alt="">
                <hr class="divider inv">
                <p>These trips are great for those getting started in the world-changing business. First
                    timers of all ages area welcome.</p>
                <hr class="divider inv lg visible-xs">
            </div>
            <div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <img class="img-lg" src="images/why-mm/level2.png" alt="">
                <hr class="divider inv">
                <p>If Level 1 proved to be a piece of cake, you’re ready for a Level 2 adventure. A little
                    tougher, a little sweatier, but much more sweeter.</p>
                <hr class="divider inv lg visible-xs">
            </div>
            <div class="col-sm-4 col-sm-offset-0 col-xs-10 col-xs-offset-1">
                <img class="img-lg" src="images/why-mm/level3.png" alt="">
                <hr class="divider inv">
                <p>Chuck Norris. Mr. T. Annie Oakley. Yep, they’re all level 3 missionaries. It may not be
                    physically tough, but it’ll be a challenge.</p>
                <hr class="divider inv lg visible-xs">
            </div>
        </div>
    @endcomponent

    <!-- MISSIONARY MODAL -->
    @component('video-modal', [
        'id' => 'video-modal-one',
        'url' => 'https://player.vimeo.com/video/187239218?title=0&byline=0&portrait=0'
    ])
    @endcomponent

    <!-- MEDICAL MODAL -->
    @component('video-modal', [
        'id' => 'video-modal-two',
        'url' => 'https://player.vimeo.com/video/185716159?title=0&byline=0&portrait=0'
    ])
    @endcomponent

    <!-- INFLUENCER MODAL -->
    @component('video-modal', [
        'id' => 'video-modal-three',
        'url' => 'https://player.vimeo.com/video/185972725?title=0&byline=0&portrait=0'
    ])
    @endcomponent

    <!-- PASTOR MODAL -->
    @component('video-modal', [
        'id' => 'video-modal-four',
        'url' => 'https://player.vimeo.com/video/187199738?title=0&byline=0&portrait=0'
    ])
    @endcomponent

    <!-- BUSINESS PERSON MODAL -->
    @component('video-modal', [
        'id' => 'video-modal-five',
        'url' => 'https://player.vimeo.com/video/187199859?title=0&byline=0&portrait=0'
    ])
    @endcomponent

    <!-- MEDIA MODAL -->
    @component('video-modal', [
        'id' => 'video-modal-six',
        'url' => 'https://player.vimeo.com/video/185716158?title=0&byline=0&portrait=0'
    ])
    @endcomponent

    <!-- ATHLETE MODAL -->
    @component('video-modal', [
        'id' => 'video-modal-seven',
        'url' => 'https://player.vimeo.com/video/235653915?title=0&byline=0&portrait=0'
    ])
    @endcomponent

@endsection