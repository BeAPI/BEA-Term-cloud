<div class="vc-tp-container">
	<input
		name="<?php echo esc_attr( $settings['param_name'] ); ?>"
		class="bea_vc_tp_value wpb_vc_param_value wpb-textinput <?php echo esc_attr( $settings['param_name'] . ' ' . $settings['type'] ); ?> _field"
		type="hidden"
		value='<?php echo esc_attr( $value ); ?>'
		<?php echo $dependency; ?>
		/>

	<div class="vc-tp-elements-container"></div>
</div>
<script>
	var fr;
	if (!fr) {
		fr = {};
	} else {
		if (typeof fr != "object") {
			throw new Error('fr already exists and not an object');
		}
	}
	if (!fr.vc) {
		fr.vc = {};
	} else {
		if (typeof fr.vc != "object") {
			throw new Error('fr.vc already exists and not an object');
		}
	}
	if (!fr.vc.tp) {
		fr.vc.tp = {};
	} else {
		if (typeof fr.vc.tp != "object") {
			throw new Error('fr.vc.sl already exists and not an object');
		}
	}
	console.log( <?php $vars; ?> );
	fr.vc.tp.vars = <?php echo json_encode( $vars ); ?>
</script>