<?php
define('PATH_TO_ROOT', '../../');
//Début du chargement de l'environnement
include_once('../../kernel/init.php');

//Chargement d'un fichier css, ici le fichier css du module news
define('ALTERNATIVE_CSS', 'builder');

//Titre de la page, ici Accueil
define('TITLE', '_builder/tests/wizard');

//Chargement de l'environnement ( header )
require_once('../../kernel/header.php');

//Chargement des fichiers de langue et autres
global $LANG,$CONFIG;

$Bread_crumb->add("Builder", url('../index.php'));
$Bread_crumb->add("Pages test", url('index.php'));
$Bread_crumb->add("Wizard plugin", url('wizard.php'));

?>
<section id="wizard-menu-tests">
    <header>
        <h1>Wizard content</h1>
    </header>
    <p>
        Wizard est un plugin jQuery qui permet d'afficher plusieurs contenus dans un ordre précis : <a href="wizard.md"> voir le code</a>
    </p>
    <article class="wizard-container">
        <nav class="wizard-header">
            <ul>
                <li><a href="#">Basic</a></li>
                <li><a href="#">Normal</a></li>
                <li><a href="#">Advanced</a></li>
                <li><a href="#">Finish</a></li>
            </ul>
        </nav>
        <div class="wizard-navigator"></div>
            <div class="wizard-step">
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium.</p>
            </div>
            <div class="wizard-step">
                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
            </div>
            <div class="wizard-step">
                <p>A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.</p>
            </div>
            <div class="wizard-step">
                <p>One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin. He lay on his armour-like back, and if he lifted his head a little he could see his brown belly, slightly domed and divided by arches into stiff sections. The bedding was hardly able to cover it and seemed ready to slide off any moment.</p>
            </div>
    </article>
    <article class="wizard-container">
        <nav class="wizard-header">
            <ul>
                <li><a href="#">Basic</a></li>
                <li><a href="#">Normal</a></li>
                <li><a href="#">Advanced</a></li>
                <li><a href="#">Finish</a></li>
            </ul>
        </nav>
        <div class="wizard-navigator"></div>
        <div class="wizard-step">
            <div class="form-element">
                <label>Viva el Presidente</label>
                <div class="form-field">
                    <input type="text" value="" name="first" />
                </div>
            </div>
        </div>
        <div class="wizard-step">
            <div class="form-element">
                <label>Choisi pour voir</label>
                <div class="form-field">
					<select name="idcat_post" id="category">
						<option>Choisi</option>
						<option>pour</option>
						<option>voir</option>
					</select>
                </div>
            </div>
        </div>
        <div class="wizard-step">
            <p>A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart. I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.</p>
        </div>
        <div class="wizard-step">
			<fieldset class="fieldset-submit">
				<legend>Reste plus qu'à valider</legend>
				<div class="fieldset-inset">
					<button type="submit" name="" value="true" class="submit">Go Go Go</button>
				</div>
			</fieldset>
        </div>
    </article>
</section>

<section>
    <header>
        <h2>Markdown</h2>
    </header>
    <article class="">
        <code>
            <pre>
content.css
.wizard-container {
    display: flex;
    display: -ms-flexbox;
    display: -webkit-flex;
    flex-direction: column;
    -ms-flex-direction: column;
    -webkit-flex-direction: column;
    transition-duration: 0.4s;
}

.fa-finish:before {
    content:'\f058';
}

.wizard-container .wizard-header {
    padding: 10px;
}

.wizard-container .wizard-header ul {
    margin: 0;
    padding: 0;
    list-style-type: none;
    width: 100%;
    display: flex;
    display: -ms-flexbox;
    display: -webkit-flex;
    flex-direction: row;
    -ms-flex-direction: row;
    -webkit-flex-direction: row;
    overflow: hidden;
    position: relative;
}

.wizard-container .wizard-header li {
    flex: 1;
    -ms-flex: 1;
    -webkit-flex: 1;
    text-align: center;
    position: relative;
    transition-duration: 0.4s;
}

.wizard-container .wizard-header a {
    display: block;
    padding: 0.618em 0;
}

.wizard-container .wizard-header li span {
    position: relative;
    z-index: 2;
}
<br />
html
&lt;article class="wizard-container">
    &lt;nav class="wizard-header">
        &lt;ul>
            &lt;li>&lt;a href="#">Step 01&lt;/a>&lt;/li>
            &lt;li>&lt;a href="#">Step 02&lt;/a>&lt;/li>
            &lt;li>&lt;a href="#">Step 03&lt;/a>&lt;/li>
            &lt;li>&lt;a href="#">Finish&lt;/a>&lt;/li>
        &lt;/ul>
    &lt;/nav>
    &lt;div class="wizard-navigator">&lt;/div>
        &lt;div class="wizard-step">
            content of step 01
        &lt;/div>
        &lt;div class="wizard-step">
            content of step 02
        &lt;/div>
        &lt;div class="wizard-step">
            content of step 03
        &lt;/div>
        &lt;div class="wizard-step">
            content of step finish
        &lt;/div>
&lt;/article>
            </pre>
        </code>
    </article>
</section>
<?php
include_once('../../kernel/footer.php');
?>
