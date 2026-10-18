/* صحنه‌ی سه‌بعدی هیرو — عروسک بامزه‌ی شناور با قابلیت چرخش */
import { KawaiiScene } from "./kawaii-scene.js";

const el = document.getElementById("hero-scene");
if (el) {
    new KawaiiScene(el, {
        type: "plush",
        color: "#f8c8dc",
        autoRotate: true,
    });
}
