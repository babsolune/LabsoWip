<section id="module-palmares">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('palmares', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING_PALMARES #{@palmares.pending}# ELSE #{@palmares}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
		</h1>
	</header>
	<div class="content">
	# IF C_PALMARES_NO_AVAILABLE #
		<div class="center">
			${LangLoader::get_message('no_item_now', 'common')}
		</div>
	# ELSE #
		# IF C_DISPLAY_BLOCK_TYPE #
			# START palmares #
				<article id="article-palmares-{palmares.ID}" class="article-palmares article-several# IF C_SEVERAL_COLUMNS # inline-block# ENDIF #" # IF C_SEVERAL_COLUMNS # style="width:calc(98% / {NUMBER_COLUMNS})" # ENDIF # itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
					<header>
						<h2>
							<a href="{palmares.U_CATEGORY}" title="{palmares.CATEGORY_NAME}">
								<img itemprop="thumbnailUrl" src="{palmares.CATEGORY_IMAGE}" alt="{palmares.NAME}" title="{palmares.NAME}" class="img-cat" />
							</a>
							<a href="{palmares.U_LINK}"><span itemprop="name">{palmares.NAME}</span></a>
							<span class="actions">
								# IF palmares.C_EDIT #
									<a href="{palmares.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
								# ENDIF #
								# IF palmares.C_DELETE #
									<a href="{palmares.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
								# ENDIF #
							</span>
						</h2>

						<meta itemprop="url" content="{palmares.U_LINK}">
						<meta itemprop="description" content="${escape(palmares.DESCRIPTION)}"/>

					</header>

					<div class="content">
						# IF palmares.C_PICTURE #<img itemprop="thumbnailUrl" src="{palmares.U_PICTURE}" alt="{palmares.NAME}" title="{palmares.NAME}" class="right" /># ENDIF #
						<div itemprop="text"># IF C_DISPLAY_CONDENSED_CONTENT # {palmares.DESCRIPTION}# IF palmares.C_READ_MORE #... <a href="{palmares.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF ## ELSE # {palmares.CONTENTS} # ENDIF #</div>
					</div>

					<footer></footer>
				</article>
			# END palmares #
		# ELSE #
				<div class="cntl">
					<span class="cntl-bar cntl-center">
						<span class="cntl-bar-fill"></span>
					</span>
					<div class="cntl-states">

			# START palmares #
						<div class="cntl-state">
							<div class="cntl-content">
								<h2>
									<a href="{palmares.U_CATEGORY}" title="{palmares.CATEGORY_NAME}">
										<img itemprop="thumbnailUrl" src="{palmares.CATEGORY_IMAGE}" alt="{palmares.NAME}" class="img-cat" />
									</a>
									{palmares.NAME}
									<span class="actions">
										# IF palmares.C_EDIT #
											<a href="{palmares.U_EDIT}" title="${LangLoader::get_message('edit', 'common')}" class="fa fa-edit"></a>
										# ENDIF #
										# IF palmares.C_DELETE #
											<a href="{palmares.U_DELETE}" title="${LangLoader::get_message('delete', 'common')}" class="fa fa-delete" data-confirmation="delete-element"></a>
										# ENDIF #
									</span>
								</h2>

								<p itemprop="text">
									# IF C_DISPLAY_CONDENSED_CONTENT # {palmares.DESCRIPTION}# IF palmares.C_READ_MORE #... <a href="{palmares.U_LINK}">[${LangLoader::get_message('read-more', 'common')}]</a># ENDIF ## ELSE # {palmares.CONTENTS} # ENDIF #
								</p>
							</div>
							<div class="cntl-image">
								# IF palmares.C_PICTURE #
									<img itemprop="thumbnailUrl" src="{palmares.U_PICTURE}" alt="{palmares.NAME}" title="{palmares.NAME}" />
								# ENDIF #
							</div>
							<div class="cntl-icon cntl-center">{palmares.DATE_YEAR}</div>
						</div>
			# END palmares #
					</div>
				</div>
		# ENDIF #
	# ENDIF #
	</div>
	<footer># IF C_PAGINATION # # INCLUDE PAGINATION # # ENDIF #</footer>
</section>
		<script src="{PATH_TO_ROOT}/palmares/templates/js/jquery.cntl.js"></script>
		<script>
			jQuery(document).ready(function(e){
				jQuery('.cntl').cntl({
					revealbefore: 300,
					anim_class: 'cntl-animate',
					onreveal: function(e){
						console.log(e);
					}
				});
			});
		</script>
