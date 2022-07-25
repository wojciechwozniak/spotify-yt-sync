@extends ('layout')
@section ('content')
    <div class="container my-5 px-0 z-depth-1">


        <!--Section: Content-->
        <section class="text-center white-text d-md-flex justify-content-between p-5" style="background: #1C2331">

            <h3 class="font-weight-bold mb-md-0 mb-4 mt-2 pt-1">Created by ApiUsed</h3>

            <a href="apiused.site" class="btn btn-white btn-rounded">Visit us!</a>

        </section>
        <!--Section: Content-->


    </div>
    <div class="container" id="app">
        <div>
            <v-dialog/>
        </div>
        <div style="min-height:55vh">
            <form_yt @result-playlist-yt="updateResults" @playlist-name="updatePlaylistname"/>
        </div>

        <div>
            <result_table :results="results" :playlistName="playlistName"/>
        </div>
    </div>
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
