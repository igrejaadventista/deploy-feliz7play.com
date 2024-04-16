<?php
/**
 * Plugin settings page template
 *
 * Place a copy of this in your theme under 'jam3-cookie-banner' subdirectory
 * to override this template
 */
?>
<?php if ( defined( 'WP_REST_HEADLESS_PLUGIN_LOADED' ) ): ?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h1><?php echo esc_html( $page_title ); ?></h1>

		<?php
		//we check if the page is visited by click on the tabs or on the menu button.
		$active_tab = WP_Headless_Settings::get_active_settings_page_tab();
		?>

		<!-- wordpress provides the styling for tabs. -->
		<h2 class="nav-tab-wrapper">

			<a href="?page=<?php echo rawurlencode( $menu_slug ); ?>&tab=quick-start" class="nav-tab <?php if('quick-start' === $active_tab){echo 'nav-tab-active';} ?> ">
				<?php echo esc_html_x( 'Quick Start', 'tab nav title', WP_REST_HEADLESS_TEXT_DOMAIN ); ?>
			</a>

			<a href="?page=<?php echo rawurlencode( $menu_slug ); ?>&tab=request-headers" class="nav-tab <?php if('request-headers' === $active_tab){echo 'nav-tab-active';} ?> ">
				<?php echo esc_html_x( 'Request Headers', 'tab nav title', WP_REST_HEADLESS_TEXT_DOMAIN ); ?>
			</a>

			<a href="?page=<?php echo rawurlencode( $menu_slug ); ?>&tab=rest-jwt" class="nav-tab <?php if('rest-jwt' === $active_tab){echo 'nav-tab-active';} ?> ">
				<?php echo esc_html_x( 'JWT Authentication', 'tab nav title', WP_REST_HEADLESS_TEXT_DOMAIN ); ?>
			</a>

			<a href="?page=<?php echo rawurlencode( $menu_slug ); ?>&tab=rest-nonce" class="nav-tab <?php if('rest-nonce' === $active_tab){echo 'nav-tab-active';} ?> ">
				<?php echo esc_html_x( 'REST Nonce', 'tab nav title', WP_REST_HEADLESS_TEXT_DOMAIN ); ?>
			</a>

			<a href="?page=<?php echo rawurlencode( $menu_slug ); ?>&tab=rest-cleanup" class="nav-tab <?php if('rest-cleanup' === $active_tab){echo 'nav-tab-active';} ?> ">
				<?php echo esc_html_x( 'REST Cleanup', 'tab nav title', WP_REST_HEADLESS_TEXT_DOMAIN ); ?>
			</a>

			<a href="?page=<?php echo rawurlencode( $menu_slug ); ?>&tab=rest-gutenberg" class="nav-tab <?php if('rest-gutenberg' === $active_tab){echo 'nav-tab-active';} ?> ">
				<?php echo esc_html_x( 'REST Gutenberg', 'tab nav title', WP_REST_HEADLESS_TEXT_DOMAIN ); ?>
			</a>

			<a href="?page=<?php echo rawurlencode( $menu_slug ); ?>&tab=disable-frontend" class="nav-tab <?php if('disable-frontend' === $active_tab){echo 'nav-tab-active';} ?> ">
				<?php echo esc_html_x( 'Disable Frontend', 'tab nav title', WP_REST_HEADLESS_TEXT_DOMAIN ); ?>
			</a>

		</h2>

		<form method="post" action="options.php">
			<?php

			settings_fields( $option_group );

			do_settings_sections( $menu_slug );

			?>
		</form>
	</div>
<?php endif; ?>
