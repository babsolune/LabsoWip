<section id="module-sponsors">
	<header>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF #
		</h1>
	</header>
	<div class="content">
		# IF NOT C_VISIBLE #
			# INCLUDE NOT_VISIBLE_MESSAGE #
		# ENDIF #
		<article id="article-sponsors-{ID}" itemscope="itemscope" itemtype="http://schema.org/CreativeWork" class="article-sponsors# IF C_IS_PARTNER # content-friends# ENDIF ## IF C_IS_PRIVILEGED_PARTNER # content-privileged-friends# ENDIF ## IF C_NEW_CONTENT # new-content# ENDIF#">
			<header>
				<h2>
					<span id="name" itemprop="name">{NAME}</span>
					<span class="actions">
						# IF C_EDIT #
							<a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF C_DELETE #
							<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
						# ENDIF #
					</span>
				</h2>

				<meta itemprop="url" content="{U_LINK}">
				<meta itemprop="description" content="${escape(DESCRIPTION)}" />

			</header>
			<div class="content">
				<div class="options infos">
					<div class="center">
						<span class="sponsors-partner-picture">
							<img src="{U_PARTNER_PICTURE}" alt="{NAME}" itemprop="image" />
						</span>
						<div class="spacer"></div>
						# IF C_VISIBLE #
							# IF C_WEBSITE_URL #
								<a href="{U_VISIT}" rel="noopener nofollow noreferrer" class="basic-button">
									<i class="fa fa-globe"></i> {@visit}
								</a>
								# IF IS_USER_CONNECTED #
								<a href="{U_DEADLINK}" class="basic-button alt" title="${LangLoader::get_message('deadlink', 'common')}">
									<i class="fa fa-unlink"></i>
								</a>
								# ENDIF #
							# ELSE #
								{@no.website.url}
							# ENDIF #
						# ENDIF #
					</div>
					<h6>{@link.infos}</h6>
					<span class="text-strong">{@visits.number} : </span><span>{NUMBER_VIEWS}</span><br/>
					<span class="text-strong">{@partner.activity} : </span><span>${TextHelper::ucfirst(L_ACTIVITY)}</span><br/>
					# IF C_KEYWORDS #
						<span class="text-strong">${LangLoader::get_message('form.keywords', 'common')} : </span>
						<span>
							# START keywords #
								<a itemprop="keywords" class="small" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
							# END keywords #
						</span><br/>
					# ENDIF #
				</div>

				# IF C_PICTURE #
				<span class="sponsors-picture">
					<img src="{U_PICTURE}" alt="{NAME}" itemprop="image" />
				</span>
				# ENDIF #

				<div itemprop="text">{CONTENTS}</div>
			</div>
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>
