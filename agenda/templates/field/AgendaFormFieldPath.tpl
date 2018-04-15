<script>
<!--
var AgendaFormFieldPath = function(){
	this.integer = {NBR_FIELDS};
	this.id_input = ${escapejs(ID)};
	this.max_input = {MAX_INPUT};
};

AgendaFormFieldPath.prototype = {
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

			jQuery('<div/>', {'id' : id}).appendTo('#input_fields_' + this.id_input);

			jQuery('<select/> ', {class : 'body-4', name : 'field_path_type_' + id, id : 'field_path_type_' + id}).appendTo('#' + id);
			jQuery('<option/> ', {value : '', text : '{@agenda.option.path.type}'}).appendTo('#field_path_type_' + id);
			jQuery('<option/> ', {value : '1', text : '{@agenda.option.path.type.dirt.cycle}'}).appendTo('#field_path_type_' + id);
			jQuery('<option/> ', {value : '2', text : '{@agenda.option.path.type.walk}'}).appendTo('#field_path_type_' + id);
			jQuery('<option/> ', {value : '3', text : '{@agenda.option.path.type.trail}'}).appendTo('#field_path_type_' + id);
			jQuery('<option/> ', {value : '4', text : '{@agenda.option.path.type.road.cycle}'}).appendTo('#field_path_type_' + id);
			jQuery('<option/> ', {value : '5', text : '{@agenda.option.path.type.horse}'}).appendTo('#field_path_type_' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {class : 'body-4', type : 'number', id : 'field_path_length_' + id, name : 'field_path_length_' + id, placeholder : '{@agenda.placeholder.path.length}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {class : 'body-4', type : 'number', id : 'field_path_elevation_' + id, name : 'field_path_elevation_' + id, placeholder : '{@agenda.placeholder.path.elevation}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<select/> ', {class : 'body-4', name : 'field_path_level_' + id, id : 'field_path_level_' + id}).appendTo('#' + id);
			jQuery('<option/> ', {value : '', text : '{@agenda.option.path.level}'}).appendTo('#field_path_level_' + id);
			jQuery('<option/> ', {value : '1', text : '{@agenda.placeholder.path.start}'}).appendTo('#field_path_level_' + id);
			jQuery('<option/> ', {value : '2', text : '{@agenda.placeholder.path.medium}'}).appendTo('#field_path_level_' + id);
			jQuery('<option/> ', {value : '3', text : '{@agenda.placeholder.path.sport}'}).appendTo('#field_path_level_' + id);
			jQuery('<option/> ', {value : '4', text : '{@agenda.placeholder.path.expert}'}).appendTo('#field_path_level_' + id);
			jQuery('#' + id).append(' ');

			jQuery('<a/> ', {href : 'javascript:AgendaFormFieldPath.delete_field('+ this.integer +');'}).html('<i class="fa fa-delete"></i>').appendTo('#' + id);

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

var AgendaFormFieldPath = new AgendaFormFieldPath();
-->
</script>

<div id="input_fields_${escape(ID)}">
# START fieldelements #
	<div id="${escape(ID)}_{fieldelements.ID}">
		<div class="col-4">
			<span>{@agenda.option.path.type}</span><br />
			<select name="field_path_type_${escape(ID)}_{fieldelements.ID}" id="field_path_type_${escape(ID)}_{fieldelements.ID}">
				<option value="{fieldelements.PATH_TYPE}">{fieldelements.PATH_TYPE}</option>
				<option value="1">{@agenda.option.path.type.dirt.cycle}</option>
				<option value="2">{@agenda.option.path.type.walk}</option>
				<option value="3">{@agenda.option.path.type.trail}</option>
				<option value="4">{@agenda.option.path.type.road.cycle}</option>
				<option value="5">{@agenda.option.path.type.horse}</option>
			</select>
		</div>
		<div class="col-4">
			<span>{@agenda.placeholder.path.length}</span><br />
			<input type="number" name="field_path_length_${escape(ID)}_{fieldelements.ID}" id="field_path_length_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.PATH_LENGTH}" />
		</div>
		<div class="col-4">
			<span>{@agenda.placeholder.path.elevation}</span><br />
			<input type="number" name="field_path_elevation_${escape(ID)}_{fieldelements.ID}" id="field_path_elevation_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.PATH_ELEVATION}" />
		</div>
		<div class="col-4">
			<span>{@agenda.option.path.level}</span><br />
			<select name="field_path_level_${escape(ID)}_{fieldelements.ID}" id="field_path_level_${escape(ID)}_{fieldelements.ID}">
				<option value="{fieldelements.PATH_LEVEL}">{fieldelements.PATH_LEVEL}</option>
				<option value="1">{@agenda.placeholder.path.start}</option>
				<option value="2">{@agenda.placeholder.path.medium}</option>
				<option value="3">{@agenda.placeholder.path.sport}</option>
				<option value="4">{@agenda.placeholder.path.expert}</option>
			</select>
		</div>
		<a href="javascript:AgendaFormFieldPath.delete_field({fieldelements.ID});" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
	</div>
# END fieldelements #
</div>
<a href="javascript:AgendaFormFieldPath.add_field();" id="add-${escape(ID)}"><i class="fa fa-plus"></i></a>
