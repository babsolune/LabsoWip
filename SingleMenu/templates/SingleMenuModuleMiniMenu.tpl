# IF C_VERTICAL #
	<div class="module-mini-container cssmenu-content">
		<div class="module-mini-top hidden-small-screens">
			<div class="sub-title">
				# IF IS_ADMIN #
					<a href="${relative_url(SingleMenuUrlBuilder::configuration())}" class="sgm-admin"><i class="fa fa-cog"></i></a>
				# ENDIF #
				{MENU_TITLE}
			</div>
		</div>
		<div class="module-mini-contents">
# ENDIF #
			# IF NOT C_VERTICAL #
				# IF IS_ADMIN #
					<a href="${relative_url(SingleMenuUrlBuilder::configuration())}" class="sgm-admin"><i class="fa fa-cog"></i></a>
				# ENDIF #
			# ENDIF #
			<nav id="cssmenu-single-menu" class="cssmenu# IF C_VERTICAL # cssmenu-vertical# ELSE # cssmenu-horizontal# ENDIF ## IF C_MENU_LEFT# cssmenu-left# ENDIF ## IF C_MENU_RIGHT # cssmenu-right # ENDIF #">
				<ul class="single-menu">
					# START links #
						<li>
							<a
								class="cssmenu-title"
								href="{links.LINK_URL}"
								# IF NOT links.C_LINK_NAME # aria-label="{links.LINK_NAME}"# ENDIF #
								# IF C_OPEN_NEW_WINDOW #
									# IF links.C_EXTERNAL_LINK #
										target="_blank" rel="noreferrer noopener"
									# ENDIF #
								# ENDIF #>
								# IF links.C_FA_LINK #<i class="fa fa-{links.FA_LINK}"></i># ENDIF #
								# IF links.C_IMG_LINK #<img src="{links.IMG_LINK}" alt="{links.LINK_NAME}" /># ENDIF #
								# IF links.C_LINK_NAME #<span class="link-name">{links.LINK_NAME}</span># ENDIF #
							</a>
						</li>
					# END links #
				</ul>
			</nav>
# IF C_VERTICAL #
		</div>
		<div class="module-mini-bottom"></div>
	</div>
# ENDIF #
<script>jQuery("#cssmenu-single-menu").menumaker({ title: "{MENU_TITLE}", format: "multitoggle", breakpoint: 768 }); </script>
