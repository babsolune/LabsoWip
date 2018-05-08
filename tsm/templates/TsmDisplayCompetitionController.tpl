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
						<a href="{U_TEAMS}" class="cssmenu-title"> {@tools.teams}</a>
					</li>
					<li>
						<a href="{U_DAYS}" class="cssmenu-title"> {@tools.days}</a>
					</li>
					<li>
						<a href="{U_MATCHES}" class="cssmenu-title"> {@tools.matches}</a>
					</li>
					<li>
						<a href="{U_RESULTS}" class="cssmenu-title"> {@tools.results}</a>
					</li>
					<li>
						<a href="{U_PARAMS}" class="cssmenu-title"> {@tools.params}</a>
					</li>
				</ul>
			</nav>

			<meta itemprop="url" content="{U_ITEM}">
			<meta itemprop="description" content="${escape(DESCRIPTION)}">
			<meta itemprop="datePublished" content="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{PUBLICATION_START_DATE_ISO8601}# ENDIF #">
			<meta itemprop="discussionUrl" content="{U_COMMENTS}">
			# IF C_HAS_THUMBNAIL #<meta itemprop="thumbnailUrl" content="{THUMBNAIL}"># ENDIF #
			<meta itemprop="interactionCount" content="{COMMENTS_NUMBER} UserComments">
		</header>
		<div class="content">
			Compétition crée par {AUTHOR} dans {SEASON_NAME} / {NAME} / {SEASON_DAY}-{SEASON_MONTH}<br />
			Vue de la page Compétition
			<div class="spacer"></div>
		</div>
		<aside>

		</aside>
		<footer></footer>
	</article>
	<footer></footer>
</section>
