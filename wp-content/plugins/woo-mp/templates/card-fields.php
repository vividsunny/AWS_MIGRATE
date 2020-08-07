<?php defined( 'ABSPATH' ) || die; ?>

<div class="field-container">
    <label for="cc-num">Card Number</label>
    <input type="tel" id="cc-num" class="field" placeholder="•••• •••• •••• ••••" data-required data-field-name="card number" />
</div>
<div class="field-container half">
    <label for="cc-exp">Expiration</label>
    <input type="text" id="cc-exp" class="field" placeholder="MM / YY" data-required data-field-name="expiration date" />
</div>
<div class="field-container half">
    <label for="cc-cvc">Security Code</label>
    <input type="text" id="cc-cvc" class="field" placeholder="•••" data-field-name="security code" />
</div>
