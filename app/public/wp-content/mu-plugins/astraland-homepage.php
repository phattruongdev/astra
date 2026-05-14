<?php
/**
 * AstraLand real estate portal templates, data, and AJAX handlers.
 *
 * This keeps the mockup independent from whether Astra or Astra Child is active.
 */

add_filter(
    'template_include',
    function ( $template ) {
        if ( astraland_is_buy_listing_page() ) {
            status_header( 200 );

            $listing_template = WP_CONTENT_DIR . '/themes/astra-child/page-buy-listings.php';

            return file_exists( $listing_template ) ? $listing_template : $template;
        }

        if ( astraland_is_submit_listing_page() ) {
            status_header( 200 );

            $submit_template = WP_CONTENT_DIR . '/themes/astra-child/page-submit-listing.php';

            return file_exists( $submit_template ) ? $submit_template : $template;
        }

        if ( is_front_page() ) {
            $custom_template = WP_CONTENT_DIR . '/themes/astra-child/front-page.php';

            return file_exists( $custom_template ) ? $custom_template : $template;
        }

        return $template;
    },
    99
);

add_filter(
    'redirect_canonical',
    function ( $redirect_url ) {
        if ( astraland_is_buy_listing_page() || astraland_is_submit_listing_page() ) {
            return false;
        }

        return $redirect_url;
    }
);

add_action(
    'wp_enqueue_scripts',
    function () {
        if ( ! is_front_page() && ! astraland_is_buy_listing_page() && ! astraland_is_submit_listing_page() ) {
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

function astraland_is_buy_listing_page() {
    $path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';

    return '/mua-ban-nha-dat-a4' === untrailingslashit( $path );
}

function astraland_is_submit_listing_page() {
    $path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( wp_unslash( $_SERVER['REQUEST_URI'] ), PHP_URL_PATH ) : '';

    return '/dang-tin' === untrailingslashit( $path );
}

add_action( 'init', 'astraland_register_real_estate_data' );
function astraland_register_real_estate_data() {
    register_post_type(
        'al_listing',
        array(
            'labels'       => array(
                'name'          => 'Tin bất động sản',
                'singular_name' => 'Tin bất động sản',
                'add_new_item'  => 'Thêm tin bất động sản',
                'edit_item'     => 'Sửa tin bất động sản',
            ),
            'public'       => true,
            'show_ui'      => true,
            'show_in_menu' => true,
            'menu_icon'    => 'dashicons-building',
            'supports'     => array( 'title', 'editor', 'thumbnail', 'author' ),
            'rewrite'      => array( 'slug' => 'nha-dat' ),
            'has_archive'  => false,
        )
    );

    register_taxonomy(
        'al_property_type',
        'al_listing',
        array(
            'labels'       => array(
                'name'          => 'Loại nhà đất',
                'singular_name' => 'Loại nhà đất',
            ),
            'public'       => true,
            'show_ui'      => true,
            'hierarchical' => true,
            'rewrite'      => array( 'slug' => 'loai-nha-dat' ),
        )
    );

    register_taxonomy(
        'al_location',
        'al_listing',
        array(
            'labels'       => array(
                'name'          => 'Khu vực',
                'singular_name' => 'Khu vực',
            ),
            'public'       => true,
            'show_ui'      => true,
            'hierarchical' => true,
            'rewrite'      => array( 'slug' => 'khu-vuc' ),
        )
    );
}

add_action( 'init', 'astraland_seed_demo_listings', 20 );
function astraland_seed_demo_listings() {
    if ( get_option( 'astraland_demo_seeded_v2' ) ) {
        return;
    }

    $types = array(
        'Nhà riêng',
        'Căn hộ chung cư',
        'Nhà mặt phố',
        'Đất nền',
        'Biệt thự liền kề',
        'Shophouse',
    );

    $locations = array(
        'Hà Nội',
        'TP. Hồ Chí Minh',
        'Đà Nẵng',
        'Bình Dương',
        'Đồng Nai',
        'Hải Phòng',
        'Bắc Ninh',
        'Khánh Hòa',
    );

    foreach ( $types as $type ) {
        if ( ! term_exists( $type, 'al_property_type' ) ) {
            wp_insert_term( $type, 'al_property_type' );
        }
    }

    foreach ( $locations as $location ) {
        if ( ! term_exists( $location, 'al_location' ) ) {
            wp_insert_term( $location, 'al_location' );
        }
    }

    $demo_listings = astraland_get_demo_listing_rows();

    foreach ( $demo_listings as $row ) {
        $existing = get_page_by_title( $row['title'], OBJECT, 'al_listing' );

        if ( $existing ) {
            continue;
        }

        $post_id = wp_insert_post(
            array(
                'post_type'    => 'al_listing',
                'post_status'  => 'publish',
                'post_title'   => $row['title'],
                'post_content' => $row['description'],
                'meta_input'   => array(
                    '_al_price_label' => $row['price_label'],
                    '_al_price_value' => $row['price_value'],
                    '_al_area'        => $row['area_label'],
                    '_al_area_value'  => $row['area_value'],
                    '_al_ppm'         => $row['ppm'],
                    '_al_location'    => $row['district'] . ', ' . $row['location'],
                    '_al_score'       => $row['score'],
                    '_al_bedrooms'    => $row['bedrooms'],
                    '_al_bathrooms'   => $row['bathrooms'],
                    '_al_phone'       => $row['phone'],
                    '_al_owner_type'  => $row['owner_type'],
                    '_al_badge'       => $row['badge'],
                    '_al_image'       => $row['image'],
                    '_al_agent'       => $row['agent'],
                    '_al_lat'         => $row['lat'],
                    '_al_lng'         => $row['lng'],
                ),
            )
        );

        if ( ! is_wp_error( $post_id ) ) {
            wp_set_object_terms( $post_id, $row['type'], 'al_property_type' );
            wp_set_object_terms( $post_id, $row['location'], 'al_location' );
        }
    }

    update_option( 'astraland_demo_seeded_v2', time(), false );
    flush_rewrite_rules( false );
}

function astraland_get_demo_listing_rows() {
    $images = array(
        'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1605146769289-440113cc3d00?auto=format&fit=crop&w=900&q=80',
        'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=900&q=80',
    );

    $rows = array(
        array( 'Xác thực', 'Nhà riêng', 'TP. Hồ Chí Minh', 'Q. 7', 'Bán gấp nhà Kiều Đàm, hẻm xe hơi 6m, hầm 5 tầng', '12,5 tỷ', 12500, '72 m²', 72, '173,61 tr/m²', '4', '5', '096309****', 'Môi giới', 'PT Phùng Trung', '6.8', 10.735, 106.721 ),
        array( 'Vàng', 'Căn hộ chung cư', 'Hà Nội', 'Q. Cầu Giấy', 'Căn hộ Hanoi Signature, view công viên, bàn giao cao cấp', '33,2 tỷ', 33200, '111 m²', 111, '298,13 tr/m²', '3', '3', '092205****', 'Môi giới', 'NT Nguyễn Tuấn', '5.8', 21.035, 105.801 ),
        array( 'Ruby', 'Đất nền', 'Bình Dương', 'TP. Thủ Dầu Một', 'Đất liền kề khu dân cư hiện hữu, đường 12m, sổ riêng', '2,3 tỷ', 2300, '150 m²', 150, '15,33 tr/m²', '0', '0', '093983****', 'Môi giới', 'MT Minh Tuyết', '9.2', 10.980, 106.652 ),
        array( 'Bạc', 'Nhà mặt phố', 'Đà Nẵng', 'Q. Sơn Trà', 'Tòa căn hộ trung tâm Sơn Trà, khai thác 19 phòng', '36 tỷ', 36000, '88 m²', 88, '409,09 tr/m²', '9', '9', '090533****', 'Môi giới', 'NP Nguyễn Phúc', '5.4', 16.083, 108.237 ),
        array( 'Xác thực', 'Đất nền', 'Đồng Nai', 'TP. Biên Hòa', 'Lô đất mặt tiền khu dân cư, gần chợ, công chứng ngay', '3,15 tỷ', 3150, '150 m²', 150, '21 tr/m²', '0', '0', '076926****', 'Chính chủ', 'CC Chính Chủ', '7.2', 10.944, 106.824 ),
        array( 'Vàng', 'Biệt thự liền kề', 'Hà Nội', 'Q. Nam Từ Liêm', 'Biệt thự Đại Mỗ, mặt tiền 12m, khu dân trí cao', '44 tỷ', 44000, '170 m²', 170, '258,82 tr/m²', '5', '5', '098866****', 'Môi giới', 'LA Lê An', '7.2', 21.006, 105.744 ),
        array( 'Thường', 'Nhà riêng', 'Hải Phòng', 'H. Kiến Thụy', 'Nhà đẹp 2 tầng Cao Tiến, vào ở ngay, ngõ thông', '2,99 tỷ', 2990, '108 m²', 108, '27,56 tr/m²', '3', '2', '076926****', 'Chính chủ', 'TH Thu Hà', '9.2', 20.746, 106.665 ),
        array( 'Ruby', 'Shophouse', 'TP. Hồ Chí Minh', 'TP. Thủ Đức', 'Shophouse trục chính khu đô thị, khai thác dòng tiền tốt', '21,5 tỷ', 21500, '108 m²', 108, '199,07 tr/m²', '3', '4', '089697****', 'Môi giới', 'NH Nhà Phố', '6.5', 10.842, 106.810 ),
        array( 'Bạc', 'Căn hộ chung cư', 'TP. Hồ Chí Minh', 'Q. Bình Thạnh', 'Căn hộ view sông, nội thất đầy đủ, nhận nhà ngay', '5,8 tỷ', 5800, '72 m²', 72, '80,56 tr/m²', '2', '2', '089533****', 'Môi giới', 'VT Võ Trí', '5.9', 10.802, 106.718 ),
        array( 'Xác thực', 'Đất nền', 'Bình Dương', 'H. Bàu Bàng', 'Đất thổ cư có sẵn vườn cây, mặt tiền kinh doanh', '950 triệu', 950, '250 m²', 250, '3,8 tr/m²', '0', '0', '091408****', 'Chính chủ', 'MT Minh Tuyết', '9.8', 11.250, 106.620 ),
        array( 'Vàng', 'Nhà mặt phố', 'Hà Nội', 'Q. Cầu Giấy', 'Mặt đường Hoa Bằng, 5 tầng, kinh doanh đỉnh cao', '109 tỷ', 109000, '314 m²', 314, '347,13 tr/m²', '4', '5', '097627****', 'Môi giới', 'LA Lê An', '7.2', 21.032, 105.796 ),
        array( 'Thường', 'Căn hộ chung cư', 'Đà Nẵng', 'Q. Cẩm Lệ', 'Căn hộ dòng tiền, kiệt ô tô Nguyễn Như Đãi', '7,8 tỷ', 7800, '229 m²', 229, '34,06 tr/m²', '8', '8', '090533****', 'Môi giới', 'NP Nguyễn Phúc', '5.6', 16.016, 108.205 ),
    );

    $listings = array();

    foreach ( $rows as $index => $row ) {
        $listings[] = array(
            'badge'       => $row[0],
            'type'        => $row[1],
            'location'    => $row[2],
            'district'    => $row[3],
            'title'       => $row[4],
            'price_label' => $row[5],
            'price_value' => $row[6],
            'area_label'  => $row[7],
            'area_value'  => $row[8],
            'ppm'         => $row[9],
            'bedrooms'    => $row[10],
            'bathrooms'   => $row[11],
            'phone'       => $row[12],
            'owner_type'  => $row[13],
            'agent'       => $row[14],
            'score'       => $row[15],
            'lat'         => $row[16],
            'lng'         => $row[17],
            'image'       => $images[ $index % count( $images ) ],
            'description' => 'Tin demo phục vụ giao diện mua bán nhà đất, có dữ liệu giá, diện tích, khu vực, liên hệ và phân loại để thử bộ lọc.',
        );
    }

    return $listings;
}

function astraland_get_market_stats() {
    $post_counts = wp_count_posts( 'al_listing' );
    $active      = isset( $post_counts->publish ) ? (int) $post_counts->publish : 0;

    $today_query = new WP_Query(
        array(
            'post_type'      => 'al_listing',
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

    $categories = get_terms(
        array(
            'taxonomy'   => 'al_property_type',
            'hide_empty' => false,
            'orderby'    => 'count',
            'order'      => 'DESC',
            'number'     => 5,
        )
    );

    $labels = array();
    $values = array();

    if ( ! is_wp_error( $categories ) ) {
        foreach ( $categories as $category ) {
            if ( count( $labels ) >= 5 ) {
                break;
            }

            $labels[] = $category->name;
            $values[] = (int) $category->count;
        }
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
    $location_term = sanitize_text_field( wp_unslash( $_POST['location'] ?? '' ) );
    $phone         = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
    $owner_type    = sanitize_text_field( wp_unslash( $_POST['owner_type'] ?? '' ) );
    $bedrooms      = sanitize_text_field( wp_unslash( $_POST['bedrooms'] ?? '' ) );
    $bathrooms     = sanitize_text_field( wp_unslash( $_POST['bathrooms'] ?? '' ) );
    $image         = esc_url_raw( wp_unslash( $_POST['image'] ?? '' ) );
    $price_value   = (int) preg_replace( '/[^0-9]/', '', $price );
    $area_value    = (int) preg_replace( '/[^0-9]/', '', $area );

    if ( ! $title || ! $price || ! $area || ! $address ) {
        wp_send_json_error( array( 'message' => 'Vui lòng điền đủ tiêu đề, giá, diện tích và địa chỉ.' ), 400 );
    }

    $type_id = 0;
    if ( $type ) {
        $term = term_exists( $type, 'al_property_type' );

        if ( 0 === $term || null === $term ) {
            $term = wp_insert_term( $type, 'al_property_type' );
        }

        if ( ! is_wp_error( $term ) ) {
            $type_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;
        }
    }

    $location_id = 0;
    if ( $location_term ) {
        $term = term_exists( $location_term, 'al_location' );

        if ( 0 === $term || null === $term ) {
            $term = wp_insert_term( $location_term, 'al_location' );
        }

        if ( ! is_wp_error( $term ) ) {
            $location_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;
        }
    }

    $post_id = wp_insert_post(
        array(
            'post_type'    => 'al_listing',
            'post_status'  => is_user_logged_in() ? 'publish' : 'pending',
            'post_title'   => $title,
            'post_content' => $content,
            'post_author'  => get_current_user_id(),
            'meta_input'   => array(
                '_al_price_label' => $price,
                '_al_price_value' => $price_value,
                '_al_area'        => $area,
                '_al_area_value'  => $area_value,
                '_al_location'    => $address,
                '_al_owner_type'  => $owner_type ? $owner_type : ( is_user_logged_in() ? 'Môi giới' : 'Chính chủ' ),
                '_al_badge'       => is_user_logged_in() ? 'Xác thực' : 'Thường',
                '_al_agent'       => wp_get_current_user()->display_name ? wp_get_current_user()->display_name : 'Khách đăng tin',
                '_al_phone'       => $phone ? $phone : 'Đang cập nhật',
                '_al_bedrooms'    => $bedrooms,
                '_al_bathrooms'   => $bathrooms,
                '_al_image'       => $image,
            ),
        ),
        true
    );

    if ( is_wp_error( $post_id ) ) {
        wp_send_json_error( array( 'message' => $post_id->get_error_message() ), 400 );
    }

    if ( $type_id ) {
        wp_set_object_terms( $post_id, array( $type_id ), 'al_property_type' );
    }

    if ( $location_id ) {
        wp_set_object_terms( $post_id, array( $location_id ), 'al_location' );
    }

    wp_send_json_success(
        array(
            'message' => is_user_logged_in() ? 'Tin đã được đăng.' : 'Tin đã gửi và đang chờ duyệt.',
        )
    );
}
