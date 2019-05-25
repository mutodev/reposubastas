/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import * as VueGoogleMaps from "vue2-google-maps";
import PayPal from 'vue-paypal-checkout';
import axios from 'axios';

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
      sandbox: 'AeAml9Oxn5lzKF1Hk1slRa1ck-a0GbUIDUtk0jHHF9GKCqHrJbCrIaEyxYE2mKuL20-oZkwVxxk4',
      production: 'AeAml9Oxn5lzKF1Hk1slRa1ck-a0GbUIDUtk0jHHF9GKCqHrJbCrIaEyxYE2mKuL20-oZkwVxxk4'
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
    paymentAuthorized: function (data) {
      console.log('Authorized', data);
    },
    paymentCompleted: async function (data) {
      console.log('Completed', data);

      await axios.post(window.location.href, data);
    },
    paymentCancelled: function (data) {
      console.log('Cancelled', data);
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

$('.bulkSendOffer').click(function (event) {
  event.preventDefault();
  var self = this;

  var offer = $('.bulk-total-offer').val();

  if (!offer) {
    alert('Offer is required');
    return;
  }

  var name = $('.bulk-name').val();

  if (!name) {
    alert('Name is required');
    return;
  }

  var phone = $('.bulk-phone').val();

  if (!phone) {
    alert('Phone is required');
    return;
  }

  $.ajax($(this).data('url')+'?'+jQuery.param({
  offer: offer,
  name: name,
  phone: phone,
})).done(function() {
    $('.bulk-total-offer').val('');
    $('.bulk-name').val('');
    $('.bulk-phone').val('');
    alert('Thanks, offer sent!');
  });
});
