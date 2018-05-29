<section id="pbtdoc-module">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('pbtdoc', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #
				{@pbtdoc.pending_courses}
			# ELSE #
				{@module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# ENDIF # # IF C_CATEGORY #
				# IF IS_ADMIN #
					<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}">
						<i class="fa fa-edit small"></i>
					</a>
				# ENDIF # # IF C_DISPLAY_REORDER_LINK #
					<a href="{U_REORDER_ITEMS}" title="{@pbtdoc.reorder}">
						<i class="fa fa-exchange small"></i>
					</a>
				# ENDIF #
			# ENDIF #
		</h1>
		# IF C_CATEGORY_DESCRIPTION #
			<div class="cat-description">
				{CATEGORY_DESCRIPTION}
			</div>
		# ENDIF #
	</header>

	# IF C_SUB_CATEGORIES #
		# IF C_TABLE #
		<table id="table1">
			<thead>
				<tr>
					<th>{@pbtdoc.sub.categories}</th>
					<th>{@pbtdoc.courses.nb}</th>
					<th colspan="3">
						# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
					</th>
				</tr>
			</thead>
			<tbody>
				# START sub_categories_list #
				<tr>
					<td class="left">
						<a class="subcat-title" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
							{sub_categories_list.CATEGORY_NAME}
						</a># IF sub_categories_list.C_CATEGORY_DESCRIPTION # - <em>{sub_categories_list.CATEGORY_DESCRIPTION}</em># ENDIF #
					</td>
					<td>
						{sub_categories_list.COURSES_NUMBER}
						# IF sub_categories_list.C_MORE_THAN_ONE_COURSE #
							${TextHelper::lcfirst(LangLoader::get_message('courses', 'common', 'pbtdoc'))}
						# ELSE #
							${TextHelper::lcfirst(LangLoader::get_message('course', 'common', 'pbtdoc'))}
						# ENDIF #
					</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				# END sub_categories_list #
			</tbody>
		</table>
			# ELSE #
			<div class="subcat-container elements-container no-style# IF C_SEVERAL_CATS_COLUMNS # columns-{NUMBER_CATS_COLUMNS}# ENDIF #">
				# START sub_categories_list #
				<div class="subcat-element block">
					<div class="subcat-content">
						# IF C_DISPLAY_CATS_ICON #
							# IF sub_categories_list.C_CATEGORY_IMAGE #
								<a class="subcat-thumbnail" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
									<img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" />
								</a>
							# ENDIF #
						# ENDIF #
						<a class="subcat-title" itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
						<span class="subcat-options">
							{sub_categories_list.COURSES_NUMBER}
							# IF sub_categories_list.C_MORE_THAN_ONE_COURSE #
								${TextHelper::lcfirst(LangLoader::get_message('courses', 'common', 'pbtdoc'))}
							# ELSE #
								${TextHelper::lcfirst(LangLoader::get_message('course', 'common', 'pbtdoc'))}
							# ENDIF #
						</span>
					</div>
				</div>
				# END sub_categories_list #
			</div>
			# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
		# ENDIF #
	# ENDIF #


	# IF C_NO_COURSE_AVAILABLE #
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
		<div class="center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
		# ENDIF #
	# ELSE #
		# IF C_TABLE #
			<table id="table">
				<thead>
					<tr>
						<th>{@courses}</th>
						<th>{@pbtdoc.creation.date}</th>
						<th>{@pbtdoc.updated.date}</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					# START items #
						<tr>
							<td class="left">
								<a itemprop="url" href="{items.U_COURSE}">
									<span itemprop="name">{items.TITLE}</span>
								</a>
							</td>
							<td>
								<time title="{@pbtdoc.creation.date}" datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.PUBLISHING_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished">
									# IF NOT items.C_DIFFERED #{items.DATE}# ELSE #{items.PUBLISHING_START_DATE}# ENDIF #
								</time>
							</td>
							<td>
								<time title="{@pbtdoc.updated.date}" datetime="{items.DATE_UPDATED}" itemprop="datePublished">
									{items.DATE_UPDATED}
								</time>
							</td>
							<td>
								# IF items.C_EDIT #
									<a href="{items.U_EDIT_COURSE}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
								# ENDIF #
							</td>
							<td>
								# IF items.C_DELETE #
									<a href="{items.U_DELETE_COURSE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
								# ENDIF #
							</td>
						</tr>
					# END items #
				</tbody>
				<tfoot>
					<tr>
						<td class="right" colspan="5"></td>
					</tr>
				</tfoot>
			</table>
		# ELSE #
			<div class="content elements-container# IF C_SEVERAL_COLUMNS # columns-{NUMBER_COLUMNS}# ENDIF#">
				# START items #
					<article id="course-items-{items.ID}" class="course-items course-several# IF C_MOSAIC # block# ENDIF ## IF items.C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						<header>
							<h2>
								<a itemprop="url" href="{items.U_COURSE}"><span itemprop="name">{items.TITLE}</span></a>
								<span class="actions">
									# IF items.C_EDIT #
										<a href="{items.U_EDIT_COURSE}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
									# ENDIF #
									# IF items.C_DELETE #
										<a href="{items.U_DELETE_COURSE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
									# ENDIF #
								</span>
							</h2>

							<div class="more">
								<time title="{@pbtdoc.creation.date}" datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.PUBLISHING_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"><i class="fas fa-calendar"></i> # IF NOT items.C_DIFFERED #{items.DATE} | # ELSE #{items.PUBLISHING_START_DATE} | # ENDIF #</time>
								<time title="{@pbtdoc.updated.date}" datetime="{items.DATE_UPDATED}" itemprop="datePublished"><i class="far fa-calendar"></i> {items.DATE_UPDATED}</time>

							</div>

							<meta itemprop="url" content="{items.U_COURSE}">
							<meta itemprop="description" content="${escape(items.DESCRIPTION)}"/>
							<meta itemprop="discussionUrl" content="{items.U_COMMENTS}">
							<meta itemprop="interactionCount" content="{items.NUMBER_COMMENTS} UserComments">

						</header>
							# IF items.C_HAS_THUMBNAIL #
								<a href="{items.U_COURSE}" class="thumbnail-item">
									<img itemprop="thumbnailUrl" src="{items.THUMBNAIL}" alt="{items.TITLE}" />
								</a>
							# ENDIF #

						<footer></footer>
					</article>
				# END items #
			</div>
		# ENDIF #
	# ENDIF #
		<div class="spacer"></div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
