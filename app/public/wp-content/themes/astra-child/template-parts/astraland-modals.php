<?php
/**
 * Shared auth and quick post modals.
 *
 * @package Astra_Child
 */
?>

<div class="al-modal" data-modal="auth" aria-hidden="true">
    <div class="al-modal__backdrop" data-modal-close></div>
    <section class="al-modal__panel" role="dialog" aria-modal="true" aria-labelledby="al-auth-title">
        <button class="al-modal__close" data-modal-close aria-label="Đóng"><i data-lucide="x"></i></button>
        <div class="al-modal__tabs">
            <button class="is-selected" data-auth-tab="login">Đăng nhập</button>
            <button data-auth-tab="register">Đăng ký</button>
        </div>
        <form class="al-form is-active" data-auth-form="login">
            <h2 id="al-auth-title">Đăng nhập tài khoản</h2>
            <label>Email hoặc tên đăng nhập<input name="email" type="text" autocomplete="username" required></label>
            <label>Mật khẩu<input name="password" type="password" autocomplete="current-password" required></label>
            <button class="al-primary" type="submit">Đăng nhập</button>
            <p class="al-form__message" data-form-message></p>
        </form>
        <form class="al-form" data-auth-form="register">
            <h2>Tạo tài khoản mới</h2>
            <label>Họ tên<input name="name" type="text" autocomplete="name" required></label>
            <label>Email<input name="email" type="email" autocomplete="email" required></label>
            <label>Mật khẩu<input name="password" type="password" autocomplete="new-password" minlength="6" required></label>
            <button class="al-primary" type="submit">Đăng ký</button>
            <p class="al-form__message" data-form-message></p>
        </form>
    </section>
</div>

<div class="al-modal" data-modal="post" aria-hidden="true">
    <div class="al-modal__backdrop" data-modal-close></div>
    <section class="al-modal__panel al-modal__panel--wide" role="dialog" aria-modal="true" aria-labelledby="al-post-title">
        <button class="al-modal__close" data-modal-close aria-label="Đóng"><i data-lucide="x"></i></button>
        <form class="al-form al-form--grid is-active" data-post-form>
            <h2 id="al-post-title">Đăng tin bất động sản</h2>
            <label class="al-form__full">Tiêu đề tin<input name="title" type="text" placeholder="Nhà phố 4 tầng, hẻm ô tô, sổ riêng" required></label>
            <label>Loại nhà đất
                <select name="property_type">
                    <option>Nhà riêng</option>
                    <option>Căn hộ chung cư</option>
                    <option>Đất nền</option>
                    <option>Văn phòng</option>
                </select>
            </label>
            <label>Khu vực
                <select name="location">
                    <option>TP. Hồ Chí Minh</option>
                    <option>Hà Nội</option>
                    <option>Đà Nẵng</option>
                    <option>Bình Dương</option>
                    <option>Đồng Nai</option>
                </select>
            </label>
            <label>Giá<input name="price" type="text" placeholder="4,5 tỷ" required></label>
            <label>Diện tích<input name="area" type="text" placeholder="72 m²" required></label>
            <label class="al-form__full">Địa chỉ<input name="address" type="text" placeholder="Quận 7, TP. Hồ Chí Minh" required></label>
            <label class="al-form__full">Mô tả<textarea name="description" rows="5" placeholder="Mô tả pháp lý, tiện ích, hướng nhà, tình trạng nội thất"></textarea></label>
            <button class="al-primary al-form__full" type="submit">Gửi tin</button>
            <p class="al-form__message al-form__full" data-form-message></p>
        </form>
    </section>
</div>
