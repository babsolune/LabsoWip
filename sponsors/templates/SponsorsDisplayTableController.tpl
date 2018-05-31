<section id="sponsors-module">
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('sponsors', id_category))}" title="${LangLoader::get_message('syndication', 'common')}"><i class="fa fa-syndication"></i></a>
			# IF C_PENDING #{@sponsors.pending.items}# ELSE #{@sponsors.module.title}# ENDIF # # IF C_CATEGORY ## IF IS_ADMIN #<a href="{U_EDIT_CATEGORY}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit smaller"></i></a># ENDIF ## ENDIF #
		</h1>
	</header>
	<div class="spacer"></div>
		# IF C_NO_ITEM_AVAILABLE #
			# IF NOT C_HIDE_NO_ITEM_MESSAGE #
				<div class="center">
					${LangLoader::get_message('no_item_now', 'common')}
				</div>
			# ENDIF #
		# ELSE #
		<table>
			<thead>
				<tr>
					<th>{@sponsors.items}</th>
					# IF C_PENDING #<th>{@sponsors.publication.author}</th># ENDIF #
					<th>{@sponsors.publication.date}</th>
					<th>{@sponsors.publication.status}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				# START items #
					<tr>
						<td><a href="{items.U_ITEM}">{items.TITLE}</a></td>
						# IF C_PENDING #<td>{items.PSEUDO}</td># ENDIF #
						<td>{items.DATE}</td>
						<td>{items.STATUS}</td>
						<td>
							# IF items.C_EDIT #
								<a href="{items.U_EDIT_ITEM}" title="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit"></i></a>
							# ENDIF #
							# IF items.C_DELETE #
								<a href="{items.U_DELETE_ITEM}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
							# ENDIF #
						</td>
					</tr>
				# END items #
			</tbody>
		</table>
		# ENDIF #

	<div class="spacer"></div>
	<footer></footer>
</section>
