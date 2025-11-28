/* ==============================
   NÚT BACK TO TOP
============================== */
const backToTopBtn = document.getElementById("backToTopBtn");

if (backToTopBtn) {
    window.addEventListener("scroll", () => {
        if (document.documentElement.scrollTop > 300) {
            backToTopBtn.classList.add("show");
        } else {
            backToTopBtn.classList.remove("show");
        }
    });

    backToTopBtn.addEventListener("click", () => {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
}

/* ==============================
   SLIDER BOOK CUSTOM
============================== */
document.addEventListener("DOMContentLoaded", () => {
    // Tìm container slider
    const sliderContainer = document.querySelector(".book-slider");
    
    // Nếu không có slider trong trang thì dừng code để tránh lỗi
    if (!sliderContainer) return;

    const slides = sliderContainer.querySelectorAll(".book-slide");
    const nextBtn = sliderContainer.querySelector(".next");
    const prevBtn = sliderContainer.querySelector(".prev");
    
    let index = 0;
    let autoSlideInterval; // Biến lưu bộ đếm thời gian

    if (slides.length === 0) return;

    // Hàm hiển thị slide theo index
    function showSlide(i) {
        // Xóa class active ở tất cả slide
        slides.forEach(slide => slide.classList.remove("active"));
        // Thêm class active vào slide hiện tại
        slides[i].classList.add("active");
    }

    // Hàm chuyển sang slide kế tiếp
    function nextSlide() {
        index = (index + 1) % slides.length;
        showSlide(index);
    }

    // Hàm quay lại slide trước
    function prevSlide() {
        index = (index - 1 + slides.length) % slides.length;
        showSlide(index);
    }

    // Hàm Reset bộ đếm tự động
    // (Để khi người dùng bấm nút, nó không tự chuyển ngay lập tức gây khó chịu)
    function resetAutoSlide() {
        clearInterval(autoSlideInterval);
        autoSlideInterval = setInterval(nextSlide, 5000);
    }

    // Gán sự kiện cho nút Next
    if (nextBtn) {
        nextBtn.addEventListener("click", () => {
            nextSlide();
            resetAutoSlide(); // Reset lại thời gian chờ 5s
        });
    }

    // Gán sự kiện cho nút Prev
    if (prevBtn) {
        prevBtn.addEventListener("click", () => {
            prevSlide();
            resetAutoSlide(); // Reset lại thời gian chờ 5s
        });
    }

    // Bắt đầu chạy slide tự động
    autoSlideInterval = setInterval(nextSlide, 5000);
});
