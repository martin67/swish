<?php

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
