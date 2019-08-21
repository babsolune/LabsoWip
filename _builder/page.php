<?php
define('PATH_TO_ROOT', '../');

//Début du chargement de l'environnement
include_once('../kernel/init.php');

//Chargement d'un fichier css, ici le fichier css du module news
define('ALTERNATIVE_CSS', PATH_TO_ROOT.'/_builder/_app/builder.css');

//Titre de la page, ici Accueil
define('TITLE', 'Wip');

//Chargement de l'environnement ( header )
require_once('../kernel/header.php');

//Chargement des fichiers de langue et autres
global $LANG,$CONFIG;

$Bread_crumb->add("Builder", url('index.php'));
$Bread_crumb->add("W.I.P page", url('page.php'));

require_once(PATH_TO_ROOT.'/_builder/_app/showContentFolder.php');

?>
<section>
    <header>
        <h1>Builder page</h1>
    </header>
    <article class="">
        <?php
            showContentFolder('./');
        ?>
    </article>
    <article class="">
        <pre>
            <code>
mon code avec un fond de couleur
            </code>
        </pre>

    </article>
    <footer></footer>
</section>


<?php
include_once('../kernel/footer.php');
?>
