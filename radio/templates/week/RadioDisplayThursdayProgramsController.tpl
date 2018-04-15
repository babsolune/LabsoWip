# IF C_DISPLAY_BLOCK #
	<h2 data-filterable data-filter-calendar="{@form.thursday}">{@form.thursday}</h2>
	<div class="calendar-list elements-container columns-2">
		# IF C_NO_PROGRAM_AVAILABLE #
			<span data-filterable data-filter-calendar="{@form.thursday}">{@radio.no.program}</span>
		# ELSE #
		# START thursday_prg #
			<article
				data-filterable data-filter-calendar="{thursday_prg.CALENDAR}"
				id="article-radio-{thursday_prg.ID}"
				style="background-image:url(# IF thursday_prg.C_PICTURE #{thursday_prg.U_PICTURE}# ELSE #{PATH_TO_ROOT}/radio/templates/images/default.jpg# ENDIF #)"
				class="article-radio article-several block# IF thursday_prg.C_EXTRA_LIST # extra-list# ENDIF #"
				itemscope="itemscope"
				itemtype="http://schema.org/CreativeWork">
				<div class="cover">
					<header>
						<h2>
							<a href="{thursday_prg.U_LINK}"><span itemprop="name">{thursday_prg.NAME}</span></a>
							<span class="actions">
								# IF thursday_prg.C_EDIT #
									<a href="{thursday_prg.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
								# ENDIF #
								# IF thursday_prg.C_DELETE #
									<a href="{thursday_prg.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
								# ENDIF #
							</span>
						</h2>

						<meta itemprop="url" content="{thursday_prg.U_LINK}">
						<meta itemprop="description" content="${escape(thursday_prg.CONTENTS)}"/>

					</header>

					<div class="content">
						<p class="float-left">{@form.announcer}</p>
						<p class="float-right">{thursday_prg.AUTHOR_CUSTOM_NAME}</p>
						<div class="spacer"></div>
						<p class="float-left">{thursday_prg.START_HOURS}h{thursday_prg.START_MINUTES}</p>
						<p class="float-right">{thursday_prg.END_HOURS}h{thursday_prg.END_MINUTES}</p>
						<div class="spacer"></div>
					</div>

					<footer class="radio-cat">
						<a itemprop="about" href="{thursday_prg.U_CATEGORY}"><i class="fa fa-folder-o"></i> {thursday_prg.CATEGORY_NAME}</a>
					</footer>
				</div>
			</article>
		# END thursday_prg #
		# ENDIF #
	</div>
# ENDIF #
# IF C_DISPLAY_TABLE #
	<h2 data-filterable data-filter-calendar="{@form.thursday}">{@form.thursday}</h2>
	<table id="thursday-table" data-filterable data-filter-calendar="{@form.thursday}">
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
			# START thursday_prg #
				<tr>
					<td><a href="{thursday_prg.U_LINK}"><span itemprop="name">{thursday_prg.NAME}</span></a></td>
					<td>{thursday_prg.START_HOURS}h{thursday_prg.START_MINUTES} - {thursday_prg.END_HOURS}h{thursday_prg.END_MINUTES}</td>
					<td>{thursday_prg.AUTHOR_CUSTOM_NAME}</td>
					<td>{thursday_prg.CATEGORY_NAME}</td>
					<td>
						# IF thursday_prg.C_EDIT #
							<a href="{thursday_prg.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF thursday_prg.C_DELETE #
							<a href="{thursday_prg.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
						# ENDIF #
					</td>
				</tr>
			# END thursday_prg #
			# ENDIF #
		</tbody>
	</table>
	<script>
		jQuery('#thursday-table').basictable();
	</script>
# ENDIF #
# IF C_DISPLAY_CALENDAR #
	<ul class="radio-calendar" data-filterable data-filter-calendar="{@form.thursday}">
		<li class="center"><h4>{@form.thursday}</h4></li>
		# IF C_NO_PROGRAM_AVAILABLE #
			<li class="center">{@radio.no.program}</li>
		# ELSE #
		# START thursday_prg #
			<li>
				<h5><a href="{thursday_prg.U_LINK}"><span itemprop="name">{thursday_prg.NAME}</span></a></h5>
				<p><i class="fa fa-microphone"></i> {thursday_prg.AUTHOR_CUSTOM_NAME}</p>
				<p><i class="fa fa-clock-o"></i> {thursday_prg.START_HOURS}h{thursday_prg.START_MINUTES} - {thursday_prg.END_HOURS}h{thursday_prg.END_MINUTES}</p>
				<p><a itemprop="about" href="{thursday_prg.U_CATEGORY}"><i class="fa fa-folder-o"></i> {thursday_prg.CATEGORY_NAME}</a></p>
			</li>
		# END thursday_prg #
		# ENDIF #
	</ul>
# ENDIF #
