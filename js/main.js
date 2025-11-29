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

    /* ==============================
       ADVANCED FILTER / SEARCH
    ============================== */
    const productCards = document.querySelectorAll(".product-card");

    if (productCards.length > 0) {
        // Sử dụng search-box input trong header thay vì filterSearch
        const searchInput = document.querySelector(".search-box input");
        const categorySelect = document.getElementById("filterCategory");
        const priceSelect = document.getElementById("filterPrice");
        const saleCheckbox = document.getElementById("filterSale");
        const resetBtn = document.getElementById("filterReset");
        const emptyState = document.getElementById("filterEmptyState");

        const priceMatchers = {
            all: () => true,
            lt100: price => price < 100000,
            "100-150": price => price >= 100000 && price <= 150000,
            gt150: price => price > 150000
        };

        function getCardTitle(card) {
            const datasetTitle = card.dataset.title;
            if (datasetTitle) return datasetTitle.toLowerCase();
            const nameEl = card.querySelector(".p-name");
            return nameEl ? nameEl.textContent.toLowerCase() : "";
        }

        function applyFilters() {
            const searchTerm = searchInput ? searchInput.value.trim().toLowerCase() : "";
            const categoryValue = categorySelect ? categorySelect.value : "all";
            const priceValue = priceSelect ? priceSelect.value : "all";
            const saleOnly = saleCheckbox ? saleCheckbox.checked : false;

            const matchPrice = priceMatchers[priceValue] || priceMatchers.all;

            let visibleCount = 0;

            productCards.forEach(card => {
                const title = getCardTitle(card);
                const category = card.dataset.category || "all";
                const price = parseInt(card.dataset.price || "0", 10);
                const isSale = card.dataset.sale === "true";

                let visible = true;

                if (searchTerm && !title.includes(searchTerm)) {
                    visible = false;
                }

                if (categoryValue !== "all" && category !== categoryValue) {
                    visible = false;
                }

                if (!matchPrice(price)) {
                    visible = false;
                }

                if (saleOnly && !isSale) {
                    visible = false;
                }

                card.style.display = visible ? "block" : "none";

                if (visible) visibleCount += 1;
            });

            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? "flex" : "none";
            }
        }

        function attachChangeListener(element, eventName = "change") {
            if (!element) return;
            element.addEventListener(eventName, applyFilters);
        }

        attachChangeListener(searchInput, "input");
        attachChangeListener(categorySelect);
        attachChangeListener(priceSelect);
        attachChangeListener(saleCheckbox);

        if (resetBtn) {
            resetBtn.addEventListener("click", () => {
                if (searchInput) searchInput.value = "";
                if (categorySelect) categorySelect.value = "all";
                if (priceSelect) priceSelect.value = "all";
                if (saleCheckbox) saleCheckbox.checked = false;
                applyFilters();
            });
        }

        applyFilters();
    }
});
