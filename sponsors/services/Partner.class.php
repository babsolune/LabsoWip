<?php
/*##################################################
 *                               Partner.class.php
 *                            -------------------
 *   begin                : September 13, 2017
 *   copyright            : (C) 2017 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class Partner
{
	private $id;
	private $id_category;
	private $name;
	private $rewrited_name;
	private $website_url;
	private $contents;
	private $activity;

	private $approbation_type;
	private $creation_date;
	private $author_user;
	private $number_views;

	private $partner_picture;
	private $partner_type;

	private $keywords;

	const NOT_APPROVAL = 0;
	const APPROVAL_NOW = 1;

	const PLATINIUM_PARTNER = 1;
	const GOLDEN_PARTNER = 2;
	const SILVER_PARTNER = 3;
	const BRONZE_PARTNER = 4;

	const DEFAULT_PICTURE = 'templates/images/default.png';

	public function get_id()
	{
		return $this->id;
	}

	public function set_id($id)
	{
		$this->id = $id;
	}

	public function get_id_category()
	{
		return $this->id_category;
	}

	public function set_id_category($id_category)
	{
		$this->id_category = $id_category;
	}

	public function get_category()
	{
		return SponsorsService::get_categories_manager()->get_categories_cache()->get_category($this->id_category);
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_name($name)
	{
		$this->name = $name;
	}

	public function get_rewrited_name()
	{
		return $this->rewrited_name;
	}

	public function set_rewrited_name($rewrited_name)
	{
		$this->rewrited_name = $rewrited_name;
	}

	public function get_website_url()
	{
		if (!$this->website_url instanceof Url)
			return new Url('');

		return $this->website_url;
	}

	public function set_website_url(Url $website_url)
	{
		$this->website_url = $website_url;
	}

	public function has_website_url()
	{
		$website_url = $this->website_url->absolute();
		return !empty($website_url);
	}

	public function get_contents()
	{
		return $this->contents;
	}

	public function set_contents($contents)
	{
		$this->contents = $contents;
	}

	public function get_activity()
	{
		return $this->activity;
	}

	public function set_activity($activity)
	{
		$this->activity = $activity;
	}

	public function get_approbation_type()
	{
		return $this->approbation_type;
	}

	public function set_approbation_type($approbation_type)
	{
		$this->approbation_type = $approbation_type;
	}

	public function is_visible()
	{
		$now = new Date();
		return SponsorsAuthorizationsService::check_authorizations($this->id_category)->read() && ($this->get_approbation_type() == self::APPROVAL_NOW );
	}

	public function get_status()
	{
		switch ($this->approbation_type) {
			case self::APPROVAL_NOW:
				return LangLoader::get_message('status.approved.now', 'common');
			break;
			case self::NOT_APPROVAL:
				return LangLoader::get_message('status.approved.not', 'common');
			break;
		}
	}

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function get_number_views()
	{
		return $this->number_views;
	}

	public function set_number_views($number_views)
	{
		$this->number_views = $number_views;
	}

	public function get_partner_picture()
	{
		if (!$this->partner_picture instanceof Url)
			return new Url($this->partner_picture);

		return $this->partner_picture;
	}

	public function set_partner_picture(Url $partner_picture)
	{
		$this->partner_picture = $partner_picture;
	}

	public function has_partner_picture()
	{
		$picture = $this->partner_picture->rel();
		return !empty($picture);
	}

	public function get_partner_type()
	{
		return $this->partner_type;
	}

	public function set_partner_type($partner_type)
	{
		$this->partner_type = $partner_type;
	}

	public function get_partner_status()
	{
		switch ($this->partner_type) {
			case self::PLATINIUM_PARTNER:
				return LangLoader::get_message('partner.type.platinium', 'common', 'sponsors');
			break;
			case self::GOLDEN_PARTNER:
				return LangLoader::get_message('partner.type.gold', 'common', 'sponsors');
			break;
			case self::SILVER_PARTNER:
				return LangLoader::get_message('partner.type.silver', 'common', 'sponsors');
			break;
			case self::BRONZE_PARTNER:
				return LangLoader::get_message('partner.type.bronze', 'common', 'sponsors');
			break;
		}
	}

	public function get_keywords()
	{
		if ($this->keywords === null)
		{
			$this->keywords = SponsorsService::get_keywords_manager()->get_keywords($this->id);
		}
		return $this->keywords;
	}

	public function get_keywords_name()
	{
		return array_keys($this->get_keywords());
	}

	public function is_authorized_to_add()
	{
		return SponsorsAuthorizationsService::check_authorizations($this->id_category)->write() || SponsorsAuthorizationsService::check_authorizations($this->id_category)->contribution();
	}

	public function is_authorized_to_edit()
	{
		return SponsorsAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((SponsorsAuthorizationsService::check_authorizations($this->id_category)->write() || (SponsorsAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_visible())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function is_authorized_to_delete()
	{
		return SponsorsAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((SponsorsAuthorizationsService::check_authorizations($this->id_category)->write() || (SponsorsAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_visible())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_id_category(),
			'name' => $this->get_name(),
			'rewrited_name' => $this->get_rewrited_name(),
			'website_url' => $this->get_website_url()->absolute(),
			'contents' => $this->get_contents(),
			'activity' => $this->get_activity(),
			'approbation_type' => $this->get_approbation_type(),
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'author_user_id' => $this->get_author_user()->get_id(),
			'number_views' => $this->get_number_views(),
			'partner_picture' => $this->get_partner_picture()->relative(),
			'partner_type' => $this->get_partner_type(),
		);
	}

	public function set_properties(array $properties)
	{
		$this->id = $properties['id'];
		$this->id_category = $properties['id_category'];
		$this->name = $properties['name'];
		$this->rewrited_name = $properties['rewrited_name'];
		$this->website_url = new Url($properties['website_url']);
		$this->contents = $properties['contents'];
		$this->approbation_type = $properties['approbation_type'];
		$this->creation_date = new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE);
		$this->number_views = $properties['number_views'];
		$this->partner_picture = new Url($properties['partner_picture']);
		$this->partner_type = $properties['partner_type'];
		$this->activity = $properties['activity'];

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);
	}

	public function init_default_properties($id_category = Category::ROOT_CATEGORY)
	{
		$this->id_category = $id_category;
		$this->approbation_type = self::APPROVAL_NOW;
		$this->author_user = AppContext::get_current_user();
		$this->creation_date = new Date();
		$this->number_views = 0;
		$this->website_url = new Url('');
		$this->partner_picture = new Url(self::DEFAULT_PICTURE);
		$this->partner_type = self::PLATINIUM_PARTNER;
	}

	public function get_array_tpl_vars()
	{
		$this->lang = LangLoader::get('common', 'sponsors');
		$category = $this->get_category();
		$contents = FormatingHelper::second_parse($this->contents);
		$user = $this->get_author_user();

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date, 'date'),
			array(
			'C_VISIBLE' => $this->is_visible(),
			'C_EDIT' => $this->is_authorized_to_edit(),
			'C_DELETE' => $this->is_authorized_to_delete(),
			'C_WEBSITE_URL' => $this->has_website_url(),

			//Partnerlink
			'ID' => $this->id,
			'NAME' => $this->name,
			'WEBSITE_URL' => $this->website_url->absolute(),
			'CONTENTS' => $contents,
			'STATUS' => $this->get_status(),
			'ACTIVITY' => $this->get_activity(),
			'L_ACTIVITY' => $this->get_activity() ? LangLoader::get_message('activity.' . $this->get_activity(), 'common', 'sponsors') : '',
			'PARTNER_TYPE' => $this->get_partner_status(),
			'NUMBER_VIEWS' => $this->number_views,
			'L_VISITED_TIMES' => StringVars::replace_vars(LangLoader::get_message('visited.times', 'common', 'sponsors'), array('number_visits' => $this->number_views)),

			//Category
			'C_ROOT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY,
			'CATEGORY_ID' => $category->get_id(),
			'CATEGORY_NAME' => $category->get_name(),
			'CATEGORY_DESCRIPTION' => $category->get_description(),
			'CATEGORY_IMAGE' => $category->get_image()->rel(),
			'U_EDIT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY ? SponsorsUrlBuilder::configuration()->rel() : SponsorsUrlBuilder::edit_category($category->get_id())->rel(),

			'U_SYNDICATION' => SyndicationUrlBuilder::rss('sponsors', $this->id_category)->rel(),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_LINK' => SponsorsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_name)->rel(),
			'U_VISIT' => SponsorsUrlBuilder::visit($this->id)->rel(),
			'U_DEADLINK' => SponsorsUrlBuilder::dead_link($this->id)->rel(),
			'U_CATEGORY' => SponsorsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_EDIT' => SponsorsUrlBuilder::edit($this->id)->rel(),
			'U_DELETE' => SponsorsUrlBuilder::delete($this->id)->rel(),
			'U_PARTNER_PICTURE' => $this->partner_picture->rel()
			)
		);
	}
}
?>
