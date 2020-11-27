<?php
/**
 * The Header for our theme.
 *
 * @package OceanWP WordPress theme
 */

?>
<!DOCTYPE html>
<html class="<?php echo esc_attr( oceanwp_html_classes() ); ?>" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<link rel="stylesheet" href="http://localhost/Mint_portal/wp-includes/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="http://localhost/Mint_portal/wp-includes/DataTables/datatables.css">
  

	<link rel='stylesheet' href='http://localhost/Mint_portal/wp-content/themes/oceanwp/custom.css' type='text/css' media='all' />
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">
	<link rel='stylesheet' href='http://localhost/Mint_portal/wp-content/themes/oceanwp/style2.css' type='text/css' media='all' />
	

	<script src="http://localhost/Mint_portal/wp-includes/js/jquery/jquery.js"></script>
	<script type="text/javascript" charset="utf8" src="http://localhost/Mint_portal/wp-includes/DataTables/datatables.js"></script>

</head>

<body <?php body_class(); ?> <?php oceanwp_schema_markup( 'html' ); ?>>

	<?php wp_body_open(); ?>

	<?php do_action( 'ocean_before_outer_wrap' ); ?>

	<div id="outer-wrap" class="site clr">

		<a class="skip-link screen-reader-text" href="#main"><?php oceanwp_theme_strings( 'owp-string-header-skip-link', 'oceanwp' ); ?></a>

		<?php do_action( 'ocean_before_wrap' ); ?>

		<div id="wrap" class="clr">

			<?php do_action( 'ocean_top_bar' ); ?>

			<?php do_action( 'ocean_header' ); ?>

			<?php do_action( 'ocean_before_main' ); ?>

			<main id="main" class="site-main clr"<?php oceanwp_schema_markup( 'main' ); ?> role="main">

				<?php do_action( 'ocean_page_header' ); ?>
