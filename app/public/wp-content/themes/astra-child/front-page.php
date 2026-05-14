<?php
/**
 * Front page template for the AstraLand real estate portal mockup.
 *
 * @package Astra_Child
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'astraland-homepage' ); ?>>
<?php wp_body_open(); ?>

<header class="al-header" data-header>
    <div class="al-topbar">
        <div class="al-shell al-topbar__inner">
            <div class="al-brand" aria-label="AstraLand">
                <span class="al-brand__mark">A</span>
                <span class="al-brand__text">AstraLand</span>
            </div>
            <nav class="al-nav" aria-label="Danh mục chính">
                <button class="al-nav__item is-active" data-mega="buy">Mua bán nhà đất</button>
                <button class="al-nav__item" data-mega="rent">Cho thuê nhà đất</button>
                <button class="al-nav__item" data-mega="transfer">Sang nhượng</button>
                <a class="al-nav__link" href="#projects">Dự án</a>
                <a class="al-nav__link" href="#news">Tin tức</a>
                <a class="al-nav__link al-nav__new" href="#explore">Khám phá</a>
            </nav>
            <div class="al-actions">
                <button class="al-icon-btn" aria-label="Thông báo"><i data-lucide="bell"></i></button>
                <button class="al-ghost">Đăng nhập</button>
                <button class="al-primary"><i data-lucide="plus"></i><span>Đăng tin</span></button>
                <button class="al-menu-btn" data-menu aria-label="Mở menu"><i data-lucide="menu"></i></button>
            </div>
        </div>
    </div>

    <div class="al-mega" data-mega-panel>
        <div class="al-shell al-mega__grid">
            <div>
                <h3>Mua bán phổ biến</h3>
                <a href="#">Bán căn hộ chung cư</a>
                <a href="#">Bán nhà riêng</a>
                <a href="#">Bán nhà mặt phố</a>
                <a href="#">Bán đất nền dự án</a>
            </div>
            <div>
                <h3>Theo khu vực</h3>
                <a href="#">Nhà đất Hà Nội</a>
                <a href="#">Nhà đất Hồ Chí Minh</a>
                <a href="#">Nhà đất Đà Nẵng</a>
                <a href="#">Nhà đất Bình Dương</a>
            </div>
            <div>
                <h3>Công cụ</h3>
                <a href="#">Định giá bất động sản</a>
                <a href="#">Tra cứu quy hoạch</a>
                <a href="#">Tính khoản vay</a>
                <a href="#">So sánh khu vực</a>
            </div>
        </div>
    </div>
</header>

<main>
    <section class="al-hero">
        <div class="al-hero__media" role="img" aria-label="Thành phố hiện đại nhìn từ trên cao"></div>
        <div class="al-shell al-hero__content">
            <p class="al-kicker">Cổng thông tin bất động sản xác thực 4.0</p>
            <h1>Tìm nhà đất nhanh hơn với dữ liệu thị trường rõ ràng</h1>
            <form class="al-search" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                <div class="al-search__tabs" role="tablist" aria-label="Loại giao dịch">
                    <button type="button" class="is-selected">Mua bán</button>
                    <button type="button">Cho thuê</button>
                    <button type="button">Dự án</button>
                </div>
                <div class="al-search__row">
                    <label class="al-field al-field--select">
                        <span>Loại nhà đất</span>
                        <select name="property_type">
                            <option>Nhà riêng</option>
                            <option>Căn hộ chung cư</option>
                            <option>Đất nền</option>
                            <option>Văn phòng</option>
                        </select>
                    </label>
                    <label class="al-field al-field--grow">
                        <span>Từ khóa, địa điểm hoặc dự án</span>
                        <input name="s" type="search" placeholder="Ví dụ: căn hộ 2 phòng ngủ tại Quận 7">
                    </label>
                    <button class="al-search__button" type="submit"><i data-lucide="search"></i><span>Tìm kiếm</span></button>
                </div>
                <div class="al-quick">
                    <a href="#">Hà Nội</a>
                    <a href="#">TP. Hồ Chí Minh</a>
                    <a href="#">Đà Nẵng</a>
                    <a href="#">Bình Dương</a>
                    <a href="#">Dưới 3 tỷ</a>
                </div>
            </form>
        </div>
    </section>

    <section class="al-shell al-market" aria-label="Thống kê thị trường">
        <div class="al-market__headline">
            <i data-lucide="trending-up"></i>
            <span>Thị trường bất động sản hôm nay</span>
            <strong>14/05/2026</strong>
        </div>
        <div class="al-stat">
            <span>Tin đăng đang hiệu lực</span>
            <strong>41.420</strong>
        </div>
        <div class="al-stat">
            <span>Tin đăng hôm nay</span>
            <strong>2.048</strong>
        </div>
        <div class="al-bars">
            <?php
            $bars = array(
                'Nhà riêng' => 708,
                'Căn hộ'    => 232,
                'Đất'       => 169,
                'Đất nền'   => 141,
                'Biệt thự'  => 139,
            );
            foreach ( $bars as $label => $value ) :
                $height = max( 22, min( 100, (int) ( $value / 7 ) ) );
                ?>
                <div class="al-bar" style="--h: <?php echo esc_attr( $height ); ?>%">
                    <strong><?php echo esc_html( $value ); ?></strong>
                    <span></span>
                    <em><?php echo esc_html( $label ); ?></em>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="al-shell al-ticker" aria-label="Tin đăng mới">
        <div class="al-section-title al-section-title--compact">
            <h2>Tin nóng</h2>
            <div class="al-segment">
                <button class="is-selected">Tin nóng</button>
                <button>Tin đăng</button>
            </div>
        </div>
        <div class="al-ticker__list">
            <?php
            $hot_items = array(
                array( 'Ngay trung tâm, hẻm ô tô, nhà 3 tầng mới hoàn thiện', 'Q. Phú Nhuận', '4 tỷ', '36 m²', 'Vừa xong' ),
                array( 'Căn hộ view sông, nội thất đầy đủ, nhận nhà ngay', 'Q. Bình Thạnh', '5,8 tỷ', '72 m²', '1 phút trước' ),
                array( 'Nhà phố kinh doanh mặt tiền rộng, dòng tiền ổn định', 'Q. Cầu Giấy', '26,8 tỷ', '57 m²', '3 phút trước' ),
                array( 'Đất nền khu dân cư hiện hữu, sổ riêng từng nền', 'TP. Biên Hòa', '3,59 tỷ', '70 m²', '5 phút trước' ),
                array( 'Căn góc chung cư gần metro, ban công thoáng', 'Q. 7', '4,59 tỷ', '96 m²', '8 phút trước' ),
            );
            foreach ( $hot_items as $item ) :
                ?>
                <a href="#" class="al-hot">
                    <span><?php echo esc_html( $item[0] ); ?></span>
                    <small><?php echo esc_html( $item[1] ); ?></small>
                    <strong><?php echo esc_html( $item[2] ); ?></strong>
                    <em><?php echo esc_html( $item[3] ); ?></em>
                    <time><?php echo esc_html( $item[4] ); ?></time>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="al-shell al-featured" aria-label="Bất động sản nổi bật">
        <div class="al-section-title">
            <div>
                <p>Bất động sản nổi bật</p>
                <h2>Lựa chọn được xác thực và cập nhật liên tục</h2>
            </div>
            <div class="al-segment">
                <button class="is-selected">Mua bán</button>
                <button>Cho thuê</button>
                <button>Sang nhượng</button>
            </div>
        </div>
        <div class="al-listing-grid">
            <?php
            $listings = array(
                array( 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=900&q=80', 'Nhà phố 4 tầng gần công viên, pháp lý rõ ràng', 'Q. 7, TP. Hồ Chí Minh', '12,5 tỷ', '72 m²', '4 PN', '5 WC', 'Vàng' ),
                array( 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=900&q=80', 'Căn hộ tầng cao view sông, nội thất tinh gọn', 'Q. Bình Thạnh, TP. Hồ Chí Minh', '4,59 tỷ', '150 m²', '4 PN', '2 WC', 'Xác thực' ),
                array( 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=900&q=80', 'Biệt thự sân vườn khu yên tĩnh, đường rộng', 'Q. Long Biên, Hà Nội', '38 tỷ', '330 m²', '5 PN', '5 WC', 'Bạc' ),
                array( 'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=900&q=80', 'Đất thổ cư mặt tiền kinh doanh, dân cư đông', 'Bàu Bàng, Bình Dương', '950 triệu', '250 m²', 'Sổ riêng', 'Đường 12m', 'Mới' ),
                array( 'https://images.unsplash.com/photo-1605146769289-440113cc3d00?auto=format&fit=crop&w=900&q=80', 'Shophouse trục chính khu đô thị, khai thác ngay', 'TP. Thủ Đức, TP. Hồ Chí Minh', '21,5 tỷ', '108 m²', '3 tầng', 'Mặt tiền', 'Hot' ),
                array( 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=900&q=80', 'Penthouse thông tầng, sân vườn riêng', 'Q. Tây Hồ, Hà Nội', '60 tỷ', '500 m²', '6 PN', '5 WC', 'Cao cấp' ),
            );
            foreach ( $listings as $listing ) :
                ?>
                <article class="al-card">
                    <div class="al-card__image" style="background-image: url('<?php echo esc_url( $listing[0] ); ?>')">
                        <span><?php echo esc_html( $listing[7] ); ?></span>
                        <button aria-label="Lưu tin"><i data-lucide="heart"></i></button>
                    </div>
                    <div class="al-card__body">
                        <h3><?php echo esc_html( $listing[1] ); ?></h3>
                        <p><i data-lucide="map-pin"></i><?php echo esc_html( $listing[2] ); ?></p>
                        <div class="al-card__meta">
                            <strong><?php echo esc_html( $listing[3] ); ?></strong>
                            <span><?php echo esc_html( $listing[4] ); ?></span>
                        </div>
                        <div class="al-card__chips">
                            <span><?php echo esc_html( $listing[5] ); ?></span>
                            <span><?php echo esc_html( $listing[6] ); ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <a class="al-more" href="#">Tất cả tin bất động sản mới nhất <i data-lucide="arrow-right"></i></a>
    </section>

    <section id="news" class="al-news">
        <div class="al-shell">
            <div class="al-section-title">
                <div>
                    <p>Tin tức bất động sản mới nhất</p>
                    <h2>Góc nhìn thị trường và xu hướng đầu tư</h2>
                </div>
                <a href="#">Xem tất cả</a>
            </div>
            <div class="al-news__grid">
                <article class="al-news-main">
                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80" alt="Tòa nhà văn phòng hiện đại">
                    <div>
                        <span>Chuyển đổi số</span>
                        <h3>Nền tảng dữ liệu giúp giao dịch bất động sản minh bạch hơn</h3>
                        <p>Các công cụ bản đồ, định giá và xác thực tin đăng đang thay đổi cách người mua đánh giá tài sản trước khi đi xem thực tế.</p>
                    </div>
                </article>
                <div class="al-news-list">
                    <a href="#">Dòng tiền quay lại căn hộ cho thuê tại các đô thị lớn</a>
                    <a href="#">Nhà phố thương mại cần dữ liệu vận hành trước khi xuống tiền</a>
                    <a href="#">Những yếu tố làm tăng sức hút của khu đô thị vệ tinh</a>
                    <a href="#">Cách đọc biến động giá theo từng phân khúc và khu vực</a>
                </div>
            </div>
        </div>
    </section>

    <section class="al-shell al-agents" aria-label="Môi giới uy tín">
        <div class="al-section-title">
            <div>
                <p>Môi giới uy tín</p>
                <h2>Đội ngũ có hồ sơ rõ ràng</h2>
            </div>
            <button class="al-primary"><i data-lucide="user-plus"></i><span>Tham gia</span></button>
        </div>
        <div class="al-agent-grid">
            <?php
            $agents = array(
                array( 'VT', 'Võ Kim Trí', '16 tin đăng' ),
                array( 'NT', 'Nguyễn Anh Tuấn', '7 tin đăng' ),
                array( 'LN', 'Lâm Năng', '800 tin đăng' ),
                array( 'VB', 'Việt BT', '224 tin đăng' ),
                array( 'LH', 'Lê Hưng', '174 tin đăng' ),
            );
            foreach ( $agents as $agent ) :
                ?>
                <article class="al-agent">
                    <div><?php echo esc_html( $agent[0] ); ?></div>
                    <h3><?php echo esc_html( $agent[1] ); ?></h3>
                    <p><?php echo esc_html( $agent[2] ); ?></p>
                    <span><i data-lucide="badge-check"></i>Đã xác thực</span>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="explore" class="al-ecosystem">
        <div class="al-shell">
            <div class="al-section-title">
                <div>
                    <p>Khám phá hệ sinh thái bất động sản 4.0</p>
                    <h2>Công cụ hỗ trợ trước, trong và sau giao dịch</h2>
                </div>
            </div>
            <div class="al-tools">
                <article>
                    <i data-lucide="map"></i>
                    <h3>Tra cứu quy hoạch</h3>
                    <p>Bản đồ số, lớp quy hoạch và thông tin vị trí trong một màn hình.</p>
                    <a href="#">Tra cứu ngay</a>
                </article>
                <article>
                    <i data-lucide="calculator"></i>
                    <h3>Định giá tự động</h3>
                    <p>So sánh dữ liệu khu vực để tham khảo khoảng giá phù hợp.</p>
                    <a href="#">Ước tính giá</a>
                </article>
                <article>
                    <i data-lucide="messages-square"></i>
                    <h3>Quản lý khách hàng</h3>
                    <p>Lưu nguồn hàng, nhu cầu và lịch hẹn cho đội ngũ môi giới.</p>
                    <a href="#">Mở CRM</a>
                </article>
            </div>
        </div>
    </section>
</main>

<footer class="al-footer">
    <div class="al-shell al-footer__grid">
        <div>
            <div class="al-brand al-brand--footer">
                <span class="al-brand__mark">A</span>
                <span class="al-brand__text">AstraLand</span>
            </div>
            <p>Nền tảng demo bất động sản với bố cục lấy cảm hứng từ các cổng tin hiện đại.</p>
        </div>
        <div>
            <h3>Danh mục</h3>
            <a href="#">Bán căn hộ chung cư</a>
            <a href="#">Bán nhà riêng</a>
            <a href="#">Cho thuê văn phòng</a>
        </div>
        <div>
            <h3>Khu vực</h3>
            <a href="#">Hà Nội</a>
            <a href="#">Hồ Chí Minh</a>
            <a href="#">Đà Nẵng</a>
        </div>
        <div>
            <h3>Liên hệ</h3>
            <p>contact@example.com</p>
            <p>Hotline: 0900 000 000</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
