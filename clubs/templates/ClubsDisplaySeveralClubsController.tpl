<section id="module-clubs">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('clubs', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #{@clubs.pending}# ELSE #{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
		</h1>

		# IF C_CATEGORY_DESCRIPTION #
		<div class="cat-description">
			{CATEGORY_DESCRIPTION}
		</div>
		# ENDIF #

	</header>

	# IF C_SUB_CATEGORIES #
		<div class="subcat-container elements-container# IF C_SEVERAL_CATS_COLUMNS # columns-{NUMBER_CATS_COLUMNS}# ENDIF #">
			# START sub_categories_list #
			<div class="subcat-element block">
				<div class="subcat-content">
					# IF sub_categories_list.C_CATEGORY_IMAGE #<a itemprop="about" href="{sub_categories_list.U_CATEGORY}"><img itemprop="thumbnailUrl" src="{sub_categories_list.CATEGORY_IMAGE}" alt="{sub_categories_list.CATEGORY_NAME}" /></a># ENDIF #
					<br />
					<a itemprop="about" href="{sub_categories_list.U_CATEGORY}">{sub_categories_list.CATEGORY_NAME}</a>
					<br />
					<span class="small">{sub_categories_list.CLUBS_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_CLUB #${TextHelper::lcfirst(LangLoader::get_message('clubs', 'common', 'clubs'))}# ELSE #${TextHelper::lcfirst(LangLoader::get_message('club', 'common', 'clubs'))}# ENDIF #</span>
				</div>
			</div>
			# END sub_categories_list #
			<div class="spacer"></div>
		</div>
		# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
	# ELSE #

			# IF C_CAT_GMAP_ENABLED #
				# IF C_DEFAULT_ADDRESS #
					<div id="gmap"></div>
				# ELSE #
					${LangLoader::get_message('clubs.no.default.address', 'common', 'clubs')}
				# ENDIF #
			# ELSE #
				<p>${LangLoader::get_message('clubs.no.gmap', 'common', 'clubs')}</p>
			# ENDIF #


		# IF NOT C_CATEGORY_DISPLAYED_TABLE #<div class="spacer"></div># ENDIF #
	# ENDIF #


	# IF C_CLUBS #
		# IF C_MORE_THAN_ONE_CLUB #
			<div class="spacer"></div>
		# ENDIF #
	<div class="content elements-container">
		# IF C_CATEGORY_DISPLAYED_TABLE #
		<table id="table">
			<thead>
				<tr>
					<th class="col-small">{@clubs.logo}</th>
					<th>${LangLoader::get_message('form.name', 'common')}</th>
					<th>${@clubs.details}</th>
					<th class="coll-small">{@website}</th>
					<th class="col-small">{@visits_number}</th>
					# IF C_MODERATE #<th class="col-small"></th># ENDIF #
				</tr>
			</thead>
			<tbody>
				# START clubs #
				<tr>
					<td>
						# IF clubs.C_LOGO_MINI #
							<img src="{clubs.U_LOGO_MINI}" alt="{clubs.NAME}" />
						# ELSE #
							<img src="{PATH_TO_ROOT}/clubs/clubs.png" alt="{clubs.NAME}" />
						# ENDIF #
					</td>
					<td>
						<span itemprop="name">{clubs.NAME}</span>
					</td>
					<td>
						<a href="{clubs.U_LINK}">{@see.details}</a>
					</td>
					<td>
						# IF clubs.C_VISIT #
							<a class="basic-button" # IF clubs.C_NEW_WINDOW #target="_blank" rel="noopener noreferrer"# ENDIF # href="{clubs.U_VISIT}">{@visit}</a>
						# ELSE #
							{@no.website}
						# ENDIF #
					</td>
					<td>
						{clubs.NUMBER_VIEWS}
					</td>
					# IF C_MODERATE #
					<td>
						# IF clubs.C_EDIT #
						<a href="{clubs.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF clubs.C_DELETE #
						<a href="{clubs.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
						# ENDIF #
					</td>
					# ENDIF #
				</tr>
				# END clubs #
			</tbody>
		</table>
		# ELSE #
		# START clubs #
		<article id="article-clubs-{clubs.ID}" class="article-clubs article-several# IF C_CATEGORY_DISPLAYED_SUMMARY # block# ENDIF ## IF clubs.C_IS_PARTNER # content-friends# ENDIF ## IF clubs.C_IS_PRIVILEGED_PARTNER # content-privileged-friends# ENDIF ## IF clubs.C_NEW_CONTENT # new-content# ENDIF#" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			<header>
				<h2>
					<span class="actions">
						# IF clubs.C_EDIT #<a href="{clubs.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a># ENDIF #
						# IF clubs.C_DELETE #<a href="{clubs.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a># ENDIF #
					</span>
					<a href="{clubs.U_LINK}" itemprop="name">{clubs.NAME}</a>
				</h2>

				<meta itemprop="url" content="{clubs.U_LINK}">
				<meta itemprop="description" content="${escape(clubs.CONTENTS)}"/>
				# IF C_COMMENTS_ENABLED #
				<meta itemprop="discussionUrl" content="{clubs.U_COMMENTS}">
				<meta itemprop="interactionCount" content="{clubs.NUMBER_COMMENTS} UserComments">
				# ENDIF #
			</header>

			<div class="content">
				<div class="options infos">
					<div class="center">
						# IF clubs.C_VISIBLE #
							# IF clubs.C_LOGO #
								<p class="clubs-logo">
									<img src="{clubs.U_LOGO}" alt="{clubs.NAME}" itemprop="image" />
								</p>
							# ENDIF #
							# IF clubs.C_VISIT #
								<a href="{clubs.U_VISIT}" # IF clubs.C_NEW_WINDOW #target="_blank" rel="noopener noreferrer"# ENDIF # class="basic-button">
									<i class="fa fa-globe"></i> {@visit}
								</a>
								# IF IS_USER_CONNECTED #
									<a href="{clubs.U_DEADLINK}" class="basic-button alt" title="${LangLoader::get_message('deadlink', 'common')}">
										<i class="fa fa-unlink"></i>
									</a>
								# ENDIF #
							# ELSE #
								{@no.website}
							# ENDIF #
						# ENDIF #
					</div>
					<h6>{@link_infos}</h6>
					<span class="text-strong">{@visits_number} : </span><span>{clubs.NUMBER_VIEWS}</span><br/>
					# IF NOT C_CATEGORY #<span class="text-strong">${LangLoader::get_message('category', 'categories-common')} : </span><span><a itemprop="about" class="small" href="{clubs.U_CATEGORY}">{clubs.CATEGORY_NAME}</a></span><br/># ENDIF #
					# IF C_COMMENTS_ENABLED #
					<span># IF clubs.C_COMMENTS # {clubs.NUMBER_COMMENTS} # ENDIF # {clubs.L_COMMENTS}</span>
					# ENDIF #
					# IF C_NOTATION_ENABLED #
					<div class="spacer"></div>
					<div class="center">{clubs.NOTATION}</div>
					# ENDIF #
				</div>

				<div itemprop="text">{clubs.CONTENTS}</div>
			</div>

			<footer></footer>
		</article>
		# END clubs #
	</div>
	# ENDIF #

	# ELSE #
	<div class="content">
		# IF NOT C_HIDE_NO_ITEM_MESSAGE #
		<div class="center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
		# ENDIF #
	</div>
	# ENDIF #

	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
# IF NOT C_SUB_CATEGORIES #
	# IF C_CAT_GMAP_ENABLED #
		# IF C_DEFAULT_ADDRESS #
			<script src="https://maps.googleapis.com/maps/api/js?key={GMAP_API_KEY}"></script>
			<script>
			<!--
				var stadiums = Array()
				# START clubs #
					# IF clubs.C_LOGO_MINI #
						var logoMini = ${escapejs(clubs.U_LOGO_MINI)};
					# ELSE #
						var logoMini = "{PATH_TO_ROOT}/clubs/clubs.png";
					# ENDIF #

					# IF clubs.C_STADIUM_LOCATION #
						stadiums.push([${escapejs(clubs.LATITUDE)}, ${escapejs(clubs.LONGITUDE)}, ${escapejs(clubs.NAME)}, logoMini, ${escapejs(clubs.U_LINK)}, ${escapejs(clubs.STAD_LAT)}, ${escapejs(clubs.STAD_LNG)}]);
					# ENDIF #
				# END clubs #

				var map = new google.maps.Map(document.getElementById('gmap'), {
					zoom: 10,
					center: new google.maps.LatLng({DEFAULT_LAT}, {DEFAULT_LNG}),
					mapTypeId: 'roadmap'
				});

				var infowindow = new google.maps.InfoWindow();

				var marker, i;
				var markers = new Array();

				for (i = 0; i < stadiums.length; i++) {
					marker = new google.maps.Marker({
						position: new google.maps.LatLng(stadiums[i][0], stadiums[i][1]),
						map: map,
						icon: stadiums[i][3]
					});

					markers.push(marker);
					# IF C_MORE_THAN_ONE_CLUB #
						google.maps.event.addListener(marker, 'click', (function(marker, i) {
							return function() {
								infowindow.setContent('<h5><a href="' + stadiums[i][4] + '">' + stadiums[i][2] + '</a></h5>{@stadium.lat}: ' + stadiums[i][5] + '<br />{@stadium.lng}: ' + stadiums[i][6]);
								infowindow.open(map, marker);
							}
						})(marker, i));
					# ELSE #
						google.maps.event.trigger(marker, 'click');
					# ENDIF #

					function AutoCenter() {
						var bounds = new google.maps.LatLngBounds();
						jQuery.each(markers, function (index, marker) {
							bounds.extend(marker.position);
						});
						# IF C_MORE_THAN_ONE_CLUB #map.fitBounds(bounds);# ELSE #map.setCenter(bounds.getCenter());# ENDIF #
					}
					AutoCenter();
				}
			-->
			</script>
		# ENDIF #
	# ENDIF #
# ENDIF #
