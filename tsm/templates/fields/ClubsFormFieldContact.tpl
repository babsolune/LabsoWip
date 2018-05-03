<script>
<!--
var ClubsFormFieldContact = function(){
	this.integer = {NBR_FIELDS};
	this.id_input = ${escapejs(ID)};
	this.max_input = {MAX_INPUT};
};

ClubsFormFieldContact.prototype = {
	add_field : function () {
		if (this.integer <= this.max_input) {
			var id = this.id_input + '_' + this.integer;

			jQuery('<div/>', {'id' : id}).appendTo('#input_fields_' + this.id_input);

			jQuery('<input/> ', {type : 'text', id : 'field_contact_name_' + id, name : 'field_contact_name_' + id, placeholder : '{@club.placeholder.name}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'email', id : 'field_contact_email_' + id, name : 'field_contact_email_' + id, placeholder : '{@club.placeholder.email}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'tel', id : 'field_contact_phone1_' + id, name : 'field_contact_phone1_' + id, placeholder : '{@club.placeholder.phone1}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'tel', id : 'field_contact_phone2_' + id, name : 'field_contact_phone2_' + id, placeholder : '{@club.placeholder.phone2}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<input/> ', {type : 'url', id : 'field_contact_site_' + id, name : 'field_contact_site_' + id, placeholder : '{@club.placeholder.contact.form}'}).appendTo('#' + id);
			jQuery('#' + id).append(' ');

			jQuery('<a/> ', {href : 'javascript:ClubsFormFieldContact.delete_field('+ this.integer +');'}).html('<i class="fa fa-delete"></i>').appendTo('#' + id);

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

var ClubsFormFieldContact = new ClubsFormFieldContact();
-->
</script>

<div id="input_fields_${escape(ID)}">
# START fieldelements #
		<div id="${escape(ID)}_{fieldelements.ID}">
			<input type="text" name="field_contact_name_${escape(ID)}_{fieldelements.ID}" id="field_contact_name_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.CONTACT_NAME}" placeholder="{@club.placeholder.name}"/>
			<input type="email" name="field_contact_email_${escape(ID)}_{fieldelements.ID}" id="field_contact_email_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.CONTACT_EMAIL}" placeholder="{@club.placeholder.email}"/>
			<input type="tel" name="field_contact_phone1_${escape(ID)}_{fieldelements.ID}" id="field_contact_phone1_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.CONTACT_PHONE1}" placeholder="{@club.placeholder.phone1}"/>
			<input type="tel" name="field_contact_phone2_${escape(ID)}_{fieldelements.ID}" id="field_contact_phone2_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.CONTACT_PHONE2}" placeholder="{@club.placeholder.phone2}"/>
			<input type="url" name="field_contact_site_${escape(ID)}_{fieldelements.ID}" id="field_contact_site_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.CONTACT_SITE}" placeholder="{@club.placeholder.contact.form}"/>
			<a href="javascript:ClubsFormFieldContact.delete_field({fieldelements.ID});" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
		</div>
# END fieldelements #
</div>
<a href="javascript:ClubsFormFieldContact.add_field();" id="add-${escape(ID)}"><i class="fa fa-plus"></i></a>
