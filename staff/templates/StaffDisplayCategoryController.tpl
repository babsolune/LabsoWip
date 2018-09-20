<section id="module-staff">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('staff', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #
				{@staff.pending}
			# ELSE #
				{@staff.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# ENDIF # # IF C_CATEGORY #
				<span class="actions">
					# IF IS_ADMIN #
						<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}">
							<i class="fa fa-edit"></i>
						</a>
					# ENDIF # # IF C_DISPLAY_REORDER_LINK #
						<a href="{U_REORDER_ITEMS}" title="{@staff.reorder}">
							<i class="fa fa-exchange"></i>
						</a>
					# ENDIF #
				</span>
			# ENDIF #
		</h1>

		# IF C_CATEGORY_DESCRIPTION #
		<div class="cat-description">
			{CATEGORY_DESCRIPTION}
		</div>
		# ENDIF #

	</header>

	# IF C_SUB_CATEGORIES #
		<div class="subcat-container elements-container# IF C_SEVERAL_CATS_COLUMNS # columns-{NUMBER_CATS_COLUMNS}# ENDIF #">
			# START sub_categories_list #
			<div class="subcat-element block">
				<div class="subcat-content">
					# IF sub_categories_list.C_CATEGORY_IMAGE #<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" /></a># ENDIF #
					<br />
					<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
					<br />
					<span class="small">{sub_categories_list.ADHERENTS_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_ADHERENT #${TextHelper::lcfirst(LangLoader::get_message('adherents', 'common', 'staff'))}# ELSE #${TextHelper::lcfirst(LangLoader::get_message('adherent', 'common', 'staff'))}# ENDIF #</span>
				</div>
			</div>
			# END sub_categories_list #
			<div class="spacer"></div>
		</div>
	# ELSE #
		<div class="spacer"></div>
	# ENDIF #

	# IF C_ADHERENTS #
		# IF C_MORE_THAN_ONE_ADHERENT #
			<div class="spacer"></div>
		# ENDIF #
		<ul class="adherent-table show-table">
			# START items #
				<li>
					<div class="li-avatar">
						# IF C_AVATARS_ALLOWED #
							<img src="{items.U_THUMBNAIL}" alt="{items.FIRSTNAME} {items.LASTNAME}" />
						# ENDIF #
					</div>
					<div class="li-infos">
						<div class="li-title">
							<div class="li-table li-leader">
								# IF items.C_IS_GROUP_LEADER # <i class="fa fa-user" title="{@staff.form.group.leader}"></i># ENDIF #
							</div>
							<div class="li-table li-adherent"><a href="{items.U_ITEM}" itemprop="name">{items.FIRSTNAME} <span class="adherent-name">{items.LASTNAME}</span></a></div>
						</div>
						<div class="li-options# IF C_MODERATE # moderator# ENDIF #">
							# IF C_PENDING #
								<div class="li-table li-role">{items.ROLE}</div>
								<div class="li-table li-phone center">{items.CATEGORY_NAME}</div>
							# ELSE #
								<div class="li-table li-role">{items.ROLE}</div>
								<div class="li-table li-phone">
									# IF items.C_ITEM_PHONE #
										<span class="show-phone">{items.ITEM_PHONE}</span>
										<span class="hide-phone">{@reveal.adherent.phone}</span>
									# ENDIF #
								</div>
							# ENDIF #
						</div>
						# IF C_MODERATE #
							<div class="moderate">
								<a href="{items.U_EDIT}"><i class="fa fa-edit fa-fw" title="${LangLoader::get_message('edit', 'common')}"></i></a>
								<a href="{items.U_DELETE}"><i class="fa fa-trash fa-fw" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"></i></a>
							</div>
						# ENDIF #
					</div>
				</li>
			# END items #
		</ul>
	# ELSE #
		<div class="content">
			# IF NOT C_HIDE_NO_ITEM_MESSAGE #
				<div class="center">
					${LangLoader::get_message('no_item_now', 'common')}
				</div>
			# ENDIF #
		</div>
	# ENDIF #

	<footer></footer>
</section>
