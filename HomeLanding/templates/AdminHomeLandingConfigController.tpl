# INCLUDE MSG #
<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	<div id="pbt-tab-container" class="pbt-tab-container">
		<ul class="pbt-etabs">
			<li class="pbt-tab"><a href="#config">Config Générale</a></li>
			<li class="pbt-tab"><a href="#slider">Carrousel</a></li>
			# IF C_ARTICLES #<li class="pbt-tab"><a href="#articles">Articles</a></li># ENDIF #
			# IF C_CALENDAR #<li class="pbt-tab"><a href="#calendar">Calendar</a></li># ENDIF #
			# IF C_CONTACT #<li class="pbt-tab"><a href="#contact">Contact</a></li># ENDIF #
			# IF C_DOWNLOAD #<li class="pbt-tab"><a href="#download">Download</a></li># ENDIF #
			# IF C_FORUM #<li class="pbt-tab"><a href="#forum">Forum</a></li># ENDIF #
			# IF C_GALLERY #<li class="pbt-tab"><a href="#gallery">Gallery</a></li># ENDIF #
			# IF C_GUESTBOOK #<li class="pbt-tab"><a href="#guestbook">Guestbook</a></li># ENDIF #
			# IF C_MEDIA #<li class="pbt-tab"><a href="#media">Media</a></li># ENDIF #
			# IF C_NEWS #<li class="pbt-tab"><a href="#news">News</a></li># ENDIF #
			# IF C_WEB #<li class="pbt-tab"><a href="#web">Web</a></li># ENDIF #
		</ul>
		<div class="pbt-items-container" id="config">
			# INCLUDE CONFIG_FORM #
		</div>
		<div class="pbt-items-container" id="slider">
			# INCLUDE CAROUSEL_FORM #
		</div>
		# IF C_ARTICLES #<div class="pbt-items-container" id="articles">
			# INCLUDE ARTICLES_FORM #
		</div># ENDIF #
		# IF C_CALENDAR #<div class="pbt-items-container" id="calendar">
			# INCLUDE CALENDAR_FORM #
		</div># ENDIF #
		# IF C_CONTACT #<div class="pbt-items-container" id="contact">
			# INCLUDE CONTACT_FORM #
		</div># ENDIF #
		# IF C_DOWNLOAD #<div class="pbt-items-container" id="download">
			# INCLUDE DOWNLOAD_FORM #
		</div># ENDIF #
		# IF C_FORUM #<div class="pbt-items-container" id="forum">
			# INCLUDE FORUM_FORM #
		</div># ENDIF #
		# IF C_GALLERY #<div class="pbt-items-container" id="gallery">
			# INCLUDE GALLERY_FORM #
		</div># ENDIF #
		# IF C_GUESTBOOK #<div class="pbt-items-container" id="guestbook">
			# INCLUDE GUESTBOOK_FORM #
		</div># ENDIF #
		# IF C_MEDIA #<div class="pbt-items-container" id="media">
			# INCLUDE MEDIA_FORM #
		</div># ENDIF #
		# IF C_NEWS #<div class="pbt-items-container" id="news">
			# INCLUDE NEWS_FORM #
		</div># ENDIF #
		# IF C_WEB #<div class="pbt-items-container" id="web">
			# INCLUDE WEB_FORM #
		</div># ENDIF #
	</div>
	<div class="multiple-select-menu-container">
		# INCLUDE SUBMIT_FORM #
	</div>
</form>


<script src="{PATH_TO_ROOT}/HomeLanding/templates/js/hashchange.js"></script>
<script src="{PATH_TO_ROOT}/HomeLanding/templates/js/easytabs.js"></script>
<script src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/form/validator.js"></script>
<script src="{PATH_TO_ROOT}/kernel/lib/js/phpboost/form/form.js"></script>
<script>
	$('#pbt-tab-container').easytabs();
</script>
