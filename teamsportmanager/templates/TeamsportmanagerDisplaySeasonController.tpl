<section id="teamsportmanager-module">
	<header>
		<h1>
			{@tsm.module.title}
		</h1>
	</header>

	<article class="elements-container# IF C_SEASONS_COLUMNS # columns-{SEASONS_COLUMNS_NUMBER}# ENDIF #">
		# START competitions #
		<div class="season-element block">
			<a href="#">{SEASON_START_DATE_YEAR}</a>
		</div>
		# END competitions #
	</article>

	<footer></footer>
</section>
