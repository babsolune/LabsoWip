# IF C_DOCUMENTS #
<script>
<!--
	var WikiDocuments = function(id){
		this.id = id;
		this.documents_number = {DOCUMENTS_NUMBER};
	};

	WikiDocuments.prototype = {
		init_sortable : function() {
			jQuery("ul#documents-list").sortable({
				handle: '.sortable-selector',
				placeholder: '<div class="dropzone">' + ${escapejs(LangLoader::get_message('position.drop_here', 'common'))} + '</div>'
			});
		},
		serialize_sortable : function() {
			jQuery('#tree').val(JSON.stringify(this.get_sortable_sequence()));
		},
		get_sortable_sequence : function() {
			var sequence = jQuery("ul#documents-list").sortable("serialize").get();
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

	var WikiDocument = function(id, wiki_documents){
		this.id = id;
		this.WikiDocuments = wiki_documents;

		if (WikiDocuments.documents_number > 1)
			WikiDocuments.change_reposition_pictures();
	};

	var WikiDocuments = new WikiDocuments('documents-list');
	jQuery(document).ready(function() {
		WikiDocuments.init_sortable();
		jQuery('li.sortable-element').on('mouseout',function(){
			WikiDocuments.change_reposition_pictures();
		});
	});
-->
</script>
# ENDIF #
# INCLUDE MSG #
<section id="module-wiki">
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

	# IF C_DOCUMENTS #
		<div class="content elements-container">
			<form action="{REWRITED_SCRIPT}" method="post" id="position-update-form" onsubmit="WikiDocuments.serialize_sortable();" class="wiki-reorder-form">
				<fieldset id="documents-management">
					<ul id="documents-list" class="sortable-block">
						# START documents #
						<li class="sortable-element# IF documents.C_NEW_CONTENT # new-content# ENDIF #" id="list-{documents.ID}" data-id="{documents.ID}">
							<div class="sortable-selector" title="${LangLoader::get_message('position.move', 'common')}"></div>
							<div class="sortable-title">
								<span class="document-title">{documents.TITLE}</span>
							</div>
							<div class="sortable-actions">
								# IF C_MORE_THAN_ONE_DOCUMENT #
								<a href="" title="${LangLoader::get_message('position.move_up', 'common')}" id="move-up-{documents.ID}" onclick="return false;"><i class="fa fa-arrow-up fa-fw"></i></a>
								<a href="" title="${LangLoader::get_message('position.move_down', 'common')}" id="move-down-{documents.ID}" onclick="return false;"><i class="fa fa-arrow-down fa-fw"></i></a>
								# ENDIF #
							</div>

							<script>
							<!--
							jQuery(document).ready(function() {
								var wiki_documents = new WikiDocument({documents.ID}, WikiDocuments);

								if (WikiDocuments.documents_number > 1) {
									jQuery('#move-up-{documents.ID}').on('click',function(){
										var li = jQuery(this).closest('li');
										li.insertBefore( li.prev() );
										WikiDocuments.change_reposition_pictures();
									});
									jQuery('#move-down-{documents.ID}').on('click',function(){
										var li = jQuery(this).closest('li');
										li.insertAfter( li.next() );
										WikiDocuments.change_reposition_pictures();
									});
								}
							});
							-->
							</script>
						</li>
						# END documents #
					</ul>
				</fieldset>
				# IF C_MORE_THAN_ONE_DOCUMENT #
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
		<div id="no-item-message"# IF C_DOCUMENTS # style="display: none;"# ENDIF #>
			<div class="center">
				${LangLoader::get_message('no_item_now', 'common')}
			</div>
		</div>
	# ENDIF #

	<footer></footer>
</section>
