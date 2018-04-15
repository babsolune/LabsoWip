<?php
/*##################################################
 *		      ClubsFormFieldLocation.class.php
 *                            -------------------
 *   begin                : June 23, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
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
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class ClubsFormFieldLocation extends AbstractFormField
{
	private $max_input = 20;

	public function __construct($id, $label, array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();

		$tpl = new FileTemplate('clubs/fields/ClubsFormFieldLocation.tpl');
		$tpl->add_lang(LangLoader::get('common', 'clubs'));
		$config = ClubsConfig::load();

		$tpl->put_all(array(
			'GMAP_API' 	=> $config->is_gmap_api(),
			'NAME' 			=> $this->get_html_id(),
			'ID' 			=> $this->get_html_id(),
			'C_DISABLED'	=> $this->is_disabled()
		));

		$this->assign_common_template_variables($template);

		$i = 0;
		foreach ($this->get_value() as $id => $options)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID' 			=> $i,
				'STREET_NUMBER' => $options['street_number'],
				'ROUTE' 		=> $options['route'],
				'CITY' 			=> $options['city'],
				'POSTAL_CODE' 	=> $options['postal_code']
			));
			$i++;
		}

		if ($i == 0)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID' 			=> $i,
				'STREET_NUMBER' => '',
				'ROUTE' 		=> '',
				'CITY' 			=> '',
				'POSTAL_CODE' 	=> ''
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
				$field_street_number_id 	= 'field_street_number_' . $this->get_html_id() . '_' . $i;
				$field_route_id 			= 'field_route_' . $this->get_html_id() . '_' . $i;
				$field_postal_code_id 		= 'field_postal_code_' . $this->get_html_id() . '_' . $i;
				$field_city_id 				= 'field_city_' . $this->get_html_id() . '_' . $i;

			if ($request->has_postparameter($field_city_id))
			{
				$field_street_number 	= $request->get_poststring($field_street_number_id);
				$field_route 			= $request->get_poststring($field_route_id);
				$field_postal_code 		= $request->get_poststring($field_postal_code_id);
				$field_city 			= $request->get_poststring($field_city_id);

				if (!empty($field_city)) {
					$values[] = array(
						'street_number' => $field_street_number,
						'route' 		=> $field_route,
						'city' 			=> $field_city,
						'postal_code' 	=> $field_postal_code
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
