<section id="module-radio">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('radio', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING_RADIO #{@radio.pending}# ELSE #{@radio}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # - {@radio.programs}# ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
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


	# IF C_RADIO_NO_AVAILABLE #
		<div class="center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
	# ELSE #
		# IF C_DISPLAY_BLOCK #
			<div class="calendar-list elements-container columns-2">
				# START radio #
					<article
						data-filterable
						data-filter-calendar="{radio.CALENDAR}"
						data-filter-anouncer="{radio.AUTHOR_CUSTOM_NAME}"
						id="article-radio-{radio.ID}"
						style="background-image:url(# IF radio.C_PICTURE #{radio.U_PICTURE}# ELSE #{PATH_TO_ROOT}/radio/templates/images/default.jpg# ENDIF #)"
						class="article-radio article-several block# IF radio.C_EXTRA_LIST # extra-list# ENDIF #"
						itemscope="itemscope"
						itemtype="http://schema.org/CreativeWork">
						<div class="cover">
							<header>
								<h2>
									<a href="{radio.U_LINK}"><span itemprop="name">{radio.NAME}</span></a>
									<span class="actions">
										# IF radio.C_EDIT #
											<a href="{radio.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
										# ENDIF #
										# IF radio.C_DELETE #
											<a href="{radio.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
										# ENDIF #
									</span>
								</h2>

								<meta itemprop="url" content="{radio.U_LINK}">
								<meta itemprop="description" content="${escape(radio.CONTENTS)}"/>

							</header>

							<div class="content">
								<p class="float-left"><span class="{radio.CALENDAR}">{radio.CALENDAR}</span></p>
								<p class="float-right">{@form.announcer} : {radio.AUTHOR_CUSTOM_NAME}</p>
								<div class="spacer"></div>
								<p class="float-left">{radio.START_HOURS}h{radio.START_MINUTES}</p>
								<p class="float-right">{radio.END_HOURS}h{radio.END_MINUTES}</p>
								<div class="spacer"></div>
							</div>

							<footer class="radio-cat">
								<a itemprop="about" href="{radio.U_CATEGORY}"><i class="fa fa-folder-o"></i> {radio.CATEGORY_NAME}</a>
							</footer>
						</div>
					</article>
				# END radio #
			</div>
		# ENDIF #
		# IF C_DISPLAY_TABLE #
			<table id="table">
				<thead>
					<tr>
						<th>{@form.program.name}</th>
						<th>{@form.release.day}</th>
						<th>{@form.time}</th>
						<th>{@form.announcer}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					# START radio #
						<tr data-filterable data-filter-calendar="{radio.CALENDAR}">
							<td><a href="{radio.U_LINK}"><span itemprop="name">{radio.NAME}</span></a></td>
							<td>{radio.CALENDAR}</td>
							<td>{radio.START_HOURS}h{radio.START_MINUTES} - {radio.END_HOURS}h{radio.END_MINUTES}</td>
							<td>{radio.AUTHOR_CUSTOM_NAME}</td>
							<td>
								# IF radio.C_EDIT #
									<a href="{radio.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
								# ENDIF #
								# IF radio.C_DELETE #
									<a href="{radio.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
								# ENDIF #
							</td>
						</tr>
					# END radio #
				</tbody>
			</table>
		# ENDIF #

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
