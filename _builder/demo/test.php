<?php
define('PATH_TO_ROOT', '../../');
//Début du chargement de l'environnement
include_once('../../kernel/init.php');

//Chargement d'un fichier css, ici le fichier css du module news
define('ALTERNATIVE_CSS', 'builder');

//Titre de la page, ici Accueil
define('TITLE', '_builder/tests/tests');

//Chargement de l'environnement ( header )
require_once('../../kernel/header.php');

//Chargement des fichiers de langue et autres
global $LANG,$CONFIG;
?>
    <section id="php-tests">
        <header>
            <h1>Tests PHP</h1>
        </header>
        <article class="">
            <header>
                <h2>date->timestamp</h2>
            </header>
            <?php
                // both lines output 813470400
                echo strtotime("19951012"), "<br />",
                     strtotime("12 October 1995");
            ?>
        </article>
        <article class="">
            <header>
                <h2>timestamp->date</h2>
            </header>

            <?php
                // prints 1995 Oct 12
                echo date("d M Y", strtotime("19951012"));
            ?>
        </article>
        <footer></footer>
    </section>
<?php
include_once('../../kernel/footer.php');
?>
