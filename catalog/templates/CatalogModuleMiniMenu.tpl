<table id="table-mini-catalog">
	<thead>
		<tr>
			<th><i class="fa # IF C_SORT_BY_DATE #fa-calendar# ELSE #fa-trophy# ENDIF #"></i></th>
			<th>${LangLoader::get_message('form.name', 'common')}</th>
			# IF NOT C_SORT_BY_DATE #
			<th><i class="fa # IF C_SORT_BY_NUMBER_DOWNLOADS #fa-catalog# ELSE #fa-star-o# ENDIF #"></i></th>
			# ENDIF #
		</tr>
	</thead>
	<tbody>
	# IF C_PRODUCTS #
		# START products #
		<tr>
			<td># IF C_SORT_BY_DATE #<time datetime="{products.DATE_ISO8601}">{products.DATE_DAY_MONTH}</time># ELSE #{products.DISPLAYED_POSITION}# ENDIF #</td>
			<td # IF C_SORT_BY_NOTATION #class="mini-catalog-table-name"# ENDIF #>
				<a href="{products.U_LINK}" title="{@most_downloaded_products} {products.DISPLAYED_POSITION} : {products.NAME}">
					{products.NAME}
				</a>
			</td>
			# IF NOT C_SORT_BY_DATE #
			<td># IF C_SORT_BY_NUMBER_DOWNLOADS #{products.NUMBER_DOWNLOADS}# ELSE #{products.STATIC_NOTATION}# ENDIF #</td>
			# ENDIF #
		</tr>
		# END products #
	# ELSE #
		<tr>
			<td colspan="# IF C_SORT_BY_DATE #2# ELSE #3# ENDIF #">${LangLoader::get_message('no_item_now', 'common')}</td>
		</tr>
	# ENDIF #
	</tbody>
</table>

<script>
	jQuery('#table-mini-catalog').basictable();
</script>
