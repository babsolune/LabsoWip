<section>
    <header>
        <h1>Gestion des compétitions</h1>
    </header>
    <article>
        <div id="manager-tabs" class="tsm-tabs">
            <ul>
                <li><a href="#create"><span>Créer</span></a></li>
                <li><a href="#edit"><span>Modifier</span></a></li>
                <li><a href="#delete"><span>Supprimer</span></a></li>
            </ul>
            <div class="panel-container">
                <aside id="create">
                    <div>
                        # INCLUDE SEASON_MSG #
                        # INCLUDE SEASON_FORM #
                    </div>
                    <div>
                        # INCLUDE DIVISION_MSG #
                        # INCLUDE DIVISION_FORM #
                    </div>
                    <div>
                        # INCLUDE COMPETITION_MSG #
                        # INCLUDE COMPETITION_FORM #
                    </div>
                    <div>
                        # INCLUDE CLUB_MSG #
                        # INCLUDE CLUB_FORM #
                    </div>
                </aside>
                <aside id="edit">

                </aside>
                <aside id="delete">

                </aside>
            </div>
        </div>
    </article>
    <footer></footer>
</section>

<script src="{PATH_TO_ROOT}/tsm/templates/js/easytabs.js"></script>
<script>
	jQuery(document).ready( function() {
	  	jQuery('#manager-tabs').easytabs({
		  	collapsible: true,
          	defaultTab: "li:first-child"
	  	});
	});
</script>
