<section id="tsm-module">
	<header>
		<h1>
			{@tsm.module.title}
		</h1>
	</header>

	<article class="elements-container columns-{SEASONS_COLS_NB} no-style">
		# START seasons #
			# IF seasons.C_PUBLISHED #
				<div class="season-element block">
					<a href="{seasons.U_SEASON}">{seasons.SEASON_NAME}</a>
					# IF IS_ADMIN #
						<span class="actions">
							<a href="{seasons.U_EDIT}"><i class="fas fa-edit fa-fw"></i></a>
							<a href="{seasons.U_DELETE}"><i class="fas fa-trash fa-fw"></i></a>
						</span>
					# ENDIF #
				</div>
			# ENDIF #
		# END seasons #
	</article>

	<footer></footer>
</section>
