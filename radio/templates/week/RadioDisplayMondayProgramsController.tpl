	# IF C_DISPLAY_BLOCK #
		<h2 data-filterable data-filter-calendar="{@form.monday}">{@form.monday}</h2>
		<div class="calendar-list elements-container columns-2">
			# IF C_NO_PROGRAM_AVAILABLE #
			 	<span data-filterable data-filter-calendar="{@form.monday}">{@radio.no.program}</span>
			# ELSE #
			# START monday_prg #
				<article
				 	data-filterable data-filter-calendar="{monday_prg.CALENDAR}"
					id="article-radio-{monday_prg.ID}"
					style="background-image:url(# IF monday_prg.C_PICTURE #{monday_prg.U_PICTURE}# ELSE #{PATH_TO_ROOT}/radio/templates/images/default.jpg# ENDIF #)"
					class="article-radio article-several block# IF monday_prg.C_EXTRA_LIST # extra-list# ENDIF #"
					itemscope="itemscope"
					itemtype="http://schema.org/CreativeWork">
					<div class="cover">
						<header>
							<h2>
								<a href="{monday_prg.U_LINK}"><span itemprop="name">{monday_prg.NAME}</span></a>
								<span class="actions">
									# IF monday_prg.C_EDIT #
										<a href="{monday_prg.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
									# ENDIF #
									# IF monday_prg.C_DELETE #
										<a href="{monday_prg.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
									# ENDIF #
								</span>
							</h2>

							<meta itemprop="url" content="{monday_prg.U_LINK}">
							<meta itemprop="description" content="${escape(monday_prg.CONTENTS)}"/>

						</header>

						<div class="content">
							<p class="float-left">{@form.announcer}</p>
							<p class="float-right">{monday_prg.AUTHOR_CUSTOM_NAME}</p>
							<div class="spacer"></div>
							<p class="float-left">{monday_prg.START_HOURS}h{monday_prg.START_MINUTES}</p>
							<p class="float-right">{monday_prg.END_HOURS}h{monday_prg.END_MINUTES}</p>
							<div class="spacer"></div>
						</div>

						<footer class="radio-cat">
							<a itemprop="about" href="{monday_prg.U_CATEGORY}"><i class="fa fa-folder-o"></i> {monday_prg.CATEGORY_NAME}</a>
						</footer>
					</div>
				</article>
			# END monday_prg #
			# ENDIF #
		</div>
	# ENDIF #
	# IF C_DISPLAY_TABLE #
		<h2 data-filterable data-filter-calendar="{@form.monday}">{@form.monday}</h2>
		<table id="monday-table" data-filterable data-filter-calendar="{@form.monday}">
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
				# START monday_prg #
					<tr>
						<td><a href="{monday_prg.U_LINK}"><span itemprop="name">{monday_prg.NAME}</span></a></td>
						<td>{monday_prg.START_HOURS}h{monday_prg.START_MINUTES} - {monday_prg.END_HOURS}h{monday_prg.END_MINUTES}</td>
						<td>{monday_prg.AUTHOR_CUSTOM_NAME}</td>
						<td>{monday_prg.CATEGORY_NAME}</td>
						<td>
							# IF monday_prg.C_EDIT #
								<a href="{monday_prg.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
							# ENDIF #
							# IF monday_prg.C_DELETE #
								<a href="{monday_prg.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
							# ENDIF #
						</td>
					</tr>
				# END monday_prg #
				# ENDIF #
			</tbody>
		</table>
		<script>
			jQuery('#monday-table').basictable();
		</script>
	# ENDIF #
	# IF C_DISPLAY_CALENDAR #
		<ul class="radio-calendar" data-filterable data-filter-calendar="{@form.monday}">
			<li class="center"><h4>{@form.monday}</h4></li>
			# IF C_NO_PROGRAM_AVAILABLE #
			 	<li class="center">{@radio.no.program}</li>
			# ELSE #
			# START monday_prg #
				<li>
					<h5><a href="{monday_prg.U_LINK}"><span itemprop="name">{monday_prg.NAME}</span></a></h5>
					<p><i class="fa fa-microphone"></i> {monday_prg.AUTHOR_CUSTOM_NAME}</p>
					<p><i class="fa fa-clock-o"></i> {monday_prg.START_HOURS}h{monday_prg.START_MINUTES} - {monday_prg.END_HOURS}h{monday_prg.END_MINUTES}</p>
					<p><a itemprop="about" href="{monday_prg.U_CATEGORY}"><i class="fa fa-folder-o"></i> {monday_prg.CATEGORY_NAME}</a></p>
				</li>
			# END monday_prg #
			# ENDIF #
		</ul>
	# ENDIF #
