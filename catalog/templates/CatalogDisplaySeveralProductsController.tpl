<section id="module-catalog">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('catalog', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #{@catalog.pending}# ELSE #{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
		</h1>
		# IF C_CATEGORY_DESCRIPTION #
			<div class="cat-description">
				{CATEGORY_DESCRIPTION}
			</div>
		# ENDIF #
	</header>

	# IF C_ROOT_CATEGORY #
		# IF C_EXTRA_DISPLAY #
			<div class="elements-container columns-{EXTRA_COLUMNS_NB}">
				# IF C_LAST_PRODUCTS #
					<article class="block">
						<h5>{@home.last.products}</h5>
						# IF C_NO_LAST_PRODUCT_AVAILABLE #
						{@home.no.product}
						# ELSE #
						<ul>
							# START last_products #
							<li>
								<a href="{last_products.U_LINK}" itemprop="name"# IF last_products.C_NEW_CONTENT # class="new-content"# ENDIF #>{last_products.NAME}</a> in
								<a href="{last_products.U_CATEGORY}" itemprop="name">{last_products.CATEGORY_NAME}</a>
							</li>
							# END last_products #
						</ul>
						# ENDIF #
					</article>
				# ENDIF #

				# IF C_FLASH_PRODUCTS #
					<article class="block">
						<h5>{@home.flash.sales}</h5>
						# IF C_NO_FLASH_PRODUCT_AVAILABLE #
						{@home.no.product}
						# ELSE #
						<ul>
							# START flash_sales #
							<li>
								<a href="{flash_sales.U_LINK}" itemprop="name"# IF flash_sales.C_NEW_CONTENT # class="new-content"# ENDIF #>{flash_sales.NAME}</a> in
								<a href="{flash_sales.U_CATEGORY}" itemprop="name">{flash_sales.CATEGORY_NAME}</a>
							</li>
							# END flash_sales #
						</ul>
						# ENDIF #
					</article>
				# ENDIF #

				# IF C_LAST_PROMOTED_PRODUCTS #
					<article class="block">
						<h5>{@home.last.promoted.products}</h5>
						# IF C_NO_PRODUCT_ON_SALE_AVAILABLE #
						{@home.no.product}
						# ELSE #
						<ul>
							# START last_promoted_products #
							<li>
								<a href="{last_promoted_products.U_LINK}" itemprop="name"# IF last_promoted_products.C_NEW_CONTENT # class="new-content"# ENDIF #>{last_promoted_products.NAME}</a> in
								<a href="{last_promoted_products.U_CATEGORY}" itemprop="name">{last_promoted_products.CATEGORY_NAME}</a>
							</li>
							# END last_promoted_products #
						</ul>
							# ENDIF #
					</article>
				# ENDIF #
			</div>


		# ENDIF #
	# ENDIF #

	# IF C_SUB_CATEGORIES #
	<div class="subcat-container elements-container# IF C_SEVERAL_CATS_COLUMNS # columns-{NUMBER_CATS_COLUMNS}# ENDIF #">
		# START sub_categories_list #
		<div class="subcat-element block">
			<div class="subcat-content">
				# IF sub_categories_list.C_CATEGORY_IMAGE #<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" /></a># ENDIF #
				<br />
				<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
				<br />
				<span class="small">{sub_categories_list.PRODUCTS_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_PRODUCT #${TextHelper::lcfirst(LangLoader::get_message('products', 'common', 'catalog'))}# ELSE #${TextHelper::lcfirst(LangLoader::get_message('product', 'common', 'catalog'))}# ENDIF #</span>
			</div>
		</div>
		# END sub_categories_list #
		<div class="spacer"></div>
	</div>
	# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
	# ELSE #
		# IF NOT C_CATEGORY_DISPLAYED_TABLE #<div class="spacer"></div># ENDIF #
	# ENDIF #

	<div class="content elements-container">
	# IF NOT C_ROOT_CATEGORY #
		# IF C_PRODUCT #
			# IF C_MORE_THAN_ONE_PRODUCT #
				# INCLUDE SORT_FORM #
				<div class="spacer"></div>
			# ENDIF #
			# IF C_CATEGORY_DISPLAYED_TABLE #
				<table id="table">
					<thead>
						<tr>
							<th>${LangLoader::get_message('form.name', 'common')}</th>
							<th class="col-small">${LangLoader::get_message('form.keywords', 'common')}</th>
							<th class="col-small">${LangLoader::get_message('form.date.creation', 'common')}</th>
							<th class="col-small">{@downloads_number}</th>
							# IF C_NB_VIEW_ENABLED #<th>{@product.number.view}</th># ENDIF #
							# IF C_NOTATION_ENABLED #<th>${LangLoader::get_message('note', 'common')}</th># ENDIF #
							# IF C_COMMENTS_ENABLED #<th class="col-small">${LangLoader::get_message('comments', 'comments-common')}</th># ENDIF #
							# IF C_MODERATION #<th class="col-smaller"></th># ENDIF #
						</tr>
					</thead>
					<tbody>
						# START products #
						<tr>
							<td>
								<a href="{products.U_LINK}" itemprop="name"# IF products.C_NEW_CONTENT # class="new-content"# ENDIF #>{products.NAME}</a>
							</td>
							<td>
								# IF products.C_KEYWORDS #
									# START products.keywords #
										<a itemprop="keywords" href="{products.keywords.URL}">{products.keywords.NAME}</a># IF products.keywords.C_SEPARATOR #, # ENDIF #
									# END products.keywords #
								# ELSE #
									${LangLoader::get_message('none', 'common')}
								# ENDIF #
							</td>
							<td>
								<time datetime="# IF NOT products.C_DIFFERED #{products.DATE_ISO8601}# ELSE #{products.DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT products.C_DIFFERED #{products.DATE}# ELSE #{products.DIFFERED_START_DATE}# ENDIF #</time>
							</td>
							<td>
								{products.NUMBER_DOWNLOADS}
							</td>
							# IF C_NB_VIEW_ENABLED #
							<td>
								{products.NUMBER_VIEW}
							</td>
							# ENDIF #
							# IF C_NOTATION_ENABLED #
							<td>
								{products.STATIC_NOTATION}
							</td>
							# ENDIF #
							# IF C_COMMENTS_ENABLED #
							<td>
								# IF products.C_COMMENTS # {products.NUMBER_COMMENTS} # ENDIF # {products.L_COMMENTS}
							</td>
							# ENDIF #
							# IF C_MODERATION #
							<td>
								# IF products.C_EDIT #
									<a href="{products.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
								# ENDIF #
								# IF products.C_DELETE #
									<a href="{products.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
								# ENDIF #
							</td>
							# ENDIF #
						</tr>
						# END products #
					</tbody>
				</table>
			# ELSE #
				# START products #
				<article id="article-catalog-{products.ID}" class="article-catalog article-several# IF C_CATEGORY_DISPLAYED_SUMMARY # block# ENDIF ## IF products.C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
					<header>
						<h2>
							<span class="actions">
								# IF products.C_EDIT #<a href="{products.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a># ENDIF #
								# IF products.C_DELETE #<a href="{products.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a># ENDIF #
							</span>
							<a href="{products.U_LINK}" itemprop="name">{products.NAME}</a>
						</h2>

						<meta itemprop="url" content="{products.U_LINK}">
						<meta itemprop="description" content="${escape(products.DESCRIPTION)}"/>
						# IF C_COMMENTS_ENABLED #
						<meta itemprop="discussionUrl" content="{products.U_COMMENTS}">
						<meta itemprop="interactionCount" content="{products.NUMBER_COMMENTS} UserComments">
						# ENDIF #
					</header>

					# IF C_CATEGORY_DISPLAYED_SUMMARY #
						<div class="more">
							<i class="fa fa-catalog" title="{products.L_DOWNLOADED_TIMES}"></i>
							<span title="{products.L_DOWNLOADED_TIMES}">{products.NUMBER_DOWNLOADS}</span>
							# IF C_NB_VIEW_ENABLED # | <span title="{products.NUMBER_VIEW} {@product.view}"><i class="fa fa-eye"></i> {products.NUMBER_VIEW}</span># ENDIF #
							# IF C_COMMENTS_ENABLED #
								| <i class="fa fa-comment" title="${LangLoader::get_message('comments', 'comments-common')}"></i>
								# IF products.C_COMMENTS # {products.NUMBER_COMMENTS} # ENDIF # {products.L_COMMENTS}
							# ENDIF #
							# IF products.C_KEYWORDS #
								| <i class="fa fa-tags" title="${LangLoader::get_message('form.keywords', 'common')}"></i>
								# START products.keywords #
									<a itemprop="keywords" href="{products.keywords.URL}">{products.keywords.NAME}</a>
									# IF products.keywords.C_SEPARATOR #, # ENDIF #
								# END products.keywords #
							# ENDIF #
							# IF C_NOTATION_ENABLED #
								<span class="float-right">{products.STATIC_NOTATION}</span>
							# ENDIF #
							<div class="spacer"></div>
						</div>
						<div class="content">
							# IF products.C_PICTURE #
							<span class="catalog-picture">
								<img src="{products.U_PICTURE}" alt="{products.NAME}" itemprop="image" />
							</span>
							# ENDIF #
							{products.DESCRIPTION}# IF products.C_READ_MORE #... <a href="{products.U_LINK}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #
							<div class="spacer"></div>
						</div>
					# ELSE #
						<div class="content">
							<div class="options infos">
								<div class="center">
									# IF products.C_PICTURE #
										<img src="{products.U_PICTURE}" alt="{products.NAME}" itemprop="image" />
										<div class="spacer"></div>
									# ENDIF #
									# IF products.C_VISIBLE #
										<a href="{products.U_DOWNLOAD}" class="basic-button">
											<i class="fa fa-catalog"></i> {@catalog}
										</a>
										# IF IS_USER_CONNECTED #
										<a href="{products.U_DEADLINK}" class="basic-button alt" title="${LangLoader::get_message('deadlink', 'common')}">
											<i class="fa fa-unlink"></i>
										</a>
										# ENDIF #
									# ENDIF #
								</div>
								<h6>{@product_infos}</h6>
								<span class="text-strong">${LangLoader::get_message('size', 'common')} : </span><span># IF products.C_SIZE #{products.SIZE}# ELSE #${LangLoader::get_message('unknown_size', 'common')}# ENDIF #</span><br/>
								<span class="text-strong">${LangLoader::get_message('form.date.creation', 'common')} : </span><span><time datetime="# IF NOT products.C_DIFFERED #{products.DATE_ISO8601}# ELSE #{products.DIFFERED_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT products.C_DIFFERED #{products.DATE}# ELSE #{products.DIFFERED_START_DATE}# ENDIF #</time></span><br/>
								# IF products.C_UPDATED_DATE #<span class="text-strong">${LangLoader::get_message('form.date.update', 'common')} : </span><span><time datetime="{products.UPDATED_DATE_ISO8601}" itemprop="dateModified">{products.UPDATED_DATE}</time></span><br/># ENDIF #
								<span class="text-strong">{@downloads_number} : </span><span>{products.NUMBER_DOWNLOADS}</span><br/>
								# IF C_NB_VIEW_ENABLED #<span class="text-strong">{@product.number.view} : </span><span title="{products.NUMBER_VIEW} {@product.view}">{products.NUMBER_VIEW}</span><br/># ENDIF #
								# IF NOT C_CATEGORY #<span class="text-strong">${LangLoader::get_message('category', 'categories-common')} : </span><span><a itemprop="about" class="small" href="{products.U_CATEGORY}">{products.CATEGORY_NAME}</a></span><br/># ENDIF #
								# IF products.C_KEYWORDS #
									<span class="text-strong">${LangLoader::get_message('form.keywords', 'common')} : </span>
									<span>
										# START products.keywords #
											<a itemprop="keywords" class="small" href="{products.keywords.URL}">{products.keywords.NAME}</a># IF products.keywords.C_SEPARATOR #, # ENDIF #
										# END products.keywords #
									</span><br/>
								# ENDIF #
								# IF C_AUTHOR_DISPLAYED #
									<span class="text-strong">${LangLoader::get_message('author', 'common')} : </span>
									<span>
										# IF products.C_AUTHOR_CUSTOM_NAME #
											{products.AUTHOR_CUSTOM_NAME}
										# ELSE #
											# IF products.C_AUTHOR_EXIST #<a itemprop="author" rel="author" class="small {products.USER_LEVEL_CLASS}" href="{products.U_AUTHOR_PROFILE}" # IF products.C_USER_GROUP_COLOR # style="color:{products.USER_GROUP_COLOR}" # ENDIF #>{products.PSEUDO}</a># ELSE #{products.PSEUDO}# ENDIF #
										# ENDIF #
									</span><br/>
								# ENDIF #
								# IF C_COMMENTS_ENABLED #
									<span># IF products.C_COMMENTS # {products.NUMBER_COMMENTS} # ENDIF # {products.L_COMMENTS}</span>
								# ENDIF #
								# IF products.C_VISIBLE #
									# IF C_NOTATION_ENABLED #
										<div class="spacer"></div>
										<div class="center">{products.NOTATION}</div>
									# ENDIF #
								# ENDIF #
							</div>

							<div itemprop="text">{products.CONTENTS}</div>
						</div>
					# ENDIF #

					<footer></footer>
				</article>
				# END products #
			# ENDIF #
		# ELSE #
			# IF NOT C_HIDE_NO_ITEM_MESSAGE #
			<div class="center">
				${LangLoader::get_message('no_item_now', 'common')}
			</div>
			# ENDIF #
		# ENDIF #
	# ENDIF #
	</div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
