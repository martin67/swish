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

class SwishGetContentController
{
    public function __construct($module, $file, $path)
    {
        $this->file = $file;
        $this->module = $module;
        $this->context = Context::getContext();
        $this->_path = $path;
    }

    public function processConfiguration()
    {
        if (Tools::isSubmit('swish_form')) {
            Configuration::updateValue('SWISH_OWNER', Tools::getValue('SWISH_OWNER'));
            Configuration::updateValue('SWISH_NUMBER', Tools::getValue('SWISH_NUMBER'));
            $this->context->smarty->assign('confirmation', 'ok');
        }
    }

    public function renderForm()
    {
        $inputs = array(
            array('name' => 'SWISH_OWNER', 'label' => $this->module->l('Swish owner'), 'type' => 'text'),
            array('name' => 'SWISH_NUMBER', 'label' => $this->module->l('Swish number'), 'type' => 'text'),
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->module->l('Swish configuration'),
                    'icon' => 'icon-wrench',
                ),
                'input' => $inputs,
                'submit' => array('title' => $this->module->l('Save')),
            ),
        );

        $helper = new HelperForm();
        $helper->table = 'swish';
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = (int) Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        $helper->submit_action = 'swish_form';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->module->name.'&tab_module='.$this->module->tab.'&module_name='.$this->module->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => array(
                'SWISH_OWNER' => Tools::getValue('SWISH_OWNER', Configuration::get('SWISH_OWNER')),
                'SWISH_NUMBER' => Tools::getValue('SWISH_NUMBER', Configuration::get('SWISH_NUMBER')),
            ),
            'languages' => $this->context->controller->getLanguages(),
        );

        return $helper->generateForm(array($fields_form));
    }

    public function run()
    {
        $this->processConfiguration();
        $html_confirmation_message = $this->module->display($this->file, 'getContent.tpl');
        $html_form = $this->renderForm();

        return $html_confirmation_message.$html_form;
    }
}
