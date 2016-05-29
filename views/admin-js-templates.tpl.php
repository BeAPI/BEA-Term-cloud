<script type="text/template" id="bea-vc-tp-element">
	<div class="type-wrapper type-content">
		<div class="vc_row-fluid wpb_el_type_dropdown">
			<select data-name="taxonomies" class="bea_vc_tp_taxonomy" id="bea_vc_tp_taxonomy_<%- id %>">
				<% _.each( taxonomies, function( taxonomy ) { %>
					<option value="<%= taxonomy.name %>" <%= fr.vc.tp.tools.selected(_.contains( model.get( 'taxonomies' ), taxonomy.name ), true ) %> > <%= taxonomy.label %> </option>
				<% } ); %>
			</select>
		</div>
	</div>
</script>