<section id="module-staff">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('staff', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			{@staff.module.title}
		</h1>
	</header>
	# IF C_ROOT_DESCRIPTION #
		<div class="root-desc">
			{ROOT_DESCRIPTION}
		</div>
	# ENDIF #

	<div class="right">
		<span class="expand-all expand">{@expand.all}</span><span class="expand-all">{@close.all}</span>
	</div>

	<nav id="category-list">
		<ul class="ul-table">
			# START staffcats #
				<li cat_id="{staffcats.ID}" parent_id="{staffcats.ID_PARENT}" c_order="{staffcats.SUB_ORDER}">
					<span class="toggle-menu-button-{staffcats.ID} expander "></span>
					<h2><a href="{staffcats.U_CATEGORY}">{staffcats.CATEGORY_NAME}</a></h2>
					<ul class="member-table-{staffcats.ID}">
						# START staffcats.members #
							<li>
								<div class="li-avatar">
									# IF C_AVATARS_ALLOWED #
										<img src="{staffcats.members.U_PICTURE}" alt="{staffcats.members.FIRSTNAME} {staffcats.members.LASTNAME}" />
									# ENDIF #
								</div>
								<div class="li-infos">
									<div class="li-title">
										<div class="li-table li-leader"># IF staffcats.members.C_IS_GROUP_LEADER # <i class="fa fa-user" title="{@staff.form.group.leader}"></i># ENDIF #</div>
										<div class="li-table li-member"><a href="{staffcats.members.U_MEMBER}" itemprop="name">{staffcats.members.FIRSTNAME} <span class="member-name">{staffcats.members.LASTNAME}</span></a></div>
									</div>
									<div class="li-options# IF C_MODERATE # moderator# ENDIF #">
										<div class="li-table li-role">{staffcats.members.ROLE}</div>
										# IF staffcats.members.C_MEMBER_PHONE #
											<div class="li-table li-phone">
												<span class="show-phone">{staffcats.members.MEMBER_PHONE}</span>
												<span class="hide-phone">{@reveal.member.phone}</span>
											</div>
										# ENDIF #
									</div>
									# IF C_MODERATE #
										<div class="moderate">
											<a href="{staffcats.members.U_EDIT}"><i class="fa fa-edit fa-fw" title="${LangLoader::get_message('edit', 'common')}"></i></a>
											<a href="{staffcats.members.U_DELETE}"><i class="fa fa-trash fa-fw" title="${LangLoader::get_message('delete', 'common')}"></i></a>
										</div>
									# ENDIF #
								</div>
							</li>
						# END staffcats.members #
					</ul>
				</li>

				<script>
					jQuery('.toggle-menu-button-{staffcats.ID}').click('slow', function(){
						jQuery('.toggle-menu-button-{staffcats.ID}').toggleClass('is-opened-list');
						jQuery(':not(.member-table-{staffcats.ID})').removeClass('show-table');
						jQuery('.member-table-{staffcats.ID}').toggleClass('show-table');
					});
				</script>
			# END staffcats #
		</ul>
	</nav>

	<footer></footer>
</section>

	<script>
		jQuery(document).ready(function () {

			// expand all categories
			jQuery('.expand-all').click('slow', function(){
				jQuery('.expand-all').toggleClass('expand');
				jQuery('[class*="toggle-menu-button"]').toggleClass('is-opened-list');
				jQuery('[class*="member-table"]').toggleClass('show-table');
			});

			// Sort order categories
			jQuery('#category-list').append(CreatChild(0)).find('ul:first').remove();
			function CreatChild(id){
			    var $li = jQuery('li[parent_id=' + id + ']').sort(function(a, b){
					return jQuery(a).attr('c_order') - jQuery(b).attr('c_order');
				});
			    if($li.length > 0){
			        for(var i = 0; i < $li.length; i++){
			            var $this = $li.eq(i);
						// $this[0].remove();
			            $this.append(CreatChild($this.attr('cat_id')));
			        }
			        return jQuery('<ul class="ul-table">').append($li);
			    }
			}

			// create indent
			jQuery('li').has('ul.ul-table').addClass('has-sub-cat');

			// add expander
			jQuery('li:has("[class*=member-table]")').find('.expander').each(
				function(){
					jQuery(this).addClass('has-member');
				}
			);

			//reveal phone nb
			jQuery('.show-phone').hide();
			jQuery('.hide-phone').show();
			jQuery('.li-phone').click(function(){
				jQuery(this).toggleClass('is-revealed');
				jQuery('.show-phone').toggle();
				jQuery('.hide-phone').toggle();
			});
		});
</script>
