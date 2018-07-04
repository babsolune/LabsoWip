<section id="module-wiki">
	<header>
		<div class="pathname"></div>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			{@documents}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit small"></i></a># ENDIF #
		</h1>
	</header>
	<div class="elements-container">
		# INCLUDE NOT_VISIBLE_MESSAGE #
		<article itemscope="itemscope" itemtype="http://schema.org/Document" id="document-wiki-{ID}" class="document-wiki# IF C_NEW_CONTENT # new-content# ENDIF #">
			<header>
				<h2>
					<span itemprop="name">{TITLE}</span>
					<span class="actions">
						# IF C_EDIT #
							<a href="{U_EDIT_DOCUMENT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF C_DELETE #
							<a href="{U_DELETE_DOCUMENT}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
						# ENDIF #
						<a href="{U_PRINT_DOCUMENT}" title="${LangLoader::get_message('printable_version', 'main')}" target="blank"><i class="fa fa-print"></i></a>
						<a href="{U_HISTORY}" title="{@wiki.historic}"><i class="fa fa-clock"></i></a>
					</span>
				</h2>

				<div class="more">
					<i class="fa fa-calendar" title="${LangLoader::get_message('date', 'date-common')}"></i>&nbsp;<time datetime="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{PUBLISHING_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT C_DIFFERED #{DATE}# ELSE #{PUBLISHING_START_DATE}# ENDIF #</time>&nbsp;|
					# IF C_DATE_UPDATED #
						${LangLoader::get_message('form.date.update', 'common')} : <time datetime="{DATE_UPDATED_ISO8601}" itemprop="datePublished">{DATE_UPDATED}</time>
					# ENDIF #
					&nbsp;<i class="fa fa-eye" title="{NUMBER_VIEW} {@wiki.views.nb}"></i>&nbsp;<span title="{NUMBER_VIEW} {@wiki.views.nb}">{NUMBER_VIEW}</span>
				</div>

				<meta itemprop="url" content="{U_DOCUMENT}">
				<meta itemprop="description" content="${escape(DESCRIPTION)}">
				<meta itemprop="datePublished" content="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{PUBLISHING_START_DATE_ISO8601}# ENDIF #">
				<meta itemprop="discussionUrl" content="{U_COMMENTS}">
				# IF C_HAS_THUMBNAIL #<meta itemprop="thumbnailUrl" content="{THUMBNAIL}"># ENDIF #
				<meta itemprop="interactionCount" content="{NUMBER_COMMENTS} UserComments">
			</header>

			<div class="content">
				# IF C_PAGINATION #
					# INCLUDE PAGES_MENU #
					<div class="spacer"></div>
				# ENDIF #
				# IF PAGE_NAME #
					<h2 class="title page_name">{PAGE_NAME}</h2>
				# ENDIF #
					<div itemprop="text">{CONTENTS}</div>

				<hr />

				# IF C_PAGINATION #
					<div class="pages-pagination right">
						# IF C_NEXT_PAGE #
						<a href="{U_NEXT_PAGE}">{L_NEXT_TITLE} <i class="fa fa-arrow-right"></i></a>
						# ELSE #
						&nbsp;
						# ENDIF #
					</div>
					<div class="pages-pagination center"># INCLUDE PAGINATION_DOCUMENTS #</div>
					<div class="pages-pagination">
						# IF C_PREVIOUS_PAGE #
						<a href="{U_PREVIOUS_PAGE}"><i class="fa fa-arrow-left"></i> {L_PREVIOUS_TITLE}</a>
						# ENDIF #
					</div>
				# ENDIF #
				<div class="spacer"></div>
			</div>
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>

<script>
	jQuery(document).ready(function() {
		var url = window.location.pathname;
	    jQuery('a[href$="' + url + '"]').parent('li').addClass("current-link");
	    var firstPage = url.split('/').pop();
	    if(firstPage == '') {
		   jQuery('.form-field-action-link ul li:first').addClass("current-link");
	   }
	});

</script>
