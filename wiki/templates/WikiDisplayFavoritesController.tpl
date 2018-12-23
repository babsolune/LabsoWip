# INCLUDE message_helper #

<section>
	<header>
		<h1>{@wiki.favorites.items}</h1>
	</header>
	<article>
		# IF NO_FAVORITE #
			<div class="message-helper notice">{@wiki.no.favorite}</div>
		# ELSE #

			<table id="table">
				<thead>
					<tr>
						<th>
							{@wiki.document.title}
						</th>
						<th>
							{@wiki.untrack.title}
						</th>
					</tr>
				</thead>
				<tbody>
					# START items #
					<tr>
						<td>
							<a href="{items.U_DOCUMENT}">{items.TITLE}</a>
						</td>
						<td>
							<a href="{items.ACTIONS}">
								<span class="fa-broken-heart fa-stack" title="{@wiki.untrack.document}">
									<i class="fa fa-heart fa-stack-1x"></i>
									<i class="fa fa-bolt fa-stack-1x"></i>
								</span>
							</a>
						</td>
					</tr>
					# END items #
				</tbody>
			</table>
		# ENDIF #
	</article>
</section>
