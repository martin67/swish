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
 */

class Swish extends PaymentModule
{
    public function __construct()
    {
        $this->name = 'swish';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.0';
        $this->author = 'Martin Hagelin';
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Swish');
        $this->description = $this->l('Accept payments with Swish.');
    }

    public function install()
    {
        if (!parent::install()
            || !$this->registerHook('displayPayment')
            || !$this->registerHook('displayPaymentReturn')) {
            return false;
        }

        if (!$this->installOrderState()) {
            return false;
        }

        return true;
    }

    public function installOrderState()
    {
        if (Configuration::get('PS_OS_SWISH') < 1) {
            $order_state = new OrderState();
            $order_state->send_email = true;
            $order_state->module_name = $this->name;
            $order_state->invoice = false;
            $order_state->color = 'RoyalBlue';
            $order_state->logable = true;
            $order_state->shipped = false;
            $order_state->unremovable = false;
            $order_state->delivery = false;
            $order_state->hidden = false;
            $order_state->paid = false;
            $order_state->deleted = false;
            $order_state->name = array((int) Configuration::get('PS_LANG_DEFAULT') => pSQL($this->l('Awaiting Swish payment')));
            $order_state->template = array();
            foreach (LanguageCore::getLanguages() as $l) {
                $order_state->template[$l['id_lang']] = 'swish';
            }

            // We copy the mails templates in mail directory
            foreach (LanguageCore::getLanguages() as $l) {
                $module_path = dirname(__FILE__).'/views/templates/mails/'.$l['iso_code'].'/';
                $application_path = dirname(__FILE__).'/../../mails/'.$l['iso_code'].'/';
                if (!copy($module_path.'swish.txt', $application_path.'swish.txt') ||
                    !copy($module_path.'swish.html', $application_path.'swish.html')) {
                    return false;
                }
            }

            if ($order_state->add()) {
                // We save the order State ID in Configuration database
                Configuration::updateValue('PS_OS_SWISH', $order_state->id);

                // We copy the module logo in order state logo directory
                copy(dirname(__FILE__).'/logo.gif', dirname(__FILE__).'/../../img/os/'.$order_state->id.'.gif');
                copy(dirname(__FILE__).'/logo.gif', dirname(__FILE__).'/../../img/tmp/order_state_mini_'.$order_state->id.'.gif');
            } else {
                return false;
            }
        }

        return true;
    }

    public function uninstall()
    {
        if (!Configuration::deleteByName('SWISH_OWNER')
            || !Configuration::deleteByName('SWISH_NUMBER')
            || !$this->uninstallOrderState()
            || !parent::uninstall()) {
            return false;
        }

        return true;
    }

    public function uninstallOrderState()
    {
        $id_order_state = Configuration::get('PS_OS_SWISH');
        if ($id_order_state > 0) {
            $orderState = new OrderState($id_order_state);
            $orderState->delete();
        }
        Configuration::deleteByName('PS_OS_SWISH');

        return true;
    }

    public function getHookController($hook_name)
    {
        // Include the controller file
        require_once dirname(__FILE__).'/controllers/hook/'.$hook_name.'.php';

        // Build dynamically the controller name
        $controller_name = $this->name.$hook_name.'Controller';
        //$controller_name = 'Swish'.$hook_name.'Controller';

        // Instantiate controller
        $controller = new $controller_name($this, __FILE__, $this->_path);

        // Return the controller
        return $controller;
    }

    public function hookDisplayPayment($params)
    {
        $controller = $this->getHookController('displayPayment');

        return $controller->run($params);
    }

    public function hookDisplayPaymentReturn($params)
    {
        $controller = $this->getHookController('displayPaymentReturn');

        return $controller->run($params);
    }

    public function getContent()
    {
        $controller = $this->getHookController('getContent');

        return $controller->run();
    }
}
