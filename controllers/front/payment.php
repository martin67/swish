<?php
/*
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
 */

class SwishPaymentModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    private function checkCurrency()
    {
        // Get cart currency and enabled currencies for this module
        $currency_order = new Currency($this->context->cart->id_currency);
        $currencies_module = $this->module->getCurrency($this->context->cart->id_currency);
        // Check if cart currency is one of the enabled currencies
        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }
        // Return false otherwise
        return false;
    }

    public function initContent()
    {
        // Disable left and right column
        $this->display_column_left = false;
        $this->display_column_right = false;

        // Call parent init content method
        parent::initContent();

        // Check if currency is accepted
        if (!$this->checkCurrency()) {
            Tools::redirect('index.php?controller=order');
        }

        // Assign data to Smarty
        $this->context->smarty->assign(array(
                                             'nb_products' => $this->context->cart->nbProducts(),
                                             'cart_currency' => $this->context->cart->id_currency,
                                             'currencies' => $this->module->getCurrency((int) $this->context->cart->id_currency),
                                             'total_amount' => $this->context->cart->getOrderTotal(true, Cart::BOTH),
                                             'path' => $this->module->getPathUri(),
                                             ));

        // Set template
        $this->setTemplate('payment.tpl');
    }
}
