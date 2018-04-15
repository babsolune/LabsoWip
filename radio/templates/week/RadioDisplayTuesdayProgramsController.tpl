# IF C_DISPLAY_BLOCK #
	<h2 data-filterable data-filter-calendar="{@form.tuesday}">{@form.tuesday}</h2>
	<div class="calendar-list elements-container columns-2">
		# IF C_NO_PROGRAM_AVAILABLE #
			<span data-filterable data-filter-calendar="{@form.tuesday}">{@radio.no.program}</span>
		# ELSE #
		# START tuesday_prg #
			<article
				data-filterable data-filter-calendar="{tuesday_prg.CALENDAR}"
				id="article-radio-{tuesday_prg.ID}"
				style="background-image:url(# IF tuesday_prg.C_PICTURE #{tuesday_prg.U_PICTURE}# ELSE #{PATH_TO_ROOT}/radio/templates/images/default.jpg# ENDIF #)"
				class="article-radio article-several block# IF tuesday_prg.C_EXTRA_LIST # extra-list# ENDIF #"
				itemscope="itemscope"
				itemtype="http://schema.org/CreativeWork">
				<div class="cover">
					<header>
						<h2>
							<a href="{tuesday_prg.U_LINK}"><span itemprop="name">{tuesday_prg.NAME}</span></a>
							<span class="actions">
								# IF tuesday_prg.C_EDIT #
									<a href="{tuesday_prg.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
								# ENDIF #
								# IF tuesday_prg.C_DELETE #
									<a href="{tuesday_prg.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
								# ENDIF #
							</span>
						</h2>

						<meta itemprop="url" content="{tuesday_prg.U_LINK}">
						<meta itemprop="description" content="${escape(tuesday_prg.CONTENTS)}"/>

					</header>

					<div class="content">
						<p class="float-left">{@form.announcer}</p>
						<p class="float-right">{tuesday_prg.AUTHOR_CUSTOM_NAME}</p>
						<div class="spacer"></div>
						<p class="float-left">{tuesday_prg.START_HOURS}h{tuesday_prg.START_MINUTES}</p>
						<p class="float-right">{tuesday_prg.END_HOURS}h{tuesday_prg.END_MINUTES}</p>
						<div class="spacer"></div>
					</div>

					<footer class="radio-cat">
						<a itemprop="about" href="{tuesday_prg.U_CATEGORY}"><i class="fa fa-folder-o"></i> {tuesday_prg.CATEGORY_NAME}</a>
					</footer>
				</div>
			</article>
		# END tuesday_prg #
		# ENDIF #
	</div>
# ENDIF #
# IF C_DISPLAY_TABLE #
	<h2 data-filterable data-filter-calendar="{@form.tuesday}">{@form.tuesday}</h2>
	<table id="tuesday-table" data-filterable data-filter-calendar="{@form.tuesday}">
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
			# START tuesday_prg #
				<tr>
					<td><a href="{tuesday_prg.U_LINK}"><span itemprop="name">{tuesday_prg.NAME}</span></a></td>
					<td>{tuesday_prg.START_HOURS}h{tuesday_prg.START_MINUTES} - {tuesday_prg.END_HOURS}h{tuesday_prg.END_MINUTES}</td>
					<td>{tuesday_prg.AUTHOR_CUSTOM_NAME}</td>
					<td>{tuesday_prg.CATEGORY_NAME}</td>
					<td>
						# IF tuesday_prg.C_EDIT #
							<a href="{tuesday_prg.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF tuesday_prg.C_DELETE #
							<a href="{tuesday_prg.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
						# ENDIF #
					</td>
				</tr>
			# END tuesday_prg #
			# ENDIF #
		</tbody>
	</table>
	<script>
		jQuery('#tuesday-table').basictable();
	</script>
# ENDIF #
# IF C_DISPLAY_CALENDAR #
	<ul class="radio-calendar" data-filterable data-filter-calendar="{@form.tuesday}">
		<li class="center"><h4>{@form.tuesday}</h4></li>
		# IF C_NO_PROGRAM_AVAILABLE #
			<li class="center">{@radio.no.program}</li>
		# ELSE #
		# START tuesday_prg #
			<li>
				<h5><a href="{tuesday_prg.U_LINK}"><span itemprop="name">{tuesday_prg.NAME}</span></a></h5>
				<p><i class="fa fa-microphone"></i> {tuesday_prg.AUTHOR_CUSTOM_NAME}</p>
				<p><i class="fa fa-clock-o"></i> {tuesday_prg.START_HOURS}h{tuesday_prg.START_MINUTES} - {tuesday_prg.END_HOURS}h{tuesday_prg.END_MINUTES}</p>
				<p><a itemprop="about" href="{tuesday_prg.U_CATEGORY}"><i class="fa fa-folder-o"></i> {tuesday_prg.CATEGORY_NAME}</a></p>
			</li>
		# END tuesday_prg #
		# ENDIF #
	</ul>
# ENDIF #
