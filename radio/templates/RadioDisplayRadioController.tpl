<section id="module-radio">
	<header>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			{@radio}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF #
		</h1>
	</header>
	<div class="content">
		# IF NOT C_VISIBLE #
			# INCLUDE NOT_VISIBLE_MESSAGE #
		# ENDIF #
		<article itemscope="itemscope" itemtype="http://schema.org/CreativeWork" id="article-radio-{ID}" class="article-radio">
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

				<meta itemprop="url" content="{U_LINK}">
				<meta itemprop="description" content="${escape(CONTENTS)}" />

			</header>
			<div class="content">
				<p class="theme float-left"><span class="{CALENDAR}">{CALENDAR}</span></p>
				<p class="float-right">{@form.announcer} : {AUTHOR_CUSTOM_NAME}</p>
				<div class="spacer"></div>
				<p class="float-left">{START_HOURS}h{START_MINUTES}</p>
				<p class="float-right">{END_HOURS}h{END_MINUTES}</p>
				<div class="spacer"></div>
				<div itemprop="text">{CONTENTS}</div>
				# IF C_PICTURE #<img itemprop="thumbnailUrl" src="{U_PICTURE}" alt="{NAME}" title="{NAME}" class="radio-picture" /># ENDIF #
			</div>

			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>
