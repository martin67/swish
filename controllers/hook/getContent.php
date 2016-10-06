<?php

class SwishGetContentController
{
	public function __construct($module, $file, $path)
	{
		$this->file = $file;
		$this->module = $module;
		$this->context = Context::getContext(); $this->_path = $path;
	}

	public function processConfiguration()
	{
		if (Tools::isSubmit('swish_form'))
		{
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
					'icon' => 'icon-wrench'
				),
				'input' => $inputs,
				'submit' => array('title' => $this->module->l('Save'))
			)
		);

		$helper = new HelperForm();
		$helper->table = 'swish';
		$helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$helper->allow_employee_form_lang = (int)Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
		$helper->submit_action = 'swish_form';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->module->name.'&tab_module='.$this->module->tab.'&module_name='.$this->module->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => array(
				'SWISH_OWNER' => Tools::getValue('SWISH_OWNER', Configuration::get('SWISH_OWNER')),
				'SWISH_NUMBER' => Tools::getValue('SWISH_NUMBER', Configuration::get('SWISH_NUMBER')),
			),
			'languages' => $this->context->controller->getLanguages()
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
