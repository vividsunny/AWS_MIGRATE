<?php defined( 'ABSPATH' ) || die; ?>

<?php $this->template( 'card-fields' ); ?>

<?php $this->template( 'charge-amount-field' ); ?>

<div class="field-container">
    <button type="button" id="toggle-additional-details" class="button field collapse-arrow" data-toggle="collapse" aria-controls="additional-details" aria-expanded="false">More</button>
</div>

<div id="additional-details" class="field-container" hidden>
    <div class="field-container third">
        <label for="tax-amount">Tax Amount</label>
        <input type="number" min="0" step="any" id="tax-amount" class="field money-field" placeholder="0" data-field-name="tax amount" />
    </div>
    <div class="field-container third">
        <label for="freight-amount">Freight Amount</label>
        <input type="number" min="0" step="any" id="freight-amount" class="field money-field" placeholder="0" data-field-name="freight amount" />
    </div>
    <div class="field-container third">
        <label for="duty-amount">Duty Amount</label>
        <input type="number" min="0" step="any" id="duty-amount" class="field money-field" placeholder="0" data-field-name="duty amount" />
    </div>
    <div class="field-container">
        <label for="po-number">PO Number</label>
        <input type="text" id="po-number" class="field" placeholder="Purchase Order Number" />
    </div>
    <div class="field-container checkbox-field-container">
        <label class="checkbox-label">Tax Exempt<input type="checkbox" id="tax-exempt" class="field" /></label>
    </div>
</div>
