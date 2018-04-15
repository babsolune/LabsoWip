<script src="http://maps.googleapis.com/maps/api/js?key={GMAP_API_KEY}&amp;libraries=places"></script>
<script src="{PATH_TO_ROOT}/agenda/templates/js/jquery.geocomplete.js"></script>

<script>
<!--
var AgendaFormFieldLocation = function(){
	this.integer = {NBR_FIELDS};
	this.id_input = ${escapejs(ID)};
	this.max_input = {MAX_INPUT};
};

AgendaFormFieldLocation.prototype = {
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

			jQuery('<div/>', {'id' : id}).appendTo('#input_fields_' + this.id_input);

			jQuery('<div/> ', {class : 'map-location_' + id}).appendTo('#' + id);

			jQuery('<input/> ', {type : 'text', class : 'gmap-autocomplete', id : 'geocomplete_' + id, placeholder : '{@agenda.labels.enter_address}'}).appendTo('.map-location_' + id);

			jQuery('<div/> ', {class : 'location-datas_' + id}).appendTo('.map-location_' + id);

			jQuery('<label/> ', {text : '{@agenda.labels.street_address} : '}).appendTo('.location-datas_' + id);

			jQuery('<input/> ', {type : 'text', 'data-location' : 'street_number', id : 'field_street_number_' + id, name : 'field_street_number_' + id, class : 'gmap-street-number'}).appendTo('.location-datas_' + id);
			jQuery('.location-datas_' + id).append(' ');

			jQuery('<input/> ', {type : 'text', 'data-location' : 'route', id : 'field_route_' + id, name : 'field_route_' + id}).appendTo('.location-datas_' + id);
			jQuery('.location-datas_' + id).append(' ');

			jQuery('<label/> ', {text : '{@agenda.labels.city} : '}).appendTo('.location-datas_' + id);

			jQuery('<input/> ', {type : 'text', 'data-location' : 'locality', id : 'field_city_' + id, name : 'field_city_' + id}).appendTo('.location-datas_' + id);
			jQuery('.location-datas_' + id).append(' ');

			jQuery('<label/> ', {text : '{@agenda.labels.state} : '}).appendTo('.location-datas_' + id);

			jQuery('<input/> ', {type : 'text', 'data-location' : 'administrative_area_level_1', id : 'field_state_' + id, name : 'field_state_' + id}).appendTo('.location-datas_' + id);
			jQuery('.location-datas_' + id).append(' ');

			jQuery('<label/> ', {text : '{@agenda.labels.postal_code} : '}).appendTo('.location-datas_' + id);

			jQuery('<input/> ', {type : 'text', 'data-location' : 'postal_code', id : 'field_postal_code_' + id, name : 'field_postal_code_' + id}).appendTo('.location-datas_' + id);
			jQuery('.location-datas_' + id).append(' ');

			jQuery('<label/> ', {text : '{@agenda.labels.department} : '}).appendTo('.location-datas_' + id);

			jQuery('<input/> ', {type : 'text', 'data-location' : 'administrative_area_level_2', id : 'field_department_' + id, name : 'field_department_' + id}).appendTo('.location-datas_' + id);
			jQuery('.location-datas_' + id).append(' ');

			jQuery('<label/> ', {text : '{@agenda.labels.country} : '}).appendTo('.location-datas_' + id);

			jQuery('<input/> ', {type : 'text', 'data-location' : 'country', id : 'field_country_' + id, name : 'field_country_' + id}).appendTo('.location-datas_' + id);
			jQuery('.location-datas_' + id).append(' ');

			jQuery('<script/>').html('jQuery(function(){ jQuery("#geocomplete_' + id + '").geocomplete({ details: ".map-location_' + id + '", detailsAttribute: "data-location", types: ["geocode", "establishment"] }); });').appendTo('#' + id);

			jQuery('<a/> ', {href : 'javascript:AgendaFormFieldLocation.delete_field('+ this.integer +');'}).html('<i class="fa fa-delete"></i>').appendTo('#' + id);

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

var AgendaFormFieldLocation = new AgendaFormFieldLocation();
-->
</script>

<div id="input_fields_${escape(ID)}">
# START fieldelements #
	<div class="map-location_${escape(ID)}_{fieldelements.ID}">
		<input id="geocomplete_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.CITY} {fieldelements.COUNTRY}" class="gmap-autocomplete" placeholder="{@agenda.labels.enter_address}" type="text"/>
	 	<div class="location-datas_${escape(ID)}_{fieldelements.ID}">
	 		<label>{@agenda.labels.street_address} :</label>
			<input data-location="street_number" value="{fieldelements.STREET_NUMBER}" name="field_street_number_${escape(ID)}_{fieldelements.ID}" class="gmap-street-number" id="field_street_number_${escape(ID)}_{fieldelements.ID}" type="text"/>
	 	    <input data-location="route" value="{fieldelements.ROUTE}" name="field_route_${escape(ID)}_{fieldelements.ID}" class="gmap-route" id="field_route_${escape(ID)}_{fieldelements.ID}" type="text"/>
	 	    <label>{@agenda.labels.city} :</label>
			<input data-location="locality" value="{fieldelements.CITY}" name="field_city_${escape(ID)}_{fieldelements.ID}" id="field_city_${escape(ID)}_{fieldelements.ID}" type="text"/>
	 	    <label>{@agenda.labels.state} :</label>
			<input data-location="administrative_area_level_1" value="{fieldelements.STATE}" name="field_state_${escape(ID)}_{fieldelements.ID}" id="field_state_${escape(ID)}_{fieldelements.ID}" type="text"/>
	 	    <label>{@agenda.labels.postal_code} :</label>
			<input data-location="postal_code" value="{fieldelements.POSTAL_CODE}" name="field_postal_code_${escape(ID)}_{fieldelements.ID}" id="field_postal_code_${escape(ID)}_{fieldelements.ID}" type="text"/>
	 	    <label>{@agenda.labels.department} :</label>
			<input data-location="administrative_area_level_2" value="{fieldelements.DEPARTMENT}" name="field_department_${escape(ID)}_{fieldelements.ID}" id="field_department_${escape(ID)}_{fieldelements.ID}" type="text"/>
	 	    <label>{@agenda.labels.country} :</label>
			<input data-location="country" value="{fieldelements.COUNTRY}" name="field_country_${escape(ID)}_{fieldelements.ID}" id="field_country_${escape(ID)}_{fieldelements.ID}" type="text"/>
	 	</div>
	</div>

    <script>
      $(function(){
        $("#geocomplete_${escape(ID)}_{fieldelements.ID}").geocomplete({
          details: ".map-location_${escape(ID)}_{fieldelements.ID}",
	      detailsAttribute: "data-location",
          types: ["geocode", "establishment"],
        });
      });
    </script>

	<a class="disp-none" href="javascript:AgendaFormFieldLocation.delete_field({fieldelements.ID});" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>

# END fieldelements #
</div>
<a class="disp-none" href="javascript:AgendaFormFieldLocation.add_field();" id="add-${escape(ID)}"><i class="fa fa-plus"></i></a>
