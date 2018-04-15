<?php
/*##################################################
 *		      NetworkLinksFormFieldLinks.class.php
 *                            -------------------
 *   begin                : April 21, 2016
 *   copyright            : (C) 2016 Sebastien Lartigue
 *   email                : babso@web33.fr
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien Lartigue <babso@web33.fr>
 */

class NetworkLinksFormFieldLinks extends AbstractFormField
{
	private $max_input = 200;

	public function __construct($id, $label, array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();

		$tpl = new FileTemplate('NetworkLinks/NetworkLinksFormFieldLinks.tpl');
		$tpl->add_lang(LangLoader::get('common', 'NetworkLinks'));
		$config = NetworkLinksConfig::load();

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
