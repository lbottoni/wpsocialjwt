
<div class="wrap">

	<h2><?php _e("SubMenu Facebook JWT") ?></h2>
    <h5>option_group: <?php 	echo "{$this->domain}_fb-group" ?></h5>
    <h5>my_option_name: <?php 	echo get_option( 'my_option_name' ); ?></h5>
	<form method="post" action="options.php">
		<?php
		settings_fields( 'my_option_group' );
		do_settings_sections( 'my-setting-admin' );
		?>
		 <?php
		submit_button();
		?>
	</form>

</div><!-- .wrap -->
