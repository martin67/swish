<div class="box">
	<p class="cheque-indent">
		<strong class="dark">{l s='Your order from %s is complete.' sprintf=$shop_name mod='swish'}</strong>
	</p>

	<p>
        {l s='Now you need to pay the order using Swish on your mobile device.' mod='swish'}
        {l s='Start Swish and enter the following values:' mod='swish'}
    </p><br>
    <p>
        {l s='Recipient:' mod='swish'} <strong>{$SWISH_NUMBER}</strong><br>
        {l s='Amount:' mod='swish'} <strong>{$total_to_pay}</strong><br>
        {l s='Message:' mod='swish'} <strong>{$reference}</strong><br>
    </p>

	<p><br>
        {l s='The recipient listed in Swish should be' mod='swish'} <strong>{$SWISH_OWNER}</strong><br>
	</p><br>

    <p>
        {l s='The above information has also been sent to you in a separate mail.' mod='swish'}<br>
    </p><br>

	<p><strong>{l s='Your order will be sent as soon as we receive payment.' mod='swish'}</strong></p>
</div>