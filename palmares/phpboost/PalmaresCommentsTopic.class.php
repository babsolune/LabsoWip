<?php
/*##################################################
 *                           PalmaresCommentsTopic.class.php
 *                            -------------------
 *   begin                : May 30, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class PalmaresCommentsTopic extends CommentsTopic
{
	private $palmares;
	
	public function __construct(Palmares $palmares = null)
	{
		parent::__construct('palmares');
		$this->palmares = $palmares;
	}
	
	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(PalmaresAuthorizationsService::check_authorizations($this->get_palmares()->get_id_cat())->read());
		return $authorizations;
	}
	
	public function is_display()
	{
		return $this->get_palmares()->is_visible();
	}

	private function get_palmares()
	{
		if ($this->palmares === null)
		{
			$this->palmares = PalmaresService::get_palmares('WHERE id=:id', array('id' => $this->get_id_in_module()));
		}
		return $this->palmares;
	}
}
?>