<section id="smallads-module">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('smallads', id_category))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #{@smallads.pending.items}# ELSE #{@smallads.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
		</h1>
	</header>

	# IF C_TYPES_FILTERS #
		# IF C_CATEGORY #
			<nav id="category-nav" class="cssmenu cssmenu-horizontal">
				<ul>
					<li cat_id="0" parent_id="0" c_order="0">
						<a class="cssmenu-title" href="{PATH_TO_ROOT}/smallads">{@smallads.all.types.filters}</a>
					</li>
					# START categories #
						<li cat_id="{categories.ID}" parent_id="{categories.ID_PARENT}" c_order="{categories.SUB_ORDER}">
							<a class="cssmenu-title" href="{categories.U_CATEGORY}">{categories.NAME}</a>
						</li>
					# END categories #
				</ul>
			</nav>
			<script>jQuery("#category-nav").menumaker({ title: "{@smallads.category.list}", format: "multitoggle", breakpoint: 768 }); </script>
		# ENDIF #

		# IF C_CATEGORY_DESCRIPTION #
			<div class="cat-description">
				# IF NOT C_ROOT_CATEGORY #
					# IF C_DISPLAY_CAT_ICONS #
						# IF C_CATEGORY_IMAGE #
							<img class="thumbnail-item" itemprop="thumbnailUrl" src="{CATEGORY_IMAGE}" alt="{CATEGORY_NAME}" />
						# ENDIF #
					# ENDIF #
				# ENDIF #
				{CATEGORY_DESCRIPTION}
			</div>
		# ENDIF #
		<div class="spacer"></div>

		# IF C_NO_ITEM_AVAILABLE #
			# IF NOT C_HIDE_NO_ITEM_MESSAGE #
			<div class="center">
				${LangLoader::get_message('no_item_now', 'common')}
			</div>
			# ENDIF #
		# ELSE #
			<h6>{@smallads.form.smallads.types}</h6>
			<div class="jplist-panel">
				<div class="pagination options no-style">
					<div
					   class="jplist-label"
					   data-type=""
					   data-control-type="pagination-info"
					   data-control-name="paging"
					   data-control-action="paging">
					</div>

					<div
					   class="jplist-pagination"
					   data-control-type="pagination"
					   data-control-name="paging"
					   data-control-action="paging"
					   data-items-per-page="{ITEMS_PER_PAGE}">
					</div>
					<div class="spacer"></div>
				</div>
				<label class="jplist-label" for="default-radio">
					<input
					   data-control-type="radio-buttons-filters"
					   data-control-action="filter"
					   data-control-name="default"
					   data-path="default"

					   id="default-radio"
					   type="radio"
					   name="jplist"
					   checked="checked"
					/>	{@smallads.all.types.filters}
				</label>
				# START types #
					<label class="jplist-label" for="{types.TYPE_NAME_FILTER}">
						<input
							data-control-type="radio-buttons-filters"
							data-control-action="filter"
							data-control-name="{types.TYPE_NAME_FILTER}"
							data-path=".{types.TYPE_NAME_FILTER}"
							id="{types.TYPE_NAME_FILTER}"
							type="radio"
							name="jplist"
						/>	{types.TYPE_NAME}
					</label>
				# END types #
				<div class="spacer"></div>

			</div>

			# IF C_MORE_THAN_ONE_ITEM #
				# IF C_ITEMS_SORT_FILTERS #
					# INCLUDE FORM #
					<div class="spacer"></div>
				# ENDIF #
			# ENDIF #
			# IF C_TABLE #
				<table class="list" id="table">
					<thead>
						<tr>
							<th>${LangLoader::get_message('title', 'main')}</th>
							<th>{@smallads.form.price}</th>
							<th>{@smallads.ad.type}</th>
							<th>${LangLoader::get_message('author', 'common')}</th>
							# IF C_CATEGORY #<th>${@smallads.category}</th># ENDIF #
							<th>${@smallads.publication.date}</th>
							# IF C_MODERATION #
								<th>${LangLoader::get_message('administrator_alerts_action', 'admin')}</th>
							# ENDIF #
						</tr>
					</thead>
					<tbody>
						# START items #
						<tr class="list-item# IF items.C_SOLD # sold-smallad# ENDIF ## IF items.C_NEW_CONTENT # new-content# ENDIF #">
							<td>
								# IF NOT items.C_SOLD #<a itemprop="url" href="{items.U_ITEM}"># ENDIF #
									<span itemprop="name">{items.TITLE}</span>
								# IF NOT items.C_SOLD #</a># ENDIF #
							</td>
							<td># IF items.C_SOLD #{@smallads.sold.item}# ELSE ## IF items.C_PRICE #{items.PRICE} €# ENDIF ## ENDIF #</td>
							<td class="{items.SMALLAD_TYPE_FILTER}">{items.SMALLAD_TYPE}</td>
							# IF items.C_DISPLAYED_AUTHOR #
								<td>
									# IF items.C_CUSTOM_AUTHOR_NAME #
										{items.CUSTOM_AUTHOR_NAME}
									# ELSE #
										# IF items.C_AUTHOR_EXIST #<a itemprop="author" href="{items.U_AUTHOR}" class="{items.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{items.USER_GROUP_COLOR}"# ENDIF #>{items.PSEUDO}</a># ELSE #{items.PSEUDO}# ENDIF #
									# ENDIF #
								</td>
							# ENDIF #
							# IF C_CATEGORY #
							<td>
								<a itemprop="about" href="{items.U_CATEGORY}">{items.CATEGORY_NAME}</a>
							</td>
							# ENDIF #
							<td>
								<time datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.PUBLICATION_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT items.C_DIFFERED #{items.DATE_RELATIVE}# ELSE #{items.PUBLICATION_START_DATE_RELATIVE}# ENDIF #</time>
							</td>
							# IF C_MODERATION #
								<td>
									# IF NOT items.C_SOLD #
										# IF items.C_EDIT #
											<a href="{items.U_EDIT_ITEM}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
										# ENDIF #
										# IF items.C_DELETE #
											<a href="{items.U_DELETE_ITEM}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
										# ENDIF #
									# ENDIF #
								</td>
							# ENDIF #
						</tr>
						# END items #
					</tbody>
				</table>

			# ELSE #

				<div class="list elements-container# IF C_SEVERAL_COLUMNS # columns-{COLUMNS_NUMBER}# ENDIF #">
					# START items #
						<article id="smallads-items-{items.ID}" class="list-item smallads-items several-items# IF items.C_SOLD# sold-smallad# ENDIF ## IF C_MOSAIC # block# ENDIF ## IF C_LIST # block-list# ENDIF ## IF items.C_NEW_CONTENT # new-content# ENDIF #" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
							# IF items.C_SOLD # <div class="sold-item"><span>{@smallads.sold.item}</span></div># ENDIF #
							<header>
								<h2>
									<span class="{items.SMALLAD_TYPE_FILTER}">{items.SMALLAD_TYPE}</span> - <a itemprop="url" href="{items.U_ITEM}"><span itemprop="name">{items.TITLE}</span></a>
									<span class="actions">
										# IF items.C_EDIT #
											<a href="{items.U_EDIT_ITEM}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
										# ENDIF #
										# IF items.C_DELETE #
											<a href="{items.U_DELETE_ITEM}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
										# ENDIF #
									</span>
								</h2>

								<div class="more">

									# IF items.C_DISPLAYED_AUTHOR #
										${LangLoader::get_message('by', 'common')}
										# IF items.C_CUSTOM_AUTHOR_NAME #
											{items.CUSTOM_AUTHOR_NAME}
										# ELSE #
											# IF items.C_AUTHOR_EXIST #<a itemprop="author" href="{items.U_AUTHOR}" class="{items.USER_LEVEL_CLASS}" # IF C_USER_GROUP_COLOR # style="color:{items.USER_GROUP_COLOR}"# ENDIF #>{items.PSEUDO}</a># ELSE #{items.PSEUDO}# ENDIF #,
										# ENDIF #
									# ENDIF #
									${LangLoader::get_message('the', 'common')} <time datetime="# IF NOT items.C_DIFFERED #{items.DATE_ISO8601}# ELSE #{items.PUBLICATION_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT items.C_DIFFERED #{items.DATE}# ELSE #{items.PUBLICATION_START_DATE}# ENDIF #</time>
									${TextHelper::lcfirst(LangLoader::get_message('in', 'common'))} <a itemprop="about" href="{items.U_CATEGORY}">{items.CATEGORY_NAME}</a>
								</div>

								<meta itemprop="url" content="{items.U_ITEM}">
								<meta itemprop="description" content="${escape(items.DESCRIPTION)}"/>
								<meta itemprop="discussionUrl" content="{items.U_COMMENTS}">
								<meta itemprop="interactionCount" content="{items.COMMENTS_NUMBER} UserComments">

							</header>

							# IF items.C_HAS_THUMBNAIL #
								<a href="{items.U_ITEM}" class="thumbnail-item">
									<img itemprop="thumbnailUrl" src="{items.THUMBNAIL}" alt="{items.TITLE}" />
								</a>
							# ELSE #
								<a href="{items.U_ITEM}" class="thumbnail-item">
									<img itemprop="thumbnailUrl" src="{PATH_TO_ROOT}/smallads/templates/images/no-thumb.png" alt="{items.TITLE}" />
								</a>
							# ENDIF #
							<div class="content">
								<div itemprop="text">{items.DESCRIPTION}# IF items.C_READ_MORE #... <a href="{items.U_ITEM}" class="read-more">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF #</div>
								# IF items.C_PRICE #<div class="smallad-price">{items.PRICE} {items.CURRENCY}</div># ENDIF #
							</div>

							# IF items.C_SOURCES #
							<div class="spacer"></div>
							<aside>
								<div id="smallads-sources-container">
									<span>${LangLoader::get_message('form.sources', 'common')}</span> :
									# START items.sources #
									<a itemprop="isBasedOnUrl" href="{items.sources.URL}" class="small">{items.sources.NAME}</a># IF items.sources.C_SEPARATOR #, # ENDIF #
									# END items.sources #
								</div>
							</aside>
							# ENDIF #

							<footer></footer>
						</article>
					# END items #
				</div>

			# ENDIF #
		# ENDIF #
	# ELSE #
		${LangLoader::get_message('smallads.no.type', 'common', 'smallads')}
	# ENDIF #
	<div class="spacer"></div>
	<footer># IF C_USAGE_TERMS # <i class="fa fa-book"></i> <a href="{U_USAGE_TERMS}">{@smallads.usage.terms}</a># ENDIF #</footer>
</section>

<script src="{PATH_TO_ROOT}/smallads/templates/js/jplist.core.min.js"></script>
<!-- Types filters radio -->
<script src="{PATH_TO_ROOT}/smallads/templates/js/jplist.filter-toggle-bundle.min.js"></script>
<!-- Pagination -->
<script src="{PATH_TO_ROOT}/smallads/templates/js/jplist.pagination-bundle.min.js"></script>

<script>
	jQuery('document').ready(function(){
		jQuery('#smallads-module').jplist({
			itemsBox: '.list',
			itemPath: '.list-item',
			panelPath: '.jplist-panel'
		});
	});
</script>
<script>
	jQuery('document').ready(function(){

		jQuery('#category-nav').append(CreatChild(0)).find('ul:first').remove();
		function CreatChild(id){
		    var $li = jQuery('li[parent_id=' + id + ']').sort(function(a, b){
				return jQuery(a).attr('c_order') - jQuery(b).attr('c_order');
			});
		    if($li.length > 0){
		        for(var i = 0; i < $li.length; i++){
		            var $this = $li.eq(i);
					$this[0].remove();
		            $this.append(CreatChild($this.attr('cat_id')));
		        }
		        return jQuery('<ul>').append($li);
		    }
		}

		jQuery('li:not([cat_id=0])').has('ul').addClass('has-sub');
	});
</script>
