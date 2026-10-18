/* ============================================================
   شخصی‌ساز سه‌بعدی — پیش‌نمایش زنده‌ی محصول با رنگ/نوشته/نوع
   ============================================================ */
import { KawaiiScene } from "./kawaii-scene.js";

const el = document.getElementById("custom-scene");

if (el) {
    const scene3d = new KawaiiScene(el, {
        type: "plush",
        color: "#f8c8dc",
        autoRotate: true,
    });

    // ── انتخاب نوع محصول ──
    const typeInput = document.getElementById("product-type");
    const typeMap = {
        plush: "plush",
        bag: "bag",
        stationery: "plush",   // برای سایر موارد فعلاً از مدل‌های موجود استفاده می‌شود
        cosmetics: "bag",
        mug: "mug",
        gift: "mug",
    };

    document.querySelectorAll("#type-grid .type-card").forEach((card) => {
        card.addEventListener("click", () => {
            document.querySelectorAll("#type-grid .type-card").forEach((c) => c.classList.remove("selected"));
            card.classList.add("selected");

            const type = card.dataset.type;
            typeInput.value = type;

            // پیش‌نمایش سه‌بعدی متناسب با محصول انتخابی
            scene3d.setType(typeMap[type] || "plush");
        });
    });

    // ── انتخاب رنگ ──
    const colorInput = document.getElementById("custom-color");
    document.querySelectorAll("#custom-colors button").forEach((btn) => {
        btn.addEventListener("click", () => {
            document.querySelectorAll("#custom-colors button").forEach((b) => b.classList.remove("active"));
            btn.classList.add("active");
            const color = btn.dataset.color;
            colorInput.value = color;
            scene3d.setColor(color);
        });
    });

    // ── نوشته‌ی روی محصول → برچسب شناور سه‌بعدی ──
    const textInput = document.getElementById("custom-text");
    if (textInput) {
        textInput.addEventListener("input", () => scene3d.setText(textInput.value));
    }
}
