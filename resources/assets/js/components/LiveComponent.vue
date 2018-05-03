<template>
    <div v-if="auction.finished">
        finished
    </div>
    <div v-else-if="auction.property">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <img class="card-img-top" src="https://assets.entrepreneur.com/content/3x2/1300/20150622231001-for-sale-real-estate-home-house.jpeg?width=700&crop=2:1" alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title">#{{ auction.propertyEvent.number }} <span class="float-right">{{ auction.property.city }}</span></h5>
                            <h4 class="card-title">Precio: {{ price(auction.property.price) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            {{ 'Offers' }}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title" v-for="bid in auction.bids.slice(0, 5)">
                                #{{ bid.number }} - {{ price(bid.offer) }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    props: ['auction'],
    mounted() {
      console.log('Component mounted. Esteeee');
    },
    methods: {
      price: function (n) {
        return "$" + n.replace(/(\d)(?=(\d{3})+\.)/g, "$1,").replace('.00', '');
      }
    }
  }
</script>
