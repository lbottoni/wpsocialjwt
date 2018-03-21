<?php
echo $this->domain;
?>
<div class="wrap">

	<h2><?php _e("SubMenu Facebook JWT") ?></h2>

	<form method="post" action="options.php">
		<?php
		settings_fields( "{$this->domain}_fb-group" );
		do_settings_sections( "{$this->domain}_fb-group" );
		?>
		 <?php
		submit_button();
		?>
	</form>

</div><!-- .wrap -->
