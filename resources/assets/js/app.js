/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import * as VueGoogleMaps from "vue2-google-maps";
import PayPal from 'vue-paypal-checkout';
import axios from 'axios';
import VueCountdownTimer from 'vuejs-countdown-timer';

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueCountdownTimer);

Vue.use(VueGoogleMaps, {
  load: {
    key: "AIzaSyDN3LWxZqLR2kcGo8pCYj_7n9YJ0UGF7F0"
  }
});

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('live-component', require('./components/LiveComponent.vue'));
Vue.component('bid-component', require('./components/BidComponent.vue'));

const app = new Vue({
  el: '#app',
  components: {
    paypal: PayPal
  },
  data: {
    auction: {
      property: null,
      bids: [],
      finished: false,
      suspense: null,
      celebrate: null
    },
    credentials: {
      sandbox: 'AQX_h6Ta_g4OgiMreaa_K-x21lH604zbL3kkEBJXvsZ8c2U8sy2YPhlciMev8iMneLl82BkfJSlr6XR2',
      production: 'AeAml9Oxn5lzKF1Hk1slRa1ck-a0GbUIDUtk0jHHF9GKCqHrJbCrIaEyxYE2mKuL20-oZkwVxxk4VNps'
    },
    experienceOptions: {
      input_fields: {
        no_shipping: 1
      }
    },
    myItems: {
      single: [
        {
          "name": "Single bidding",
          "description": "This will allow only to bid in one property",
          "quantity": "1",
          "price": "1575",
          "currency": "USD"
        }
      ],
      multiple: [
        {
          "name": "Multiple bidding",
          "description": "This will allow to bid in multiple properties",
          "quantity": "1",
          "price": "5000",
          "currency": "USD"
        }
      ]
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
    },
    startCelebrate: function() {

      $('.celebrate').show();
      $('.celebrate img').attr('src', '/images/celebrate.gif');

      setTimeout(function () {
        $('.celebrate').hide();
        $('.celebrate img').attr('src', 'gg');
      }, 8000);
    },
    paymentCompleted: async function (data) {
      await axios.post('/paypal', data);
      window.location.reload();
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
  })
  .listen('Celebrate', (e) => {
      app.startCelebrate();
  });


//Forms
require('./forms/register');
require('./forms/property');

//Auction
$('.suspense, .celebrate').click(function (event) {
  event.preventDefault();
  $.ajax($(this).data('url'));
  return false;
});

$('.select').click(function () {
  var self = this;

  $.ajax($(this).data('url')).done(function() {
    if ($(self).data('clear')) {
      $('.selected').removeClass('selected');
    } else {
      $(self).closest('tr').toggleClass('selected');
    }
  });
});

$('.selectProperty').click(function () {
  var self = this;

  $.ajax($(this).data('url')).done(function() {
    console.log('Selected');
  });
});
