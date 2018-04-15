<?php
/*##################################################
 *		      AgendaFormFieldContact.class.php
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

class AgendaFormFieldContact extends AbstractFormField
{
	private $max_input = 20;

	public function __construct($id, $label, array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();

		$tpl = new FileTemplate('agenda/field/AgendaFormFieldContact.tpl');
		$tpl->add_lang(LangLoader::get('common', 'agenda'));
		$config = AgendaConfig::load();

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
				'ID' 				=> $i,
				'CONTACT_MAIL' 		=> $options['contact_mail'],
				'CONTACT_PHONE1'	=> $options['contact_phone1'],
				'CONTACT_PHONE2' 	=> $options['contact_phone2'],
				'CONTACT_NAME' 		=> $options['contact_name'],
				'CONTACT_SITE' 		=> $options['contact_site']
			));
			$i++;
		}

		if ($i == 0)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID' 				=> $i,
				'CONTACT_MAIL' 		=> '',
				'CONTACT_PHONE1' 	=> '',
				'CONTACT_PHONE2' 	=> '',
				'CONTACT_NAME' 		=> '',
				'CONTACT_SITE' 		=> ''
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
			$field_contact_name_id 	= 'field_contact_name_' . $this->get_html_id() . '_' . $i;
			$field_contact_site_id 	= 'field_contact_site_' . $this->get_html_id() . '_' . $i;

			if ($request->has_postparameter($field_contact_name_id) || $request->has_postparameter($field_contact_site_id))
			{
				$field_contact_phone1_id 	= 'field_contact_phone1_' . $this->get_html_id() . '_' . $i;
				$field_contact_phone1		= $request->get_poststring($field_contact_phone1_id);
				$field_contact_phone2_id 	= 'field_contact_phone2_' . $this->get_html_id() . '_' . $i;
				$field_contact_phone2 		= $request->get_poststring($field_contact_phone2_id);
				$field_contact_mail_id 		= 'field_contact_mail_' . $this->get_html_id() . '_' . $i;
				$field_contact_mail 		= $request->get_poststring($field_contact_mail_id);

				$field_contact_site 		= $request->get_poststring($field_contact_site_id);
				$field_contact_name		= $request->get_poststring($field_contact_name_id);

				if (!empty($field_contact_name) || !empty($field_contact_site))
				{
					$values[] = array(
						'contact_mail' 		=> $field_contact_mail,
						'contact_phone1' 	=> $field_contact_phone1,
						'contact_phone2' 	=> $field_contact_phone2,
						'contact_name' 		=> $field_contact_name,
						'contact_site' 		=> $field_contact_site
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
