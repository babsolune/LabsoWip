<?php
define('PATH_TO_ROOT', '../../');
//Début du chargement de l'environnement
include_once('../../kernel/init.php');

//Chargement d'un fichier css, ici le fichier css du module news
define('ALTERNATIVE_CSS', 'builder');

//Titre de la page, ici Accueil
define('TITLE', '_builder/tests/easytabs');

//Chargement de l'environnement ( header )
require_once('../../kernel/header.php');

//Chargement des fichiers de langue et autres
global $LANG,$CONFIG;

$Bread_crumb->add("Builder", url('../index.php'));
$Bread_crumb->add("Pages test", url('index.php'));
$Bread_crumb->add("Easytabs plugin", url('easytabs.php'));

?>
        <section id="php-tests">
            <header>
                <h1>EasyTabs</h1>
            </header>
            <p>
                Easytabs est un plugin jQuery qui permet d'afficher plusieurs contenus sur une même page grâce à un système d'onglet qui cache les autres contenus quand un est montré.
            </p>
            <article class="">
                several tab-container
                <div id="first-tab-container" class="tab-container">
                    <nav>
                        <ul>
                            <li><a href="#tab-01">Item 01</a></li>
                            <li><a href="#tab-02">Item 02</a></li>
                            <li><a href="#tab-03">Item 03</a></li>
                        </ul>
                    </nav>
                    <div class="panel-container">
                        <div id="tab-01"> plop of the content 01 </div>
                        <div id="tab-02"> plop of the content 02 </div>
                        <div id="tab-03"> plop of the content 03 </div>
                    </div>
                </div>
                <hr />
                <div id="second-tab-container" class="tab-container">
                    <nav>
                        <ul>
                            <li><a href="#tab-04">Item 04</a></li>
                            <li><a href="#tab-05">Item 05</a></li>
                            <li><a href="#tab-06">Item 06</a></li>
                        </ul>
                    </nav>
                    <div class="panel-container">
                        <div id="tab-04"> plop of the content 04 </div>
                        <div id="tab-05"> plop of the content 05 </div>
                        <div id="tab-06"> plop of the content 06 </div>
                    </div>
                </div>
            </article>
            <hr />
            <article class="">
                Nested tab-container
                <div id="third-tab-container" class="tab-container">
                    <nav>
                        <ul>
                            <li><a href="#tab-07">Item 07</a></li>
                            <li><a href="#tab-08">Item 08</a></li>
                            <li><a href="#tab-09">Nested</a></li>
                        </ul>
                    </nav>
                    <div class="panel-container">
                        <div id="tab-07"> plop of the content 07 </div>
                        <div id="tab-08"> plop of the content 08 </div>
                        <div id="tab-09">
                            <div id="forth-tab-container" class="tab-container">
                                <nav>
                                    <ul>
                                        <li><a href="#tab-10">Item 10</a></li>
                                        <li><a href="#tab-11">Item 11</a></li>
                                        <li><a href="#tab-12">Item 12</a></li>
                                    </ul>
                                </nav>
                                <div class="panel-container">
                                    <div id="tab-10"> plop of the content 10 </div>
                                    <div id="tab-11"> plop of the content 11 </div>
                                    <div id="tab-12"> plop of the content 12 </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </section>


        <?php
        include_once('../../kernel/footer.php');
        ?>
