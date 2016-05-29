<div class="vc_content-list">
	<div class="vc_content vc_row wpb_row vc_row-fluid">
		<?php foreach( $contents as $taxonomy ) :
			BEA_VC_Helper::get_term_link( $taxonomy );
		endforeach; ?>
	</div>
</div>