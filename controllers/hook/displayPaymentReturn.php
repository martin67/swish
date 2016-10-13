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

class SwishDisplayPaymentReturnController
{
    public function __construct($module, $file, $path)
    {
        $this->file = $file;
        $this->module = $module;
        $this->context = Context::getContext();
        $this->_path = $path;
    }

    public function run($params)
    {
        if ($params['objOrder']->payment != $this->module->displayName) {
            return '';
        }

        $reference = $params['objOrder']->id;
        if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference)) {
            $reference = $params['objOrder']->reference;
        }
        $total_to_pay = Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false);

        $this->context->smarty->assign(array(
            'SWISH_OWNER' => Configuration::get('SWISH_OWNER'),
            'SWISH_NUMBER' => Configuration::get('SWISH_NUMBER'),
            'reference' => $reference,
            'total_to_pay' => $total_to_pay,
        ));

        return $this->module->display($this->file, 'displayPaymentReturn.tpl');
    }
}
