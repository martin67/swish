<?php
/**
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
 */

class SwishValidationModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        // Check if cart exists and all fields are set
        $cart = $this->context->cart;
        if ($cart->id_customer == 0 ||
            $cart->id_address_delivery == 0 ||
            $cart->id_address_invoice == 0 ||
            !$this->module->active) {
            Tools::redirect('index.php?controller=order&step=1');
        }

        // Check if module is enabled
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == $this->module->name) {
                $authorized = true;
            }
        }
        if (!$authorized) {
            die('This payment method is not available.');
        }

        // Check if customer exists
        $customer = new Customer($cart->id_customer);
        if (!Validate::isLoadedObject($customer)) {
            Tools::redirect('index.php?controller=order&step=1');
        }

        // Set datas
        $currency = $this->context->currency;
        $total = (float) $cart->getOrderTotal(true, Cart::BOTH);
        $extra_vars = array(
            '{total_to_pay}' => Tools::displayPrice($total),
            '{swish_owner}' => Configuration::get('SWISH_OWNER'),
            '{swish_number}' => Configuration::get('SWISH_NUMBER'),
        );

        // Validate order
        $this->module->validateOrder(
            $cart->id,
            Configuration::get('PS_OS_SWISH'),
            $total,
            $this->module->displayName,
            null,
            $extra_vars,
            (int) $currency->id,
            false,
            $customer->secure_key
        );

        // Redirect on order confirmation page
        Tools::redirect(
            'index.php?controller=order-confirmation&id_cart='.$cart->id.'&id_module='.$this->module->id.'&id_order='.$this->module->currentOrder.'&key='.$customer->secure_key
        );
    }
}
