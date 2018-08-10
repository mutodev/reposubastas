<template>
    <div v-if="!auction.property">
        <table style="height: 500px;width: 100%">
            <tr>
                <td style="text-align: center; vertical-align: middle;">
                    <img src="/images/logo.png" />
                </td>
            </tr>
        </table>
    </div>
    <div v-else-if="auction.property">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <table style="height: 100%;width: 100%">
                        <tr>
                            <td style="ertical-align: middle;">
                                <div class="card">
                                    <div class="wm">
                                        <img class="card-img-top" width="100%" style="max-height: 600px" v-bind:src="'https://s3.amazonaws.com/reposubastas/'+auction.property['image'+auction.property.main_image]">
                                    </div>
                                    <div class="property-badges">
                                        <span class="badge badge-dark">{{ auction.propertyEvent.number }}</span>
                                        <span v-if="auction.property.status_id && auction.property.status.is_public" class="badge badge-danger">{{ auction.property.status.name_es }}</span>
                                        <span v-if="auction.property.is_cash_only" class="badge badge-danger">Solo Cash</span>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title mb-0">{{ auction.property.address }}, {{ auction.property.city }}</h5>
                                        <!--<p class="card-text text-muted">-->
                                        <!--Tipo: {{ auction.property.type.name_es }}-->

                                        <!--<span v-if="auction.property.bedrooms">-->
                                        <!--<br />-->
                                        <!--Cuartos: {{ auction.property.bedrooms }}-->
                                        <!--</span>-->

                                        <!--<span v-if="auction.property.bathrooms">-->
                                        <!--<br />-->
                                        <!--Baños: {{ auction.property.bathrooms }}-->
                                        <!--</span>-->

                                        <!--<span v-if="auction.property.sqf_area">-->
                                        <!--<br />-->
                                        <!--Pies cuadrados: {{ auction.property.sqf_area }}-->
                                        <!--</span>-->

                                        <!--<span v-if="auction.property.sqm_area">-->
                                        <!--<br />-->
                                        <!--Metros cuadrados: {{ auction.property.sqm_area }}-->
                                        <!--</span>-->

                                        <!--<span v-if="auction.property.cuerdas">-->
                                        <!--<br />-->
                                        <!--Cuerdas: {{ auction.property.cuerdas }}-->
                                        <!--</span>-->
                                        <!--</p>-->
                                    </div>
                                    <!--<ul class="list-group list-group-flush">-->
                                    <!--<li class="list-group-item border-0 bg-dark-blue">-->
                                    <!--<span>Precio de venta: {{ price(auction.property.price) }}</span>-->
                                    <!--</li>-->
                                    <!--</ul>-->
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col">
                    <table style="height: 100%;width: 100%">
                        <tr>
                            <td class="bidingArea" style="text-align: center; vertical-align: middle;">
                                <img src="/images/logocenter.png" width="50%" />
                                <br />
                                <br />
                                <h2>Licitación</h2>
                                <h1 id="currentBid" class="align-middle" v-for="bid in auction.bids.slice(0, 1)">
                                    <span v-if="auction.bids">{{ price(auction.bids[0].offer) }}</span>
                                    <span v-else>&nbsp;</span>
                                </h1>
                            </td>
                        </tr>
                    </table>
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
