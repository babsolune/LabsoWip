<section id="teamsportmanager-module">
	<header>
		<h1>
			{@tsm.module.title}
		</h1>
	</header>

	<article class="elements-container# IF C_SEASONS_COLUMNS # columns-{SEASONS_COLUMNS_NUMBER}# ENDIF #">
		# START seasons #
		<div class="season-element block">
			<a href="#">{seasons.SEASON_START_DATE_YEAR}</a>
		</div>
		# END seasons #
	</article>

	<footer></footer>
</section>
