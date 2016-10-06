{capture name=path}
    {l s='Swish Payment' mod='swish'}
{/capture}


<h1 class="page-heading">
{l s='Order summary' mod='swish'}
</h1>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nb_products <= 0}
    <p class="alert alert-warning">
        {l s='Your shopping cart is empty.' mod='swish'}
    </p>
{else}
    <form action="{$link->getModuleLink('swish', 'validation', [], true)|escape:'html'}" method="post">
	<div class="box cheque-box">
		<h3 class="page-subheading">
            {l s='Swish Payment' mod='swish'}
		</h3>
		<p class="cheque-indent">
			<strong class="dark">
                {l s='You have chosen to pay with Swish.' mod='swish'} {l s='Here is a short summary of your order:' mod='swish'}
			</strong>
		</p>
		<p>
			- {l s='The total amount of your order is' mod='swish'}
			<span id="amount" class="price">{displayPrice price=$total_amount}</span>
            {if $use_taxes == 1}
                {l s='(tax incl.)' mod='swish'}
            {/if}
		</p>
		<p>
			- {l s='Swish payment information will be displayed on the next page.' mod='swish'}
			<br />
			- {l s='Please confirm your order by clicking "I confirm my order"' mod='swish'}.
		</p>
	</div><!-- .cheque-box -->

	<p class="cart_navigation clearfix" id="cart_navigation">
		<a
				class="button-exclusive btn btn-default"
				href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}">
			<i class="icon-chevron-left"></i>{l s='Other payment methods' mod='swish'}
		</a>
		<button
				class="button btn btn-default button-medium"
				type="submit">
			<span>{l s='I confirm my order' mod='swish'}<i class="icon-chevron-right right"></i></span>
		</button>
	</p>
    </form>
{/if}
