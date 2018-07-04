<section id="wiki-module">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('wiki', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #
				{@wiki.pending_documents}
			# ELSE #
				{@module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF #
			# ENDIF # # IF C_CATEGORY #
				# IF IS_ADMIN #
					<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}">
						<i class="fa fa-edit small"></i>
					</a>
				# ENDIF # # IF C_DISPLAY_REORDER_LINK #
					<a href="{U_REORDER_ITEMS}" title="{@wiki.reorder}">
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
						<th>{@wiki.sub.categories}</th>
						<th>{@wiki.documents.nb}</th>
						<th colspan="3">
							# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
						</th>
					</tr>
				</thead>
				<tbody>
					# START sub_categories_list #
					<tr>
						<td class="left" # IF C_DISPLAY_CATS_COLOR #style="border-left-color: {sub_categories_list.CATEGORY_COLOR}"# ENDIF #>
							<a class="subcat-title" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
								{sub_categories_list.CATEGORY_NAME}
							</a># IF sub_categories_list.C_CATEGORY_DESCRIPTION # - <em>{sub_categories_list.CATEGORY_DESCRIPTION}</em># ENDIF #
						</td>
						<td>
							{sub_categories_list.DOCUMENTS_NUMBER}
							# IF sub_categories_list.C_MORE_THAN_ONE_DOCUMENT #
								${TextHelper::lcfirst(LangLoader::get_message('documents', 'common', 'wiki'))}
							# ELSE #
								${TextHelper::lcfirst(LangLoader::get_message('document', 'common', 'wiki'))}
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
			# IF C_MOSAIC #
				<div class="subcat-container elements-container# IF C_SEVERAL_CATS_COLUMNS # columns-{NUMBER_CATS_COLUMNS}# ENDIF #">
					# START sub_categories_list #
						<article class="subcat-element block">
							<header # IF C_DISPLAY_CATS_COLOR #style="background-color: {sub_categories_list.CATEGORY_COLOR}"# ENDIF #>
								<h2><a class="subcat-title" itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a></h2>
								<span class="subcat-options">
									{sub_categories_list.DOCUMENTS_NUMBER}
									# IF sub_categories_list.C_MORE_THAN_ONE_DOCUMENT #
										${TextHelper::lcfirst(LangLoader::get_message('documents', 'common', 'wiki'))}
									# ELSE #
										${TextHelper::lcfirst(LangLoader::get_message('document', 'common', 'wiki'))}
									# ENDIF #
								</span>
							</header>
							<div class="subcat-content">
								# IF C_DISPLAY_CATS_ICON #
									# IF sub_categories_list.C_CATEGORY_IMAGE #
										<a class="subcat-thumbnail" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
											<img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" />
										</a>
									# ENDIF #
								# ENDIF #
								{sub_categories_list.CATEGORY_DESCRIPTION}
							</div>
						</article>
					# END sub_categories_list #
				</div>
				# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
			# ELSE #
				<div class="subcat-container">
					# START sub_categories_list #
						<article class="subcat-element subcat-list" # IF C_DISPLAY_CATS_COLOR #style="border-color: {sub_categories_list.CATEGORY_COLOR}"# ENDIF #>
							<div class="subcat-content">
								# IF C_DISPLAY_CATS_ICON #
									# IF sub_categories_list.C_CATEGORY_IMAGE #
										<a class="subcat-thumbnail" itemprop="about" href="{sub_categories_list.U_CATEGORY}">
											<img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" />
										</a>
									# ENDIF #
								# ENDIF #
								<header>
									<h2><a class="subcat-title" itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a></h2>
									<span class="subcat-options">
										{sub_categories_list.DOCUMENTS_NUMBER}
										# IF sub_categories_list.C_MORE_THAN_ONE_DOCUMENT #
											${TextHelper::lcfirst(LangLoader::get_message('documents', 'common', 'wiki'))}
										# ELSE #
											${TextHelper::lcfirst(LangLoader::get_message('document', 'common', 'wiki'))}
										# ENDIF #
									</span>
									{sub_categories_list.CATEGORY_DESCRIPTION}
								</header>
							</div>
						</article>
					# END sub_categories_list #
				</div>
				# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
			# ENDIF #
		# ENDIF #
	# ENDIF #


	# IF C_NO_DOCUMENT_AVAILABLE #
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
		<div class="center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
		# ENDIF #
	# ELSE #
		# IF C_TABLE #
			<table id="table2">
				<thead>
					<tr>
						<th>{@documents}</th>
						<th>{@wiki.creation.date}</th>
						<th>{@wiki.updated.date}</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					# START items #
						<tr>
							<td class="left">
								<a itemprop="url" href="{items.U_DOCUMENT}">
									<span itemprop="name">{items.TITLE}</span>
								</a>
							</td>
							<td>
								<time title="{@wiki.creation.date}" datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.PUBLISHING_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished">
									# IF NOT items.C_DIFFERED #{items.DATE}# ELSE #{items.PUBLISHING_START_DATE}# ENDIF #
								</time>
							</td>
							<td>
								<time title="{@wiki.updated.date}" datetime="{items.DATE_UPDATED}" itemprop="datePublished">
									{items.DATE_UPDATED}
								</time>
							</td>
							<td>
								# IF items.C_EDIT #
									<a href="{items.U_EDIT_DOCUMENT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
								# ENDIF #
							</td>
							<td>
								# IF items.C_DELETE #
									<a href="{items.U_DELETE_DOCUMENT}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
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
			# IF C_MOSAIC #
				<div class="elements-container# IF C_SEVERAL_COLUMNS # columns-{NUMBER_COLUMNS}# ENDIF#">
					# START items #
						<article
							id="document-items-{items.ID}"
							class="document-items document-mosaic# IF C_MOSAIC # block# ENDIF ## IF items.C_NEW_CONTENT # new-content# ENDIF #"
							itemscope="itemscope"
							itemtype="http://schema.org/CreativeWork"
							style="">

							<div
								class="thumbnail-document"
								style="background-image: url(# IF items.C_HAS_THUMBNAIL #{items.THUMBNAIL}# ELSE #{PATH_TO_ROOT}/wiki/templates/images/default.jpg# ENDIF #)">
							</div>

							<header>
								<h2>
									<span itemprop="name">{items.TITLE}</span>
								</h2>

								<div class="more">
									<time datetime="{items.DATE_ISO8601}" itemprop="datePublished">{@wiki.creation.date}: {items.DATE}</time>
									# IF items.C_DATE_UPDATED #<time datetime="{items.DATE_UPDATED}" itemprop="datePublished">{@wiki.updated.date}: {items.DATE_UPDATED}</time># ENDIF #
								</div>

								<meta itemprop="url" content="{items.U_DOCUMENT}">
								<meta itemprop="description" content="${escape(items.DESCRIPTION)}"/>
								<meta itemprop="discussionUrl" content="{items.U_COMMENTS}">
								<meta itemprop="interactionCount" content="{items.NUMBER_COMMENTS} UserComments">

							</header>

							<div class="document-description">
								<p><a itemprop="url" href="{items.U_DOCUMENT}"><strong>{items.TITLE}</strong></a></p>
								<div class="description">{items.DESCRIPTION}...</div>
								<a href="{items.U_DOCUMENT}" class="submit">${LangLoader::get_message('read-more', 'common')}</a>
								<span class="document-actions">
									# IF items.C_EDIT #
										<a href="{items.U_EDIT_DOCUMENT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
									# ENDIF #
									# IF items.C_DELETE #
										<a href="{items.U_DELETE_DOCUMENT}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
									# ENDIF #
								</span>
							</div>
							<footer></footer>
						</article>
					# END items #
				</div>
			# ELSE #
			<div class="content">
				# START items #
					<article
						id="document-items-{items.ID}-list"
						class="document-items document-list# IF items.C_NEW_CONTENT # new-content# ENDIF #"
						itemscope="itemscope"
						itemtype="http://schema.org/CreativeWork"
						style="">

						<a
							href="{items.U_DOCUMENT}"
							title="{items.TITLE}"
							class="thumbnail-document"
							style="background-image: url(# IF items.C_HAS_THUMBNAIL #{items.THUMBNAIL}# ELSE #{PATH_TO_ROOT}/wiki/templates/images/default.jpg# ENDIF #)">
						</a>
						<div class="content-document">
							<header>
								<h2>
									<a itemprop="url" href="{items.U_DOCUMENT}"><span itemprop="name">{items.TITLE}</span></a>
									<span class="actions">
										# IF items.C_EDIT #
											<a href="{items.U_EDIT_DOCUMENT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
										# ENDIF #
										# IF items.C_DELETE #
											<a href="{items.U_DELETE_DOCUMENT}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
										# ENDIF #
									</span>
								</h2>

								<div class="more">
									<time datetime="{items.DATE_ISO8601}" itemprop="datePublished">{@wiki.creation.date}: {items.DATE}</time>
									# IF items.C_DATE_UPDATED # | <time datetime="{items.DATE_UPDATED}" itemprop="datePublished">{@wiki.updated.date}: {items.DATE_UPDATED}</time># ENDIF #
								</div>

								<meta itemprop="url" content="{items.U_DOCUMENT}">
								<meta itemprop="description" content="${escape(items.DESCRIPTION)}"/>
								<meta itemprop="discussionUrl" content="{items.U_COMMENTS}">
								<meta itemprop="interactionCount" content="{items.NUMBER_COMMENTS} UserComments">

							</header>

							<div class="document-list-description">
								<div class="description">{items.DESCRIPTION}</div>
								<a href="{items.U_DOCUMENT}" class="submit">${LangLoader::get_message('read-more', 'common')}</a>
							</div>
							<footer></footer>
						</div>


					</article>
				# END items #
			</div>
			# ENDIF #
		# ENDIF #
	# ENDIF #
		<div class="spacer"></div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
