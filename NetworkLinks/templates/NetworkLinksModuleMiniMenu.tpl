<nav id="cssmenu-network-links" class="cssmenu# IF C_VERTICAL # cssmenu-vertical# ELSE # cssmenu-horizontal# ENDIF ## IF C_MENU_LEFT# cssmenu-left# ENDIF ## IF C_MENU_RIGHT # cssmenu-right # ENDIF #">
	<ul class="network-links">
		# START links #
			<li>
				<a class="cssmenu-title" href="{links.LINK_URL}" title="{links.LINK_NAME}" # IF C_NEW_WINDOW #onclick="window.open(this.href); return false;"# ENDIF #>
					# IF links.C_FA_LINK #<i class="fa fa-{links.FA_LINK}"></i># ENDIF ## IF links.C_IMG_LINK #<img src="{PATH_TO_ROOT}/{links.IMG_LINK}" alt="{links.LINK_NAME}" /># ENDIF #<span class="link-name">{links.LINK_NAME}</span>
				</a>
			</li>
		# END links #
	</ul>
</nav>
<script>jQuery("#cssmenu-network-links").menumaker({ title: "{@nl.module.title}", format: "multitoggle", breakpoint: 768 }); </script>
