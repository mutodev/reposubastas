<paypal
        amount="{{@$mode ? ($mode === 'single' ? '1575.00': '5075.00') : '1575.00'}}"
        currency="USD"
        env="{{ env('PAYPAL_ENV', 'production') }}"
        :client="credentials"
        :experience="experienceOptions"
        :items="myItems.{{@$mode ? $mode : 'single'}}"
        :button-style='{"label":"",size:"small",shape: "rect",color: "gold"}'
        v-on:payment-completed="paymentCompleted"
>
</paypal>
