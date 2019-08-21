# Changelog 5.2 to 5.3

# Fichiers/Dossiers à supprimer (update)
`/kernel/lib/js/lightcase`  
`/kernel/lib/js/lightcase/lightcase.js`    
`/kernel/lib/js/lightcase/css/lightcase.css`  
`/kernel/lib/js/lightcase/fonts/lightcase.ttf`  
`/kernel/lib/js/lightcase/fonts/lightcase.woff`

# Lightbox
La lightbox a été déplacée dans le thème et profite des icones Font Awesome 5
- déplacement et modification du fichier lightcase.css dans le thème
- transfert des propriétés de couleur dans le fichier colors.css
```
/* -- lightcase.css
    #      #####  #####  #   #  #####  ####   #####  #   #
    #        #    #      #   #    #    #   #  #   #   # #
    #        #    #  ##  #####    #    ####   #   #    #
    #        #    #   #  #   #    #    #   #  #   #   # #
    #####  #####  #####  #   #    #    ####   #####  #   #
----------------------------------------------------------------------------- */
a[class*='lightcase-icon-'], a[class*='lightcase-icon-']:focus {
    color: rgba(255, 255, 255, 0.2);
}

a[class*='lightcase-icon-']:hover {
    color: #FFFFFF;
    text-shadow: 0 0 0.618em #FFFFFF;
}

.lightcase-isMobileDevice a[class*='lightcase-icon-']:hover {
    color: #aaa;
}

#lightcase-case {
    text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

@media screen and (min-width: 641px) {
    html:not([data-lc-type=error]) #lightcase-content {
        background-color: #FFFFFF;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
    }
}

@media screen and (min-width: 641px) {
    html[data-lc-type=image] #lightcase-content, html[data-lc-type=video] #lightcase-content {
        background-color: #333333;
    }
}

#lightcase-content h1,
#lightcase-content h2,
#lightcase-content h3,
#lightcase-content h4,
#lightcase-content h5,
#lightcase-content h6,
#lightcase-content p {
    color: #333;
}

#lightcase-case p.lightcase-error {
    color: #CE271A;
}

#lightcase-info #lightcase-title {
    color: #FFFFFF;
}

#lightcase-info #lightcase-caption {
    color: #FFFFFF;
}

#lightcase-info #lightcase-sequenceInfo {
    color: #FFFFFF;
}

#lightcase-loading {
    text-shadow: 0 0 0.618em #FFFFFF;
}

#lightcase-loading, #lightcase-loading:focus {
    color: #FFFFFF;
}

#lightcase-overlay {
    background: #366493;
}
```
- modifier l'appel du fichier lightcase.css dans templates/{THEME}/frame.tpl  
avec le cache
`/templates/{THEME}/theme/lightcase.css;`  
sans le cache
`<link rel="stylesheet" href="{PATH_TO_ROOT}/templates/{THEME}/theme/lightcase.css" type="text/css" media="screen" />`

# Tableaux
Les tableaux en bbcode sont maintenant responsifs
- ajouter la classe .table dans toutes les tables en __html__ ou la classe .table-no-header pour les tables sans entête (ex: galerie)
- mise à jour du fichier table.css  

Remplacer
```
table.formatter-table th.formatter-table-head {...
table.formatter-table th.formatter-table-head p {...
```  

par
```
table.formatter-table td.formatter-table-head {...
table.formatter-table td.formatter-table-head p {...
```
ajouter
```
table.bt.formatter-table td.formatter-table-head {
	display: none;
}

table.bt tfoot th,
table.bt tfoot td,
table.bt tbody td {
	display: flex;
	display: -ms-flexbox;
	display: -webkit-flex;
	vertical-align: top;
}

table.bt tfoot th::before,
table.bt tfoot td::before,
table.bt tbody td::before {
  content: attr(data-th) ": ";
  display: inline-block;
  -webkit-flex-shrink: 0;
  flex-shrink: 0;
  font-weight: bold;
  width: 6.5em;
}

table.bt tfoot th.bt-hide,
table.bt tfoot td.bt-hide,
table.bt tbody td.bt-hide {
  display: none;
}

table.bt tfoot th .bt-content,
table.bt tfoot td .bt-content,
table.bt tbody td .bt-content {
  vertical-align: top;
}

table.bt.bt--no-header tfoot td::before,
table.bt.bt--no-header tbody td::before {
  display: none;
}
```
- Des classes ont été rajoutées pour être utilisées dans le BBCode  [FEATURE]

quand la table comporte trop de colonnes   
`[container class="responsive-table"][table]....[/table][/container]`
```
.responsive-table {
	max-width: 100%;
	overflow: auto;
}
```
Si voulez des bordures sur toutes les cellules     
`[container class="bordered"][table]....[/table][/container]`
```
.bordered td {
	border-width: 1px 0 0 1px;
	border-style: solid;
	border-color: transparent;
}
.bordered td:last-child {
	border-width: 1px 1px 0 1px;
}
.bordered tr:last-child td {
	border-width: 1px 0 1px 1px;
}
.bordered tr:last-child td:last-child {
	border-width: 1px;
}
```

# Plugins jQuery
Afin d'améliorer la maintenance des plugins jquery, certains ont été sorti du fichier global.js et déplacés dans le dossier template  
- **_SI_** le js_bottom.tpl est dans le thème
    - supprimer l'appel du lightcase.js
    - remplacer le script du plugin basictable:     
```
// BBCode table with no header
jQuery('.formatter-table').each(function(){
    $this = jQuery(this).find('tbody tr:first-child td');
    if ($this.hasClass('formatter-table-head')) {}
    else
        $this.closest('.formatter-table').removeClass('table').addClass('table-no-header');
});

// All tables
jQuery('.table').basictable();     
jQuery('.table-no-header').basictable({     
    header: false       
});
```
à noter que l'ancien appel sur les identifiants `jQuery('#table').basictable()` fonctionne toujours si vous ne voulez pas modifier toutes les tables.

- **_SI_** le js_top.tpl est dans le thème, ajouter les appel des plugins après l'appel du global.js
    - `<script src="{PATH_TO_ROOT}/templates/default/plugins/autocomplete.js"></script>`
    - `<script src="{PATH_TO_ROOT}/templates/default/plugins/basictable.js"></script>`
    - `<script src="{PATH_TO_ROOT}/templates/default/plugins/lightcase.js"></script>`
    - `<script src="{PATH_TO_ROOT}/templates/default/plugins/sortable.js"></script>`
    - `<script src="{PATH_TO_ROOT}/templates/default/plugins/menumaker.js"></script>`
    - `<script src="{PATH_TO_ROOT}/templates/default/plugins/tooltip.js"></script>`

# Tooltip [FEATURE]
Afin de supprimer les title="" qui limitent les performances en mobile, un plugin `tooltip.js` est mis en place sur les aria-label, ce qui permet l'affichage au survol de la souris, des textes cachés, en profitant des attributs d'accessibilité  
- ajouter les classes du tooltip
colors.css:  
```
#tooltip {
	box-shadow: 0 0 3px 0 rgba(0, 0, 0, 0.15);
	background-color: #FFFFFF;
}
```
content.css:
```
#tooltip {
    position: absolute;
    padding: 0.228em 0.456em;
    font-size: 0.809em;
    display: inline-block;
    opacity: 0;
    max-width: 200px;
    width: auto;
    z-index: 1000;
}

#tooltip.position-t{ margin-top:  -9px; }
#tooltip.position-b{ margin-top:   9px; }
#tooltip.position-r{ margin-left:  9px; }
#tooltip.position-l{ margin-left: -9px; }

#tooltip.position-tr{ margin-left:  7px; margin-top: -7px; }
#tooltip.position-br{ margin-left:  7px; margin-top:  7px; }
#tooltip.position-bl{ margin-left: -7px; margin-top:  7px; }
#tooltip.position-tl{ margin-left: -7px; margin-top: -7px; }
```
- déclarez un aria-label où vous voulez voir apparaitre un tooltip  
`<button aria-label="Fermer">X</button>`

# Tabs menu [FEATURE]
Ce plugin `easytabs.js` permet d'afficher un menu tabs: chaque lien du menu fait apparaitre une partie du contenu et cache le reste  

content.css
```
.tab-container > ul {
    margin: 0;
    padding: 0;
}

.tab-container > ul > li {
    display: inline-block;
}

.tab-container > ul > li a {
    display: block;
    padding: 0.618em;
    outline: none;
    text-decoration: none;
}

.tab-container > ul > li a.shown {
    position: relative;
}

.tab-container .panel-container {
    padding: 0.618em;
}
```

colors.css
```
.tab-container > ul > li a.shown {
	background-color: rgba(54, 100, 147, 0.2);
}
```

js_bottom.tpl
```
$('.tab-container').easytabs();
```
howTo
On peut en mettre plusieurs sur une même page
```
<div id="first-tab-container" class="tab-container">
    <ul>
        <li><a href="#tab-01">HTML</a></li>
        <li><a href="#tab-02">JS</a></li>
        <li><a href="#tab-03">CSS</a></li>
    </ul>
    <div class="panel-container">
        <div id="tab-01"> plop of the 01 </div>
        <div id="tab-02"> plop of the 02 </div>
        <div id="tab-03"> plop of the 03 </div>
    </div>
</div>
<div id="second-tab-container" class="tab-container">
    <ul>
        <li><a href="#tab-04">page 01</a></li>
        <li><a href="#tab-05">page 02</a></li>
        <li><a href="#tab-06">page 03</a></li>
    </ul>
    <div class="panel-container">
        <div id="tab-04"> plop of the 04 </div>
        <div id="tab-05"> plop of the 05 </div>
        <div id="tab-06"> plop of the 06 </div>
    </div>
</div>
```

on peut les imbriquer les uns dans les autres
```
<div id="third-tab-container" class="tab-container">
    <ul>
        <li><a href="#tab-07">page 01</a></li>
        <li><a href="#tab-08">page 02</a></li>
        <li><a href="#tab-09">nested</a></li>
    </ul>
    <div class="panel-container">
        <div id="tab-07"> plop of the 01 </div>
        <div id="tab-08"> plop of the 02 </div>
        <div id="tab-09">
            <div id="forth-tab-container" class="tab-container">
                <ul>
                    <li><a href="#tab-10">HTML</a></li>
                    <li><a href="#tab-11">JS</a></li>
                    <li><a href="#tab-12">CSS</a></li>
                </ul>
                <div class="panel-container">
                    <div id="tab-10"> plop of the 04 </div>
                    <div id="tab-11"> plop of the 05 </div>
                    <div id="tab-12"> plop of the 06 </div>
                </div>
            </div>
        </div>
    </div>
</div>
```
# Wizard [FEATURE]
Ce plugin `wizard.js` permet d'afficher un menu "step by step": les différentes parties de la page se succèdent en cliquant sur un bouton "suivant" ou "précédent"  
content.css
```
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
```

html
```
<article class="wizard-container">
    <nav class="wizard-header">
        <ul>
            <li><a href="#">Step 01</a></li>
            <li><a href="#">Step 02</a></li>
            <li><a href="#">Step 02</a></li>
            <li><a href="#">Finish</a></li>
        </ul>
    </nav>
    <div class="wizard-navigator"></div>
    <div class="wizard-body">
        <div class="wizard-step">
            content of step 01
        </div>
        <div class="wizard-step">
            content of step 02
        </div>
        <div class="wizard-step">
            content of step 03
        </div>
        <div class="wizard-step">
            content of step finish
        </div>
    </div>
</article>
```
# Templates
- Supprimer tous les title="" ou les remplacer par aria-label="" selon les cas
## config.ini (modules + templates)
Remplacer `date="DD/MM/YYYY"`  
par  
`creation_date="YYYY/MM/DD"`  
`last_update="YYYY/MM/DD"`

# IDCard
Nouvelle option de module: remplace le nom de l'auteur d'un item par un combo {nom + avatar + biographie} (la bio est à remplir dans le profil)  
L'autorisation d'affichage dans chaque module est dans l'admin \Contenu\Contenu\#Gestion des informations sur l'auteur: l'option est autorisée par défaut.  
Priorité d'affichage:  
1. Nom de l'auteur modifié
2. IDCard (sous l'article)
3. nom de l'auteur

Modifications à apporter à un module:
- config.ini: ajouter idcard dans les enabled_features
- tpl de l'item  
```
# IF C_AUTHOR_DISPLAYED #
    # IF C_AUTHOR_CUSTOM_NAME #
        <i class="fa fa-user-o" aria-hidden="true"></i> {AUTHOR_CUSTOM_NAME}
    # ELSE #
        # IF NOT C_ID_CARD #
            <i class="fa fa-user-o" aria-hidden="true"></i> # IF C_AUTHOR_EXIST #<a itemprop="author" href="{U_AUTHOR}" class="{USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}"# ENDIF #>&nbsp;{PSEUDO}&nbsp;</a># ELSE #{PSEUDO}# ENDIF # |&nbsp;
        # ENDIF #
    # ENDIF #
# ENDIF #

...

# IF C_AUTHOR_DISPLAYED #
    # IF NOT C_AUTHOR_CUSTOM_NAME #
        # IF C_ID_CARD #
            {ID_CARD}
        # ENDIF #
    # ENDIF #
# ENDIF #
```
