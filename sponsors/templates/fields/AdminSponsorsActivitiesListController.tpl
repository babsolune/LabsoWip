<script>
<!--
var SponsorsFormFieldActivity = function(){
	this.integer = {NEXT_ID};
	this.max_input = {MAX_INPUT};
};

SponsorsFormFieldActivity.prototype = {
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.integer;

			jQuery('<div/>', {'id' : id}).appendTo('#activities_list');

			jQuery('<input/> ', {type : 'text', id : 'field_name_' + id, class : 'field-large', name : 'field_name_' + id, placeholder : '{name}'}).appendTo('#' + id);
			jQuery('#activities-list' + id).append(' ');

			jQuery('<a/> ', {id : 'delete_' + id, onclick : 'SponsorsFormFieldActivity.delete_activity(' + id + ');return false;', title : ${escapejs(LangLoader::get_message('delete', 'common'))}}).html('<i class="fa fa-delete"></i>').appendTo('#activities_list' + id);

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

var SponsorsFormFieldActivity = new SponsorsFormFieldActivity();
-->
</script>

<div id="input_fields_${escape(ID)}">
# START fieldelements #
		<div id="${escape(ID)}_{fieldelements.ID}">
			<input type="text" name="field_name_${escape(ID)}_{fieldelements.ID}" id="field_name_${escape(ID)}_{fieldelements.ID}" class="field-large" value="{fieldelements.NAME}" placeholder="{name}"/>
			<a href="javascript:SponsorsFormFieldActivity.delete_field({fieldelements.ID});" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
		</div>
# END fieldelements #
</div>
<a href="SponsorsFormFieldActivity.add_activity();" id="add-activity" title="${LangLoader::get_message('add', 'common')}"><i class="fa fa-plus"></i></a>
