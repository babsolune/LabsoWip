<?php
/*##################################################
 *                               Product.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class Product
{
	private $id;
	private $id_category;
	private $name;
	private $rewrited_name;
	private $product_url;
	private $download_enabled;
	private $size;
	private $formated_size;
	private $contents;
	private $description;
	private $promotion;
	private $promotion_enabled;
	private $flash_sales_enabled;
	private $price;

	private $product_color;
	private $product_size;
	private $product_details;
	private $carousel;

	private $approbation_type;
	private $start_date;
	private $end_date;
	private $end_date_enabled;

	private $creation_date;
	private $updated_date;
	private $number_view;
	private $author_user;
	private $author_custom_name;
	private $author_custom_name_enabled;

	private $picture_url;
	private $number_downloads;
	private $notation;
	private $keywords;

	const SORT_ALPHABETIC       = 'name';
	const SORT_AUTHOR           = 'display_name';
	const SORT_DATE             = 'creation_date';
	const SORT_UPDATED_DATE     = 'updated_date';
	const SORT_NOTATION         = 'average_notes';
	const SORT_NUMBER_COMMENTS  = 'number_comments';
	const SORT_NUMBER_DOWNLOADS = 'number_downloads';
	const SORT_NUMBER_VIEWS 	= 'number_view';

	const ASC  = 'ASC';
	const DESC = 'DESC';

	const NOT_APPROVAL = 0;
	const APPROVAL_NOW = 1;
	const APPROVAL_DATE = 2;

	const DEFAULT_PICTURE = '/catalog/catalog.png';

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
		return CatalogService::get_categories_manager()->get_categories_cache()->get_category($this->id_category);
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

	public function get_product_url()
	{
		if (!$this->product_url instanceof Url)
			return new Url('');

		return $this->product_url;
	}

	public function set_product_url(Url $product_url)
	{
		$this->product_url = $product_url;
	}

	public function has_link()
	{
		$product_link = $this->product_url->rel();
		return !empty($product_link);
	}

	public function is_downloadable()
	{
		return $this->download_enabled;
	}

	public function get_size()
	{
		return $this->size;
	}

	public function set_size($size)
	{
		$this->size = $size;
	}

	public function get_contents()
	{
		return $this->contents;
	}

	public function set_contents($contents)
	{
		$this->contents = $contents;
	}

	public function get_description()
	{
		return $this->description;
	}

	public function set_description($description)
	{
		$this->description = $description;
	}

	public function is_description_enabled()
	{
		return !empty($this->description);
	}

	public function get_real_description()
	{
		if ($this->is_description_enabled())
		{
			return FormatingHelper::second_parse($this->description);
		}
		return TextHelper::cut_string(@strip_tags(FormatingHelper::second_parse($this->contents), '<br><br/>'), (int)CatalogConfig::NUMBER_CARACTERS_BEFORE_CUT);
	}

	public function set_promotion($promotion)
	{
		$this->promotion = $promotion;
	}

	public function get_promotion()
	{
		return $this->promotion;
	}

	public function set_promotion_enabled($promotion_enabled)
	{
		$this->promotion_enabled = $promotion_enabled;
	}

	public function get_promotion_enabled()
	{
		return $this->promotion_enabled;
	}

	public function set_flash_sales_enabled($flash_sales_enabled)
	{
		$this->flash_sales_enabled = $flash_sales_enabled;
	}

	public function get_flash_sales_enabled()
	{
		return $this->flash_sales_enabled;
	}

	public function set_price($price)
	{
		$this->price = $price;
	}

	public function get_price()
	{
		return $this->price;
	}

	public function add_carousel_picture($carousel_picture)
	{
		$this->carousel[] = $carousel_picture;
	}

	public function set_carousel($carousel)
	{
		$this->carousel = $carousel;
	}

	public function get_carousel()
	{
		return $this->carousel;
	}

	public function add_color_picker($color_picker)
	{
		$this->product_color[] = $color_picker;
	}

	public function set_product_color($product_color)
	{
		$this->product_color = $product_color;
	}

	public function get_product_color()
	{
		return $this->product_color;
	}

	public function add_new_size($new_size)
	{
		$this->product_size[] = $new_size;
	}

	public function set_product_size($product_size)
	{
		$this->product_size = $product_size;
	}

	public function get_product_size()
	{
		return $this->product_size;
	}

	public function add_details_type($details_type)
	{
		$this->product_details[] = $details_type;
	}

	public function set_product_details($product_details)
	{
		$this->product_details = $product_details;
	}

	public function get_product_details()
	{
		return $this->product_details;
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
		return CatalogAuthorizationsService::check_authorizations($this->id_category)->read() && ($this->get_approbation_type() == self::APPROVAL_NOW || ($this->get_approbation_type() == self::APPROVAL_DATE && $this->get_start_date()->is_anterior_to($now) && ($this->end_date_enabled ? $this->get_end_date()->is_posterior_to($now) : true)));
	}

	public function get_status()
	{
		switch ($this->approbation_type) {
			case self::APPROVAL_NOW:
				return LangLoader::get_message('status.approved.now', 'common');
			break;
			case self::APPROVAL_DATE:
				return LangLoader::get_message('status.approved.date', 'common');
			break;
			case self::NOT_APPROVAL:
				return LangLoader::get_message('status.approved.not', 'common');
			break;
		}
	}

	public function get_start_date()
	{
		return $this->start_date;
	}

	public function set_start_date(Date $start_date)
	{
		$this->start_date = $start_date;
	}

	public function get_end_date()
	{
		return $this->end_date;
	}

	public function set_end_date(Date $end_date)
	{
		$this->end_date = $end_date;
		$this->end_date_enabled = true;
	}

	public function is_end_date_enabled()
	{
		return $this->end_date_enabled;
	}

	public function get_creation_date()
	{
		return $this->creation_date;
	}

	public function set_creation_date(Date $creation_date)
	{
		$this->creation_date = $creation_date;
	}

	public function get_updated_date()
	{
		return $this->updated_date;
	}

	public function set_updated_date(Date $updated_date)
	{
		$this->updated_date = $updated_date;
	}

	public function has_updated_date()
	{
		return $this->updated_date !== null && $this->updated_date->get_timestamp() !== $this->creation_date->get_timestamp();
	}

	public function get_author_user()
	{
		return $this->author_user;
	}

	public function set_author_user(User $user)
	{
		$this->author_user = $user;
	}

	public function get_author_custom_name()
	{
		return $this->author_custom_name;
	}

	public function set_author_custom_name($author_custom_name)
	{
		$this->author_custom_name = $author_custom_name;
	}

	public function is_author_custom_name_enabled()
	{
		return $this->author_custom_name_enabled;
	}

	public function set_number_view($number_view)
	{
		$this->number_view = $number_view;
	}

	public function get_number_view()
	{
		return $this->number_view;
	}

	public function get_picture()
	{
		return $this->picture_url;
	}

	public function set_picture(Url $picture)
	{
		$this->picture_url = $picture;
	}

	public function has_picture()
	{
		$picture = $this->picture_url->rel();
		return !empty($picture);
	}

	public function get_number_downloads()
	{
		return $this->number_downloads;
	}

	public function set_number_downloads($number_downloads)
	{
		$this->number_downloads = $number_downloads;
	}

	public function get_notation()
	{
		return $this->notation;
	}

	public function set_notation(Notation $notation)
	{
		$this->notation = $notation;
	}

	public function get_keywords()
	{
		if ($this->keywords === null)
		{
			$this->keywords = CatalogService::get_keywords_manager()->get_keywords($this->id);
		}
		return $this->keywords;
	}

	public function get_keywords_name()
	{
		return array_keys($this->get_keywords());
	}

	public function is_authorized_to_add()
	{
		return CatalogAuthorizationsService::check_authorizations($this->id_category)->write() || CatalogAuthorizationsService::check_authorizations($this->id_category)->contribution();
	}

	public function is_authorized_to_edit()
	{
		return CatalogAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CatalogAuthorizationsService::check_authorizations($this->id_category)->write() || (CatalogAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_visible())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function is_authorized_to_delete()
	{
		return CatalogAuthorizationsService::check_authorizations($this->id_category)->moderation() || ((CatalogAuthorizationsService::check_authorizations($this->id_category)->write() || (CatalogAuthorizationsService::check_authorizations($this->id_category)->contribution() && !$this->is_visible())) && $this->get_author_user()->get_id() == AppContext::get_current_user()->get_id() && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL));
	}

	public function get_properties()
	{
		return array(
			'id' => $this->get_id(),
			'id_category' => $this->get_id_category(),
			'name' => $this->get_name(),
			'rewrited_name' => $this->get_rewrited_name(),
			'product_url' => $this->get_product_url()->relative(),
			'size' => $this->get_size(),
			'contents' => $this->get_contents(),
			'description' => $this->get_description(),
			'promotion' => $this->get_promotion(),
			'promotion_enabled' => (int)$this->get_promotion_enabled(),
			'flash_sales_enabled' => (int)$this->get_flash_sales_enabled(),
			'price' => $this->get_price(),
			'approbation_type' => $this->get_approbation_type(),
			'start_date' => $this->get_start_date() !== null ? $this->get_start_date()->get_timestamp() : 0,
			'end_date' => $this->get_end_date() !== null ? $this->get_end_date()->get_timestamp() : 0,
			'creation_date' => $this->get_creation_date()->get_timestamp(),
			'updated_date' => $this->get_updated_date() !== null ? $this->get_updated_date()->get_timestamp() : $this->get_creation_date()->get_timestamp(),
			'author_custom_name' => $this->get_author_custom_name(),
			'author_user_id' => $this->get_author_user()->get_id(),
			'number_downloads' => $this->get_number_downloads(),
			'number_view' => $this->get_number_view(),
			'picture_url' => $this->get_picture()->relative(),
			'carousel' => Texthelper::serialize($this->get_carousel()),
			'product_color' => Texthelper::serialize($this->get_product_color()),
			'product_size' => Texthelper::serialize($this->get_product_size()),
			'product_details' => Texthelper::serialize($this->get_product_details())
		);
	}

	public function set_properties(array $properties)
	{
		$this->id = $properties['id'];
		$this->id_category = $properties['id_category'];
		$this->name = $properties['name'];
		$this->rewrited_name = $properties['rewrited_name'];
		$this->product_url = new Url($properties['product_url']);
		$this->download_enabled = !empty($properties['product_url']);
		$this->size = $properties['size'];
		$this->contents = $properties['contents'];
		$this->description = $properties['description'];
		$this->promotion = $properties['promotion'];
		$this->promotion_enabled = !empty($properties['promotion']);
		$this->flash_sales_enabled = (bool)$properties['flash_sales_enabled'];
		$this->price = $properties['price'];
		$this->number_view = $properties['number_view'];
		$this->approbation_type = $properties['approbation_type'];
		$this->start_date = !empty($properties['start_date']) ? new Date($properties['start_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->end_date = !empty($properties['end_date']) ? new Date($properties['end_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->end_date_enabled = !empty($properties['end_date']);
		$this->creation_date = new Date($properties['creation_date'], Timezone::SERVER_TIMEZONE);
		$this->updated_date = !empty($properties['updated_date']) ? new Date($properties['updated_date'], Timezone::SERVER_TIMEZONE) : null;
		$this->number_downloads = $properties['number_downloads'];
		$this->picture_url = new Url($properties['picture_url']);
		$this->set_carousel(!empty($properties['carousel']) ? Texthelper::unserialize($properties['carousel']) : array());
		$this->set_product_color(!empty($properties['color']) ? Texthelper::unserialize($properties['product_color']) : array());
		$this->set_product_size(!empty($properties['color']) ? Texthelper::unserialize($properties['product_size']) : array());
		$this->set_product_details(!empty($properties['details']) ? Texthelper::unserialize($properties['product_details']) : array());

		$user = new User();
		if (!empty($properties['user_id']))
			$user->set_properties($properties);
		else
			$user->init_visitor_user();

		$this->set_author_user($user);

		$this->author_custom_name = !empty($properties['author_custom_name']) ? $properties['author_custom_name'] : $this->author_user->get_display_name();
		$this->author_custom_name_enabled = !empty($properties['author_custom_name']);

		$notation = new Notation();
		$notation_config = new CatalogNotation();
		$notation->set_module_name('catalog');
		$notation->set_notation_scale($notation_config->get_notation_scale());
		$notation->set_id_in_module($properties['id']);
		$notation->set_number_notes($properties['number_notes']);
		$notation->set_average_notes($properties['average_notes']);
		$notation->set_user_already_noted(!empty($properties['note']));
		$this->notation = $notation;

		$units = array(LangLoader::get_message('unit.bytes', 'common'), LangLoader::get_message('unit.kilobytes', 'common'), LangLoader::get_message('unit.megabytes', 'common'), LangLoader::get_message('unit.gigabytes', 'common'));
		$power = $this->size > 0 ? floor(log($this->size, 1024)) : 0;
		$this->formated_size = (float)number_format($this->size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
	}

	public function init_default_properties($id_category = Category::ROOT_CATEGORY)
	{
		$this->id_category = $id_category;
		$this->product_url = new Url('');
		$this->size = 0;
		$this->price = 0;
		$this->download_enabled = false;
		$this->promotion_enabled = false;
		$this->approbation_type = self::APPROVAL_NOW;
		$this->author_user = AppContext::get_current_user();
		$this->start_date = new Date();
		$this->end_date = new Date();
		$this->creation_date = new Date();
		$this->number_downloads = 0;
		$this->number_view = 0;
		$this->picture_url = new Url(self::DEFAULT_PICTURE);
		$this->end_date_enabled = false;
		$this->author_custom_name = $this->author_user->get_display_name();
		$this->author_custom_name_enabled = false;
		$this->carousel = array();
		$this->product_color = array();
		$this->product_size = array();
		$this->product_details = array();
	}

	public function clean_start_and_end_date()
	{
		$this->start_date = null;
		$this->end_date = null;
		$this->end_date_enabled = false;
	}

	public function clean_end_date()
	{
		$this->end_date = null;
		$this->end_date_enabled = false;
	}

	public function get_array_tpl_vars()
	{
		$category = $this->get_category();
		$contents = FormatingHelper::second_parse($this->contents);
		$description = $this->get_real_description();
		$user = $this->get_author_user();
		$user_group_color = User::get_group_color($user->get_groups(), $user->get_level(), true);
		$number_comments = CommentsService::get_number_comments('catalog', $this->id);
		$new_content = new CatalogNewContent();
		$config = CatalogConfig::load();

		return array_merge(
			Date::get_array_tpl_vars($this->creation_date, 'date'),
			Date::get_array_tpl_vars($this->updated_date, 'updated_date'),
			Date::get_array_tpl_vars($this->start_date, 'differed_start_date'),
			array(
 			'C_VISIBLE' => $this->is_visible(),
			'C_EDIT' => $this->is_authorized_to_edit(),
			'C_DELETE' => $this->is_authorized_to_delete(),
			'C_READ_MORE' => !$this->is_description_enabled() && TextHelper::strlen($contents) > CatalogConfig::NUMBER_CARACTERS_BEFORE_CUT && $description != @strip_tags($contents, '<br><br/>'),
			'C_IS_DOWNLOADABLE' => $this->is_downloadable(),
			'C_SIZE' => !empty($this->size),
			'C_PICTURE' => $this->has_picture(),
			'C_AUTHOR_CUSTOM_NAME' => $this->is_author_custom_name_enabled(),
			'C_NB_VIEW_ENABLED' => $config->get_nb_view_enabled(),
			'C_USER_GROUP_COLOR' => !empty($user_group_color),
			'C_UPDATED_DATE' => $this->has_updated_date(),
			'C_DIFFERED' => $this->approbation_type == self::APPROVAL_DATE,
			'C_NEW_CONTENT' => $new_content->check_if_is_new_content($this->get_start_date() != null ? $this->get_start_date()->get_timestamp() : $this->get_creation_date()->get_timestamp()) && $this->is_visible(),
			'C_HAS_PROMOTION' => (bool)$this->get_promotion_enabled(),
			'C_IS_FLASH_SOLD' => (bool)$this->get_flash_sales_enabled(),

			//Cataloglink
			'ID' => $this->id,
			'NAME' => $this->name,
			'SIZE' => $this->formated_size,
			'CONTENTS' => $contents,
			'DESCRIPTION' => $description,
			'PROMOTION_PRICE' => $this->promotion,
			'PRICE' => $this->price,
			'PRICE_UNIT'=> $config->get_price_unit(),
			'STATUS' => $this->get_status(),
			'AUTHOR_CUSTOM_NAME' => $this->author_custom_name,
			'C_AUTHOR_EXIST' => $user->get_id() !== User::VISITOR_LEVEL,
			'PSEUDO' => $user->get_display_name(),
			'USER_LEVEL_CLASS' => UserService::get_level_class($user->get_level()),
			'USER_GROUP_COLOR' => $user_group_color,
			'NUMBER_DOWNLOADS' => $this->number_downloads,
			'NUMBER_VIEW' => $this->get_number_view(),
			'L_DOWNLOADED_TIMES' => StringVars::replace_vars(LangLoader::get_message('downloaded_times', 'common', 'catalog'), array('number_downloads' => $this->number_downloads)),
			'STATIC_NOTATION' => NotationService::display_static_image($this->get_notation()),
			'NOTATION' => NotationService::display_active_image($this->get_notation()),

			'C_COMMENTS' => !empty($number_comments),
			'L_COMMENTS' => CommentsService::get_lang_comments('catalog', $this->id),
			'NUMBER_COMMENTS' => CommentsService::get_number_comments('catalog', $this->id),

			//Category
			'C_ROOT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY,
			'CATEGORY_ID' => $category->get_id(),
			'CATEGORY_NAME' => $category->get_name(),
			'CATEGORY_DESCRIPTION' => $category->get_description(),
			'CATEGORY_IMAGE' => $category->get_image()->rel(),
			'U_EDIT_CATEGORY' => $category->get_id() == Category::ROOT_CATEGORY ? CatalogUrlBuilder::configuration()->rel() : CatalogUrlBuilder::edit_category($category->get_id())->rel(),

			'U_SYNDICATION' => SyndicationUrlBuilder::rss('catalog', $this->id_category)->rel(),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($this->get_author_user()->get_id())->rel(),
			'U_LINK' => CatalogUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_name)->rel(),
			'U_DOWNLOAD' => CatalogUrlBuilder::download_product_link($this->id)->rel(),
			'U_DEADLINK' => CatalogUrlBuilder::dead_link($this->id)->rel(),
			'U_CATEGORY' => CatalogUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel(),
			'U_EDIT' => CatalogUrlBuilder::edit($this->id)->rel(),
			'U_DELETE' => CatalogUrlBuilder::delete($this->id)->rel(),
			'U_PICTURE' => $this->get_picture()->rel(),
			'U_COMMENTS' => CatalogUrlBuilder::display_comments($category->get_id(), $category->get_rewrited_name(), $this->id, $this->rewrited_name)->rel()
			)
		);
	}
}
?>
