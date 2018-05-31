<section id="sponsors-module">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('sponsors', id_category))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #{@sponsors.pending.level_links.items}# ELSE #{@sponsors.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
		</h1>
	</header>
	<div class="membership"><a href="{U_MEMBERSHIP}" class="membership-link">{@sponsors.membership}</a></div>
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
<!-- Categories -->
	# IF C_CATEGORY #
		<div class="category-select block">
			<h6><p>{@sponsors.category.select} :</p></h6>
			<div class="category-selected">{CATEGORY_NAME} <i class="fa fa-fw fa-caret-down"></i></div>
			<nav id="category-nav" class="cssmenu cssmenu-static bg-container">
				<ul>
					<li cat_id="0" parent_id="0" c_order="0">
						<a class="cssmenu-title" href="{PATH_TO_ROOT}/sponsors">{@sponsors.all.types.filters}</a>
					</li>
					# START categories #
						<li cat_id="{categories.ID}" parent_id="{categories.ID_PARENT}" c_order="{categories.SUB_ORDER}">
							<a class="cssmenu-title" href="{categories.U_CATEGORY}">{categories.NAME}</a>
						</li>
					# END categories #
				</ul>
			</nav>
			<script>jQuery("#category-nav").menumaker({ title: "{@sponsors.select.category}", format: "multitoggle", breakpoint: 768 }); </script>
		</div>
	# ENDIF #

	<div id="pbt-tabs-container" class="pbt-tab-container">
		<ul class="pbt-etabs">
			# START level_links #
				<li class="pbt-tab" style="width: calc(100% / {level_links.WIDTH}) ">
					<a href="\#{level_links.TARGET}">{level_links.NAME}</a> <span>{level_links.ITEM_ROWS}</span>
				</li>
			# END level_links #
		</ul>
		# START level_links #
			<div id="{level_links.TARGET}" class="pbt-items-container">
				# IF level_links.C_NO_ITEM_AVAILABLE #
					# IF NOT C_HIDE_NO_ITEM_MESSAGE #
						<div class="center">
							${LangLoader::get_message('no_item_now', 'common')}
						</div>
					# ENDIF #
				# ELSE #
					{ROWS_COUNT}
					<div class="list elements-container columns-{ITEMS_PER_LINE} no-style">
						# START level_links.items #
							<article id="sponsors-items-{level_links.items.ID}"
								class="list-item block sponsors-items several-items # IF level_links.items.C_NEW_CONTENT # new-content# ENDIF #"
								style="background-image: url(# IF level_links.items.C_HAS_THUMBNAIL #{level_links.items.U_THUMBNAIL}# ELSE #{PATH_TO_ROOT}/sponsors/templates/images/no-thumb.png# ENDIF #);"
								itemscope="itemscope"
								itemtype="http://schema.org/CreativeWork">
								<header>
									<h2>
										# IF level_links.items.C_HAS_WEBSITE #
											<a href="{level_links.items.U_WEBSITE}" # IF level_links.items.C_NEW_WINDOW #target="_blank"# ENDIF # rel="noopener noreferrer nofollow">{level_links.items.TITLE}</a>
										# ELSE #
											<a href="{level_links.items.U_ITEM}">{level_links.items.TITLE}</a>
										# ENDIF #
									</h2>
									<span class="actions">
										# IF level_links.items.C_EDIT #
											<a href="{level_links.items.U_EDIT_ITEM}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
										# ENDIF #
										# IF level_links.items.C_DELETE #
											<a href="{level_links.items.U_DELETE_ITEM}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
										# ENDIF #
									</span>

									<meta itemprop="url" content="{level_links.items.U_ITEM}">
									<meta itemprop="description" content="${escape(level_links.items.DESCRIPTION)}"/>

								</header>
								<footer></footer>
							</article>
							<script>
								setInterval(function() {
									jQuery('#sponsors-items-{level_links.items.ID}').height(jQuery('#sponsors-items-{level_links.items.ID}').width());
								}, 1);
							</script>
						# END level_links.items #
					</div>
				# ENDIF #
			</div>
		# END level_links #
	</div>

	<div class="spacer"></div>
	<footer></footer>
</section>

<script src="{PATH_TO_ROOT}/sponsors/templates/js/hashchange.js"></script>
<script src="{PATH_TO_ROOT}/sponsors/templates/js/easytabs.js"></script>
<script>
	$('#pbt-tabs-container').easytabs();
</script>

<script>
	jQuery('document').ready(function(){

		// Categories
			// build order
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

			// build cssmenu
		jQuery('li:not([cat_id=0])').has('ul').addClass('has-sub');

			// change root name
		jQuery('.category-selected:contains("${LangLoader::get_message('root', 'main')}")').html('{@sponsors.category.all} <i class="fa fa-fw fa-caret-down"></i>');

			// toggle sub-menu (close on click outside)
		jQuery('.category-selected').click(function(e){
			jQuery('.category-select').toggleClass('reveal-subcat');
    		e.stopPropagation();
		});
		jQuery(document).click(function(e) {
		    if (jQuery(e.target).is('.category-selected') === false) {
		      jQuery('.category-select').removeClass('reveal-subcat');
		    }
		});
	});

</script>
