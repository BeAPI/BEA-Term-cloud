// Object basic
var fr;
if (!fr) {
	fr = {};
} else {
	if (typeof fr !== "object") {
		throw new Error('fr already exists and not an object');
	}
}

if (!fr.vc) {
	fr.vc = {};
} else {
	if (typeof fr.vc !== "object") {
		throw new Error('fr.vc already exists and not an object');
	}
}

if (!fr.vc.tp) {
	fr.vc.tp = {};
} else {
	if (typeof fr.vc.tp !== "object") {
		throw new Error('fr.vc.tp already exists and not an object');
	}
}

fr.vc.tp.tools = {
	selected: function (value, check) {
		"use strict";
		return fr.vc.tp.tools.checked_selected_helper(value, check, 'selected');
	},
	checked: function (value, check) {
		"use strict";
		return fr.vc.tp.tools.checked_selected_helper(value, check, 'checked');
	},
	checked_selected_helper: function (helper, current, type) {
		"use strict";
		return ( helper === current ) ? type + '="' + type + '"' : '';
	},
	extract_post_type_taxonomies: function (post_type) {
		"use strict";
		var out = [];
		_.each(fr.vc.tp.vars.taxonomies, function (taxonomy) {
			if (_.contains(taxonomy.object_type, post_type)) {
				out.push(taxonomy);
			}
		});

		return out;
	}
};

/**
 * Model for the data in the form
 *
 *
 */
fr.vc.tp.Taxonomy = Backbone.Model.extend({
	defaults: {
		post_type: false,
		taxonomies: [],
		terms: [],
	}
});


Backbone.sync = function (method, model, success) {
	"use strict";
	success.success();
};

// Because the new features (swap and delete) are intrinsic to each `Item`, there is no need to modify `ListView`.
fr.vc.tp.Taxonomies = Backbone.View.extend({
	template: jQuery('#bea-vc-tp-element').html(),
	events: {
		// When changing the elements sorting
		'edit-item': 'edit_item',

		// When changing the elements sorting
		'update-sort': 'update_sort',

		// On change select
		'change select': 'changed',

		// changing the post_tyoe
		'change .post_type': 'render'

	},
	initialize: function () {
		"use strict";
		// every function that uses 'this' as the current object should be in here
		_.bindAll(this, 'render', 'update_items_json', 'init_terms_select2', 'init_select2');

		this.field = this.$el.find( '.bea_vc_tp_value' );

		this.render();

		return this;
	},
	render: function (parse_data) {
		"use strict";

		// Parse the content of the field only if needed
		if (parse_data !== false) {
			// Get the main field value
			var self = this, values = base64_decode( this.field.val() );
			// Check there is something on the field, do not make empty
			if (!_.isEmpty( values ) ) {
				this.model.set( JSON.parse( values ));

			}
		}

		// Change the default value if only one post_type
		if(this.model.get( 'post_type' ) === false ) {
			this.model.set( 'post_type', _.first( _.toArray( fr.vc.tp.vars.post_types ) ).name );
		}

		this.$el.find( '.vc-tp-elements-container' ).html(_.template(this.template, {
			id: this.model.id,
			model: this.model,
			post_types: fr.vc.tp.vars.post_types,
			taxonomies: fr.vc.tp.tools.extract_post_type_taxonomies(this.model.get('post_type')),
			formated_terms: _.pluck(this.model.get('terms'), 'id').join()
		}));

		// Init the terms select2
		this.init_terms_select2( this.$el.find('.bea_vc_tp_terms'), this.model.get('terms') );

		// Init select2 for the taxonomies
		this.init_select2( this.$el.find('.bea_vc_tp_taxonomy'), this.model.get('taxonomies') );

		this.update_items_json();

		return this;
		// for chainable calls, like .render().el
	},
	// Change the model data
	changed: function (evt) {
		"use strict";
		var el = jQuery(evt.currentTarget), value = el.val(), obj = {};
		obj[  el.data('name') ] = value;
		this.model.set(obj);

		this.update_items_json();
	},
	init_terms_select2: function (el, ids) {
		"use strict";
		var self = this;

		el.select2({
			width: '100%',
			multiple: true,
			// For calls when searching on post_type
			ajax: {
				url: ajaxurl,
				dataType: 'json',
				type: 'POST',
				data: function (term, page) {
					return {
						action: 'bea_vc_tp_get_terms',
						s: term,
						posts_per_page: 10,
						page: page,
						taxonomies: self.model.get('taxonomies')
					};
				},
				results: function (data, page) {
					var more = ( page * 10 ) < data.data.total;
					return {
						results: data.data.elements,
						more: more
					};
				}
			},
			initSelection: function (element, callback) {
				callback(ids);
			},
			escapeMarkup: function (m) {
				return m;
			}
		}).on('select2-selecting',function (e) {
			var terms = self.model.get('terms');
			terms.push(e.object);

			self.model.set({
				terms: terms
			});
			return this;
		}).on('change', function (e) {
			if (!_.isUndefined(e.removed)) {
				self.model.set({ terms: _.filter(self.model.get('terms'), function (element) {
					return element.id !== e.removed.id;
				}) });
			}

			self.update_items_json();
		});
	},
	init_select2: function ( el, taxonomies ) {
		"use strict";
		var self = this;

		el.select2({
			width: '100%'
		});
	},
	update_items_json: function () {
		"use strict";
		this.field.val(base64_encode(JSON.stringify(this.model.toJSON())));
	}
});

if (!_.isUndefined(fr.vc.tp.vars)) {
	_.each( jQuery( '.vc-tp-container' ), function( el ) {
		"use strict";
		// Random number for the model
		var bea_tp_taxonomy_model = new fr.vc.tp.Taxonomy( { id : _.random( 0, 1000000 ) } );
		new fr.vc.tp.Taxonomies( { model: bea_tp_taxonomy_model, el : el } );
	} );
}