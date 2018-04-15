# IF C_DISPLAY_BLOCK #
	<h2 data-filterable data-filter-calendar="{@form.sunday}">{@form.sunday}</h2>
	<div class="calendar-list elements-container columns-2">
		# IF C_NO_PROGRAM_AVAILABLE #
			<span data-filterable data-filter-calendar="{@form.sunday}">{@radio.no.program}</span>
		# ELSE #
		# START sunday_prg #
			<article
				data-filterable data-filter-calendar="{sunday_prg.CALENDAR}"
				id="article-radio-{sunday_prg.ID}"
				style="background-image:url(# IF sunday_prg.C_PICTURE #{sunday_prg.U_PICTURE}# ELSE #{PATH_TO_ROOT}/radio/templates/images/default.jpg# ENDIF #)"
				class="article-radio article-several block# IF sunday_prg.C_EXTRA_LIST # extra-list# ENDIF #"
				itemscope="itemscope"
				itemtype="http://schema.org/CreativeWork">
				<div class="cover">
					<header>
						<h2>
							<a href="{sunday_prg.U_LINK}"><span itemprop="name">{sunday_prg.NAME}</span></a>
							<span class="actions">
								# IF sunday_prg.C_EDIT #
									<a href="{sunday_prg.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
								# ENDIF #
								# IF sunday_prg.C_DELETE #
									<a href="{sunday_prg.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
								# ENDIF #
							</span>
						</h2>

						<meta itemprop="url" content="{sunday_prg.U_LINK}">
						<meta itemprop="description" content="${escape(sunday_prg.CONTENTS)}"/>

					</header>

					<div class="content">
						<p class="float-left">{@form.announcer}</p>
						<p class="float-right">{sunday_prg.AUTHOR_CUSTOM_NAME}</p>
						<div class="spacer"></div>
						<p class="float-left">{sunday_prg.START_HOURS}h{sunday_prg.START_MINUTES}</p>
						<p class="float-right">{sunday_prg.END_HOURS}h{sunday_prg.END_MINUTES}</p>
						<div class="spacer"></div>
					</div>

					<footer class="radio-cat">
						<a itemprop="about" href="{sunday_prg.U_CATEGORY}"><i class="fa fa-folder-o"></i> {sunday_prg.CATEGORY_NAME}</a>
					</footer>
				</div>
			</article>
		# END sunday_prg #
		# ENDIF #
	</div>
# ENDIF #
# IF C_DISPLAY_TABLE #
	<h2 data-filterable data-filter-calendar="{@form.sunday}">{@form.sunday}</h2>
	<table id="sunday-table" data-filterable data-filter-calendar="{@form.sunday}">
		<thead>
			<tr>
				<th>{@form.program.name}</th>
				<th>{@form.time}</th>
				<th>{@form.announcer}</th>
				<th>{@form.category}</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			# IF C_NO_PROGRAM_AVAILABLE #
				<tr>
					<td colspan="5">{@radio.no.program}</td>
				</tr>
			# ELSE #
			# START sunday_prg #
				<tr>
					<td><a href="{sunday_prg.U_LINK}"><span itemprop="name">{sunday_prg.NAME}</span></a></td>
					<td>{sunday_prg.START_HOURS}h{sunday_prg.START_MINUTES} - {sunday_prg.END_HOURS}h{sunday_prg.END_MINUTES}</td>
					<td>{sunday_prg.AUTHOR_CUSTOM_NAME}</td>
					<td>{sunday_prg.CATEGORY_NAME}</td>
					<td>
						# IF sunday_prg.C_EDIT #
							<a href="{sunday_prg.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF sunday_prg.C_DELETE #
							<a href="{sunday_prg.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
						# ENDIF #
					</td>
				</tr>
			# END sunday_prg #
			# ENDIF #
		</tbody>
	</table>
	<script>
		jQuery('#sunday-table').basictable();
	</script>
# ENDIF #
# IF C_DISPLAY_CALENDAR #
	<ul class="radio-calendar" data-filterable data-filter-calendar="{@form.sunday}">
		<li class="center"><h4>{@form.sunday}</h4></li>
		# IF C_NO_PROGRAM_AVAILABLE #
			<li class="center">{@radio.no.program}</li>
		# ELSE #
		# START sunday_prg #
			<li>
				<h5><a href="{sunday_prg.U_LINK}"><span itemprop="name">{sunday_prg.NAME}</span></a></h5>
				<p><i class="fa fa-microphone"></i> {sunday_prg.AUTHOR_CUSTOM_NAME}</p>
				<p><i class="fa fa-clock-o"></i> {sunday_prg.START_HOURS}h{sunday_prg.START_MINUTES} - {sunday_prg.END_HOURS}h{sunday_prg.END_MINUTES}</p>
				<p><a itemprop="about" href="{sunday_prg.U_CATEGORY}"><i class="fa fa-folder-o"></i> {sunday_prg.CATEGORY_NAME}</a></p>
			</li>
		# END sunday_prg #
		# ENDIF #
	</ul>
# ENDIF #
