<?php
/**
 * Astra Child Theme functions and definitions.
 */

function astra_child_enqueue_styles() {
    wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array( 'astra-theme-css' ), '1.0.0', 'all' );

    if ( is_front_page() ) {
        wp_enqueue_style(
            'astraland-home',
            get_stylesheet_directory_uri() . '/assets/css/astraland-home.css',
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
            get_stylesheet_directory_uri() . '/assets/js/astraland-home.js',
            array( 'lucide-icons' ),
            '1.0.0',
            true
        );
    }
}

add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_styles', 15 );
