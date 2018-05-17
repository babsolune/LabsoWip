<section id="module-tsm">
	<header>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			{@tsm.module.title} - {SEASON_NAME} # IF IS_ADMIN #<a href="{U_EDIT_SEASON}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a># ENDIF #
		</h1>
	</header>
	# INCLUDE NOT_VISIBLE_MESSAGE #
	<article itemscope="itemscope" itemtype="http://schema.org/Competition" id="article-tsm-{ID}" class="article-tsm# IF C_NEW_CONTENT # new-content# ENDIF #">
		<header>
			<h2>
				<span itemprop="name">{NAME}</span>
				<span class="actions">
					# IF C_EDIT #
						<a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
					# ENDIF #
					# IF C_DELETE #
						<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
					# ENDIF #
				</span>
			</h2>

			<nav id="tsm-compet-tools" class="cssmenu cssmenu-right cssmenu-actionslinks cssmenu-tools">
				<ul class="level-0 hidden">
					<li>
						<a href="{U_TEAMS}" class="cssmenu-title"> {@tools.teams.manager}</a>
					</li>
					<li>
						<a href="{U_DAYS}" class="cssmenu-title"> {@tools.days.manager}</a>
					</li>
					<li>
						<a href="{U_MATCHES}" class="cssmenu-title"> {@tools.matches.manager}</a>
					</li>
					<li>
						<a href="{U_RESULTS}" class="cssmenu-title"> {@tools.results.manager}</a>
					</li>
					<li>
						<a href="{U_PARAMS}" class="cssmenu-title"> {@tools.params.manager}</a>
					</li>
				</ul>
			</nav>
			<script>
				jQuery("#tsm-compet-tools").menumaker({
					title: "{@tools.competition}",
					format: "multitoggle",
					breakpoint: 768
				});
				jQuery(document).ready(function() {
					jQuery("#tsm-compet-tools ul").removeClass('hidden');
				});
			</script>

			<meta itemprop="url" content="{U_ITEM}">
			<meta itemprop="description" content="${escape(DESCRIPTION)}">
			<meta itemprop="datePublished" content="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{PUBLICATION_START_DATE_ISO8601}# ENDIF #">
			<meta itemprop="discussionUrl" content="{U_COMMENTS}">
			# IF C_HAS_THUMBNAIL #<meta itemprop="thumbnailUrl" content="{THUMBNAIL}"># ENDIF #
			<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
		</header>
		<div class="content"><div id="tab-container" class="tsm-tabs">
              <ul class="etabs">
                <li class="tab"><a href="#tsm-home">{@competition.home}</a></li>
                <li class="tab"><a href="#tsm-clubs">{@competition.clubs}</a></li>
                <li class="tab"><a href="#tsm-calendar">{@competition.calendar}</a></li>
                <li class="tab"><a href="#tsm-results">{@competition.results}</a></li>
                <li class="tab"><a href="#tsm-ranking">{@competition.ranking}</a></li>
              </ul>
              <div id="tsm-home">
                <p>
					Vue de la page présentation<br />
					Vignette - Texte de présa - Niveau de compétition - Mini Classement - tableau/résultats phase finale<br /><br />
					Test de récup des infos (season/division) :<br />
					Compétition / <em>Nom de la compétition</em>{NAME} / créée par {AUTHOR} - Saison {SEASON_NAME} / {SEASON_DAY}-{SEASON_MONTH}
				</p>
              </div>
              <div id="tsm-clubs">
                <p>
					Vue de la page liste des clubs de la poule<br /><br /><br />
				</p>
              </div>
              <div id="tsm-calendar">
                <p>
					Vue de la page calendrier de la saison<br /><br /><br />
				</p>
              </div>
              <div id="tsm-results">
                <p>
					Vue de la page résultats de l'équipe<br /><br /><br />
				</p>
              </div>
              <div id="tsm-ranking">
                <p>
					Vue de la page classement provisoire<br />
					Tableau : Pl - équipe - J - G - N - P - PP - PC - GA - Bon - Pen<br /><br />
				</p>
              </div>
            </div>
		    <script src="{PATH_TO_ROOT}/tsm/templates/js/hashchange.js"></script>
		    <script src="{PATH_TO_ROOT}/tsm/templates/js/easytabs.js"></script>
		    <script>
		        $('#tab-container').easytabs();
		    </script>
			<div class="spacer"></div>
		</div>
		<aside>

		</aside>
		<footer></footer>
	</article>
	<footer></footer>
</section>
