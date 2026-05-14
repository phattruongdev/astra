<?php
/**
 * Custom submit listing page at /dang-tin.
 *
 * @package Astra_Child
 */

$property_terms = get_terms( array( 'taxonomy' => 'al_property_type', 'hide_empty' => false ) );
$location_terms = get_terms( array( 'taxonomy' => 'al_location', 'hide_empty' => false ) );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'astraland-homepage al-submit-page' ); ?>>
<?php wp_body_open(); ?>

<header class="al-header" data-header>
    <div class="al-topbar">
        <div class="al-shell al-topbar__inner">
            <a class="al-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="AstraLand">
                <span class="al-brand__mark">A</span>
                <span class="al-brand__text">AstraLand</span>
            </a>
            <nav class="al-nav" aria-label="Danh mục chính" data-nav>
                <a class="al-nav__link" href="<?php echo esc_url( home_url( '/mua-ban-nha-dat-a4/' ) ); ?>">Mua bán nhà đất</a>
                <a class="al-nav__link" href="#">Cho thuê nhà đất</a>
                <a class="al-nav__link" href="#">Dự án</a>
                <a class="al-nav__link" href="#">Tin tức</a>
            </nav>
            <div class="al-actions">
                <button class="al-ghost" data-auth-open>Đăng nhập</button>
                <a class="al-primary" href="<?php echo esc_url( home_url( '/mua-ban-nha-dat-a4/' ) ); ?>"><i data-lucide="list"></i><span>Xem tin</span></a>
                <button class="al-menu-btn" data-menu aria-label="Mở menu"><i data-lucide="menu"></i></button>
            </div>
        </div>
    </div>
</header>

<main class="al-submit">
    <section class="al-submit-hero">
        <div class="al-shell">
            <nav class="al-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a>
                <i data-lucide="chevron-right"></i>
                <span>Đăng tin</span>
            </nav>
            <h1>Đăng tin bất động sản</h1>
            <p>Hoàn thiện thông tin tài sản, giá bán, vị trí và liên hệ để đưa tin lên danh sách mua bán.</p>
        </div>
    </section>

    <section class="al-shell al-submit-layout">
        <aside class="al-submit-guide">
            <div class="al-submit-steps" data-step-indicator>
                <button class="is-active" type="button" data-step-dot="1"><span>1</span>Thông tin cơ bản</button>
                <button type="button" data-step-dot="2"><span>2</span>Giá và vị trí</button>
                <button type="button" data-step-dot="3"><span>3</span>Liên hệ và xem trước</button>
            </div>
            <div class="al-submit-tip">
                <i data-lucide="badge-check"></i>
                <strong>Tin chất lượng cao</strong>
                <p>Tiêu đề rõ vị trí, giá và pháp lý sẽ giúp tin có tỷ lệ liên hệ tốt hơn.</p>
            </div>
        </aside>

        <form class="al-submit-form" data-post-form data-step-form>
            <section class="al-step is-active" data-step="1">
                <div class="al-form-heading">
                    <p>Bước 1</p>
                    <h2>Thông tin cơ bản</h2>
                </div>
                <label class="al-form__full">Tiêu đề tin
                    <input name="title" type="text" placeholder="VD: Bán nhà phố 4 tầng, hẻm ô tô, sổ riêng" required data-preview-title>
                </label>
                <label>Loại nhà đất
                    <select name="property_type" required>
                        <?php foreach ( is_wp_error( $property_terms ) ? array() : $property_terms as $term ) : ?>
                            <option><?php echo esc_html( $term->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Người đăng
                    <select name="owner_type">
                        <option>Môi giới</option>
                        <option>Chính chủ</option>
                    </select>
                </label>
                <label>Phòng ngủ
                    <input name="bedrooms" type="number" min="0" placeholder="3">
                </label>
                <label>Phòng tắm
                    <input name="bathrooms" type="number" min="0" placeholder="2">
                </label>
                <label class="al-form__full">Mô tả
                    <textarea name="description" rows="7" placeholder="Mô tả chi tiết pháp lý, hướng nhà, tiện ích, nội thất và điểm nổi bật." required></textarea>
                </label>
            </section>

            <section class="al-step" data-step="2">
                <div class="al-form-heading">
                    <p>Bước 2</p>
                    <h2>Giá bán và vị trí</h2>
                </div>
                <label>Giá bán
                    <input name="price" type="text" placeholder="VD: 12,5 tỷ" required data-preview-price>
                </label>
                <label>Diện tích
                    <input name="area" type="text" placeholder="VD: 72 m²" required data-preview-area>
                </label>
                <label>Khu vực
                    <select name="location" required data-preview-location>
                        <?php foreach ( is_wp_error( $location_terms ) ? array() : $location_terms as $term ) : ?>
                            <option><?php echo esc_html( $term->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="al-form__full">Địa chỉ chi tiết
                    <input name="address" type="text" placeholder="VD: Đường Nguyễn Thị Thập, Quận 7, TP. Hồ Chí Minh" required>
                </label>
                <label class="al-form__full">Ảnh đại diện URL
                    <input name="image" type="url" placeholder="https://images.unsplash.com/...">
                </label>
            </section>

            <section class="al-step" data-step="3">
                <div class="al-form-heading">
                    <p>Bước 3</p>
                    <h2>Liên hệ và xem trước</h2>
                </div>
                <label>Họ tên liên hệ
                    <input name="contact_name" type="text" placeholder="Nguyễn Văn A">
                </label>
                <label>Số điện thoại
                    <input name="phone" type="tel" placeholder="0900 000 000">
                </label>
                <div class="al-submit-preview al-form__full">
                    <span>Tin xem trước</span>
                    <h3 data-preview-output="title">Tiêu đề tin của bạn</h3>
                    <p data-preview-output="location">Khu vực</p>
                    <div>
                        <strong data-preview-output="price">Giá bán</strong>
                        <em data-preview-output="area">Diện tích</em>
                    </div>
                </div>
            </section>

            <div class="al-submit-actions">
                <button class="al-ghost" type="button" data-step-prev>Quay lại</button>
                <button class="al-primary" type="button" data-step-next>Tiếp tục</button>
                <button class="al-primary is-submit" type="submit">Đăng tin</button>
            </div>
            <p class="al-form__message" data-form-message></p>
        </form>
    </section>
</main>

<?php require get_stylesheet_directory() . '/template-parts/astraland-modals.php'; ?>
<?php wp_footer(); ?>
</body>
</html>
