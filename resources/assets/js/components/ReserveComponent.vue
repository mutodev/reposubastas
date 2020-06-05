<template>
    <div>
        <div  class="alert alert-danger" v-if="met()">
            {{labelmet}}
        </div>
        <div class="alert alert-info" v-else>{{ labelnotmet }}</div>
    </div>
</template>

<script>
  export default {
    props: ['property', 'reserve', 'current', 'labelmet', 'labelnotmet'],
    data: function() {
      return {
        bid: null
      }
    },
    methods: {
      price: function (n) {
        return "$" + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",").replace('.00', '');
      },
      met: function() {
        return (this.bid && this.bid.offer >= this.reserve) || this.current && this.current >= this.reserve;
      },
      activateBlinking: function() {
        const blinkElements = jQuery('.blink-'+this.property);
        var theinterval = setInterval(function(){
          blinkElements.toggleClass('blink-red');
        }, 500);
      }
    },
    mounted: function() {
      console.log(this, this.property, this.current, this.reserve);

      Echo.channel('local')
        .listen('Bid', (e) => {
          if (this.property === e.bid.property_id) {
            this.bid = e.bid;

            if (this.met()) {
              this.activateBlinking();
            }
          }
        });

      if (this.met()) {
        this.activateBlinking();
      }
    }
  }
</script>
