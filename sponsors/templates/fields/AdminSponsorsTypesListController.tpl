<script>
<!--
var SponsorsFormFieldType = function(){
	this.integer = {NEXT_ID};
	this.max_input = {MAX_INPUT};
};

SponsorsFormFieldType.prototype = {
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.integer;

			jQuery('<div/>', {'id' : id}).appendTo('#types_list');

			jQuery('<input/> ', {type : 'text', id : 'field_name_' + id, class : 'field-large', name : 'field_name_' + id, placeholder : '{name}'}).appendTo('#' + id);
			jQuery('#types_list' + id).append(' ');

			jQuery('<a/> ', {id : 'delete_' + id, onclick : 'SponsorsFormFieldTypes.delete_type(' + id + ');return false;', title : ${escapejs(LangLoader::get_message('delete', 'common'))}}).html('<i class="fa fa-delete"></i>').appendTo('#types_list' + id);

			this.integer++;
		}
		if (this.integer == this.max_input) {
			jQuery('#add-type' + this.id_input).hide();
		}
		if (this.integer) {
			jQuery('#no-type').hide();
		}
	},
	delete_field : function (id) {
		var id = this.id_input + '_' + id;
		jQuery('#' + id).remove();
		this.integer--;
		jQuery('#add-' + this.id_input).show();
	}
};

var SponsorsFormFieldType = new SponsorsFormFieldType();
-->
</script>

<div id="input_fields_${escape(ID)}">
# START fieldelements #
		<div id="${escape(ID)}_{fieldelements.ID}">
			<input type="text" name="field_name_${escape(ID)}_{fieldelements.ID}" id="field_name_${escape(ID)}_{fieldelements.ID}" class="field-large" value="{fieldelements.NAME}" placeholder="{name}"/>
			<a href="javascript:SponsorsFormFieldType.delete_field({fieldelements.ID});" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
		</div>
# END fieldelements #
</div>
<a href="SponsorsFormFieldType.add_types();" id="add-type" title="${LangLoader::get_message('add', 'common')}"><i class="fa fa-plus"></i></a>
