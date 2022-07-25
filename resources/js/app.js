
import Vue from 'vue';
import axios from 'axios';
import VueAxios from 'vue-axios';
import VModal from 'vue-js-modal'


// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.use(VModal, { dialog: true })
Vue.use(VueAxios, axios);

//components
import form_yt from './components/form_yt.vue';
import result_table from './components/result_table.vue';
// import subresults from  './components/subresults.vue';

// Vue.component('result_table', require('./components/result_table.vue'));
// Vue.component('form_yt', require('./components/form_yt.vue'));

const app = new Vue({
    el: '#app',
    components: {form_yt, result_table},
    data: function () {
        return {
            results: "",
            playlistName: ''
        };
    },
    methods: {
        updateResults(results) {
            this.results = results;
        },
        updatePlaylistname(playlistname) {
            this.playlistName = playlistname;
        }
    }
});
