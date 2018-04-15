<section id="module-clubs">
	<header>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			{@module_title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF # # IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF #
		</h1>
	</header>
	<div class="content">
		# IF NOT C_VISIBLE #
			# INCLUDE NOT_VISIBLE_MESSAGE #
		# ENDIF #
		<article id="article-clubs-{ID}" itemscope="itemscope" itemtype="http://schema.org/CreativeWork" class="article-clubs# IF C_IS_PARTNER # content-friends# ENDIF ## IF C_IS_PRIVILEGED_PARTNER # content-privileged-friends# ENDIF ## IF C_NEW_CONTENT # new-content# ENDIF#">
			<header>
				<h2>
					<span id="name" itemprop="name">{NAME}</span>
					<span class="actions">
						# IF C_EDIT #
							<a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
						# ENDIF #
						# IF C_DELETE #
							<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
						# ENDIF #
					</span>
				</h2>

				<meta itemprop="url" content="{U_LINK}">
				<meta itemprop="description" content="${escape(DESCRIPTION)}" />
				# IF C_COMMENTS_ENABLED #
				<meta itemprop="discussionUrl" content="{U_COMMENTS}">
				<meta itemprop="interactionCount" content="{NUMBER_COMMENTS} UserComments">
				# ENDIF #

			</header>
			<div class="content">
				<div class="options infos">
					# IF C_LOGO #
					<p class="clubs-logo center">
						<img src="{U_LOGO}" alt="{NAME}" itemprop="image" />
					</p>
					# ENDIF #
					<div class="center">
						# IF C_VISIBLE #
							# IF C_VISIT #
								<a href="{U_VISIT}" # IF C_NEW_WINDOW #target="_blank" rel="noopener noreferrer"# ENDIF # class="basic-button">
									<i class="fa fa-globe"></i> {@visit}
								</a>
								# IF IS_USER_CONNECTED #
									<a href="{U_DEADLINK}" class="basic-button alt" title="${LangLoader::get_message('deadlink', 'common')}">
										<i class="fa fa-unlink"></i>
									</a>
								# ENDIF #
							# ELSE #
								{@no.website}
							# ENDIF #
						# ENDIF #
					</div>
					<h6>{@link_infos}</h6>
					<span class="text-strong">{@visits_number} : </span><span>{NUMBER_VIEWS}</span><br/>
					<span class="text-strong">${LangLoader::get_message('category', 'categories-common')} : </span><span><a itemprop="about" class="small" href="{U_CATEGORY}">{CATEGORY_NAME}</a></span><br/>
					# IF C_COMMENTS_ENABLED #
						<span># IF C_COMMENTS # {NUMBER_COMMENTS} # ENDIF # {L_COMMENTS}</span>
					# ENDIF #
					# IF C_VISIBLE #
						# IF C_NOTATION_ENABLED #
							<div class="spacer"></div>
							<div class="center">{NOTATION}</div>
						# ENDIF #
					# ENDIF #
				</div>

				<div itemprop="text">

					<h6>{@clubs.district} :</h6>
					# IF C_DISTRICT #
						<p>{DISTRICT}</p>
					# ENDIF #

					<h6>{@clubs.headquarter.address} :</h6>
					# IF C_LOCATION #
						# START location #
							<p>{location.STREET_NUMBER}# IF C_STREET_NUMBER #,# ENDIF # {location.ROUTE} </p>
							<p>{location.POSTAL_CODE} {location.CITY}</p>
						# END location #
					# ENDIF #

					<h6>{@clubs.colors} :</h6>
					# IF C_COLORS #
						# START colors #
							<div class="club-colors" style="background-color: {colors.COLOR}"></div>
						# END colors #
					# ENDIF #
					<h6>{@clubs.contact} :</h6>
					# IF C_PHONE #
						<p><i class="fa fa-fw fa-phone fa-2x"></i> {PHONE}</p>
					# ENDIF #
					# IF C_EMAIL #
						<a href="mailto:{EMAIL}" title="{@clubs.labels.email}"><i class="fa fa-fw fa-envelope-o fa-2x"></i></a>
					# ENDIF #
					# IF C_FACEBOOK #
						<a href="{U_FACEBOOK}" title="{@clubs.labels.facebook}" # IF C_NEW_WINDOW #target="_blank" rel="noopener noreferrer"# ENDIF #><i class="fa fa-fw fa-facebook fa-2x"></i></a>
					# ENDIF #
					# IF C_TWITTER #
						<a href="{U_TWITTER}" title="{@clubs.labels.twitter}" # IF C_NEW_WINDOW #target="_blank" rel="noopener noreferrer"# ENDIF #><i class="fa fa-fw fa-twitter fa-2x"></i></a>
					# ENDIF #
					# IF C_GPLUS #
						<a href="{U_GPLUS}" title="{@clubs.labels.gplus}" # IF C_NEW_WINDOW #target="_blank" rel="noopener noreferrer"# ENDIF #><i class="fa fa-fw fa-google-plus fa-2x"></i></a>
					# ENDIF #

					# IF C_CONTENTS #
						<h6>{@clubs.description} :</h6>
						{CONTENTS}
					# ENDIF #
				</div>
				<div class="spacer"></div>
			</div>
			# IF C_GMAP_ENABLED #
				# IF C_DEFAULT_ADDRESS #
					# IF C_STADIUM_LOCATION #
						<div class="fixed-top">
							<div id="gmap"></div>
						</div>
						<div id="panel"></div>
						<h5>{@stadium.gps} :</h5>
						<p>
							{@stadium.lat} : {STAD_LAT} / {LATITUDE}
						<br />{@stadium.lng} : {STAD_LNG} / {LONGITUDE}
						</p>
					# ELSE #
						${LangLoader::get_message('clubs.no.gps', 'common', 'clubs')}
					# ENDIF #
				# ELSE #
					${LangLoader::get_message('clubs.no.default.address', 'common', 'clubs')}
				# ENDIF #
			# ELSE #
				${LangLoader::get_message('clubs.no.gmap', 'common', 'clubs')}
			# ENDIF #
			<aside>
				# INCLUDE COMMENTS #
			</aside>
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>

# IF C_GMAP_ENABLED #
	# IF C_DEFAULT_ADDRESS #
		# IF C_STADIUM_LOCATION #
			<script src="https://maps.googleapis.com/maps/api/js?key={GMAP_API_KEY}"></script>
			<script src="{PATH_TO_ROOT}/clubs/templates/js/sticky.js"></script>
			<script>
			<!--
				jQuery(function(){
					jQuery('.fixed-top').sticky();
				});
			--></script>

			<script>
			<!--
				var club = {lat: {LATITUDE}, lng: {LONGITUDE}};

				var map = new google.maps.Map(document.getElementById('gmap'), {
				  	zoom: 10,
				  	center: club,
					mapTypeId: 'roadmap'
				});

				var panel = document.getElementById('panel');
					origin = {lat: {DEFAULT_LATITUDE}, lng: {DEFAULT_LONGITUDE}}

				calculate = function(){
					origin      = origin
					destination = club; // Le point d'arrivé
					if(origin && destination){
						var request = {
							origin      : origin,
							destination : destination,
							provideRouteAlternatives: true,
							// avoidTolls: true,
							travelMode  : google.maps.DirectionsTravelMode.DRIVING, // Mode de conduite
						}
						direction = new google.maps.DirectionsRenderer({
							draggable: true,
							map: map,
							panel: panel
						});
						var directionsService = new google.maps.DirectionsService(); // Service de calcul d'itinéraire
						directionsService.route(request, function(response, status){ // Envoie de la requête pour calculer le parcours
							if(status == google.maps.DirectionsStatus.OK){
								direction.setDirections(response); // Trace l'itinéraire sur la carte et les différentes étapes du parcours
							}
						});
					}
				};

				calculate();
			-->
			</script>
		# ENDIF #
	# ENDIF #
# ENDIF #
