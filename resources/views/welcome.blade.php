@extends('layout')
@section('content')
    <div class="view"
         style="background-image: url({{asset('img/main_image.jpg')}}); background-repeat: no-repeat; background-size: cover;">

        <div class="mask rgba-black-light d-flex justify-content-center align-items-center">

            <div class="text-center white-text mx-5 wow fadeIn">
                <h1 class="mb-4">
                    <strong>ApiUsed</strong>
                </h1>

                <p>
                    <strong>We combine services for your
                        convenience!</strong>
                </p>

                <p class="mb-4 d-none d-md-block">
                    <strong>Read details below to discover our ideas!</strong>
                </p>

                <a href="#info"
                   class="btn btn-outline-white btn-lg">Read more
                    <i class="fas fa-arrow-circle-down ml-2"></i>
                </a>
            </div>

        </div>

    </div>
    <main>
        <div class="container">
            <section class="mt-5 wow fadeIn" id="info">
                <div class="row">
                    <div class="col-md-6 mb-4">

                        <img src="{{asset('img/section_2.jpg')}}" class="img-fluid z-depth-1-half"
                             alt="">

                    </div>
                    <div class="col-md-6 mb-4">
                        <h3 class="h3 mb-3">About us</h3>
                        <p>Our projects are created to combine the best features of websites that we use everyday.</p>

                        <p>We want to share with you the projects that we create for ourselves.</p>

                        <hr>
                    </div>
                </div>
            </section>
            <hr class="my-5">
            <section class="text-center">
                <div class="row mb-4 wow fadeIn">
                    <div class="col-lg-4 col-md-12 mb-4">
                        <div class="card">
                            <div class="view overlay">
                                <img src="{{asset('img/pro_1.jpg')}}"
                                     class="card-img-top" alt="Project 1 image">
                                <a href="#"
                                   target="_blank">
                                    <div class="mask rgba-white-slight"></div>
                                </a>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title">YT Playlist
                                    <small><i class="fas fa-arrow-right"></i></small>
                                    Spotify
                                </h4>
                                <p class="card-text">We want to share with you the first of our few ideas, namely the
                                    combination of created
                                    playlists on YouTube or created by YouTube and the Spotify application</p>

                                <form action="{{route('login')}}" method="POST" class="text-center">
                                {{csrf_field()}}
                                <input type="submit" class="btn btn-primary btn-md btn-mdb-color" value="Login with Spotify" />
                                </form>

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card">
                            <div class="view overlay">
                                <img src="{{asset('/img/pro_2.jpg')}}"
                                     class="card-img-top" alt="Project 2 image">
                            </div>
                            <div class="card-body">
                                <h4 class="card-title">Coming soon..</h4>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card">
                            <div class="view overlay">
                                <img src="{{asset('/img/pro_3.jpg')}}"
                                     class="card-img-top" alt="Project 3 image">
                            </div>
                            <div class="card-body">
                                <h4 class="card-title">Coming soon..</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <hr/>
            <Section>
                <div class="container my-5 py-5 z-depth-1">


                    <!--Section: Content-->
                    <section class="px-md-5 mx-md-5 text-center text-lg-left dark-grey-text">


                        <!--Grid row-->
                        <div class="row d-flex justify-content-center mb-1">

                            <!--Grid column-->
                            <div class="col-md-7 text-center">
                                <p>
                                    If you would like to share your opinion with us or any ideas, write to us!</p>
                                <p> We will
                                    be grateful for each email separately!</p>
                                <p class="font-weight-bold"></i><a href="mailto:info@apiused.site">info@apiused.site</a></p>


                            </div>
                            <!--Grid column-->

                        </div>
                        <!--Grid row-->


                    </section>
                    <!--Section: Content-->


                </div>
            </Section>
        </div>
    </main>
    <!--Main layout-->

    <!--Footer-->
    <footer class="page-footer text-center font-small mt-4 wow fadeIn">

        <!--Call to action-->
        <div class="pt-4">
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick"/>
                <input type="hidden" name="hosted_button_id" value="K2RH4765Q9HNN"/>
                <input type="image" src="https://www.paypalobjects.com/en_US/PL/i/btn/btn_donateCC_LG.gif" border="0"
                       name="submit" title="PayPal - The safer, easier way to pay online!"
                       alt="Donate with PayPal button"/>
                <img alt="" border="0" src="https://www.paypal.com/en_PL/i/scr/pixel.gif" width="1" height="1"/>
            </form>
        </div>
        <!--/.Call to action-->

        <hr class="my-4">

        <!-- Social icons -->
        <div class="pb-4">

            <a href="https://www.freepik.com/free-photos-vectors/business">Business photo created by freepik -
                www.freepik.com</a>
        </div>
        <!-- Social icons -->

        <!--Copyright-->
        <div class="footer-copyright py-3">
            © 2019 Copyright:
            <a href="http://spiused.site" target="_blank"> ApiUsed.site </a>
        </div>
        <!--/.Copyright-->

    </footer>
    <!--/.Footer-->




@stop

