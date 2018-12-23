<?php

/**
 * @package 	SingleMenu
 * @subpackage 	Fields
 * @category 	Modules
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2016 04 21
 * @since   	PHPBoost 5.0 - 2016 04 21
 */

class SingleMenuFormFieldLinks extends AbstractFormField
{
	private $max_input = 200;

	public function __construct($id, $label, array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();

		$tpl = new FileTemplate('SingleMenu/SingleMenuFormFieldLinks.tpl');
		$tpl->add_lang(LangLoader::get('common', 'SingleMenu'));
		$config = SingleMenuConfig::load();

		$tpl->put_all(array(
			'NAME' 			=> $this->get_html_id(),
			'ID' 			=> $this->get_html_id(),
			'C_DISABLED'	=> $this->is_disabled()
		));

		$this->assign_common_template_variables($template);

		$i = 0;
		foreach ($this->get_value() as $id => $options)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID'        =>$i,
				'LINK_URL'  =>$options['link_url'],
				'LINK_NAME' =>$options['link_name'],
				'FA_LINK'  	=>$options['fa_link'],
				'IMG_LINK'  =>$options['img_link']
			));
			$i++;
		}

		if ($i == 0)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID' 				=> $i,
				'LINK_URL'  => '',
				'LINK_NAME' => '',
				'FA_LINK'  	=> '',
				'IMG_LINK'  => ''
			));
		}

		$tpl->put_all(array(
			'MAX_INPUT' => $this->max_input,
			'NBR_FIELDS' => $i == 0 ? 1 : $i
		));

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $tpl->render()
		));

		return $template;
	}

	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$values = array();
		for ($i = 0; $i < $this->max_input; $i++)
		{
			$field_link_url_id 	= 'field_link_url_' . $this->get_html_id() . '_' . $i;

			if ($request->has_postparameter($field_link_url_id))
			{
				$field_link_name_id  = 'field_link_name_' . $this->get_html_id() . '_' . $i;
				$field_link_name	 = $request->get_poststring($field_link_name_id);
				$field_fa_link_id 	 = 'field_fa_link_' . $this->get_html_id() . '_' . $i;
				$field_fa_link 		 = $request->get_poststring($field_fa_link_id);
				$field_img_link_id 	 = 'field_img_link_' . $this->get_html_id() . '_' . $i;
				$field_img_link 	 = $request->get_poststring($field_img_link_id);

				$field_link_url 	 = $request->get_poststring($field_link_url_id);

				if (!empty($field_link_url) && (!empty($field_link_name) || !empty($field_fa_link) || !empty($field_img_link)))
				{
					$values[] = array(
						'link_url'  => $field_link_url,
						'link_name' => $field_link_name,
						'fa_link'   => $field_fa_link,
						'img_link'  => $field_img_link
					);
				}
			}
		}
		$this->set_value($values);
	}

	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
			case 'max_input':
				$this->max_input = $value;
				unset($field_options['max_input']);
				break;
			}
		}
		parent::compute_options($field_options);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>
