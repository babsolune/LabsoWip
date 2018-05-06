<?php
/*##################################################
 *                        Division.class.php
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

class Division
{
    private $id;
    private $name;
    private $rewrited_name;
	private $author_user;
	private $is_published;

    const NOT_PUBLISHED = 0;
    const PUBLISHED = 1;

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

	public function set_rewrited_name($rewrited_name)
	{
		$this->rewrited_name = $rewrited_name;
	}

	public function get_rewrited_name()
	{
		return $this->rewrited_name;
	}

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function published()
	{
		$this->publication = true;
	}

	public function not_published()
	{
		$this->publication = false;
	}

	public function is_published()
	{
		return $this->publication;
	}

	public function get_status()
	{
		switch ($this->is_published()) {
			case self::PUBLISHED:
				return LangLoader::get_message('divisions.published', 'division', 'tsm');
			break;
			case self::NOT_PUBLISHED:
				return LangLoader::get_message('divisions.not.published', 'division', 'tsm');
			break;
		}
	}

	public function is_authorized_to_add()
	{
		return TsmDivisionsAuthService::check_division_auth($this->id)->moderation_division() && AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL);
	}

	public function is_authorized_to_edit()
	{
		return TsmDivisionsAuthService::check_division_auth($this->id)->moderation_division() && AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL);
	}

	public function is_authorized_to_delete()
	{
        return TsmDivisionsAuthService::check_division_auth($this->id)->moderation_division() && AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL);
    }

    public function get_properties()
    {
        return array(
            'id' => $this->get_id(),
            'name' => $this->get_name(),
            'rewrited_name' => $this->get_rewrited_name(),
			'author_user_id'  => $this->get_author_user()->get_id(),
			'publication'     => (int)$this->is_published(),
        );
    }

    public function set_properties(array $properties)
    {
		$this->set_id($properties['id']);
		$this->set_name($properties['name']);
		$this->set_rewrited_name($properties['rewrited_name']);

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);

		if ($properties['publication'])
			$this->published();
		else
			$this->not_published();
    }

    public function init_default_properties()
	{
        $this->author_user = AppContext::get_current_user();
		if (TsmDivisionsAuthService::check_division_auth()->write_division())
			$this->published();
		else
			$this->not_published();
    }

	public function get_array_tpl_vars()
	{
        return array(
            'C_MODERATE' => $this->is_authorized_to_edit() || $this->is_authorized_to_delete(),
			'C_EDIT' => $this->is_authorized_to_edit(),
			'C_DELETE' => $this->is_authorized_to_delete(),

            'NAME' => $this->get_name(),
        );
    }
}
?>
