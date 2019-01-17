<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version   	PHPBoost 5.2 - last update: 2018 05 25
 * @since   	PHPBoost 5.1 - 2018 05 25
*/

####################################################
#                     French                      #
####################################################

$lang = array();

$lang['default.category.name'] = 'Catégorie de test';
$lang['default.category.description'] = 'Articles de démonstration';
$lang['default.document.title'] = 'Débuter avec le module Documentation';
$lang['default.document.description'] = '';
$lang['default.document.contents'] = 'Ce bref document va vous donner quelques conseils simples pour prendre en main ce module.<br />
<br />
<ul class="formatter-ul">
<li class="formatter-li">Pour configurer votre module, <a href="' . WikiUrlBuilder::configuration()->rel() . '">cliquez ici</a>
</li><li class="formatter-li">Pour ajouter des catégories : <a href="' . WikiUrlBuilder::add_category()->rel() . '">cliquez ici</a> (les catégories et sous catégories sont à l\'infini)
</li><li class="formatter-li">Pour ajouter un document : <a href="' . WikiUrlBuilder::add_item()->rel() . '">cliquez ici</a>
</li></ul>
<ul class="formatter-ul">
<li class="formatter-li">Pour mettre en page vos wiki, vous pouvez utiliser le langage bbcode ou l\'éditeur WYSIWYG (cf cet <a href="http://www.phpboost.com/wiki/bbcode">document</a>)<br />
</li></ul><br />
<br />
Pour en savoir plus, n\'hésitez pas à consulter la documentation du module sur le site de <a href="https://www.phpboost.com/wiki/wiki">PHPBoost</a>.<br />
<br />
<br />
Bonne utilisation de ce module.';

?>
