<template>
    <div>
        <loading :active.sync="loading"></loading>
        <div v-show="items.length > 0">
            <div class="row">
                <div class="col-1">
                    <p class="font-weight-bold">Add</p>
                </div>
                <div class="col-4">
                    <p class="font-weight-bold">Original title from YouTube playlist</p>
                </div>
                <div class="col-6">
                    <p class="font-weight-bold">Title of song from Spotify</p>
                </div>
                <div class="col-1 text-center">
                    <p class="font-weight-bold">Actions</p>
                </div>
            </div>
            <div :class="getRowClass(item.uri)" v-for="(item,index) in resultArray" :key="index" v-if="renderComponent"
                 @click="checbkoxMethod">
                <div class="col-1 justify-content-center align-self-center p-0">
                    <div class="text-left">
                        <input type="checkbox" class="css-checkbox" name="songs" :value="item.uri"
                               v-if="item.uri != 404"
                               :checked="item.uri != 404" :id="'song_checkbox_'+index"/>
                        <label :for="'song_checkbox_'+index" class="css-label"></label>
                    </div>
                </div>
                <div class="col-4 justify-content-center align-self-center">
                    <p :id="'title_yt' + index">{{item.title_yt}}</p>
                    <input type="hidden" name="title_yt" class="form-control input_yt"
                           :id="'input_title_yt' + index" @change="findSubitems"
                           v-model="resultArray[index]['title_yt']"/>
                </div>
                <div class="col-4 justify-content-center align-self-center">
                    <p :id="'title_spotify' + index">{{item.title_spotify}}</p>
                    <input type="hidden" name="title_spotify" class="form-control"
                           :id="'input_title_spotify' + index" :value="item.title_spotify"/>
                </div>
                <div class="col-2 justify-content-center align-self-center">
                    <img :src="item.image" class="img-fluid d-block m-auto"/>
                </div>
                <div class="col-1 justify-content-center align-self-center">
                    <div class="text-center">
                        <span class="text-danger edit" style="cursor:pointer" :id="'edit_' + index"
                              @click="editElement(index)"><i class="fas fa-edit"></i></span>
                    </div>
                </div>
                <div v-if="merged['index'+index] != 'undefined'" class="col-12">
                    <div
                        v-for="(subItem,key) in merged['index' + index]">
                        <div v-if="key=='data'">
                            <div v-for="i in subItem" class="row subresultRow" @click="checbkoxMethod;uncheckedMain">
                                <div class="col-1 text-center justify-content-center align-self-center">
                                    <input type="checkbox" class="css-checkbox" name="songs"
                                           :id="'subresult_song_checbkox' + i.uri" :value="i.uri"/>
                                    <label :for="'subresult_song_checbkox' + i.uri" class="css-label"></label>
                                </div>
                                <div class="col-4 justify-content-center align-self-center">
                                    <p>{{i.title}}</p>
                                </div>
                                <div class="col-2 justify-content-center align-self-center">
                                    <img :src="i.image" class="img-fluid"/>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-12 text-center pt-5 pb-5">
                <button class="btn btn-success" id="create_playlist" @click="createSpotifyPlaylist">Create Spotify
                    Playlist
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.css';

    export default {
        name: "result_table",
        components: {
            Loading
        },
        props: {
            results: '',
            playlistname: ''
        },
        data() {
            return {
                loading: false,
                playlistName: '',
                perPage: 50,
                currentPage: 1,
                items: [],
                items_sub: {},
                merged: {},
                edited: null,
                renderComponent: true,
                subQuery: {},
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        },
        computed: {
            resultArray() {

                return this.items[0];
            },
            editedCompute() {
                return this.edited
            },
            mergedResult() {
                return this.merged['index' + this.editedCompute]
            }
        },
        methods: {
            nextPage: function () {
                if ((this.currentPage * this.pageSize) < this.cats.length) this.currentPage++;
            }
            ,
            prevPage: function () {
                if (this.currentPage > 1) this.currentPage--;
            },
            editElement: function (id) {
                document.getElementById('title_yt' + id).style.display = 'none';
                document.getElementById('input_title_yt' + id).type = 'text';
                this.edited = id;
            },
            forceRerender(element = 1) {
                if (element == 1) {
                    this.renderComponent = false;
                    this.merged = [];
                    this.$nextTick().then(() => {
                        this.renderComponent = true;
                    });
                }
                if (element == 2) {
                    this.subQuery['index' + this.edited] = false;
                    this.$nextTick().then(() => {
                        this.subQuery['index' + this.edited] = true;
                    });
                }

            },
            findSubitems() {
                let v = document.getElementById('input_title_yt' + this.edited).value;
                let input = document.getElementById('input_title_yt' + this.edited);
                let el = this;
                let m = 'index' + this.edited;
                this.axios.post(
                    '/similarSongs', {
                        _token: this.csrf,
                        value: v
                    }
                ).then(function (response) {
                    el.items_sub = {
                        [m]: response
                    };
                    el.subQuery[m] = true;

                }).catch(function (msg) {
                    console.log(msg);
                })
            },
            uncheckedMain: function () {
                if(typeof document.getElementById('song_checkbox_' + this.edited) != 'undefined'){
                    document.getElementById('song_checkbox_' + this.edited).checked = false;
                }
                else{
                    return false;
                }
            },
            checbkoxMethod: function (event) {
                let self = this;
                let main = false;
                if (event.target.classList.contains('fas') || event.target.classList.contains('input_yt') || event.target.classList.contains('edit')) {
                    return false;
                }
                event.path.forEach(function (item) {
                    if (typeof item.classList != 'undefined' && item.classList.contains('subresultRow')) {
                        var input = item.querySelector('.css-checkbox');
                        if (typeof input == 'undefined') {
                            return false;
                        }
                        input.checked = !input.checked;
                        main = true;
                        self.uncheckedMain();
                        return false;
                    }
                    if(typeof item.classList != 'undefined' && item.classList.contains('row') && !main) {
                        var input = item.querySelector('.css-checkbox');
                        if (typeof input == 'undefined') {
                            return false;
                        }
                        input.checked = !input.checked;
                        console.log('input +' + input);
                        return false;
                    }
                });
            },
            getRowClass(property) {
                let classes = 'row';
                if (property == 404) {
                    classes += ' danger';
                }
                return classes;
            },
            createSpotifyPlaylist() {
                this.loading = true;
                let self = this;
                let data = document.querySelectorAll('input[name=songs]:checked');
                let spotify_ids = [];
                data.forEach(function (item) {
                    spotify_ids.push(item.value)
                });
                this.axios.post(
                    '/createSpotifyPlaylist', {
                        _token: this.csrf,
                        data: spotify_ids,
                        name: this.playlistname
                    }
                ).then(function (response) {
                    self.$modal.show('dialog', {
                        title: '<span class="text-succes"><b>Success!</b></span>',
                        text: 'Your Spotify playlist was created!<br /> Go to Spotify App and check it out!',
                        buttons: [
                            {
                                title: '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">\n' +
                                    '<input type="hidden" name="cmd" value="_s-xclick" />\n' +
                                    '<input type="hidden" name="hosted_button_id" value="TVJT52GURA4WL" />\n' +
                                    '<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />\n' +
                                    '<img alt="" border="0" src="https://www.paypal.com/en_PL/i/scr/pixel.gif" width="1" height="1" />\n' +
                                    '</form>'
                            },
                            {
                                title: '<span class="text-danger">Close</span>'
                            }
                        ]
                    });
                    self.loading = false;
                    console.log(response)
                }).catch(function (msg) {
                    console.log(msg);
                })
            }
        },
        watch: {
            results: function () {
                this.items = [];
                this.items.push(this.results.data);
                this.forceRerender();
                let o = this;
                this.items[0].forEach(function (key, value) {
                    o.subQuery['index' + value] = false;
                });
            },
            items_sub: function (newVal, oldVal) {
                this.merged = {...this.merged, ...this.items_sub};
                // Object.assign({}, newVal, this.items_sub);
            }
        }
    }
</script>
<style scoped>
    .danger {
        color: red;
    }

    .row:not(:first-of-type), .subresultRow {
        padding-bottom: .5rem;
        padding-top: .5rem;
        /*border:1px solid #27ae60;*/
        margin: .25rem 0;
        cursor: pointer;
    }

    .subresultRow {
        padding: 0;
        margin: 0;
    }

    input[type=checkbox].css-checkbox {
        position: absolute;
        z-index: -1000;
        left: -1000px;
        overflow: hidden;
        clip: rect(0 0 0 0);
        height: 1px;
        width: 1px;
        margin: -1px;
        padding: 0;
        border: 0;
    }

    input[type=checkbox].css-checkbox + label.css-label {
        padding-left: 29px;
        height: 24px;
        display: inline-block;
        line-height: 24px;
        background-repeat: no-repeat;
        background-position: 0 0;
        font-size: 24px;
        vertical-align: middle;
        cursor: pointer;

    }

    input[type=checkbox].css-checkbox:checked + label.css-label {
        background-position: 0 -24px;
    }

    label.css-label {
        background-image: url(http://csscheckbox.com/checkboxes/u/csscheckbox_4f6bc9a7cc47504b9c36e04aa489f19e.png);
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
</style>
