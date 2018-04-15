<section id="module-staff">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('staff', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #{@staff.pending}# ELSE #{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
		</h1>

		# IF C_CATEGORY_DESCRIPTION #
		<div class="cat-description">
			{CATEGORY_DESCRIPTION}
		</div>
		# ENDIF #

	</header>
	# IF C_ROOT_CATEGORY #
	plop
		# START staffcats #
			<h2><a href="{staffcats.U_CATEGORY}">{staffcats.CATEGORY_NAME}</a></h2>
			<div class="elements-container # IF NOT C_CATEGORY_DISPLAYED_TABLE #columns-{NUMBER_MEMBERS}# ENDIF #">
				# IF C_CATEGORY_DISPLAYED_TABLE #
					# IF staffcats.C_MEMBERS #
						<table id="table">
							<thead>
								<tr>
									<th>{@staff.form.lastname}</th>
									<th>{@staff.form.role}</th>
									<th class="col-small">{@staff.form.group.leader}</th>
									<th>{@staff.form.member.phone}</th>
									# IF C_MODERATE #<th class="col-small"><i class="fa fa-cogs"></i></th># ENDIF #
								</tr>
							</thead>
							<tbody>
								# START staffcats.members #
								<tr>
									<td>
										<a href="{staffcats.members.U_MEMBER}" itemprop="name"# IF staffcats.members.C_NEW_CONTENT # class="new-content"# ENDIF#>{staffcats.members.FIRSTNAME} {staffcats.members.LASTNAME}</a>
									</td>
									<td>
										{staffcats.members.ROLE}
									</td>
									<td>
										# IF staffcats.members.C_IS_GROUP_LEADER # <img src="{PATH_TO_ROOT}/staff/templates/images/group_leader.png" alt="{@staff.form.group.leader}" /># ENDIF #
									</td>
									<td>
										{staffcats.members.MEMBER_PHONE}
									</td>
									# IF C_MODERATE #
									<td>
										# IF staffcats.members.C_EDIT #
										<a href="{staffcats.members.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
										# ENDIF #
										# IF staffcats.members.C_DELETE #
										<a href="{staffcats.members.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
										# ENDIF #
									</td>
									# ENDIF #
								</tr>
								# END staffcats.members #
							</tbody>
						</table>
					# ENDIF #
				# ELSE #
					# START staffcats.members #
						<article id="article-staff-{staffcats.members.ID}" class="article-staff article-several block# IF staffcats.members.C_IS_GROUP_LEADER # group-leader# ENDIF ## IF staffcats.members.C_NEW_CONTENT # new-content# ENDIF#" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
							<header>
								<h2>
									<span class="actions">
										# IF staffcats.members.C_EDIT #<a href="{staffcats.members.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a># ENDIF #
										# IF staffcats.members.C_DELETE #<a href="{staffcats.members.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a># ENDIF #
									</span>
									<a href="{staffcats.members.U_MEMBER}" itemprop="name">{staffcats.members.FIRSTNAME} {staffcats.members.LASTNAME}</a>
								</h2>

								<meta itemprop="url" content="{staffcats.members.U_MEMBER}">
								<meta itemprop="description" content="${escape(staffcats.members.FIRSTNAME)} ${escape(staffcats.members.LASTNAME)}"/>
							</header>

							<div class="content">
								<figure# IF staffcats.members.C_IS_GROUP_LEADER # class="group-leader-figure"# ENDIF #>
									<img src="{staffcats.members.U_PICTURE}" alt="{staffcats.members.FIRSTNAME} {staffcats.members.LASTNAME}" />
									<figcaption>{staffcats.members.ROLE}</figcaption>
								</figure>
							</div>

							<footer></footer>
						</article>
					# END staffcats.members #
				# ENDIF #
			</div>
		# END staffcats #
	# ELSE #
		# IF C_SUB_CATEGORIES #
			<div class="subcat-container elements-container# IF C_SEVERAL_CATS_COLUMNS # columns-{NUMBER_CATS_COLUMNS}# ENDIF #">
				# START sub_categories_list #
				<div class="subcat-element block">
					<div class="subcat-content">
						# IF sub_categories_list.C_CATEGORY_IMAGE #<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" /></a># ENDIF #
						<br />
						<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
						<br />
						<span class="small">{sub_categories_list.MEMBERS_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_MEMBER #${TextHelper::lcfirst(LangLoader::get_message('members', 'common', 'staff'))}# ELSE #${TextHelper::lcfirst(LangLoader::get_message('member', 'common', 'staff'))}# ENDIF #</span>
					</div>
				</div>
				# END sub_categories_list #
				<div class="spacer"></div>
			</div>
			# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
		# ELSE #
			# IF NOT C_CATEGORY_DISPLAYED_TABLE #<div class="spacer"></div># ENDIF #
		# ENDIF #

		# IF C_MEMBERS #
			# IF C_MORE_THAN_ONE_MEMBER #
				<div class="spacer"></div>
			# ENDIF #
			<div class="content elements-container # IF NOT C_CATEGORY_DISPLAYED_TABLE #columns-{NUMBER_MEMBERS}# ENDIF #">
				# IF C_CATEGORY_DISPLAYED_TABLE #
					<table id="table">
						<thead>
							<tr>
								<th>{@staff.form.lastname}</th>
								<th>{@staff.form.role}</th>
								<th class="col-small">{@staff.form.group.leader}</th>
								<th>{@staff.form.member.phone}</th>
								# IF C_MODERATE #<th class="col-small"><i class="fa fa-cogs"></i></th># ENDIF #
							</tr>
						</thead>
						<tbody>
							# START members #
							<tr>
								<td>
									<a href="{members.U_MEMBER}" itemprop="name"# IF members.C_NEW_CONTENT # class="new-content"# ENDIF#>{members.FIRSTNAME} {members.LASTNAME}</a>
								</td>
								<td>
									# IF members.C_ROLE #{members.ROLE}# ENDIF #
								</td>
								<td>
									# IF members.C_IS_GROUP_LEADER # <img src="{PATH_TO_ROOT}/staff/templates/images/group_leader.png" alt="{@staff.form.group.leader}" /># ENDIF #
								</td>
								<td>
									# IF members.MEMBER_PHONE #{members.MEMBER_PHONE}# ENDIF #
								</td>
								# IF C_MODERATE #
								<td>
									# IF members.C_EDIT #
									<a href="{members.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
									# ENDIF #
									# IF members.C_DELETE #
									<a href="{members.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
									# ENDIF #
								</td>
								# ENDIF #
							</tr>
							# END members #
						</tbody>
					</table>
				# ELSE #
					# START members #
						<article id="article-staff-{members.ID}" class="article-staff article-several block# IF members.C_IS_GROUP_LEADER # group-leader# ENDIF ## IF members.C_NEW_CONTENT # new-content# ENDIF#" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
							<header>
								<h2>
									<span class="actions">
										# IF members.C_EDIT #<a href="{members.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a># ENDIF #
										# IF members.C_DELETE #<a href="{members.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a># ENDIF #
									</span>
									<a href="{members.U_MEMBER}" itemprop="name">{members.FIRSTNAME} {members.LASTNAME}</a>
								</h2>

								<meta itemprop="url" content="{members.U_MEMBER}">
								<meta itemprop="description" content="${escape(members.FIRSTNAME)} ${escape(members.LASTNAME)}"/>
							</header>

							<div class="content">
								<figure# IF members.C_IS_GROUP_LEADER # class="group-leader-figure"# ENDIF #>
									# IF members.C_PICTURE # <img src="{members.U_PICTURE}" alt="{members.FIRSTNAME} {members.LASTNAME}" /># ENDIF #
									# IF members.C_ROLE # <figcaption>{members.ROLE}</figcaption>
								</figure>
							</div>
							# ENDIF #

							<footer></footer>
						</article>
					# END members #
				# ENDIF #
			</div>
		# ELSE #
			<div class="content">
				# IF NOT C_HIDE_NO_ITEM_MESSAGE #
					<div class="center">
						${LangLoader::get_message('no_item_now', 'common')}
					</div>
				# ENDIF #
			</div>
		# ENDIF #
	# ENDIF #

	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
