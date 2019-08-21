
# Wizard
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
