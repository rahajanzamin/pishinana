/* اسکریپت‌های عمومی سایت */

// محو شدن خودکار پیام‌های موفقیت/خطا بعد از ۵ ثانیه
document.querySelectorAll(".flash").forEach((flash) => {
    setTimeout(() => {
        flash.style.transition = "opacity 0.6s, transform 0.6s";
        flash.style.opacity = "0";
        flash.style.transform = "translateY(-8px)";
        setTimeout(() => flash.remove(), 700);
    }, 5000);
});

// تأیید حذف (برای دکمه‌های خطرناک)
document.querySelectorAll("[data-confirm]").forEach((el) => {
    el.addEventListener("click", (e) => {
        if (!confirm(el.dataset.confirm || "مطمئنی؟")) e.preventDefault();
    });
});
