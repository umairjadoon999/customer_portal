<?php
/**
 * The template for displaying the footer
 *
 * Contains the opening of the #site-footer div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

?>
			<footer id="site-footer" role="contentinfo" class="header-footer-group">

				<div class="section-inner">

					<div class="footer-credits">

						<p class="footer-copyright">&copy;
							<?php
							echo date_i18n(
								/* translators: Copyright date format, see https://www.php.net/date */
								_x( 'Y', 'copyright date format', 'twentytwenty' )
							);
							?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
						</p><!-- .footer-copyright -->

						<p class="powered-by-wordpress">
							<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'twentytwenty' ) ); ?>">
								<?php _e( 'Powered by WordPress', 'twentytwenty' ); ?>
							</a>
						</p><!-- .powered-by-wordpress -->

					</div><!-- .footer-credits -->

					<a class="to-the-top" href="#site-header">
						<span class="to-the-top-long">
							<?php
							/* translators: %s: HTML character for up arrow. */
							printf( __( 'To the top %s', 'twentytwenty' ), '<span class="arrow" aria-hidden="true">&uarr;</span>' );
							?>
						</span><!-- .to-the-top-long -->
						<span class="to-the-top-short">
							<?php
							/* translators: %s: HTML character for up arrow. */
							printf( __( 'Up %s', 'twentytwenty' ), '<span class="arrow" aria-hidden="true">&uarr;</span>' );
							?>
						</span><!-- .to-the-top-short -->
					</a><!-- .to-the-top -->

				</div><!-- .section-inner -->

			</footer><!-- #site-footer -->

		<?php wp_footer(); ?>
		<script>
        // script added by hassaan on 22aug2019
	function Confirm(title, msg, $true, $false, $link) { /*change*/
        var $content =  "<div class='dialog-ovelay'>" +
                        "<div class='dialog'><header>" +
                         " <h3> " + title + " </h3> " +
                         "<i class='fa fa-close'></i>" +
                     "</header>" +
                     "<div class='dialog-msg'>" +
                         " <p> " + msg + " </p> " +
                     "</div>" +
                     "<footer>" +
                         "<div class='controls'>" +
                             " <button class='button button-danger doAction'>" + $true + "</button> " +
                             " <button class='button button-default cancelAction'>" + $false + "</button> " +
                         "</div>" +
                     "</footer>" +
                  "</div>" +
                "</div>";
         jQuery ('body').prepend($content);
      jQuery('.doAction').click(function () {
          jQuery('#approvalForm').submit();
        /*window.open($link, "_blank");*/ /*new*/
        jQuery(this).parents('.dialog-ovelay').fadeOut(500, function () {
          jQuery(this).remove();
        });
      });
    jQuery('.cancelAction, .fa-close').click(function () {
        jQuery(this).parents('.dialog-ovelay').fadeOut(500, function () {
          jQuery(this).remove();
        });
      });
      
   }
    jQuery('#approval').click(function () {
        Confirm('Approval Required', 'Are you sure you want to approve Editing', 'Yes', 'Cancel', "https://www.google.com.eg"); /*change*/
    });
    // script added by hassaan on 22aug2019
	</script>




<script>
    // script added by Rao on 1oct2019
    function Confirm(title, msg, $true, $false, $link) { /*change*/
        var $content =  "<div class='dialog-ovelay'>" +
            "<div class='dialog'><header>" +
            " <h3> " + title + " </h3> " +
            "<i class='fa fa-close'></i>" +
            "</header>" +
            "<div class='dialog-msg'>" +
            " <p> " + msg + " </p> " +
            "</div>" +
            "<footer>" +
            "<div class='controls'>" +
            " <button class='button button-danger doAction'>" + $true + "</button> " +
            " <button class='button button-default cancelAction'>" + $false + "</button> " +
            "</div>" +
            "</footer>" +
            "</div>" +
            "</div>";
        jQuery ('body').prepend($content);
        jQuery('.doAction').click(function () {
            jQuery('#scriptApprovalForm').submit();
            /*window.open($link, "_blank");*/ /*new*/
            jQuery(this).parents('.dialog-ovelay').fadeOut(500, function () {
                jQuery(this).remove();
            });
        });
        jQuery('.cancelAction, .fa-close').click(function () {
            jQuery(this).parents('.dialog-ovelay').fadeOut(500, function () {
                jQuery(this).remove();
            });
        });

    }
    jQuery('#scriptApproval').click(function () {
        Confirm('Approval Required', 'Are you sure you want to approve Script', 'Yes', 'Cancel', "#"); /*change*/
    });
    // script added by Rao on 1oct2019
</script>

<script>
					
 </script>
	</body>
</html>
