/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('live-component', require('./components/LiveComponent.vue'));

const app = new Vue({
  el: '#app',
  data: {
    auction: {
      property: null,
      bids: [],
      finished: false
    }
  }
});

Echo.channel('local')
  .listen('Auction', (e) => {
    console.log('auction', e);
    app.auction = Object.assign(app.auction, e, {finished: false});
  })
  .listen('Bid', (e) => {
    console.log('bid', e);

    app.auction.finished = e.bid.is_winner;

    if (!app.auction.finished) {
      app.auction.bids = [e.bid].concat(app.auction.bids);
    }
  });
