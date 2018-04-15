<section id="module-staff">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('staff', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			{@module_title}
		</h1>
	</header>

	# IF C_DISPLAYED_TABLE #
		<div class="right">
			<span class="expand-all expand">{@expand.all}</span><span class="expand-all">{@close.all}</span>
		</div>
		<ul class="ul-table">
			# START staffcats #
			<li class="toggle-menu-button-{staffcats.CAT_ID}">
				# IF staffcats.C_ROOT_COMMISSION #
					<h2><a href="{staffcats.U_CATEGORY}">{staffcats.CATEGORY_NAME}</a></h2>
				# ELSE #
					# IF staffcats.C_SUB_COMMISSION #
						<h6><a href="{staffcats.U_CATEGORY}">{staffcats.CATEGORY_NAME}</a></h6>
					# ELSE #
						<h4><a href="{staffcats.U_CATEGORY}">{staffcats.CATEGORY_NAME}</a></h4>
					# ENDIF #
				# ENDIF #
				<ul class="member-table-{staffcats.CAT_ID}">
					# START staffcats.members #
						<li>
							<span class="li-table li-leader"># IF staffcats.members.C_IS_GROUP_LEADER # <i class="fa fa-user" title="{@staff.form.group.leader}"></i># ENDIF #</span>
							<span class="li-table li-member"><a href="{staffcats.members.U_MEMBER}" itemprop="name">{staffcats.members.FIRSTNAME} <span class="member-name">{staffcats.members.LASTNAME}</span></a></span>
							<span class="li-table li-options hidden-small-screens">{staffcats.members.ROLE}</span>
							<span class="li-table li-options hidden-small-screens">{staffcats.members.MEMBER_PHONE}</span>
						</li>
					# END staffcats.members #
				</ul>
			</li>

			<script>
			<!--
				jQuery(document).ready(function () {
					jQuery('.toggle-menu-button-{staffcats.CAT_ID}').click('slow', function(){
						jQuery('.toggle-menu-button-{staffcats.CAT_ID}').toggleClass('is-open-menu');
						jQuery(':not(.member-table-{staffcats.CAT_ID})').removeClass('show-table');
						jQuery('.member-table-{staffcats.CAT_ID}').toggleClass('show-table');
					});
				});
			-->
			</script>
			# END staffcats #
		</ul>
	# ELSE #
		<div class="elements-container columns-{COLUMNS_NUMBER}">
			# START staffcats #
				<article class="block">
					<header>
						<h2><a href="{staffcats.U_CATEGORY}">{staffcats.CATEGORY_NAME}</a></h2>
					</header>
					<div class="content">
						<ul class="member-list">
							# START staffcats.members #
								<li>
									<a href="{staffcats.members.U_MEMBER}" itemprop="name">{staffcats.members.FIRSTNAME} <span class="member-name">{staffcats.members.LASTNAME}</span></a>
								</li>
							# END staffcats.members #
						</ul>
					</div>
				</article>
			# END staffcats #
		</div>
	# ENDIF #

	<footer></footer>
</section>

# IF C_DISPLAYED_TABLE #
	<script>
	<!--
		jQuery(document).ready(function () {
			jQuery('.expand-all').click('slow', function(){
				jQuery('.expand-all').toggleClass('expand');
				jQuery('[class*="toggle-menu-button"]').toggleClass('is-open-menu');
				jQuery('[class*="member-table"]').toggleClass('show-table');
			});
		});
	-->
	</script>
# ENDIF #
