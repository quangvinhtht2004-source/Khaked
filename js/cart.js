/* =========================================
   CART LOGIC - XỬ LÝ GIỎ HÀNG (ĐÃ SỬA LỖI PHÍ SHIP)
   ========================================= */

document.addEventListener("DOMContentLoaded", () => {
    
    // Cập nhật badge giỏ hàng
    function updateCartBadge() {
        const badge = document.querySelector(".header-actions .badge");
        if (!badge) return;
        
        const cartItems = document.querySelectorAll(".cart-item");
        const totalItems = cartItems.length;
        
        if (totalItems === 0) {
            // Ẩn badge khi giỏ hàng trống
            badge.style.display = "none";
        } else {
            // Hiển thị badge với số lượng
            badge.style.display = "inline-block";
            badge.textContent = totalItems;
        }
    }
    
    // Cập nhật tổng giỏ hàng
    function updateCartTotal() {
        let subTotal = 0;
        const cartItems = document.querySelectorAll(".cart-item");

        cartItems.forEach(item => {
            const priceElement = item.querySelector(".product-price");
            const qtyElement = item.querySelector(".qty-input");
            const itemTotalElement = item.querySelector(".item-total-price");

            // Lấy giá gốc từ data-price
            const price = parseFloat(priceElement.getAttribute("data-price"));
            const qty = parseInt(qtyElement.value);

            // Tính thành tiền từng món
            const itemTotal = price * qty;
            itemTotalElement.innerText = itemTotal.toLocaleString("vi-VN") + "đ";

            subTotal += itemTotal;
        });

        // --- ĐOẠN SỬA LỖI Ở ĐÂY ---
        let shippingFee = 30000; // Phí ship mặc định

        // Kiểm tra: Nếu giỏ hàng trống (Tạm tính = 0) thì Phí ship = 0
        if (subTotal === 0) {
            shippingFee = 0;
        }

        const finalTotal = subTotal + shippingFee;

        // Cập nhật giao diện
        document.getElementById("sub-total").innerText = subTotal.toLocaleString("vi-VN") + "đ";
        document.getElementById("final-total").innerText = finalTotal.toLocaleString("vi-VN") + "đ";
        
        // Cập nhật badge
        updateCartBadge();
        
        // (Tùy chọn) Cập nhật hiển thị dòng phí vận chuyển trên giao diện nếu cần
        // Bạn cần thêm id="shipping-fee" vào thẻ span chứa 30.000đ ở file HTML nếu muốn số này nhảy tự động
    }

    // Xử lý nút Tăng/Giảm số lượng
    const qtyButtons = document.querySelectorAll(".qty-btn");
    
    qtyButtons.forEach(btn => {
        btn.addEventListener("click", (e) => {
            const input = e.target.closest(".qty-control").querySelector(".qty-input");
            let value = parseInt(input.value);

            if (btn.classList.contains("plus")) {
                value++;
            } else if (btn.classList.contains("minus")) {
                if (value > 1) value--;
            }

            input.value = value;
            updateCartTotal(); 
        });
    });

    // Xử lý nút Xóa sản phẩm
    const removeButtons = document.querySelectorAll(".remove-btn");
    removeButtons.forEach(btn => {
        btn.addEventListener("click", (e) => {
            const item = e.target.closest(".cart-item");
            item.remove();
            
            // Kiểm tra lại nếu xóa hết thì hiện thông báo trống (Logic thêm)
            const cartList = document.querySelector(".cart-list");
            if(cartList.querySelectorAll(".cart-item").length === 0){
                 // Nếu muốn hiện lại thông báo giỏ hàng trống thì code ở đây
                 // Hiện tại ta chỉ cần cập nhật lại tiền về 0
            }

            updateCartTotal(); 
        });
    });

    // Chạy lần đầu để tính đúng số liệu ban đầu
    updateCartTotal();
    updateCartBadge();
});