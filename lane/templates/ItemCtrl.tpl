<pre hidden>
	@copyright   &copy; 2005-2019 PHPBoost https://www.phpboost.com
	@license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
	@author      Benoit SAUTEL [ben.popeye@phpboost.com]
	@version     PHPBoost 5.2 - last update: 2018 12 24
	@since       PHPBoost 2.0 - 2007 10 14
	@contributor Julien BRISWALTER [j1.seth@phpboost.com]
	@contributor Arnaud GENET [elenwii@phpboost.com]
	@contributor mipel [mipel@phpboost.com]
	@contributor Sebastien LARTIGUE [babsolune@phpboost.com]
</pre>

<section id="wiki-item">
	<header>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			{@items}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit small"></i></a># ENDIF #
		</h1>
	</header>
	<div class="elements-container">
		# INCLUDE NOT_VISIBLE_MESSAGE #
		<article itemscope="itemscope" itemtype="http://schema.org/Document" id="single-item-{ID}" class="single-item# IF C_NEW_CONTENT # new-content# ENDIF #">
			<header>
				<h2>
					<span itemprop="name">{TITLE}</span>
					<span class="actions">
						# IF C_EDIT #
							<a href="{U_EDIT_ITEM}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
							<a href="{U_HISTORY}" title="{@item.historic}"><i class="fa fa-clock"></i></a>
						# ENDIF #
						# IF IS_USER_CONNECTED #
							# IF ITEM_IS_FAVORITE #
							# ELSE #
								<a href="{U_FAVORITE}" aria-label="{@add.favorite.items}"><i class="fa fa-heart"></i> <span class="sr-only">{@add.favorite.items}</span></a>
							# ENDIF
						# ENDIF #
						<a href="{U_PRINT_ITEM}" title="${LangLoader::get_message('printable_version', 'main')}" target="blank"><i class="fa fa-print"></i></a>
						# IF C_DELETE #
							<a href="{U_DELETE_ITEM}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
						# ENDIF #
					</span>
				</h2>

				<div class="more">
					<i class="fa fa-calendar" title="${LangLoader::get_message('date', 'date-common')}"></i>&nbsp;<time datetime="# IF NOT C_DIFFERED #{DATE_ISO8601}# ELSE #{PUBLISHING_START_DATE_ISO8601}# ENDIF #" itemprop="datePublished"># IF NOT C_DIFFERED #{DATE}# ELSE #{PUBLISHING_START_DATE}# ENDIF #</time>&nbsp;|
					# IF C_DATE_UPDATED #
						${LangLoader::get_message('form.date.update', 'common')} : <time datetime="{DATE_UPDATED_ISO8601}" itemprop="datePublished">{DATE_UPDATED}</time>
					# ENDIF #
					&nbsp;<i class="fa fa-eye" title="{NUMBER_VIEW} {@item.views.nb}"></i>&nbsp;<span title="{NUMBER_VIEW} {@item.views.nb}">{NUMBER_VIEW}</span>
				</div>

				<meta itemprop="url" content="{U_ITEM}">
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

				# IF C_KEYWORDS #
					<span class="infos-options">
						<span class="text-strong">${LangLoader::get_message('form.keywords', 'common')} : </span>
						# START keywords #
							<a itemprop="keywords" class="small" href="{keywords.URL}">{keywords.NAME}</a># IF keywords.C_SEPARATOR #, # ENDIF #
						# END keywords #
					</span>
				# ENDIF #
				<hr />

				# IF C_PAGINATION #
					<div class="pages-pagination right">
						# IF C_NEXT_PAGE #
						<a href="{U_NEXT_PAGE}">{L_NEXT_TITLE} <i class="fa fa-arrow-right"></i></a>
						# ELSE #
						&nbsp;
						# ENDIF #
					</div>
					<div class="pages-pagination center"># INCLUDE PAGINATION_ITEMS #</div>
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
