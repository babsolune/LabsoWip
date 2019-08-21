<?php
define('PATH_TO_ROOT', '../../');
//Début du chargement de l'environnement
include_once('../../kernel/init.php');

//Chargement d'un fichier css, ici le fichier css du module news
define('ALTERNATIVE_CSS', 'builder');

//Titre de la page, ici Accueil
define('TITLE', 'Changelogs');

//Chargement de l'environnement ( header )
require_once('../../kernel/header.php');

//Chargement des fichiers de langue et autres
global $LANG,$CONFIG;

$Bread_crumb->add("Builder", url('../index.php'));
$Bread_crumb->add("Changelogs", url('page.php'));

require_once('../_app/showContentFolder.php')

?>
<article class="">
    <?php showContentFolder('./') ?>
</article>


<?php
include_once('../../kernel/footer.php');
?>
