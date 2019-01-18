<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost https://www.phpboost.com
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE [babsolune@phpboost.com]
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

####################################################
#                     French                      #
####################################################

use \Wiki\util\ModUrlBuilder;

$lang['default.category.name'] = 'Catégorie de test';
$lang['default.category.description'] = 'Articles de démonstration';
$lang['default.item.title'] = 'Débuter avec le module Documentation';
$lang['default.item.description'] = '';
$lang['default.item.contents'] = 'Ce bref article va vous donner quelques conseils simples pour prendre en main ce module.<br />
<br />
<ul class="formatter-ul">
<li class="formatter-li">Pour configurer votre module, <a href="' . ModUrlBuilder::configuration()->rel() . '">cliquez ici</a>
</li><li class="formatter-li">Pour ajouter des catégories : <a href="' . ModUrlBuilder::add_category()->rel() . '">cliquez ici</a> (les catégories et sous catégories sont à l\'infini)
</li><li class="formatter-li">Pour ajouter un article : <a href="' . ModUrlBuilder::add_item()->rel() . '">cliquez ici</a>
</li></ul>
<ul class="formatter-ul">
<li class="formatter-li">Pour mettre en page vos articles, vous pouvez utiliser le langage bbcode ou l\'éditeur WYSIWYG (cf cet <a href="https://www.phpboost.com/wiki/bbcode">article</a>)<br />
</li></ul><br />
<br />
Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a href="https://www.phpboost.com/wiki/wiki">PHPBoost</a>.<br />
<br />
<br />
Bonne utilisation de ce module.';

?>
