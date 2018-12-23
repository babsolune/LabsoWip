# IF C_NEWS #
	# IF C_CAT #
		# IF C_MENU_VERTICAL #
			<div id="{MODULE_ID}" class="module-mini-container cssmenu-content">
				<div class="module-mini-top">
					<div class="sub-title">{MODULE_TITLE}</div>
				</div>
				<div class="module-mini-contents">
		# ENDIF #
					<nav id="newscat-list" class="cssmenu # IF C_MENU_VERTICAL #cssmenu-vertical# ELSE #cssmenu-horizontal# ENDIF ## IF C_MENU_LEFT # cssmenu-left# ENDIF ## IF C_MENU_RIGHT # cssmenu-right# ENDIF #">
						<ul>
							# START items #
								<li data-nc-id="{items.ID}" data-nc-parent-id="{items.ID_PARENT}" data-nc-c-order="{items.SUB_ORDER}" class="">
									<a href="{items.U_CATEGORY}" class="cssmenu-title">{items.CATEGORY_NAME}</a>
								</li>
							# END items #
						</ul>
					</nav>
		# IF C_MENU_VERTICAL #
				</div>
			</div>
		# ENDIF #

		<script>
			jQuery(document).ready(function () {
				// Sort order categories
				jQuery('#newscat-list').append(CreatChild(0)).find('ul:first').remove();
				function CreatChild(id){
				    var $li = jQuery('li[data-nc-parent-id=' + id + ']').sort(function(a, b){
						return jQuery(a).attr('data-nc-c-order') - jQuery(b).attr('data-nc-c-order');
					});
				    if($li.length > 0){
				        for(var i = 0; i < $li.length; i++){
				            var $this = $li.eq(i);
							// $this[0].remove();
				            $this.append(CreatChild($this.attr('data-nc-id')));
				        }
				        return jQuery('<ul class="newscat-ul">').append($li);
				    }
				}
				// Add sub-menu icon
				jQuery('li').has('ul.newscat-ul').addClass('has-sub');
			});
		</script>
		<script>jQuery("#newscat-list").menumaker({ title: "{MODULE_TITLE}", format: "multitoggle", breakpoint: 768}); </script>
	# ELSE #
		{@no.news.cat}
	# ENDIF #
# ELSE #
	{@no.news.module}
# ENDIF #
