<?php settings_errors(); ?>

<div class="wrap">

	<h2><?php _e("SubMenu Facebook JWT") ?></h2>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'fbgroup' );
		do_settings_sections( 'fb_page' );
		submit_button();
		?>

	</form>

</div><!-- .wrap -->
