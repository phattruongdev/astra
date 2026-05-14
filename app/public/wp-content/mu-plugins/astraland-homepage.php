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
            'chart-js',
            'https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js',
            array(),
            '4.4.7',
            true
        );

        wp_enqueue_script(
            'astraland-home',
            content_url( 'themes/astra-child/assets/js/astraland-home.js' ),
            array( 'lucide-icons', 'chart-js' ),
            '1.0.0',
            true
        );

        wp_localize_script(
            'astraland-home',
            'AstraLandData',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'astraland_home' ),
                'stats'   => astraland_get_market_stats(),
            )
        );
    },
    15
);

function astraland_get_market_stats() {
    $post_counts = wp_count_posts( 'post' );
    $active      = isset( $post_counts->publish ) ? (int) $post_counts->publish : 0;

    $today_query = new WP_Query(
        array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'posts_per_page' => 1,
            'date_query'     => array(
                array(
                    'after'     => 'today',
                    'inclusive' => true,
                ),
            ),
        )
    );

    $categories = get_categories(
        array(
            'hide_empty' => false,
            'orderby'    => 'count',
            'order'      => 'DESC',
            'number'     => 5,
        )
    );

    $labels = array();
    $values = array();

    foreach ( $categories as $category ) {
        if ( count( $labels ) >= 5 ) {
            break;
        }

        $labels[] = $category->name;
        $values[] = (int) $category->count;
    }

    if ( empty( $labels ) ) {
        $fallback_labels = array( 'Nhà riêng', 'Căn hộ', 'Đất', 'Đất nền', 'Biệt thự' );
        $base            = max( 0, $active );

        foreach ( $fallback_labels as $index => $label ) {
            $labels[] = $label;
            $values[] = (int) round( $base / ( $index + 2 ) );
        }
    }

    return array(
        'date'      => wp_date( 'd/m/Y' ),
        'active'    => $active,
        'today'     => (int) $today_query->found_posts,
        'labels'    => $labels,
        'values'    => $values,
    );
}

add_action( 'wp_ajax_nopriv_astraland_login', 'astraland_ajax_login' );
add_action( 'wp_ajax_astraland_login', 'astraland_ajax_login' );
function astraland_ajax_login() {
    check_ajax_referer( 'astraland_home', 'nonce' );

    $creds = array(
        'user_login'    => sanitize_user( wp_unslash( $_POST['email'] ?? '' ) ),
        'user_password' => (string) wp_unslash( $_POST['password'] ?? '' ),
        'remember'      => true,
    );

    $user = wp_signon( $creds, is_ssl() );

    if ( is_wp_error( $user ) ) {
        wp_send_json_error( array( 'message' => 'Email hoặc mật khẩu không đúng.' ), 400 );
    }

    wp_send_json_success( array( 'message' => 'Đăng nhập thành công.' ) );
}

add_action( 'wp_ajax_nopriv_astraland_register', 'astraland_ajax_register' );
add_action( 'wp_ajax_astraland_register', 'astraland_ajax_register' );
function astraland_ajax_register() {
    check_ajax_referer( 'astraland_home', 'nonce' );

    $email    = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
    $name     = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
    $password = (string) wp_unslash( $_POST['password'] ?? '' );

    if ( ! is_email( $email ) || strlen( $password ) < 6 ) {
        wp_send_json_error( array( 'message' => 'Vui lòng nhập email hợp lệ và mật khẩu tối thiểu 6 ký tự.' ), 400 );
    }

    $username = sanitize_user( current( explode( '@', $email ) ), true );
    $username = $username ? $username : 'user';
    $base     = $username;
    $suffix   = 1;

    while ( username_exists( $username ) ) {
        $username = $base . $suffix;
        $suffix++;
    }

    $user_id = wp_create_user( $username, $password, $email );

    if ( is_wp_error( $user_id ) ) {
        wp_send_json_error( array( 'message' => $user_id->get_error_message() ), 400 );
    }

    wp_update_user(
        array(
            'ID'           => $user_id,
            'display_name' => $name ? $name : $username,
        )
    );

    wp_set_current_user( $user_id );
    wp_set_auth_cookie( $user_id, true );

    wp_send_json_success( array( 'message' => 'Tạo tài khoản thành công.' ) );
}

add_action( 'wp_ajax_astraland_submit_listing', 'astraland_ajax_submit_listing' );
add_action( 'wp_ajax_nopriv_astraland_submit_listing', 'astraland_ajax_submit_listing' );
function astraland_ajax_submit_listing() {
    check_ajax_referer( 'astraland_home', 'nonce' );

    $title   = sanitize_text_field( wp_unslash( $_POST['title'] ?? '' ) );
    $content = sanitize_textarea_field( wp_unslash( $_POST['description'] ?? '' ) );
    $price   = sanitize_text_field( wp_unslash( $_POST['price'] ?? '' ) );
    $area    = sanitize_text_field( wp_unslash( $_POST['area'] ?? '' ) );
    $address = sanitize_text_field( wp_unslash( $_POST['address'] ?? '' ) );
    $type    = sanitize_text_field( wp_unslash( $_POST['property_type'] ?? '' ) );

    if ( ! $title || ! $price || ! $area || ! $address ) {
        wp_send_json_error( array( 'message' => 'Vui lòng điền đủ tiêu đề, giá, diện tích và địa chỉ.' ), 400 );
    }

    $category_id = 0;
    if ( $type ) {
        $term = term_exists( $type, 'category' );

        if ( 0 === $term || null === $term ) {
            $term = wp_insert_term( $type, 'category' );
        }

        if ( ! is_wp_error( $term ) ) {
            $category_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;
        }
    }

    $post_id = wp_insert_post(
        array(
            'post_type'    => 'post',
            'post_status'  => is_user_logged_in() ? 'publish' : 'pending',
            'post_title'   => $title,
            'post_content' => $content,
            'post_author'  => get_current_user_id(),
            'post_category' => $category_id ? array( $category_id ) : array(),
            'meta_input'   => array(
                '_astraland_price' => $price,
                '_astraland_area'  => $area,
                '_astraland_addr'  => $address,
                '_astraland_type'  => $type,
            ),
        ),
        true
    );

    if ( is_wp_error( $post_id ) ) {
        wp_send_json_error( array( 'message' => $post_id->get_error_message() ), 400 );
    }

    wp_send_json_success(
        array(
            'message' => is_user_logged_in() ? 'Tin đã được đăng.' : 'Tin đã gửi và đang chờ duyệt.',
        )
    );
}
