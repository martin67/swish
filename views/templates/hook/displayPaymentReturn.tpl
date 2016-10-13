{*
* MIT License
*
* Copyright (c) 2016 Martin Hagelin
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to
* deal in the Software without restriction, including without limitation the
* rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
* sell copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
* FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
* IN THE SOFTWARE.
*
* @author     Martin Hagelin
* @copyright  2016 Martin Hagelin
* @license    MIT License, https://opensource.org/licenses/MIT
*
*}

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