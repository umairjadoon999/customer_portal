<?php

/**
 * The template for displaying the footer.
 *
 * @package OceanWP WordPress theme
 */

?>

</main><!-- #main -->

<?php do_action('ocean_after_main'); ?>

<?php do_action('ocean_before_footer'); ?>

<?php
// Elementor `footer` location.
if (!function_exists('elementor_theme_do_location') || !elementor_theme_do_location('footer')) {
?>

    <?php do_action('ocean_footer'); ?>

<?php } ?>

<?php do_action('ocean_after_footer'); ?>

</div><!-- #wrap -->

<?php do_action('ocean_after_wrap'); ?>

</div><!-- #outer-wrap -->

<?php do_action('ocean_after_outer_wrap'); ?>

<?php
// If is not sticky footer.
if (!class_exists('Ocean_Sticky_Footer')) {
    get_template_part('partials/scroll-top');
}
?>

<?php
// Search overlay style.
if ('overlay' === oceanwp_menu_search_style()) {
    get_template_part('partials/header/search-overlay');
}
?>

<?php
// If sidebar mobile menu style.
if ('sidebar' === oceanwp_mobile_menu_style()) {

    // Mobile panel close button.
    if (get_theme_mod('ocean_mobile_menu_close_btn', true)) {
        get_template_part('partials/mobile/mobile-sidr-close');
    }
?>

    <?php
    // Mobile Menu (if defined).
    get_template_part('partials/mobile/mobile-nav');
    ?>

<?php
    // Mobile search form.
    if (get_theme_mod('ocean_mobile_menu_search', true)) {
        get_template_part('partials/mobile/mobile-search');
    }
}
?>

<?php
// If full screen mobile menu style.
if ('fullscreen' === oceanwp_mobile_menu_style()) {
    get_template_part('partials/mobile/mobile-fullscreen');
}
?>

<?php wp_footer(); ?>
<script>
    // script added by hassaan on 22aug2019
    function Confirm(title, msg, $true, $false, $link) {
        /*change*/
        var $content = "<div class='dialog-ovelay'>" +
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
        jQuery('body').prepend($content);
        jQuery('.doAction').click(function() {
            jQuery('#approvalForm').submit();
            /*window.open($link, "_blank");*/
            /*new*/
            jQuery(this).parents('.dialog-ovelay').fadeOut(500, function() {
                jQuery(this).remove();
            });
        });
        jQuery('.cancelAction, .fa-close').click(function() {
            jQuery(this).parents('.dialog-ovelay').fadeOut(500, function() {
                jQuery(this).remove();
            });
        });

    }
    jQuery('#approval').click(function() {
        Confirm('Approval Required', 'Are you sure you want to approve Editing', 'Yes', 'Cancel', "https://www.google.com.eg"); /*change*/
    });
    // script added by hassaan on 22aug2019
</script>




<script type="text/javascript">
    // script added by Rao on 1oct2019
    function Confirm(title, msg, $true, $false, $link) {
        /*change*/
        var $content = "<div class='dialog-ovelay'>" +
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
        jQuery('body').prepend($content);
        jQuery('.doAction').click(function() {
            jQuery('#scriptApprovalForm').submit();
            /*window.open($link, "_blank");*/
            /*new*/
            jQuery(this).parents('.dialog-ovelay').fadeOut(500, function() {
                jQuery(this).remove();
            });
        });
        jQuery('.cancelAction, .fa-close').click(function() {
            jQuery(this).parents('.dialog-ovelay').fadeOut(500, function() {
                jQuery(this).remove();
            });
        });

    }
    jQuery('#scriptApproval').click(function() {
        Confirm('Approval Required', 'Are you sure you want to approve Script', 'Yes', 'Cancel', "#"); /*change*/
    });
    // script added by Rao on 1oct2019
    jQuery(document).ready(function() {
        jQuery('#table_id').DataTable();
    });
    //Added By Umair Khan On 09-11-2020
    function check_pass() {
        if (document.getElementById('password').value == document.getElementById('confirm_password').value) {
            document.getElementById('update').disabled = false;
        } else {
            document.getElementById('checkbox').checked = false;

        }
    }

    function check_passs() {
        if (document.getElementById('password').value == document.getElementById('confirm_password').value) {
            document.getElementById('update').disabled = false;
        } else {
            document.getElementById('update').disabled = true;
            window.alert("Password Not Matched");
            document.getElementById('checkbox').checked = false;
        }
    }

    function displayNone() {
        document.getElementById("alert").style.display = "none";
    }
    // for getting the userName Field
    function saveValue(e) {
        var id = e.id; // get the sender's id to save it . 
        var val = e.value; // get the value. 
        sessionStorage.setItem(id, val); // Every time user writing something, the localStorage's value will override . 
    }

    //get the saved value function - return the value of "v" from localStorage.
    function getSavedValue(v) {
        if (!sessionStorage.getItem(v)) {
            return ""; // You can change this to your defualt value. 
        }
        return sessionStorage.getItem(v);
    }
    jQuery(login_form).ready(function() {
        document.getElementById("u_namee").value = getSavedValue("u_namee");
        document.getElementById("u_pass").value = getSavedValue("u_pass");
        sessionStorage.removeItem('u_namee');
        sessionStorage.removeItem('u_pass');
    });
</script>
</body>
</html>