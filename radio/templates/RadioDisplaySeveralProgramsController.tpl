<section id="module-radio">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('radio', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING_RADIO #{@radio.pending}# ELSE #{@radio}# ENDIF ## IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # - {@radio.programs} # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
		</h1>
	</header>
	<div class="content" id="radio-calendar">
		<nav id="calendar-filters" class="cssmenu cssmenu-horizontal">
				<ul>
					<li><a href="{PATH_TO_ROOT}/radio" class="cssmenu-title">{@all.programs}</a></li>
					<li><a href="?filter-calendar={@form.monday}" class="cssmenu-title">{@form.monday}</a></li>
					<li><a href="?filter-calendar={@form.tuesday}" class="cssmenu-title">{@form.tuesday}</a></li>
					<li><a href="?filter-calendar={@form.wednesday}" class="cssmenu-title">{@form.wednesday}</a></li>
					<li><a href="?filter-calendar={@form.thursday}" class="cssmenu-title">{@form.thursday}</a></li>
					<li><a href="?filter-calendar={@form.friday}" class="cssmenu-title">{@form.friday}</a></li>
					<li><a href="?filter-calendar={@form.saturday}" class="cssmenu-title">{@form.saturday}</a></li>
					<li><a href="?filter-calendar={@form.sunday}" class="cssmenu-title">{@form.sunday}</a></li>
				</ul>
			</nav>
			<script>jQuery("#calendar-filters").menumaker({title: "{@pick.date}", format: "multitoggle", breakpoint: 768}); </script>
		<div class="spacer"></div>

		# IF C_DISPLAY_CALENDAR #
			<div class="radio-flex">
		# ENDIF #

		# INCLUDE MONDAY_PRG #
		# INCLUDE TUESDAY_PRG #
		# INCLUDE WEDNESDAY_PRG #
		# INCLUDE THURSDAY_PRG #
		# INCLUDE FRIDAY_PRG #
		# INCLUDE SATURDAY_PRG #
		# INCLUDE SUNDAY_PRG #

		# IF C_DISPLAY_CALENDAR #
			</div>
		# ENDIF #
	</div>
	<footer></footer>
</section>

<script src="{PATH_TO_ROOT}/radio/templates/js/jquery.filterjitsu.js"></script>
<script>
  $(document).ready(function() {
	$.fn.filterjitsu(	);
  });
</script>
