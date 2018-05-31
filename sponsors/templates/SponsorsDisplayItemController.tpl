<section id="sponsors-module">
	<header>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-fw fa-syndication"></i></a>
			{@sponsors.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-fw fa-edit smaller"></i></a># ENDIF #
		</h1>
	</header>
	# INCLUDE NOT_VISIBLE_MESSAGE #
	<article itemscope="itemscope" itemtype="http://schema.org/Partner" id="article-sponsors-{ID}" class="article-sponsors# IF C_NEW_CONTENT # new-content# ENDIF #">
		<header>
			<h2>
				{TITLE}
				<span class="actions">
					# IF C_EDIT #
						<a href="{U_EDIT_ITEM}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-fw fa-edit"></i></a>
					# ENDIF #
					# IF C_DELETE #
						<a href="{U_DELETE_ITEM}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-fw fa-delete"></i></a>
					# ENDIF #
				</span>
			</h2>

			<meta itemprop="url" content="{U_ITEM}">
			<meta itemprop="description" content="${escape(DESCRIPTION)}">
			<meta itemprop="datePublished" content="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{PUBLICATION_START_DATE_ISO8601}# ENDIF #">
			# IF C_HAS_THUMBNAIL #<meta itemprop="thumbnailUrl" content="{THUMBNAIL}"># ENDIF #
		</header>
		<div class="content">
			<div class="options infos thumbnail-item">
				# IF C_HAS_THUMBNAIL #
					<img src="{U_THUMBNAIL}" alt="{TITLE}" />
				# ELSE #
					<img src="{PATH_TO_ROOT}/sponsors/templates/images/no-thumb.png" alt="{TITLE}" />
				# ENDIF #
				<div class="website">
					# IF C_HAS_WEBSITE #
						# IF IS_USER_CONNECTED #
							<a href="{U_DEADLINK}" class="website-dl" title="{@sponsors.dead.link}">
								<i class="fa fa-unlink"></i>
							</a>
						# ENDIF #
						<a href="{U_WEBSITE}" rel="noopener nofollow noreferrer" class="website-link">
							<i class="fa fa-globe"></i> {@sponsors.visit}
						</a>
					# ELSE #
						<span class="no-website">{@no.website}</span>
					# ENDIF #
				</div>
			</div>
			<div itemprop="text">{CONTENTS}</div>


			<div class="spacer"></div>
		</div>
		<aside>

			# IF C_UPDATED_DATE #
				<hr />
				<div>
					<i>${LangLoader::get_message('form.date.update', 'common')} : <time datetime="{UPDATED_DATE_ISO8601}" itemprop="datePublished">{UPDATED_DATE_FULL}</time></i>
				</div>
			# ENDIF #

			# IF C_SUGGESTED_ITEMS #
				<hr />
				<h6><i class="fa fa-fw fa-lightbulb-o"></i> ${LangLoader::get_message('suggestions', 'common')} :</h6>
				<div class="elements-container columns-{SUGGESTED_COLUMNS} no-style">
					# START suggested_items #
					<div class="block suggested-thumbnail">
						<a href="{suggested_items.U_ITEM}">
							<figure>
								# IF suggested_items.C_HAS_THUMBNAIL #<img src="# IF suggested_items.C_PTR #{PATH_TO_ROOT}# ENDIF #{suggested_items.THUMBNAIL}" alt="{suggested_items.TITLE}" /># ENDIF #
								<figcaption>{suggested_items.TITLE}</figcaption>
							</figure>
						</a>
					</div>
					# END suggested_items #
				</div>
			# ENDIF #

			# IF C_NAVIGATION_LINKS #
				<hr />
				<div class="navigation-link">
					# IF C_PREVIOUS_ITEM #
						<span class="navigation-link-previous">
							<a href="{U_PREVIOUS_ITEM}">
								<figure class="navigation-link-thumbnail">
									# IF C_PREVIOUS_HAS_THUMBNAIL #<img src="# IF C_PREVIOUS_PTR #{PATH_TO_ROOT}# ENDIF #{PREVIOUS_THUMBNAIL}" alt="{PREVIOUS_ITEM_TITLE}" /># ENDIF #
									<figcaption><i class="fa fa-fw fa-arrow-circle-left"></i> {PREVIOUS_ITEM_TITLE}</figcaption>
								</figure>
							</a>
						</span>
					# ENDIF #
					# IF C_NEXT_ITEM #
						<span class="navigation-link-next">
							<a href="{U_NEXT_ITEM}">
								<figure class="navigation-link-thumbnail">
									# IF C_NEXT_HAS_THUMBNAIL #<img src="# IF C_NEXT_PTR #{PATH_TO_ROOT}# ENDIF #{NEXT_THUMBNAIL}" alt="{NEXT_ITEM_TITLE}" /># ENDIF #
									<figcaption>{NEXT_ITEM_TITLE} <i class="fa fa-fw fa-arrow-circle-right"></i></figcaption>
								</figure>
							</a>
						</span>
					# ENDIF #
					<div class="spacer"></div>
				</div>
			# ENDIF #
		</aside>
		<footer></footer>
	</article>
	<footer></footer>
</section>
