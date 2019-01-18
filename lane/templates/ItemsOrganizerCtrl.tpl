# IF C_ITEMS #
<script>
<!--
	var ModItems = function(id){
		this.id = id;
		this.items_number = {ITEMS_NUMBER};
	};

	ModItems.prototype = {
		init_sortable : function() {
			jQuery("ul#items-list").sortable({
				handle: '.sortable-selector',
				placeholder: '<div class="dropzone">' + ${escapejs(LangLoader::get_message('position.drop_here', 'common'))} + '</div>'
			});
		},
		serialize_sortable : function() {
			jQuery('#tree').val(JSON.stringify(this.get_sortable_sequence()));
		},
		get_sortable_sequence : function() {
			var sequence = jQuery("ul#items-list").sortable("serialize").get();
			return sequence[0];
		},
		change_reposition_pictures : function() {
			sequence = this.get_sortable_sequence();
			var length = sequence.length;
			for(var i = 0; i < length; i++)
			{
				if (jQuery('#list-' + sequence[i].id).is(':first-child'))
					jQuery("#move-up-" + sequence[i].id).hide();
				else
					jQuery("#move-up-" + sequence[i].id).show();

				if (jQuery('#list-' + sequence[i].id).is(':last-child'))
					jQuery("#move-down-" + sequence[i].id).hide();
				else
					jQuery("#move-down-" + sequence[i].id).show();
			}
		}
	};

	var ModItem = function(id, mod_items){
		this.id = id;
		this.ModItems = mod_items;

		if (ModItems.items_number > 1)
			ModItems.change_reposition_pictures();
	};

	var ModItems = new ModItems('items-list');
	jQuery(document).ready(function() {
		ModItems.init_sortable();
		jQuery('li.sortable-element').on('mouseout',function(){
			ModItems.change_reposition_pictures();
		});
	});
-->
</script>
# ENDIF #
# INCLUDE MSG #
<section id="wiki-organizer">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('wiki', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			{@module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit small"></i></a># ENDIF #
		</h1>
		# IF C_CATEGORY_DESCRIPTION #
			<div class="cat-description">
				{CATEGORY_DESCRIPTION}
			</div>
		# ENDIF #
	</header>

	# IF C_ITEMS #
		<div class="content elements-container">
			<form action="{REWRITED_SCRIPT}" method="post" id="position-update-form" onsubmit="ModItems.serialize_sortable();" class="organizerr-form">
				<fieldset id="items-management">
					<ul id="items-list" class="sortable-block">
						# START items #
						<li class="sortable-element# IF items.C_NEW_CONTENT # new-content# ENDIF #" id="list-{items.ID}" data-id="{items.ID}">
							<div class="sortable-selector" title="${LangLoader::get_message('position.move', 'common')}"></div>
							<div class="sortable-title">
								<span class="item-title">{items.TITLE}</span>
							</div>
							<div class="sortable-actions">
								# IF C_MORE_THAN_ONE_ITEM #
								<a href="" title="${LangLoader::get_message('position.move_up', 'common')}" id="move-up-{items.ID}" onclick="return false;"><i class="fa fa-arrow-up fa-fw"></i></a>
								<a href="" title="${LangLoader::get_message('position.move_down', 'common')}" id="move-down-{items.ID}" onclick="return false;"><i class="fa fa-arrow-down fa-fw"></i></a>
								# ENDIF #
							</div>

							<script>
							<!--
							jQuery(document).ready(function() {
								var mod_items = new ModItem({items.ID}, ModItems);

								if (ModItems.items_number > 1) {
									jQuery('#move-up-{items.ID}').on('click',function(){
										var li = jQuery(this).closest('li');
										li.insertBefore( li.prev() );
										ModItems.change_reposition_pictures();
									});
									jQuery('#move-down-{items.ID}').on('click',function(){
										var li = jQuery(this).closest('li');
										li.insertAfter( li.next() );
										ModItems.change_reposition_pictures();
									});
								}
							});
							-->
							</script>
						</li>
						# END items #
					</ul>
				</fieldset>
				# IF C_MORE_THAN_ONE_ITEM #
				<fieldset class="fieldset-submit" id="position-update-button">
					<button type="submit" name="submit" value="true" class="submit">${LangLoader::get_message('position.update', 'common')}</button>
					<input type="hidden" name="token" value="{TOKEN}">
					<input type="hidden" name="tree" id="tree" value="">
				</fieldset>
				# ENDIF #
			</form>
		</div>
	# ENDIF #
	# IF NOT C_HIDE_NO_ITEM_MESSAGE #
		<div id="no-item-message"# IF C_ITEMS # style="display: none;"# ENDIF #>
			<div class="center">
				${LangLoader::get_message('no_item_now', 'common')}
			</div>
		</div>
	# ENDIF #

	<footer></footer>
</section>
