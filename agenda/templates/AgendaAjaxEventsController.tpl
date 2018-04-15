<section id="module-agenda-events">
	<header>
		<h2 class="center"># IF C_PENDING_PAGE #{@agenda.pending}# ELSE #{@agenda.titles.events_of} {DATE}# ENDIF #</h2>
	</header>

	# IF C_EVENTS #
		# START event #
		<article itemscope="itemscope" itemtype="http://schema.org/Event" id="article-agenda-{event.ID}" class="article-agenda article-several">
			<header>
				<h2>
					<a href="{event.U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
					<a href="{event.U_LINK}"><span itemprop="name"><time datetime="{event.START_DATE_ISO8601}" itemprop="startDate">{event.START_DATE_SHORT}</time> - {event.TITLE}</span></a>
					<span class="actions">
						# IF C_COMMENTS_ENABLED #
						<a href="{event.U_COMMENTS}"><i class="fa fa-comments-o"></i> {event.L_COMMENTS}</a>
						# ENDIF #
						# IF event.C_EDIT #
						<a href="{event.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF event.C_DELETE #
						<a href="{event.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}"# IF NOT event.C_BELONGS_TO_A_SERIE # data-confirmation="delete-element"# ENDIF #><i class="fa fa-delete"></i></a>
						# ENDIF #
					</span>
				</h2>

				<a itemprop="url" href="{event.U_LINK}"></a>
			</header>

			<div class="content # IF event.C_CANCELLED #cancelled# ENDIF #">
				<div itemscope="itemscope" itemtype="http://schema.org/CreativeWork">

					<meta itemprop="about" content="{event.CATEGORY_NAME}">
					# IF C_COMMENTS_ENABLED #
					<meta itemprop="discussionUrl" content="{event.U_COMMENTS}">
					<meta itemprop="interactionCount" content="{event.NUMBER_COMMENTS} UserComments">
					# ENDIF #

					# IF event.C_PICTURE #
						# IF event.C_PICTURE_IS_PDF #
						<img itemprop="thumbnailUrl" src="{PATH_TO_ROOT}/agenda/templates/images/pdf.png" alt="{event.NAME}" title="{event.NAME}" class="event-img" />
						# ELSE #
						<a href="{event.U_PICTURE}" rel="lightbox[1]"><img itemprop="thumbnailUrl" src="{event.U_PICTURE}" alt="{event.NAME}" title="{event.NAME}" class="event-img" /></a>
						# ENDIF #
					# ELSE #
						<img itemprop="thumbnailUrl" src="{PATH_TO_ROOT}/agenda/templates/images/no_flyer.png" alt="{event.NAME}" title="{event.NAME}" class="event-img" />
					# ENDIF #

					# IF event.C_CANCELLED #
					<span class="cancelled">{@agenda.labels.event_cancelled}</span>
					# ENDIF #
					<p itemprop="author">
						<span class="text-strong">{@agenda.labels.created_by}</span> : # IF event.C_AUTHOR_EXIST #<a itemprop="author" href="{event.U_AUTHOR_PROFILE}" class="{event.AUTHOR_LEVEL_CLASS}" # IF event.C_AUTHOR_GROUP_COLOR # style="color:{event.AUTHOR_GROUP_COLOR}" # ENDIF #>{event.AUTHOR}</a># ELSE #{event.AUTHOR}# ENDIF #
					</p>
					# START event.location #
					# IF event.location.C_LOCATION #
					<p itemprop="location" itemscope="itemscope" itemtype="http://schema.org/Place">
						<span class="text-strong">{@agenda.labels.location}</span> : <span itemprop="name">{event.location.CITY} - {event.location.POSTAL_CODE} {event.location.DEPARTMENT} </span>
					</p>
					# ENDIF #
					# END event.location #
					# IF event.C_PARTICIPATION_ENABLED #
					<p>
						<span class="text-strong">{@agenda.labels.nb_participants}</span> : {event.NB_REGISTRED}
					</p>
					# ENDIF #
					# IF event.C_PATH_INFORMATIONS #
					<p>
						<span class="text-strong">{@agenda.labels.path_informations}</span> : {event.NBR_PATH}
					</p>
					# ENDIF #
					<p>
						<a href="{event.U_LINK}" class="basic-button">{@agenda.labels.details}</a>
					</p>

				</div>
			</div>
			<footer></footer>
		</article>
		# END event #
	# ELSE #
		<div class="center">
		# IF C_PENDING_PAGE #{@agenda.notice.no_pending_event}# ELSE #{@agenda.notice.no_current_action}# ENDIF #
		</div>
	# ENDIF #
</section>
