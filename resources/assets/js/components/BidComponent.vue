<template>
    <span >
        <span v-if="bid">{{ price(bid.offer) }}</span>
        <span v-else>{{ price(current) }}</span>
    </span>
</template>

<script>
  export default {
    props: ['property', 'current'],
    data: function() {
      return {
        bid: null
      }
    },
    methods: {
      price: function (n) {
        return "$" + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",").replace('.00', '');
      }
    },
    mounted: function() {
      console.log(this, this.property, this.current);

      Echo.channel('local')
        .listen('Bid', (e) => {
          if (this.property === e.bid.property_id) {
            this.bid = e.bid;
          }
        });
    }
  }
</script>
