<?php
/*##################################################
 *		      AgendaFormFieldPath.class.php
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

class AgendaFormFieldPath extends AbstractFormField
{
	private $max_input = 20;

	public function __construct($id, $label, array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();

		$tpl = new FileTemplate('agenda/field/AgendaFormFieldPath.tpl');
		$tpl->add_lang(LangLoader::get('common', 'agenda'));
		$config = AgendaConfig::load();

		$tpl->put_all(array(
			'NAME' 				=> $this->get_html_id(),
			'ID' 					=> $this->get_html_id(),
			'C_DISABLED'	=> $this->is_disabled()
		));

		$this->assign_common_template_variables($template);

		$i = 0;
		foreach ($this->get_value() as $id => $options)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID' 				=> $i,
				'PATH_TYPE' 		=> $options['path_type'],
				'PATH_LENGTH'		=> $options['path_length'],
				'PATH_ELEVATION' 	=> $options['path_elevation'],
				'PATH_LEVEL' 		=> $options['path_level']
			));
			$i++;
		}

		if ($i == 0)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID' 				=> $i,
				'PATH_TYPE' 		=> '',
				'PATH_LENGTH' 		=> '',
				'PATH_ELEVATION' 	=> '',
				'PATH_LEVEL' 		=> ''
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
			$field_path_type_id = 'field_path_type_' . $this->get_html_id() . '_' . $i;

			if ($request->has_postparameter($field_path_type_id))
			{
				$field_path_length_id 		= 'field_path_length_' . $this->get_html_id() . '_' . $i;
				$field_path_length			= $request->get_poststring($field_path_length_id);
				$field_path_elevation_id 	= 'field_path_elevation_' . $this->get_html_id() . '_' . $i;
				$field_path_elevation 		= $request->get_poststring($field_path_elevation_id);
				$field_path_level_id 		= 'field_path_level_' . $this->get_html_id() . '_' . $i;
				$field_path_level	 		= $request->get_poststring($field_path_level_id);

				$field_path_type 			= $request->get_poststring($field_path_type_id);

				if (!empty($field_path_type))
				{
					$values[] = array(
						'path_type' 		=> $field_path_type,
						'path_length' 		=> $field_path_length,
						'path_elevation' 	=> $field_path_elevation,
						'path_level' 		=> $field_path_level
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
