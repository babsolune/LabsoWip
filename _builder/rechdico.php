<?php
// definition de ka racine du site
define('PATH_TO_ROOT', '../');
//Début du chargement de l'environnement
include_once(PATH_TO_ROOT . '/kernel/init.php');

//Chargement d'un fichier css, ici le fichier css du module news
define('ALTERNATIVE_CSS', 'design');

//Titre de la page, ici Accueil
define('TITLE', 'Dictionnaire Technique');

//Chargement de l'environnement ( header )
require_once(PATH_TO_ROOT . '/kernel/header.php');

$Bread_crumb->add("Wiki", url('index.php'));
$Bread_crumb->add("Dictionnaire Technique", url('rechdico.php'));

$token = AppContext::get_session()->get_token();
$submit = AppContext::get_request();
$request = PersistenceContext::get_querier();

?>
<section>
    <header>
        <h1>Dictionnaire Technique</h1>
    </header>
    <article>
        <form name="dictionnaire" method="post" action="rechdico.php?token=<?php echo $token; ?>">
            <fieldset>
                <legend>Sélectionner le début du mot à rechercher</legend>
                <div class="fieldset-inset ">
                    <div class="form-element">
                        <label for="max_width">Anglais :<span class="field-description"></span></label>
                        <div class="form-field"><input type="text" name="gb" value=""></div>
                    </div>
                    <div class="form-element">
                        <label for="max_width">Francais :<span class="field-description"></span></label>
                        <div class="form-field"><input type="text" name="fr" value=""></div>
                    </div>
                    <div class="form-element">
                        <label for="max_width">Allemand :<span class="field-description"></span></label>
                        <div class="form-field"><input type="text" name="db" value=""></div>
                    </div>
                </div>
            </fieldset>
            <fieldset class="fieldset-submit">
                <div class="fieldset-inset">
                    <input type="hidden" name="token" value="{TOKEN}" />
                    <button type="submit" name="valider" value="true" class="submit">Envoyer</button>
                    <button type="reset" value="true">Effacer</button>
                </div>
            </fieldset>
            <?php

                $valid = $submit->get_postvalue('valider', false);

                // Start rechdico
                if ($valid)
                {
                    if ( $_REQUEST["gb"] <> "")
                    {
                        $zone = $_POST["gb"];
                        $result=$request->select("SELECT * FROM phpboost_dico WHERE anglais LIKE '$zone%'");
                        echo '<table id="table1" class="table">'."\n";
                        // thead de la table
                        echo '<thead><tr>';
                        echo '<th>English</th>';
                        echo '<th>French</th>';
                        echo '<th>German</th>';
                        echo '</tr></thead>'."\n";
                        echo '<tbody>';
                        while($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td sytle="background-color:#2ECCFA;">'.$row['anglais'].'</td>';
                            echo '<td>'.$row['francais'].'</td>';
                            echo '<td>'.$row['allemand'].'</td>';
                            echo '</tr>'."\n";
                        }
                        echo '</tbody></table>'."\n";
                        mysqli_free_result($result);
                    }
                    elseif ( $_REQUEST["fr"] <> "")
                    {
                        $zone = $_POST["fr"];
                        $request->select("SELECT * FROM phpboost_dico WHERE francais LIKE '$zone%'");
                        echo '<table id="table2" class="table">'."\n";
                        // thead de la table
                        echo '<thead><tr>';
                        echo '<th>Anglais</th>';
                        echo '<th>Francais</th>';
                        echo '<th>Allemand</th>';
                        echo '</tr></thead>'."\n";
                        echo '<tbody>';
                        while($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>'.$row['anglais'].'</td>';
                            echo '<td sytle="background-color:#2ECCFA;">'.$row['francais'].'</td>';
                            echo '<td>'.$row['allemand'].'</td>';
                            echo '</tr>'."\n";
                        }
                        echo '</tbody></table>'."\n";
                        mysqli_free_result($result);
                    }

                    elseif ( $_REQUEST["db"] <> "" )
                    {
                        $zone = $_POST["db"];
                        $request->select("SELECT * FROM phpboost_dico WHERE allemand LIKE '$zone%'");
                        echo '<table id="table3" class="table">'."\n";
                        // thead de la table
                        echo '<thead><tr>';
                        echo '<th>Englisch</th>';
                        echo '<th>Französisch</th>';
                        echo '<th>Deutsch</th>';
                        echo '</tr></thead>'."\n";
                        echo '<tbody>';
                        while($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>'.$row['anglais'].'</td>';
                            echo '<td>'.$row['francais'].'</td>';
                            echo '<td sytle="background-color:#2ECCFA;">'.$row['allemand'].'</td>';
                            echo '</tr>'."\n";
                        }
                        echo '</tbody></table>'."\n";
                        mysqli_free_result($result);
                    }
                }
            ?>
<?php

    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db = 'pbt_53_upload';
    // connection à la DB
    $link = mysqli_connect ($host,$user,$pass,$db);

    if (!$link) {
        echo "Erreur : Impossible de se connecter à MySQL." . PHP_EOL;
        echo "Errno de débogage : " . mysqli_connect_errno() . PHP_EOL;
        echo "Erreur de débogage : " . mysqli_connect_error() . PHP_EOL;
        exit;
    } else {
        echo 'Bravo ! Une connexion correcte à MySQL a été faite sur La base de donnée <span style="color:#f0f">'. $db .'</span>' . PHP_EOL;
        echo 'Information de l\'hôte : ' . mysqli_get_host_info($link) . PHP_EOL;
    }


?>
        </form>
    </article>
</section>
<?php
include_once(PATH_TO_ROOT . '/kernel/footer.php');
?>
