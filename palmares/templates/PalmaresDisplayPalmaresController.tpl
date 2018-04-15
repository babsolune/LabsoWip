<section id="module-palmares">
	<header>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			{@palmares}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF #
		</h1>
	</header>
	<div class="content">
		# IF NOT C_VISIBLE #
			# INCLUDE NOT_VISIBLE_MESSAGE #
		# ENDIF #
		<article itemscope="itemscope" itemtype="http://schema.org/CreativeWork" id="article-palmares-{ID}" class="article-palmares# IF C_NEW_CONTENT # new-content# ENDIF #">
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
	
				<div class="more">
					# IF C_AUTHOR_DISPLAYED #
						${LangLoader::get_message('by', 'common')}
						# IF C_AUTHOR_CUSTOM_NAME #
							{AUTHOR_CUSTOM_NAME}
						# ELSE #
							# IF C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="small {USER_LEVEL_CLASS}" href="{U_AUTHOR_PROFILE}" # IF C_USER_GROUP_COLOR # style="color:{USER_GROUP_COLOR}" # ENDIF #>{PSEUDO}</a># ELSE #{PSEUDO}# ENDIF #,
						# ENDIF #
					# ENDIF #
					${TextHelper::lcfirst(LangLoader::get_message('the', 'common'))} <time datetime="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT C_DIFFERED #{DATE}# ELSE #{DIFFERED_START_DATE}# ENDIF #</time>
					${TextHelper::lcfirst(LangLoader::get_message('in', 'common'))} <a itemprop="about" href="{U_CATEGORY}">{CATEGORY_NAME}</a>
					# IF C_COMMENTS_ENABLED #- # IF C_COMMENTS # {NUMBER_COMMENTS} # ENDIF # {L_COMMENTS}# ENDIF #
					# IF C_NB_VIEW_ENABLED #- <span title="{NUMBER_VIEW} {@palmares.view}"><i class="fa fa-eye"></i> {NUMBER_VIEW}</span># ENDIF #
				</div>
	
				<meta itemprop="url" content="{U_LINK}">
				<meta itemprop="description" content="${escape(DESCRIPTION)}" />
				# IF C_COMMENTS_ENABLED #
				<meta itemprop="discussionUrl" content="{U_COMMENTS}">
				<meta itemprop="interactionCount" content="{NUMBER_COMMENTS} UserComments">
				# ENDIF #
	
			</header>
			<div class="content">
				# IF C_PICTURE #<img itemprop="thumbnailUrl" src="{U_PICTURE}" alt="{NAME}" title="{NAME}" class="palmares-picture" /># ENDIF #
	
				<div itemprop="text">{CONTENTS}</div>
			</div>
			<aside>
				# IF C_SOURCES #
				<div id="palmares-sources-container">
					<span class="palmares-sources-title"><i class="fa fa-map-signs"></i> ${LangLoader::get_message('form.sources', 'common')}</span> :
					# START sources #
					<a itemprop="isBasedOnUrl" href="{sources.URL}" class="small palmares-sources-item">{sources.NAME}</a># IF sources.C_SEPARATOR #, # ENDIF #
					# END sources #
				</div>
				# ENDIF #
	
				# IF C_KEYWORDS #
				<div id="palmares-tags-container">
					<span class="palmares-tags-title"><i class="fa fa-tags"></i> ${LangLoader::get_message('form.keywords', 'common')}</span> :
						# START keywords #
							<a itemprop="keywords" rel="tag" href="{keywords.URL}" class="palmares-tags-item">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
						# END keywords #
				</div>
				# ENDIF #
	
				# IF C_SUGGESTED_PALMARES #
					<div id="palmares-suggested-container">
						<span class="palmares-suggested-title"><i class="fa fa-lightbulb-o"></i> ${LangLoader::get_message('suggestions', 'common')} :</span>
						<ul>
							# START suggested #
							<li><a href="{suggested.URL}" class="palmares-suggested-item">{suggested.NAME}</a></li>
							# END suggested #
						</ul>
					</div>
				# ENDIF #
	
				<hr class="palmares-separator">
	
				# IF C_PALMARES_NAVIGATION_LINKS #
				<div class="navigation-link">
					# IF C_PREVIOUS_PALMARES #
					<span class="navigation-link-previous">
						<a href="{U_PREVIOUS_PALMARES}"><i class="fa fa-arrow-circle-left"></i>{PREVIOUS_PALMARES}</a>
					</span>
					# ENDIF #
					# IF C_NEXT_PALMARES #
					<span class="navigation-link-next">
						<a href="{U_NEXT_PALMARES}">{NEXT_PALMARES}<i class="fa fa-arrow-circle-right"></i></a>
					</span>
					# ENDIF #
					<div class="spacer"></div>
				</div>
				# ENDIF #
	
				# INCLUDE COMMENTS #
			</aside>
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>
