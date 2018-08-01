<template>
    <div v-if="auction.finished">
        finished
    </div>
    <div v-else-if="auction.property">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="wm">
                            <img class="card-img-top" width="100%" v-bind:src="'https://s3.amazonaws.com/reposubastas/'+auction.property['image'+auction.property.main_image]">
                        </div>
                        <div class="property-badges">
                            <span class="badge badge-dark">{{ auction.propertyEvent.number }}</span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ auction.property.address }}, {{ auction.property.city }}</h5>
                            <p class="card-text text-muted">
                                Tipo: {{ auction.property.type.name_es }}

                                <span v-if="auction.property.bedrooms">
                                    <br />
                                    Cuartos: {{ auction.property.bedrooms }}
                                </span>

                                <span v-if="auction.property.bathrooms">
                                    <br />
                                    Baños: {{ auction.property.bathrooms }}
                                </span>

                                <span v-if="auction.property.sqf_area">
                                    <br />
                                    Pies cuadrados: {{ auction.property.sqf_area }}
                                </span>

                                <span v-if="auction.property.sqm_area">
                                    <br />
                                    Metros cuadrados: {{ auction.property.sqm_area }}
                                </span>

                                <span v-if="auction.property.cuerdas">
                                    <br />
                                    Cuerdas: {{ auction.property.cuerdas }}
                                </span>
                            </p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 bg-dark-blue">
                                <span>Precio de venta: {{ price(auction.property.price) }}</span>
                            </li>
                        </ul>
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
    methods: {
      price: function (n) {
        return "$" + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",").replace('.00', '');
      }
    }
  }
</script>
