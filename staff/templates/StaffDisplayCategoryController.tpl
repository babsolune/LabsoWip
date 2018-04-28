<section id="module-staff">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('staff', ID_CAT))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #{@staff.pending}# ELSE #{@staff.module.title}# IF NOT C_ROOT_CATEGORY # - {CATEGORY_NAME}# ENDIF ## ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
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
					<span class="small">{sub_categories_list.MEMBERS_NUMBER} # IF sub_categories_list.C_MORE_THAN_ONE_MEMBER #${TextHelper::lcfirst(LangLoader::get_message('members', 'common', 'staff'))}# ELSE #${TextHelper::lcfirst(LangLoader::get_message('member', 'common', 'staff'))}# ENDIF #</span>
				</div>
			</div>
			# END sub_categories_list #
			<div class="spacer"></div>
		</div>
		# IF C_SUBCATEGORIES_PAGINATION #<span class="center"># INCLUDE SUBCATEGORIES_PAGINATION #</span># ENDIF #
	# ELSE #
		<div class="spacer"></div>
	# ENDIF #

	# IF C_MEMBERS #
		# IF C_MORE_THAN_ONE_MEMBER #
			<div class="spacer"></div>
		# ENDIF #
		<ul class="member-table show-table">
			# START members #
				<li>
					<div class="li-avatar">
						# IF C_AVATARS_ALLOWED #
							<img src="{members.U_PICTURE}" alt="{members.FIRSTNAME} {members.LASTNAME}" />
						# ENDIF #
					</div>
					<div class="li-infos">
						<div class="li-title">
							<div class="li-table li-leader">
								# IF members.C_IS_GROUP_LEADER # <i class="fa fa-user" title="{@staff.form.group.leader}"></i># ENDIF #
							</div>
							<div class="li-table li-member"><a href="{members.U_MEMBER}" itemprop="name">{members.FIRSTNAME} <span class="member-name">{members.LASTNAME}</span></a></div>
						</div>
						<div class="li-options# IF C_MODERATE # moderator# ENDIF #">
							# IF C_PENDING #
								<div class="li-table li-role">{members.ROLE}</div>
								<div class="li-table li-phone">{members.CATEGORY_NAME}</div>
							# ELSE #
								<div class="li-table li-role">{members.ROLE}</div>
								<div class="li-table li-phone">
									# IF members.C_MEMBER_PHONE #
										<span class="show-phone">{members.MEMBER_PHONE}</span>
										<span class="hide-phone">{@reveal.member.phone}</span>
									# ENDIF #
								</div>
							# ENDIF #
						</div>
						# IF C_MODERATE #
							<div class="moderate">
								<a href="{members.U_EDIT}"><i class="fa fa-edit fa-fw"></i></a>
								<a href="{members.U_DELETE}"><i class="fa fa-trash fa-fw"></i></a>
							</div>
						# ENDIF #
					</div>
				</li>
			# END members #
		</ul>
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

<script>
	jQuery(document).ready(function () {
		//reveal phone nb
		jQuery('.show-phone').hide();
		jQuery('.hide-phone').show();
		jQuery('.li-phone').click(function(){
			jQuery(this).toggleClass('is-revealed');
			jQuery('.show-phone').toggle();
			jQuery('.hide-phone').toggle();
		});
	});
</script>
