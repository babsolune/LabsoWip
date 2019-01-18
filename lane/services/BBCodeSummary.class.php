<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL [ben.popeye@phpboost.com]
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 1.6 - 2006 05 07
 * @contributor Julien BRISWALTER [j1.seth@phpboost.com]
 * @contributor Arnaud GENET [elenwii@phpboost.com]
 * @contributor mipel [mipel@phpboost.com]
 * @contributor Sebastien LARTIGUE [babsolune@phpboost.com]
*/

class BBCodeSummary
{
    //Parsing en ajoutant la balise [link]
	function bbc_mod_parse($contents)
	{
		$content_manager = AppContext::get_content_formatting_service()->get_default_factory();
		$parser = $content_manager->get_parser();

		//Parse la balise link
		$parser->add_module_special_tag('`\[link=([a-z0-9+#-_]+)\](.+)\[/link\]`isuU', '<a href="/wiki/$1">$2</a>');
		$parser->set_content($contents);
		$parser->parse();

		return $parser->get_content();
	}

	//Unparsing en tenant compte de [link]
	function bbc_mod_unparse($contents)
	{
		$content_manager = AppContext::get_content_formatting_service()->get_default_factory();
		$unparser = $content_manager->get_unparser();

		//Unparse la balise link
		$unparser->add_module_special_tag('`<a href="/wiki/([a-z0-9+#-_]+)">(.*)</a>`suU', '[link=$1]$2[/link]');
		$unparser->set_content($contents);
		$unparser->parse();

		return $unparser->get_content();
	}

	//Second parse -> à l'affichage
	function bbc_mod_second_parse($contents)
	{
		$content_manager = AppContext::get_content_formatting_service()->get_default_factory();
		$second_parser = $content_manager->get_second_parser();
		$second_parser->set_content(bbc_mod_unparse($contents));
		$second_parser->parse();

		return $second_parser->get_content();
	}

	//Fonction de correction dans le cas où il n'y a pas de rewriting (balise link considére par défaut le rewriting activé)
	function bbc_mod_no_rewrite($var)
	{
		if (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled()) //Pas de rewriting
			return preg_replace('`<a href="/wiki/([a-z0-9+#-]+)">(.*)</a>`suU', '<a href="/wiki/?url=/title=$1">$2</a>', $var);
		else
			return $var;
	}

	function remove_chapter_number_in_rewrited_title($title)
	{
		return Url::encode_rewrite(preg_replace('`((?:[0-9 ]+)|(?:[IVXCL ]+))[\.-](.*)`iuU', '$2', $title));
	}

	//Fonction de décomposition récursive (passage par référence pour la variable content qui passe de chaîne à tableau de chaînes (5 niveaux maximum)
	function bbc_mod_explode_menu(&$content)
	{
		$lines = explode("\n", $content);
		$num_lines = count($lines);
		$max_level_expected = 2;

		$list = array();

		//We read the text line by line
		foreach ($lines as $id => &$line)
		{
			for ($level = 2; $level <= $max_level_expected; $level++)
			{
				$matches = array();

				//If the line contains a title
				if (preg_match('`^(?:<br />)?\s*[\-]{' . $level . '}[\s]+(.+)[\s]+[\-]{' . $level . '}(?:<br />)?\s*$`u', $line, $matches))
				{
					$title_name = strip_tags(TextHelper::html_entity_decode($matches[1]));

					//We add it to the list
					$list[] = array($level - 1, $title_name);
					//Now we wait one of its children or its brother
					$max_level_expected = min($level + 1, WIKI_MENU_MAX_DEPTH + 1);

					//Réinsertion
					$line = '<h' . $level . ' class="formatter-title mod-paragraph-' .  $level . '" id="paragraph-' . Url::encode_rewrite($title_name) . '">' . TextHelper::htmlspecialchars($title_name) .'</h' . $level . '><br />' . "\n";
				}
			}
		}

		$content = implode("\n", $lines);

		return $list;
	}

	//Fonction d'affichage récursive
	function bbc_mod_display_menu($menu_list)
	{
		if (count($menu_list) == 0) //Aucun titre de paragraphe
		{
			return '';
		}

		$menu = '';
		$last_level = 0;

		foreach ($menu_list as $title)
		{
			$current_level = $title[0];

			$title_name = stripslashes($title[1]);
			$title_link = '<a href="#paragraph-' . Url::encode_rewrite($title_name) . '">' . TextHelper::htmlspecialchars($title_name) . '</a>';

			if ($current_level > $last_level)
			{
				$menu .= '<ol class="mod-list mod-list-' . $current_level . '"><li>' . $title_link;
			}
			elseif ($current_level == $last_level)
			{
				$menu .= '</li><li>' . $title_link;
			}
			else
			{
				if (TextHelper::substr($menu, TextHelper::strlen($menu) - 4, 4) == '<li>')
				{
					$menu = TextHelper::substr($menu, 0, TextHelper::strlen($menu) - 4);
				}
				$menu .= str_repeat('</li></ol>', $last_level - $current_level) . '</li><li>' . $title_link;
			}
			$last_level = $title[0];
		}

		//End
		if (TextHelper::substr($menu, TextHelper::strlen($menu) - 4, 4) == '<li>')
		{
			$menu = TextHelper::substr($menu, 0, TextHelper::strlen($menu) - 4);
		}
		$menu .= str_repeat('</li></ol>', $last_level);

		return $menu;
	}
}
?>
