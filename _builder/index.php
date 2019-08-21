<?php
define('PATH_TO_ROOT', '../');
//Début du chargement de l'environnement
include_once('../kernel/init.php');

//Chargement d'un fichier css, ici le fichier css du module news
define('ALTERNATIVE_CSS', 'builder');

//Titre de la page, ici Accueil
define('TITLE', 'Tests _builder');

//Chargement de l'environnement ( header )
require_once('../kernel/header.php');

//Chargement des fichiers de langue et autres
global $LANG,$CONFIG;

$Bread_crumb->add("Builder", url('index.php'));

require_once(PATH_TO_ROOT.'/_builder/_app/showContentFolder2.php')

?>
    <section>
        <header>
            <h1>Builder page</h1>
        </header>
        <article class="">
            <?php showContentFolder('./') ?>
        </article>
        <article class="">
            <img src="_app/about.jpg" alt="">
        </article>
        <footer></footer>
    </section>


<?php
include_once('../kernel/footer.php');
?>
