<?php
/*##################################################
 *                        Club.class.php
 *                            -------------------
 *   begin                : February 13, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
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

class Club
{
    private $id;
    private $name;
    private $logo_url;
    private $website_url;

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id()
	{
		return $this->id;
	}

	public function set_name($name)
	{
		$this->name = $name;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_logo(Url $logo)
	{
		$this->logo_url = $logo;
	}

	public function get_logo()
	{
		return $this->logo_url;
	}

	public function has_logo()
	{
		$logo_url = $this->logo_url->rel();
		return !empty($logo);
	}

	public function set_website(Url $website)
	{
		$this->website_url = $website;
	}

	public function get_website()
	{
		return $this->website_url;
	}

	public function has_website()
	{
		$website_url = $this->website_url->rel();
		return !empty($website);
	}

    public function get_properties()
    {
        return array(
            'id'          => $this->get_id(),
            'name'        => $this->get_name(),
			'logo_url'    => $this->get_logo()->relative(),
			'website_url' => $this->get_website()->relative(),
        );
    }

    public function set_properties(array $properties)
    {
		$this->set_id($properties['id']);
		$this->set_name($properties['name']);
		$this->set_logo(new Url($properties['logo_url']));
		$this->set_website(new Url($properties['website_url']));
    }

    public function init_default_properties()
	{
        $this->logo_url = new Url('');
        $this->website_url = new Url('');
    }

	public function get_array_tpl_vars()
	{
        return array(
            'C_HAS_LOGO'    => $this->has_logo(),
            'C_HAS_WEBSITE' => $this->has_website(),
            'ID'            => $this->get_id(),
            'NAME'          => $this->get_name(),
			'U_LOGO'        => $this->get_logo()->rel(),
			'U_WEBSITE'     => $this->get_website()->rel(),
        );
    }
}
?>
