/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import * as VueGoogleMaps from "vue2-google-maps";

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueGoogleMaps, {
  load: {
    key: "AIzaSyDN3LWxZqLR2kcGo8pCYj_7n9YJ0UGF7F0"
  }
});

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('live-component', require('./components/LiveComponent.vue'));

const app = new Vue({
  el: '#app',
  data: {
    auction: {
      property: null,
      bids: [],
      finished: false,
      suspense: null
    }
  },
  methods: {
    startSuspense: function() {
      if (this.auction.suspense) {
        this.stopSuspense();
      }

      this.auction.suspense = setInterval(function(){
        $('.auctionBackground').toggleClass("backgroundRed");
      }, 500);
    },
    stopSuspense: function() {
      if (!this.auction.suspense) {
        return;
      }

      $('.auctionBackground').removeClass("backgroundRed");
      clearInterval(this.auction.suspense);
    }
  }
});

Echo.channel('local')
  .listen('Auction', (e) => {
    app.auction = Object.assign(app.auction, e, {finished: false});
  })
  .listen('Bid', (e) => {
    //app.auction.finished = e.bid.is_winner;

    if (!app.auction.finished && app.auction.property.id == e.bid.property_id) {
      app.auction.bids = [e.bid].concat(app.auction.bids);
    }
  })
  .listen('Suspense', (e) => {
      if (e.start) {
        app.startSuspense();
      } else {
        app.stopSuspense();
      }
  });


//Forms
require('./forms/register');
require('./forms/property');

//Auction
$('.suspense').click(function (event) {
  event.preventDefault();
  $.ajax($(this).data('url'));
  return false;
});
