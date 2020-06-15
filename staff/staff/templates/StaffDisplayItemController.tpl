<section id="module-staff">
	<header>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			{@staff.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF #
		</h1>
	</header>
	<div class="content">
		# IF NOT C_VISIBLE #
			# INCLUDE NOT_VISIBLE_MESSAGE #
		# ENDIF #
		<article id="article-staff-{ID}" itemscope="itemscope" itemtype="http://schema.org/CreativeWork" class="article-staff# IF C_IS_GROUP_LEADER # group-leader# ENDIF ## IF C_NEW_CONTENT # new-content# ENDIF#">
			<header>
				<h2>
					<span id="name" itemprop="name">{FIRSTNAME} {LASTNAME}</span>
					<span class="actions">
						# IF C_EDIT #
							<a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF C_DELETE #
							<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
						# ENDIF #
					</span>
				</h2>

				<meta itemprop="url" content="{U_ITEM}">
				<meta itemprop="description" content="${escape(FIRSTNAME)} ${escape(LASTNAME)}" />

			</header>
			<div class="content">
				<div class="options infos">
					# IF C_ROLE # <p>{ROLE}</p># ENDIF #
					# IF C_ITEM_PHONE # <p>{ITEM_PHONE}</p># ENDIF #
					# IF C_ITEM_EMAIL #
						<a href="#email-modal" class="email-modal-btn">{@email.contact} <i class="fa fa-fw fa-at"></i></a>
						<div id="email-modal" class="adherent-modal">
							<a href="#email-modal-close" class="modal-close"><i class="fa fa-fw fa-remove"></i></a>
							<div class="email-form">
								# INCLUDE MSG #
								# IF NOT C_ITEM_EMAIL_SENT #
									# INCLUDE EMAIL_FORM #
								# ENDIF  #
							</div>
						</div>
					# ENDIF #
				</div>

				# IF C_HAS_THUMBNAIL #
				<span class="staff-picture">
					<img src="{U_THUMBNAIL}" alt="{FIRSTNAME} {LASTNAME}" itemprop="image" />
				</span>
				# ENDIF #
				<div class="spacer"></div>
				<div itemprop="text">{CONTENTS}</div>
			</div>
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>
