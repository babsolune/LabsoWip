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
			# START staff #
				<li cat_id="{staff.ID}" parent_id="{staff.ID_PARENT}" c_order="{staff.SUB_ORDER}">
					<span class="toggle-menu-button-{staff.ID} expander "></span>
					<h2><a href="{staff.U_CATEGORY}">{staff.CATEGORY_NAME}</a></h2>
					<ul class="adherent-table-{staff.ID}">
						# START staff.items #
							<li>
								<div class="li-avatar">
									# IF C_AVATARS_ALLOWED #
										<img src="{staff.items.U_THUMBNAIL}" alt="{staff.items.FIRSTNAME} {staff.items.LASTNAME}" />
									# ENDIF #
								</div>
								<div class="li-infos">
									<div class="li-title">
										<div class="li-table li-leader"># IF staff.items.C_IS_GROUP_LEADER # <i class="fa fa-user" title="{@staff.form.group.leader}"></i># ENDIF #</div>
										<div class="li-table li-adherent"><a href="{staff.items.U_ITEM}" itemprop="name">{staff.items.FIRSTNAME} <span class="adherent-name">{staff.items.LASTNAME}</span></a></div>
									</div>
									<div class="li-options# IF C_MODERATE # moderator# ENDIF #">
										<div class="li-table li-role">{staff.items.ROLE}</div>
										# IF staff.items.C_ITEM_PHONE #
											<div class="li-table li-phone">
												<span class="show-phone">{staff.items.ITEM_PHONE}</span>
												<span class="hide-phone">{@reveal.adherent.phone}</span>
											</div>
										# ENDIF #
									</div>
									# IF C_MODERATE #
										<div class="moderate">
											<a href="{staff.items.U_EDIT}"><i class="fa fa-edit fa-fw" title="${LangLoader::get_message('edit', 'common')}"></i></a>
											<a href="{staff.items.U_DELETE}"><i class="fa fa-trash fa-fw" title="${LangLoader::get_message('delete', 'common')}"></i></a>
										</div>
									# ENDIF #
								</div>
							</li>
						# END staff.items #
					</ul>
				</li>

				<script>
					jQuery('.toggle-menu-button-{staff.ID}').click('slow', function(){
						jQuery('.toggle-menu-button-{staff.ID}').toggleClass('is-opened-list');
						jQuery(':not(.adherent-table-{staff.ID})').removeClass('show-table');
						jQuery('.adherent-table-{staff.ID}').toggleClass('show-table');
					});
				</script>
			# END staff #
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
				jQuery('[class*="adherent-table"]').toggleClass('show-table');
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
			jQuery('li:has("[class*=adherent-table]")').find('.expander').each(
				function(){
					jQuery(this).addClass('has-adherent');
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
