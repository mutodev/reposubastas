<paypal
        amount="1575.00"
        currency="USD"
        :client="credentials"
        :button-style='{"label":"checkout",size:"small",shape: "rect",color: "gold"}'
        v-on:payment-authorized="paymentAuthorized"
        v-on:payment-completed="paymentCompleted"
        v-on:payment-cancelled="paymentCancelled"
>
</paypal>
