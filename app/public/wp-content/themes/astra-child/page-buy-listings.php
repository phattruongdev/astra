<?php
/**
 * Custom listing archive at /mua-ban-nha-dat-a4.
 *
 * @package Astra_Child
 */

if ( ! function_exists( 'al_buy_url' ) ) {
    function al_buy_url( $changes = array() ) {
        $params = array();

        foreach ( wp_unslash( $_GET ) as $key => $value ) {
            $params[ sanitize_key( $key ) ] = sanitize_text_field( $value );
        }

        foreach ( $changes as $key => $value ) {
            if ( '' === $value || null === $value ) {
                unset( $params[ $key ] );
            } else {
                $params[ $key ] = $value;
            }
        }

        if ( ! isset( $changes['page_no'] ) ) {
            unset( $params['page_no'] );
        }

        return esc_url( add_query_arg( $params, home_url( '/mua-ban-nha-dat-a4/' ) ) );
    }
}

$keyword       = sanitize_text_field( wp_unslash( $_GET['keyword'] ?? '' ) );
$type          = sanitize_title( wp_unslash( $_GET['type'] ?? '' ) );
$location      = sanitize_title( wp_unslash( $_GET['location'] ?? '' ) );
$price         = sanitize_key( wp_unslash( $_GET['price'] ?? '' ) );
$area          = sanitize_key( wp_unslash( $_GET['area'] ?? '' ) );
$owner         = sanitize_text_field( wp_unslash( $_GET['owner'] ?? '' ) );
$verified      = sanitize_key( wp_unslash( $_GET['verified'] ?? '' ) );
$sort          = sanitize_key( wp_unslash( $_GET['sort'] ?? 'newest' ) );
$page_no       = max( 1, absint( $_GET['page_no'] ?? 1 ) );
$property_terms = get_terms( array( 'taxonomy' => 'al_property_type', 'hide_empty' => false ) );
$location_terms = get_terms( array( 'taxonomy' => 'al_location', 'hide_empty' => false ) );

$price_ranges = array(
    'duoi-1'  => array( 'label' => 'Dưới 1 tỷ', 'min' => 0, 'max' => 999 ),
    '1-3'     => array( 'label' => '1 - 3 tỷ', 'min' => 1000, 'max' => 3000 ),
    '3-5'     => array( 'label' => '3 - 5 tỷ', 'min' => 3000, 'max' => 5000 ),
    '5-10'    => array( 'label' => '5 - 10 tỷ', 'min' => 5000, 'max' => 10000 ),
    'tren-10' => array( 'label' => 'Trên 10 tỷ', 'min' => 10000, 'max' => 999999 ),
);

$area_ranges = array(
    'duoi-50'   => array( 'label' => 'Dưới 50 m²', 'min' => 0, 'max' => 49 ),
    '50-100'    => array( 'label' => '50 - 100 m²', 'min' => 50, 'max' => 100 ),
    '100-200'   => array( 'label' => '100 - 200 m²', 'min' => 100, 'max' => 200 ),
    'tren-200'  => array( 'label' => 'Trên 200 m²', 'min' => 200, 'max' => 99999 ),
);

$tax_query  = array();
$meta_query = array();

if ( $type ) {
    $tax_query[] = array(
        'taxonomy' => 'al_property_type',
        'field'    => 'slug',
        'terms'    => $type,
    );
}

if ( $location ) {
    $tax_query[] = array(
        'taxonomy' => 'al_location',
        'field'    => 'slug',
        'terms'    => $location,
    );
}

if ( isset( $price_ranges[ $price ] ) ) {
    $meta_query[] = array(
        'key'     => '_al_price_value',
        'value'   => array( $price_ranges[ $price ]['min'], $price_ranges[ $price ]['max'] ),
        'compare' => 'BETWEEN',
        'type'    => 'NUMERIC',
    );
}

if ( isset( $area_ranges[ $area ] ) ) {
    $meta_query[] = array(
        'key'     => '_al_area_value',
        'value'   => array( $area_ranges[ $area ]['min'], $area_ranges[ $area ]['max'] ),
        'compare' => 'BETWEEN',
        'type'    => 'NUMERIC',
    );
}

if ( $owner ) {
    $meta_query[] = array(
        'key'     => '_al_owner_type',
        'value'   => $owner,
        'compare' => '=',
    );
}

if ( '1' === $verified ) {
    $meta_query[] = array(
        'key'     => '_al_badge',
        'value'   => 'Xác thực',
        'compare' => '=',
    );
}

$query_args = array(
    'post_type'      => 'al_listing',
    'post_status'    => 'publish',
    'posts_per_page' => 8,
    'paged'          => $page_no,
    's'              => $keyword,
);

if ( $tax_query ) {
    $query_args['tax_query'] = $tax_query;
}

if ( $meta_query ) {
    $query_args['meta_query'] = $meta_query;
}

if ( 'price_asc' === $sort || 'price_desc' === $sort ) {
    $query_args['meta_key'] = '_al_price_value';
    $query_args['orderby']  = 'meta_value_num';
    $query_args['order']    = 'price_asc' === $sort ? 'ASC' : 'DESC';
} elseif ( 'area_desc' === $sort ) {
    $query_args['meta_key'] = '_al_area_value';
    $query_args['orderby']  = 'meta_value_num';
    $query_args['order']    = 'DESC';
} else {
    $query_args['orderby'] = 'date';
    $query_args['order']   = 'DESC';
}

$listings = new WP_Query( $query_args );
$total    = (int) $listings->found_posts;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'astraland-homepage al-listing-page' ); ?>>
<?php wp_body_open(); ?>

<header class="al-header" data-header>
    <div class="al-topbar">
        <div class="al-shell al-topbar__inner">
            <a class="al-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="AstraLand">
                <span class="al-brand__mark">A</span>
                <span class="al-brand__text">AstraLand</span>
            </a>
            <nav class="al-nav" aria-label="Danh mục chính" data-nav>
                <a class="al-nav__link is-active" href="<?php echo esc_url( home_url( '/mua-ban-nha-dat-a4/' ) ); ?>">Mua bán nhà đất</a>
                <a class="al-nav__link" href="#">Cho thuê nhà đất</a>
                <a class="al-nav__link" href="#">Dự án</a>
                <a class="al-nav__link" href="#news">Tin tức</a>
                <a class="al-nav__link al-nav__new" href="#">Khám phá</a>
            </nav>
            <div class="al-actions">
                <button class="al-ghost" data-auth-open>Đăng nhập</button>
                <a class="al-primary" href="<?php echo esc_url( home_url( '/dang-tin/' ) ); ?>"><i data-lucide="plus"></i><span>Đăng tin</span></a>
                <button class="al-menu-btn" data-menu aria-label="Mở menu"><i data-lucide="menu"></i></button>
            </div>
        </div>
    </div>
</header>

<main class="al-archive">
    <section class="al-archive-hero">
        <div class="al-shell">
            <nav class="al-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a>
                <i data-lucide="chevron-right"></i>
                <span>Mua bán nhà đất</span>
            </nav>
            <h1>Mua bán nhà đất toàn quốc</h1>
            <p><?php echo esc_html( number_format_i18n( $total ) ); ?> tin đăng đang được cập nhật, lọc theo giá, diện tích, khu vực và người đăng.</p>
            <form class="al-archive-search" action="<?php echo esc_url( home_url( '/mua-ban-nha-dat-a4/' ) ); ?>" method="get">
                <label>
                    <span>Từ khóa</span>
                    <input name="keyword" type="search" value="<?php echo esc_attr( $keyword ); ?>" placeholder="Nhập địa điểm, dự án hoặc tiêu đề">
                </label>
                <label>
                    <span>Loại nhà đất</span>
                    <select name="type">
                        <option value="">Tất cả loại hình</option>
                        <?php foreach ( is_wp_error( $property_terms ) ? array() : $property_terms as $term ) : ?>
                            <option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $type, $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>
                    <span>Khu vực</span>
                    <select name="location">
                        <option value="">Toàn quốc</option>
                        <?php foreach ( is_wp_error( $location_terms ) ? array() : $location_terms as $term ) : ?>
                            <option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $location, $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button type="submit"><i data-lucide="search"></i><span>Tìm kiếm</span></button>
            </form>
        </div>
    </section>

    <section class="al-shell al-archive-layout">
        <aside class="al-filter-panel">
            <div class="al-map-card">
                <div class="al-map-card__map">
                    <span></span><span></span><span></span>
                </div>
                <strong>Tìm kiếm theo bản đồ</strong>
                <p>Xem khu vực, biên giá và mật độ tin đăng theo vị trí.</p>
                <button type="button">Mở bản đồ</button>
            </div>

            <div class="al-filter-block">
                <h2>Lọc theo giá</h2>
                <?php foreach ( $price_ranges as $slug => $range ) : ?>
                    <a class="<?php echo $price === $slug ? 'is-active' : ''; ?>" href="<?php echo al_buy_url( array( 'price' => $price === $slug ? '' : $slug ) ); ?>"><?php echo esc_html( $range['label'] ); ?></a>
                <?php endforeach; ?>
            </div>

            <div class="al-filter-block">
                <h2>Lọc theo diện tích</h2>
                <?php foreach ( $area_ranges as $slug => $range ) : ?>
                    <a class="<?php echo $area === $slug ? 'is-active' : ''; ?>" href="<?php echo al_buy_url( array( 'area' => $area === $slug ? '' : $slug ) ); ?>"><?php echo esc_html( $range['label'] ); ?></a>
                <?php endforeach; ?>
            </div>

            <div class="al-filter-block">
                <h2>Khu vực nổi bật</h2>
                <?php foreach ( is_wp_error( $location_terms ) ? array() : array_slice( $location_terms, 0, 6 ) as $term ) : ?>
                    <a class="<?php echo $location === $term->slug ? 'is-active' : ''; ?>" href="<?php echo al_buy_url( array( 'location' => $location === $term->slug ? '' : $term->slug ) ); ?>">
                        <span><?php echo esc_html( $term->name ); ?></span>
                        <em><?php echo esc_html( number_format_i18n( $term->count ) ); ?></em>
                    </a>
                <?php endforeach; ?>
            </div>
        </aside>

        <div class="al-results">
            <div class="al-results-toolbar">
                <div class="al-chip-row">
                    <a class="<?php echo ! $owner && '1' !== $verified ? 'is-active' : ''; ?>" href="<?php echo al_buy_url( array( 'owner' => '', 'verified' => '' ) ); ?>">Tất cả</a>
                    <a class="<?php echo 'Môi giới' === $owner ? 'is-active' : ''; ?>" href="<?php echo al_buy_url( array( 'owner' => 'Môi giới', 'verified' => '' ) ); ?>">Môi giới</a>
                    <a class="<?php echo 'Chính chủ' === $owner ? 'is-active' : ''; ?>" href="<?php echo al_buy_url( array( 'owner' => 'Chính chủ', 'verified' => '' ) ); ?>">Chính chủ</a>
                    <a class="<?php echo '1' === $verified ? 'is-active' : ''; ?>" href="<?php echo al_buy_url( array( 'verified' => '1', 'owner' => '' ) ); ?>">Đã xác thực</a>
                </div>
                <form action="<?php echo esc_url( home_url( '/mua-ban-nha-dat-a4/' ) ); ?>" method="get">
                    <?php foreach ( array( 'keyword', 'type', 'location', 'price', 'area', 'owner', 'verified' ) as $field ) : ?>
                        <?php if ( isset( $_GET[ $field ] ) && '' !== $_GET[ $field ] ) : ?>
                            <input type="hidden" name="<?php echo esc_attr( $field ); ?>" value="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_GET[ $field ] ) ) ); ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="newest" <?php selected( $sort, 'newest' ); ?>>Mới nhất</option>
                        <option value="price_asc" <?php selected( $sort, 'price_asc' ); ?>>Giá thấp đến cao</option>
                        <option value="price_desc" <?php selected( $sort, 'price_desc' ); ?>>Giá cao đến thấp</option>
                        <option value="area_desc" <?php selected( $sort, 'area_desc' ); ?>>Diện tích lớn nhất</option>
                    </select>
                </form>
            </div>

            <?php if ( $listings->have_posts() ) : ?>
                <div class="al-result-list">
                    <?php
                    while ( $listings->have_posts() ) :
                        $listings->the_post();
                        $id       = get_the_ID();
                        $image    = get_post_meta( $id, '_al_image', true );
                        $badge    = get_post_meta( $id, '_al_badge', true );
                        $loc      = get_post_meta( $id, '_al_location', true );
                        $price_lb = get_post_meta( $id, '_al_price_label', true );
                        $area_lb  = get_post_meta( $id, '_al_area', true );
                        $ppm      = get_post_meta( $id, '_al_ppm', true );
                        $score    = get_post_meta( $id, '_al_score', true );
                        $agent    = get_post_meta( $id, '_al_agent', true );
                        $phone    = get_post_meta( $id, '_al_phone', true );
                        ?>
                        <article class="al-result-card">
                            <a class="al-result-card__image" href="<?php the_permalink(); ?>" style="background-image: url('<?php echo esc_url( $image ); ?>')">
                                <span><?php echo esc_html( $badge ? $badge : 'Tin mới' ); ?></span>
                            </a>
                            <div class="al-result-card__body">
                                <div class="al-result-card__top">
                                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                    <button aria-label="Lưu tin"><i data-lucide="heart"></i></button>
                                </div>
                                <p class="al-result-card__loc"><i data-lucide="map-pin"></i><?php echo esc_html( $loc ); ?></p>
                                <div class="al-result-card__meta">
                                    <strong><?php echo esc_html( $price_lb ); ?></strong>
                                    <span><?php echo esc_html( $area_lb ); ?></span>
                                    <?php if ( $ppm ) : ?><em><?php echo esc_html( $ppm ); ?></em><?php endif; ?>
                                </div>
                                <p class="al-result-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_content(), 24 ) ); ?></p>
                                <div class="al-result-card__footer">
                                    <div>
                                        <b><?php echo esc_html( $agent ); ?></b>
                                        <small><?php echo esc_html( $phone ); ?></small>
                                    </div>
                                    <span><i data-lucide="shield-check"></i>Điểm <?php echo esc_html( $score ? $score : '7.0' ); ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php if ( $listings->max_num_pages > 1 ) : ?>
                    <nav class="al-pagination" aria-label="Phân trang">
                        <?php for ( $i = 1; $i <= $listings->max_num_pages; $i++ ) : ?>
                            <a class="<?php echo $i === $page_no ? 'is-active' : ''; ?>" href="<?php echo al_buy_url( array( 'page_no' => $i ) ); ?>"><?php echo esc_html( $i ); ?></a>
                        <?php endfor; ?>
                    </nav>
                <?php endif; ?>
            <?php else : ?>
                <div class="al-empty">
                    <i data-lucide="search-x"></i>
                    <h2>Chưa có tin phù hợp</h2>
                    <p>Thử bỏ bớt bộ lọc hoặc quay lại danh sách tất cả tin đăng.</p>
                    <a class="al-primary" href="<?php echo esc_url( home_url( '/mua-ban-nha-dat-a4/' ) ); ?>">Xem tất cả</a>
                </div>
            <?php endif; wp_reset_postdata(); ?>
        </div>
    </section>
</main>

<?php require get_stylesheet_directory() . '/template-parts/astraland-modals.php'; ?>
<?php wp_footer(); ?>
</body>
</html>
