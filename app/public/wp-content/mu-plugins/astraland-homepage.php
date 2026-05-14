<?php
/**
 * Force the custom real estate homepage template on the front page.
 *
 * This keeps the mockup independent from whether Astra or Astra Child is active.
 */

add_filter(
    'template_include',
    function ( $template ) {
        if ( ! is_front_page() ) {
            return $template;
        }

        $custom_template = WP_CONTENT_DIR . '/themes/astra-child/front-page.php';

        return file_exists( $custom_template ) ? $custom_template : $template;
    },
    99
);

add_action(
    'wp_enqueue_scripts',
    function () {
        if ( ! is_front_page() ) {
            return;
        }

        wp_enqueue_style(
            'astraland-home',
            content_url( 'themes/astra-child/assets/css/astraland-home.css' ),
            array(),
            '1.0.0',
            'all'
        );

        wp_enqueue_script(
            'lucide-icons',
            'https://unpkg.com/lucide@latest/dist/umd/lucide.min.js',
            array(),
            null,
            true
        );

        wp_enqueue_script(
            'astraland-home',
            content_url( 'themes/astra-child/assets/js/astraland-home.js' ),
            array( 'lucide-icons' ),
            '1.0.0',
            true
        );
    },
    15
);
