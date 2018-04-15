<?php
/*##################################################
 *                         SmalladsScheduledJobs.class.php
 *                            -------------------
 *   begin                : March 15, 2018
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

class SmalladsScheduledJobs extends AbstractScheduledJobExtensionPoint
{
	public function on_changepage()
	{
		$config = SmalladsConfig::load();
		$deferred_operations = $config->get_deferred_operations();

		if (!empty($deferred_operations))
		{
			$now = new Date();
			$is_modified = false;

			foreach ($deferred_operations as $id => $timestamp)
			{
				if ($timestamp <= $now->get_timestamp())
				{
					unset($deferred_operations[$id]);
					$is_modified = true;
				}
			}

			if ($is_modified)
			{
				Feed::clear_cache('smallads');
				SmalladsCache::invalidate();
				SmalladsCategoriesCache::invalidate();

				$config->set_deferred_operations($deferred_operations);
				SmalladsConfig::save();
			}
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function on_changeday(Date $yesterday, Date $today)
	{
		// Delete item at the end of max_weeks
		// $config = SmalladsConfig::load();
		//
		// if ($config->is_max_weeks_number_displayed())
		// {
		// 	PersistenceContext::get_querier()->delete(SmalladsSetup::$smallads_table,
		// 		'WHERE (published = 1) AND (DATEDIFF(NOW(), FROM_UNIXTIME(creation_date)) > 7 * IF(max_weeks IS NULL OR max_weeks = 0, :delay, max_weeks))', array('delay' => (int)$config->get_max_weeks_number()));
		//
		// 	Feed::clear_cache('smallads');
		// 	SmalladsCache::invalidate();
		// 	SmalladsCategoriesCache::invalidate();
		// }

		// Delete item after 24h if sold is checked
		// PersistenceContext::get_querier()->delete(SmalladsSetup::$smallads_table,
		// 	'WHERE (published = 1) AND (sold = 1) AND (DATEDIFF(NOW(), FROM_UNIXTIME(update_date + 86400)))');
		//
		// Feed::clear_cache('smallads');
		// SmalladsCache::invalidate();
		// SmalladsCategoriesCache::invalidate();
	}

	/**
	 * {@inheritDoc}
	 */
	public function on_sold(Date $yesterday, Date $today)
	{
		$config = SmalladsConfig::load();


	}
}
?>
