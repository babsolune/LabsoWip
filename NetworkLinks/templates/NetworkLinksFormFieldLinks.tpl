<script>
<!--
var NetworkLinksFormFieldLinks = function(){
	this.integer = {NBR_FIELDS};
	this.id_input = ${escapejs(ID)};
	this.max_input = {MAX_INPUT};
};

NetworkLinksFormFieldLinks.prototype = {
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

			jQuery('<div/>', {'id' : id}).appendTo('#input_fields_' + this.id_input);

			jQuery('<input/> ', {type : 'text', id : 'field_link_name_' + id, name : 'field_link_name_' + id, placeholder : '{@nl.placeholder.name}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'url', id : 'field_link_url_' + id, name : 'field_link_url_' + id, placeholder : '{@nl.placeholder.url}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'text', id : 'field_fa_link_' + id, name : 'field_fa_link_' + id, placeholder : '{@nl.placeholder.fa}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'text', id : 'field_img_link_' + id, name : 'field_img_link_' + id, placeholder : '{@nl.placeholder.img}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<a/> ', {href : '', title : '${LangLoader::get_message('files_management', 'main')}', class : 'fa fa-cloud-upload fa-2x', onclick : "window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&fd=field_url_" + id + "&parse=true&no_path=true', '', 'height=500,width=720,resizable=yes,scrollbars=yes');return false;"}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<a/> ', {href : 'javascript:NetworkLinksFormFieldLinks.delete_field('+ this.integer +');'}).html('<i class="fa fa-delete"></i>').appendTo('#' + id);

			this.integer++;
		}
		if (this.integer == this.max_input) {
			jQuery('#add-' + this.id_input).hide();
		}
	},
	delete_field : function (id) {
		var id = this.id_input + '_' + id;
		jQuery('#' + id).remove();
		this.integer--;
		jQuery('#add-' + this.id_input).show();
	}
};

var NetworkLinksFormFieldLinks = new NetworkLinksFormFieldLinks();
-->
</script>

<div id="input_fields_${escape(ID)}">
# START fieldelements #
		<div id="${escape(ID)}_{fieldelements.ID}">
			<input type="text" name="field_link_name_${escape(ID)}_{fieldelements.ID}" id="field_link_name_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.LINK_NAME}" placeholder="{@nl.placeholder.name}"/>
			<input type="url" name="field_link_url_${escape(ID)}_{fieldelements.ID}" id="field_link_url_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.LINK_URL}" placeholder="{@nl.placeholder.url}"/>
			<input type="text" name="field_fa_link_${escape(ID)}_{fieldelements.ID}" id="field_fa_link_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.FA_LINK}" placeholder="{@nl.placeholder.fa}"/>
			<input type="text" name="field_img_link_${escape(ID)}_{fieldelements.ID}" id="field_img_link_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.IMG_LINK}" placeholder="{@nl.placeholder.img}" />
			<a title="${LangLoader::get_message('files_management', 'main')}" href="" class="fa fa-cloud-upload fa-2x" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&fd=field_url_${escape(ID)}_{fieldelements.ID}&parse=true&no_path=true', '', 'height=500,width=720,resizable=yes,scrollbars=yes');return false;"></a>
			<a href="javascript:NetworkLinksFormFieldLinks.delete_field({fieldelements.ID});" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
		</div>
# END fieldelements #
</div>
<a href="javascript:NetworkLinksFormFieldLinks.add_field();" id="add-${escape(ID)}"><i class="fa fa-plus"></i></a>
