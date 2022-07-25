<template>
    <div class="row">
        <div class="col-12 mt-5 mb-5">
            <input type="hidden" name="_token" :value="csrf">
            <div class="form-group">
                <label for="playlistName">Name of your new Spotify playlist</label>
                <input name="playlistName"
                       v-model="playlistName"
                       type="text"
                       class="form-control"
                       placeholder="Name of your new playlist"
                       id="playlistName"
                       required
                       @change = "resetPlaylistName"
                />
            </div>
            <div class="form-group">
                <label for="youtubePlaylistId">URL of YouTube Playlist</label>
                <input v-model="youtubePlaylistId"
                       @change="checkUrl"
                       type="text"
                       name="youtubePlaylistId"
                       :class="'form-control ' + { 'has-success' : responseObject.status} "
                       id="youtubeplaylistid"
                       placeholder="URL of YouTube Playlist"
                       required/>
                <p class="help alert-success p-1 mt-2" v-show="responseObject.status == 200">
                    Your url to playlist is fine!
                    Playlist has {{responseObject.length}} items.
                </p>
                <p class="help alert-danger p-1 mt-2" v-show="responseObject.status == 400">
                    Wrong url, it is not YouTube playlist!
                </p>
            </div>
            <div class="form-group">
                <button
                    :disabled="!(typeof responseObject.status != 'undefined' && responseObject.status == 200 && playlistName.length > 3)"
                    class="btn btn-success"
                    @click="sendForm">
                    Prepare
                </button>
            </div>
        </div>
        <loading :active.sync="isLoading"></loading>
    </div>

</template>

<script>
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

    export default {
        components: {
            Loading
        },
        data() {
            return {
                isLoading: false,
                playlistName: '',
                youtubePlaylistId: '',
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                responseObject: {
                    length: 0,
                    status: 0
                }
            }
        },
        methods: {
            checkUrl: function () {
                let data = this;
                this.axios.post(
                    '/lengthPlaylist', {
                        youtubePlaylistId: this.youtubePlaylistId,
                        _token: this.csrf
                    })
                    .then(function (response) {
                        if(response.data == 'error'){
                            data.responseObject.length = 0;
                            data.responseObject.status = 400
                        }
                        else{
                            data.responseObject.length = response.data;
                            data.responseObject.status = response.status;
                        }
                    }).catch(function (error) {
                    console.log(error);
                });
            },
            sendForm: function () {
                //@TODO preloader
                this.isLoading = true;
                let data = this;
                this.axios.post(
                    '/createYoutubePlaylist', {
                        playlistName: this.playlistName,
                        youtubePlaylistId: this.youtubePlaylistId,
                        _token: this.csrf
                    })
                    .then(function (response) {
                        data.$emit("result-playlist-yt", response);
                        data.$emit("playlist-name", data.playlistName);
                        data.isLoading = false;
                    //    @TODO success message
                    }).catch(function (error) {
                    console.log(error);
                });
            },
            resetPlaylistName: function(){
                this.$emit("playlist-name", this.playlistName);
            }
        }
    }
</script>

<style scoped>

</style>
