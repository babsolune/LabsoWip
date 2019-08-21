<?php
define('PATH_TO_ROOT', '../../');
//Début du chargement de l'environnement
include_once('../../kernel/init.php');

//Chargement d'un fichier css, ici le fichier css du module news
define('ALTERNATIVE_CSS', 'builder');

//Titre de la page, ici Accueil
define('TITLE', '_builder/tests/treeview');

//Chargement de l'environnement ( header )
require_once('../../kernel/header.php');

//Chargement des fichiers de langue et autres
global $LANG,$CONFIG;

$Bread_crumb->add("Builder", url('../index.php'));
$Bread_crumb->add("Pages test", url('index.php'));
$Bread_crumb->add("Treeview plugin", url('treeview.php'));

?>

<style>
    .treeview ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }

</style>

<section id="php-tests">
    <header>
        <h1>jQuery hierarchical Data Tree</h1>
    </header>
    <p>
        jQuery Hierarchical Data Tree est un plugin jQuery qui permet d'afficher un menu dépliable.
    </p>
    <article class="">
        <nav class="treeview">
            <ul id="tree">
                <li data-tree-branch="01" data-tree-click="01">Plop 01</li>
                <li data-tree-branch="01.02" data-tree-click="01.02">Plop 02
                    <ul>
                        <li data-tree-branch="01.02.a" data-tree-click="01.02.a">Plop 02.a</li>
                        <li data-tree-branch="01.02.b" data-tree-click="01.02.b">Plop 02.b</li>
                        <li data-tree-branch="01.02.c" data-tree-click="01.02.c">Plop 02.c</li>
                        <li data-tree-branch="01.02.d" data-tree-click="01.02.d">Plop 02.d</li>
                    </ul>
                </li>
                <li data-tree-branch="01.03" data-tree-click="01.03">Plop 03</li>
                <li data-tree-branch="04" data-tree-click="04">Plop 04</li>
            </ul>
        </nav>
        <nav class="treeview">
            <ul>
                <li data-tree-branch="05" data-tree-click="05">Plop 05</li>
                <li data-tree-branch="05.06" data-tree-click="05.06">Plop 06</li>
                <li data-tree-branch="05.07" data-tree-click="05.07">Plop 07</li>
                <li data-tree-branch="08" data-tree-click="08">Plop 08</li>
            </ul>
        </nav>
    </article>
</section>

<script src="../_app/datatree.js"></script>
<script>
    $(function () {
        $('.treeview').dataTree({
            delimeter: ".", // separate parent from children
            openCSS: "dtv-open", // added class when a branch is opened
            closedCSS: "dtv-closed", // added class when a branch is closed
            endCSS: "dtv-end", // class added to every item
            opened: [] // What you want to be opened a start of the page (eg: 'myList.01', 'myList.04')
        });
    });
</script>

<?php
include_once('../../kernel/footer.php');
?>
