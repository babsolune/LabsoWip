<?php
/*##################################################
 *		      AgendaFormFieldLocation.class.php
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

class AgendaFormFieldLocation extends AbstractFormField
{
	private $max_input = 20;

	public function __construct($id, $label, array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();

		$tpl = new FileTemplate('agenda/field/AgendaFormFieldLocation.tpl');
		$tpl->add_lang(LangLoader::get('common', 'agenda'));
		$config = AgendaConfig::load();

		$tpl->put_all(array(
			'GMAP_API_KEY' 	=> 'AIzaSyDa_Ph-ORGTmXcYdNjw7MS5svx_6W7t_5A'/*$config->get_gapi_key()*/,
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
				'STATE' 		=> $options['state'],
				'DEPARTMENT' 	=> $options['department'],
				'POSTAL_CODE' 	=> $options['postal_code'],
				'COUNTRY' 		=> $options['country']
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
				'STATE' 		=> '',
				'DEPARTMENT' 	=> '',
				'POSTAL_CODE' 	=> '',
				'COUNTRY' 		=> ''
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
				$field_department_id 		= 'field_department_' . $this->get_html_id() . '_' . $i;
				$field_state_id 			= 'field_state_' . $this->get_html_id() . '_' . $i;
				$field_country_id 			= 'field_country_' . $this->get_html_id() . '_' . $i;

			if ($request->has_postparameter($field_city_id))
			{
				$field_street_number 	= $request->get_poststring($field_street_number_id);
				$field_route 			= $request->get_poststring($field_route_id);
				$field_postal_code 		= $request->get_poststring($field_postal_code_id);
				$field_city 			= $request->get_poststring($field_city_id);
				$field_department 		= $request->get_poststring($field_department_id);
				$field_state 			= $request->get_poststring($field_state_id);
				$field_country 			= $request->get_poststring($field_country_id);

				if (!empty($field_city)) {
					$values[] = array(
						'street_number' => $field_street_number,
						'route' 		=> $field_route,
						'city' 			=> $field_city,
						'postal_code' 	=> $field_postal_code,
						'department' 	=> $field_department,
						'state' 		=> $field_state,
						'country' 		=> $field_country
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
