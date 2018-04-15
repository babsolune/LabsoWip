<script>
<!--
function OpenFacebookShare(file,width,height,scrollbars){
if (scrollbars == '') {
scrollbars = 'no';
}
window.open('https://www.facebook.com/share.php?u=http://'+window.location.host+encodeURIComponent(file),'_blank','top=50,left=50,width='+width+',height='+height+',scrollbars='+scrollbars);
}
// -->
</script>

<section id="module-agenda">
	<header>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF #
		</h1>
	</header>
	<div class="content">
		# IF NOT C_APPROVED #
			# INCLUDE NOT_VISIBLE_MESSAGE #
		# ENDIF #
		<article itemscope="itemscope" itemtype="http://schema.org/Event" id="article-agenda-{ID}" class="article-agenda">
			<header>
				<h2>
					<span itemprop="name"><time datetime="{START_DATE_ISO8601}" itemprop="startDate">{START_DATE_SHORT}</time> - {TITLE}</span>
					<span class="actions">
						# IF C_EDIT #
							<a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF C_DELETE #
							<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'common')}"# IF NOT C_BELONGS_TO_A_SERIE # data-confirmation="delete-element"# ENDIF #><i class="fa fa-delete"></i></a>
						# ENDIF #
					</span>
				</h2>

				<a itemprop="url" href="{U_LINK}"></a>
			</header>
			<div class="content# IF C_CANCELLED # cancelled# ENDIF #">
				<div itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
					# IF C_CANCELLED #
					<span class="cancelled">{@agenda.labels.event_cancelled}</span>
					<div class="spacer"></div>
					# ENDIF #

					<meta itemprop="about" content="{CATEGORY_NAME}">
					# IF C_COMMENTS_ENABLED #
					<meta itemprop="discussionUrl" content="{U_COMMENTS}">
					<meta itemprop="interactionCount" content="{NUMBER_COMMENTS} UserComments">
					# ENDIF #


					<div class="agenda-options">
						<span class="float-right">
							<p>
								<a class="button fb-share" href="Javascript:OpenFacebookShare('{U_LINK}','626','436');">{@agenda.labels.share} <i class="fa fa-facebook-square fa-lg"></i></a>
							</p>
							# IF C_FORUM_LINK #
							<p>
								<a href="{U_FORUM_LINK}">
									<span class="basic-button text-strong">{@agenda.labels.forum_talk}</span>
								</a>
							</p>
							# ENDIF #
						</span>
						<p>
							<span class="text-strong">{@agenda.labels.created_by}</span> : # IF C_AUTHOR_EXIST #<a itemprop="author" href="{U_AUTHOR_PROFILE}" class="{AUTHOR_LEVEL_CLASS}" # IF C_AUTHOR_GROUP_COLOR # style="color: {AUTHOR_GROUP_COLOR};" # ENDIF #>{AUTHOR}</a># ELSE #{AUTHOR}# ENDIF #
						</p>
						# START location #
						# IF location.C_LOCATION #
						<p>
							<span class="text-strong">{@agenda.labels.location}</span> : <span>{location.CITY} - {location.POSTAL_CODE} {location.DEPARTMENT}</span>
						</p>
						# ENDIF #
						# END location #
						# IF C_LOCATION_MORE #
						<p>
							<span class="text-strong">{@agenda.labels.place.more}</span> : {LOCATION_MORE}
						</p>
						# ENDIF #
						<p>
							<span class="text-strong">{@agenda.labels.start_time}</span> :
							<time datetime="{START_DATE_ISO8601}" itemprop="endDate">{START_DATE_SHORT} <span class="label label-success">{START_DATE_HOUR}h{START_DATE_MINUTE}</span></time>
						</p>
						# IF C_END_DATE #
						<p>
							<span class="text-strong">{@agenda.labels.end_time}</span> :
							<time datetime="{END_DATE_ISO8601}" itemprop="endDate">{END_DATE_SHORT} <span class="label label-success">{END_DATE_HOUR}h{END_DATE_MINUTE}</span></time>
						</p>
						# ENDIF #
						# IF C_CONTACT_INFORMATIONS #
						<p>
							<span class="text-strong">{@agenda.labels.contact_informations}</span> :
							# START contact #
							# IF contact.C_MAIL #<a href="mailto:{contact.CONTACT_MAIL}" title=""># ENDIF #
								# IF contact.C_NAME #<i class="fa fa-at"></i> {contact.CONTACT_NAME}# ENDIF #
							# IF contact.C_MAIL #</a># ENDIF #
							# IF contact.C_TEL1 # - <i class="fa fa-mobile"></i> {contact.CONTACT_PHONE1}# ENDIF #
							# IF contact.C_TEL2 # - <i class="fa fa-phone"></i> {contact.CONTACT_PHONE2}# ENDIF #
							# IF contact.C_WEBSITE # - <a href="{contact.CONTACT_SITE}" title=""><i class="fa fa-globe"></i> {@agenda.placeholder.site}</a># ENDIF #
							# IF C_SEVERAL_CONTACTS #<br /># ENDIF #
							# END contact #
						</p>
						# ENDIF #
						<div class="# IF C_PATH_INFORMATIONS #split# ELSE #pict-only# ENDIF #">
						# IF C_PATH_INFORMATIONS #
							<table class="path-table">
								<caption>{@agenda.labels.path_informations}</caption>
								<thead>
									<tr>
										<th>{@agenda.option.path.type}</th>
										<th>{@agenda.placeholder.path.length}</th>
										<th>{@agenda.placeholder.path.elevation}</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<td colspan="3">
											<img width="16px" height="16px" src="{PATH_TO_ROOT}/agenda/templates/images/1-nc.png" alt="icon" /> : {@agenda.option.path.type.dirt.cycle}
											 | <img width="16px" height="16px" src="{PATH_TO_ROOT}/agenda/templates/images/2-nc.png" alt="icon" /> : {@agenda.option.path.type.walk}
											 | <img width="16px" height="16px" src="{PATH_TO_ROOT}/agenda/templates/images/3-nc.png" alt="icon" /> : {@agenda.option.path.type.trail}
											 | <img width="16px" height="16px" src="{PATH_TO_ROOT}/agenda/templates/images/4-nc.png" alt="icon" /> : {@agenda.option.path.type.road.cycle}
											 | <img width="16px" height="16px" src="{PATH_TO_ROOT}/agenda/templates/images/5-nc.png" alt="icon" /> : {@agenda.option.path.type.horse}
											&nbsp;|&nbsp;nc : {@agenda.placeholder.path.none}
										</td>
									</tr>
									<tr>
										<td colspan="3">
											<img width="16px" height="16px" src="{PATH_TO_ROOT}/agenda/templates/images/2-nc.png" alt="icon" /> : {@agenda.placeholder.path.none}
											<img width="16px" height="16px" src="{PATH_TO_ROOT}/agenda/templates/images/2-1.png" alt="icon" /> : {@agenda.placeholder.path.start}
											<img width="16px" height="16px" src="{PATH_TO_ROOT}/agenda/templates/images/2-2.png" alt="icon" /> : {@agenda.placeholder.path.medium}
										</td>
									</tr>
									<tr>
										<td colspan="3">
											<img width="16px" height="16px" src="{PATH_TO_ROOT}/agenda/templates/images/2-3.png" alt="icon" /> : {@agenda.placeholder.path.sport}
											<img width="16px" height="16px" src="{PATH_TO_ROOT}/agenda/templates/images/2-4.png" alt="icon" /> : {@agenda.placeholder.path.expert}
										</td>
									</tr>
								</tfoot>
								<tbody>
									# START path #
									<tr>
										<td># IF path.C_PATH_LEVEL #<img src="{PATH_TO_ROOT}/agenda/templates/images/{path.PATH_TYPE}-{path.PATH_LEVEL}.png" alt="icon" /># ELSE #<img src="{PATH_TO_ROOT}/agenda/templates/images/{path.PATH_TYPE}-nc.png" alt="icon" /># ENDIF #</td>
										<td># IF path.C_PATH_LENGTH #{path.PATH_LENGTH}# ELSE #nc# ENDIF #</td>
										<td># IF path.C_PATH_ELEVATION #{path.PATH_ELEVATION}# ELSE #nc# ENDIF #</td>
									</tr>
									# END path #
								</tbody>
							</table>
							# ENDIF #
							# IF C_PICTURE #
								# IF C_PICTURE_IS_PDF #
								<a href="#pdf">
									<img itemprop="thumbnailUrl" src="{PATH_TO_ROOT}/agenda/templates/images/pdf.png" alt="{NAME}" title="{NAME}" />
									<p class="small text-italic center">{@clic.to.enlarge.pdf}</p>
								</a>
								# ELSE #
								<a href="{U_PICTURE}" rel="lightbox[1]">
									<img itemprop="thumbnailUrl" src="{U_PICTURE}" alt="{NAME}" title="{NAME}" />
									<p class="small text-italic center">{@clic.to.enlarge}</p>
								</a>
								# ENDIF #
							# ELSE #
							<img itemprop="thumbnailUrl" src="{PATH_TO_ROOT}/agenda/templates/images/no_flyer.png" alt="{NAME}" title="{NAME}" />
							# ENDIF #
						</div>
					</div>

					<div itemprop="text"><p>{CONTENTS}</p></div>

					# IF C_PARTICIPATION_ENABLED #
						<div class="spacer"></div>
						# IF C_DISPLAY_PARTICIPANTS #
						<div>
							<span class="basic-button text-strong">{@agenda.labels.participants}</span> :
							<span>
								# IF C_PARTICIPANTS #
									# START participant #
										<a href="{participant.U_PROFILE}" class="{participant.LEVEL_CLASS}" # IF participant.C_GROUP_COLOR # style="color: {participant.GROUP_COLOR};" # ENDIF #>{participant.DISPLAY_NAME}</a># IF NOT participant.C_LAST_PARTICIPANT #,# ENDIF #
									# END participant #
								# ELSE #
									{@agenda.labels.no_one}
								# ENDIF #
							</span>
						</div>
						# ENDIF #
						# IF C_PARTICIPATE #
							<p>
								<a href="{U_SUSCRIBE}" class="participate-button float-right">{@agenda.labels.suscribe}</a>
								# IF C_MISSING_PARTICIPANTS #
								<span class="small text-italic">({L_MISSING_PARTICIPANTS})</span>
								# ENDIF #
								# IF C_REGISTRATION_DAYS_LEFT #
								<div class="spacer"></div>
								<span class="small text-italic">{L_REGISTRATION_DAYS_LEFT}</span>
								# ENDIF #
							</p>
						# ENDIF #
						# IF C_IS_PARTICIPANT #
						<p><a href="{U_UNSUSCRIBE}" class="participate-button participate-button-reverse float-right">{@agenda.labels.unsuscribe}</a></p>
						# ELSE #
							# IF C_MAX_PARTICIPANTS_REACHED #<p><span class="small text-italic">{@agenda.labels.max_participants_reached}</span># ENDIF #</p>
						# ENDIF #
						# IF C_REGISTRATION_CLOSED #<p><span class="small text-italic">{@agenda.labels.registration_closed}</span></p># ENDIF #
					# ENDIF #
				</div>
				<div class="spacer"></div>
				<div class="center">
					# IF C_PICTURE #
						# IF C_PICTURE_IS_PDF #
						<div class="responsive-object">
							<object data="{U_PICTURE}" type="application/pdf" id="pdf">
								{@alternative.pdf.link} <p><a href="{U_PICTURE}">{TITLE}</a></p>
							</object>
						</div>
						# ENDIF #
					# ENDIF #
				</div>
			</div>

			<div class="spacer"></div>
			<hr>

			# INCLUDE COMMENTS #
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>
