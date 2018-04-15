<section id="module-sponsors">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('sponsors', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #{@sponsors.pending}# ELSE #{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
		</h1>

		# IF C_CATEGORY_DESCRIPTION #
		<div class="cat-description">
			{CATEGORY_DESCRIPTION}
		</div>
		# ENDIF #

	</header>

	# IF C_SUB_CATEGORIES #
	<div class="subcat-container elements-container# IF C_SEVERAL_CATS_COLUMNS # columns-{NUMBER_CATS_COLUMNS}# ENDIF #">
		# START sub_categories_list #
		<div class="subcat-element block">
			<div class="subcat-content">
				# IF sub_categories_list.C_CATEGORY_IMAGE #<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" /></a># ENDIF #
				<br />
				<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
				<br />
				<span class="small">{sub_categories_list.PARTNERS_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_PARTNER #${TextHelper::lcfirst(LangLoader::get_message('partners', 'common', 'sponsors'))}# ELSE #${TextHelper::lcfirst(LangLoader::get_message('partner', 'common', 'sponsors'))}# ENDIF #</span>
			</div>
		</div>
		# END sub_categories_list #
		<div class="spacer"></div>
	</div>
	# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
	# ELSE #
		# IF NOT C_CATEGORY_DISPLAYED_TABLE #<div class="spacer"></div># ENDIF #
	# ENDIF #


	# IF C_PARTNERS #
	<div class="content">
		# IF C_CATEGORY_DISPLAYED_TABLE #
		<table id="table">
			<thead>
				<tr>
					<th>${LangLoader::get_message('form.name', 'common')}</th>
					<th class="col-small">${LangLoader::get_message('form.keywords', 'common')}</th>
					<th class="col-small">{@visits.number}</th>
					# IF C_MODERATE #<th class="col-smaller"></th># ENDIF #
				</tr>
			</thead>
			<tbody>
				# START partners #
				<tr>
					<td>
						<a href="{partners.U_LINK}" itemprop="name"# IF partners.C_NEW_CONTENT # class="new-content"# ENDIF#>{partners.NAME}</a>
					</td>
					<td>
						# IF partners.C_KEYWORDS #
							# START partners.keywords #
								<a itemprop="keywords" href="{partners.keywords.URL}">{partners.keywords.NAME}</a># IF partners.keywords.C_SEPARATOR #, # ENDIF #
							# END partners.keywords #
						# ELSE #
							${LangLoader::get_message('none', 'common')}
						# ENDIF #
					</td>
					<td>
						{partners.NUMBER_VIEWS}
					</td>
					# IF C_MODERATE #
					<td>
						# IF partners.C_EDIT #
						<a href="{partners.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF partners.C_DELETE #
						<a href="{partners.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
						# ENDIF #
					</td>
					# ENDIF #
				</tr>
				# END partners #
			</tbody>
		</table>
		# ENDIF #
		# IF C_CATEGORY_DISPLAYED_BLOCK #
		<div id="portfolio" class="work">
			<div class="container">
				<div class="row portfolio-controllers-container">
					<div class="portfolio-controllers wow fadeLeft" data-wow-duration="1s" data-wow-delay=".1s">
						<button type="button" class="submit active-work" data-filter="all">${TextHelper::ucfirst(@activity.all)}</button>
						<button type="button" class="submit" data-filter=".{@activity.1}">${TextHelper::ucfirst(@activity.1)}</button>
						<button type="button" class="submit" data-filter=".{@activity.2}">${TextHelper::ucfirst(@activity.2)}</button>
						<button type="button" class="submit" data-filter=".{@activity.3}">${TextHelper::ucfirst(@activity.3)}</button>
						<button type="button" class="submit" data-filter=".{@activity.4}">${TextHelper::ucfirst(@activity.4)}</button>
						<button type="button" class="submit" data-filter=".{@activity.5}">${TextHelper::ucfirst(@activity.5)}</button>
						<button type="button" class="submit" data-filter=".{@activity.6}">${TextHelper::ucfirst(@activity.6)}</button>
						<button type="button" class="submit" data-filter=".{@activity.7}">${TextHelper::ucfirst(@activity.7)}</button>
						<button type="button" class="submit" data-filter=".{@activity.8}">${TextHelper::ucfirst(@activity.8)}</button>
					</div>
				</div>
			</div>
			<div class="elements-container columns-{NUMBER_COLUMNS} partner-list">
				# START partners #
					<div class="block portfolio {partners.L_ACTIVITY}">
						<figure class="portfolio-image">
							<img src="{partners.U_PARTNER_PICTURE}" alt="{partners.NAME}" class="img-responsive">
							<figcaption class="caption">
								<div class="caption-content">
									<h3 class="portfolio-item-title sub-title">{partners.NAME}</h3>
									<ul class="portfolio-link">
										<li><a href="{partners.U_VISIT}" target="_blank" rel="noopener nofollow noreferrer">{@visit}</a></li>
										<li><a href="{partners.U_LINK}">{@details}</a></li>
										<li>
											# IF partners.C_EDIT #<a href="{partners.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a># ENDIF #
											# IF partners.C_DELETE #<a href="{partners.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a># ENDIF #
										</li>
									</ul>
								</div>
							</figcaption>
						</figure>
					</div>
				# END partners #
			</div>
		# ENDIF #
		</div>
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

	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
<script src="{PATH_TO_ROOT}/sponsors/templates/js/activity-filter.js"></script>
<script>
	$(document).ready(function(){
		$('.partner-list').filterData({
			aspectRatio: '1:1',
			filterController : '.submit',
			responsive : [
				{
					breakpoint : 1200,
					containerWidth : 992,
					settings : {
						nOfRow : 12,
						nOfColumn : 4
					}
				},
				{
					breakpoint : 992,
					containerWidth : 768,
					settings : {
						nOfRow : 18,
						nOfColumn : 2
					}
				},
				{
					breakpoint : 768,
					containerWidth : 480,
					settings : {
						nOfRow : 35,
						nOfColumn : 1
					}
				},
				{
					breakpoint : 480,
					containerWidth : 320,
					settings : {
						nOfRow : 35,
						nOfColumn : 1
					}
				}
			]
	});
		$('.portfolio-controllers button').on('click',function(){
			$('.portfolio-controllers button').removeClass('active-work');
			$(this).addClass('active-work');
		});
	});
</script>
