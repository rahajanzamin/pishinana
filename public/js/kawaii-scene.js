/* ============================================================
   kawaii-scene.js — موتور صحنه‌ی سه‌بعدی کاوهایی پیشینانا
   ------------------------------------------------------------
   یک کلاس آماده برای ساخت صحنه‌ی سه‌بعدی با محصولات بامزه:
   عروسک (plush)، ماگ (mug)، کیف (bag) — با قابلیت تغییر رنگ،
   چرخش با موس و متن شناور.
   ============================================================ */

import * as THREE from "three";
import { RoomEnvironment } from "../vendor/RoomEnvironment.js";

// متن فارسی روی بوم (canvas) برای برچسب شناور
function makeTextTexture(text, bg = "#ffffff", fg = "#b0295a") {
    const canvas = document.createElement("canvas");
    canvas.width = 512;
    canvas.height = 160;
    const ctx = canvas.getContext("2d");

    // پس‌زمینه‌ی گرد
    const r = 40;
    ctx.fillStyle = bg;
    ctx.beginPath();
    ctx.roundRect(10, 10, 492, 140, r);
    ctx.fill();
    ctx.strokeStyle = "#f7c6d9";
    ctx.lineWidth = 8;
    ctx.stroke();

    // متن
    ctx.fillStyle = fg;
    ctx.font = "bold 64px Tahoma, sans-serif";
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.direction = "rtl";
    ctx.fillText(text.slice(0, 22), 256, 82);

    const texture = new THREE.CanvasTexture(canvas);
    texture.colorSpace = THREE.SRGBColorSpace;
    return texture;
}

// تکسچر قلب برای تزئینات
function makeEmojiTexture(emoji) {
    const canvas = document.createElement("canvas");
    canvas.width = 128;
    canvas.height = 128;
    const ctx = canvas.getContext("2d");
    ctx.font = "100px serif";
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.fillText(emoji, 64, 68);
    const texture = new THREE.CanvasTexture(canvas);
    texture.colorSpace = THREE.SRGBColorSpace;
    return texture;
}

export class KawaiiScene {
    constructor(container, options = {}) {
        this.container = container;
        this.type = options.type || "plush";
        this.autoRotate = options.autoRotate !== false;
        this.reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

        // ── رندر ──
        this.renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        this.renderer.setSize(container.clientWidth, container.clientHeight);
        this.renderer.setClearColor(0x000000, 0);
        this.renderer.shadowMap.enabled = true;
        this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
        this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
        this.renderer.toneMappingExposure = 1.15;
        container.appendChild(this.renderer.domElement);

        // ── دوربین ──
        this.camera = new THREE.PerspectiveCamera(40, container.clientWidth / container.clientHeight, 0.1, 50);
        this.camera.position.set(0, 1.3, 6.4);
        this.camera.lookAt(0, 0.75, 0);

        // ── صحنه ──
        this.scene = new THREE.Scene();

        // نور محیطی واقع‌گرایانه
        const pmrem = new THREE.PMREMGenerator(this.renderer);
        this.scene.environment = pmrem.fromScene(new RoomEnvironment(this.renderer), 0.06).texture;

        // ── نورپردازی صورتی و ملایم ──
        this.scene.add(new THREE.HemisphereLight(0xffe9f2, 0xf8d4e2, 1.1));

        const key = new THREE.DirectionalLight(0xffffff, 2.2);
        key.position.set(4, 6, 5);
        key.castShadow = true;
        key.shadow.mapSize.set(1024, 1024);
        key.shadow.camera.near = 1;
        key.shadow.camera.far = 20;
        key.shadow.camera.left = key.shadow.camera.bottom = -5;
        key.shadow.camera.right = key.shadow.camera.top = 5;
        this.scene.add(key);

        const fill = new THREE.DirectionalLight(0xffd0e4, 1.0);
        fill.position.set(-5, 3, -3);
        this.scene.add(fill);

        const rim = new THREE.PointLight(0xff8fb8, 40, 20, 2);
        rim.position.set(-3, 2, -3.5);
        this.scene.add(rim);

        // ── کف + سایه‌ی نرم ──
        const floor = new THREE.Mesh(
            new THREE.CircleGeometry(9, 48),
            new THREE.MeshStandardMaterial({ color: 0xfbd6e4, metalness: 0.05, roughness: 0.95 })
        );
        floor.rotation.x = -Math.PI / 2;
        floor.position.y = -1.35;
        floor.receiveShadow = true;
        this.scene.add(floor);

        // سایه‌ی گرد نرم زیر محصول (فیک، ولی خوشگل!)
        const shadowCanvas = document.createElement("canvas");
        shadowCanvas.width = shadowCanvas.height = 128;
        const sctx = shadowCanvas.getContext("2d");
        const grad = sctx.createRadialGradient(64, 64, 8, 64, 64, 62);
        grad.addColorStop(0, "rgba(176,41,90,0.30)");
        grad.addColorStop(1, "rgba(176,41,90,0)");
        sctx.fillStyle = grad;
        sctx.fillRect(0, 0, 128, 128);
        const blob = new THREE.Mesh(
            new THREE.PlaneGeometry(3.6, 3.6),
            new THREE.MeshBasicMaterial({ map: new THREE.CanvasTexture(shadowCanvas), transparent: true, depthWrite: false })
        );
        blob.rotation.x = -Math.PI / 2;
        blob.position.y = -1.33;
        this.scene.add(blob);

        // ── محصول ──
        this.productGroup = new THREE.Group();
        this.scene.add(this.productGroup);

        // متریال‌ها — برای تغییر رنگ زنده
        this.mainMat = new THREE.MeshStandardMaterial({
            color: new THREE.Color(options.color || "#f8c8dc"),
            metalness: 0.08,
            roughness: 0.55,
            envMapIntensity: 0.9,
        });
        this.creamMat = new THREE.MeshStandardMaterial({ color: 0xfff3f7, metalness: 0.05, roughness: 0.6 });
        this.darkMat = new THREE.MeshStandardMaterial({ color: 0x4a2332, metalness: 0.1, roughness: 0.45 });
        this.blushMat = new THREE.MeshStandardMaterial({ color: 0xff9db8, metalness: 0, roughness: 0.7 });

        this.targetColor = new THREE.Color(options.color || "#f8c8dc");

        this.buildProduct();

        // ── تزئینات شناور (قلب و ستاره) ──
        this.decor = new THREE.Group();
        const emojis = ["❤", "✦", "🌸", "♡", "⭐"];
        for (let i = 0; i < 14; i++) {
            const tex = makeEmojiTexture(emojis[i % emojis.length]);
            const sprite = new THREE.Sprite(
                new THREE.SpriteMaterial({ map: tex, transparent: true, opacity: 0.85, depthWrite: false })
            );
            const angle = (i / 14) * Math.PI * 2;
            sprite.position.set(Math.cos(angle) * (2.6 + Math.random()), Math.random() * 2.8 - 0.4, Math.sin(angle) * (2.2 + Math.random()) - 0.5);
            const s = 0.32 + Math.random() * 0.28;
            sprite.scale.setScalar(s);
            sprite.userData = { baseY: sprite.position.y, speed: 0.5 + Math.random() * 0.8, phase: Math.random() * 6 };
            this.decor.add(sprite);
        }
        this.scene.add(this.decor);

        // ── برچسب متن شناور ──
        this.label = new THREE.Sprite(new THREE.SpriteMaterial({ map: makeTextTexture("پیشینانا 💕"), transparent: true, depthWrite: false }));
        this.label.position.set(0, 2.9, 0);
        this.label.scale.set(2.4, 0.75, 1);
        this.label.visible = false;
        this.scene.add(this.label);

        // ── تعامل موس/لمس ──
        this.targetRotY = -0.25;
        this.targetRotX = 0;
        this.idle = 0;
        this.dragging = false;
        this.bindDrag();

        // ── ریسایز ──
        this.onResize = () => {
            const w = container.clientWidth, h = container.clientHeight;
            this.camera.aspect = w / h;
            this.camera.updateProjectionMatrix();
            this.renderer.setSize(w, h);
        };
        window.addEventListener("resize", this.onResize);

        this.clock = new THREE.Clock();
        this.animate();
    }

    // ---------- ساخت محصولات ----------
    buildProduct() {
        // پاک‌سازی محصول قبلی
        this.productGroup.traverse((o) => {
            if (o.isMesh) o.geometry?.dispose?.();
        });
        this.productGroup.clear();

        const g = new THREE.Group();
        const { mainMat, creamMat, darkMat, blushMat } = this;

        const mesh = (geo, mat, x = 0, y = 0, z = 0) => {
            const m = new THREE.Mesh(geo, mat);
            m.position.set(x, y, z);
            m.castShadow = true;
            m.receiveShadow = true;
            return m;
        };

        if (this.type === "plush") {
            // بدن
            const body = mesh(new THREE.SphereGeometry(1.05, 48, 48), mainMat, 0, -0.15, 0);
            body.scale.set(1, 0.92, 0.88);
            g.add(body);
            // شکم کرم
            const belly = mesh(new THREE.SphereGeometry(0.62, 32, 32), creamMat, 0, -0.22, 0.78);
            belly.scale.set(1, 1.15, 0.35);
            g.add(belly);
            // سر
            const head = mesh(new THREE.SphereGeometry(0.88, 48, 48), mainMat, 0, 1.22, 0.05);
            g.add(head);
            // گوش‌ها
            for (const s of [-1, 1]) {
                g.add(mesh(new THREE.SphereGeometry(0.34, 24, 24), mainMat, s * 0.66, 1.92, -0.02));
                g.add(mesh(new THREE.SphereGeometry(0.19, 20, 20), blushMat, s * 0.68, 1.93, 0.16));
            }
            // پوزه
            const muzzle = mesh(new THREE.SphereGeometry(0.4, 28, 28), creamMat, 0, 1.05, 0.72);
            muzzle.scale.set(1.1, 0.85, 0.6);
            g.add(muzzle);
            // چشم‌ها
            for (const s of [-1, 1]) {
                g.add(mesh(new THREE.SphereGeometry(0.115, 20, 20), darkMat, s * 0.32, 1.42, 0.78));
                // برق چشم
                const shine = mesh(new THREE.SphereGeometry(0.035, 12, 12), creamMat, s * 0.35, 1.47, 0.87);
                g.add(shine);
            }
            // بینی
            g.add(mesh(new THREE.SphereGeometry(0.1, 16, 16), darkMat, 0, 1.17, 0.99));
            // گونه‌های رنگی
            for (const s of [-1, 1]) {
                const blush = mesh(new THREE.SphereGeometry(0.16, 16, 16), blushMat, s * 0.55, 1.12, 0.7);
                blush.scale.set(1, 0.7, 0.4);
                g.add(blush);
            }
            // دست‌ها و پاها
            for (const s of [-1, 1]) {
                const arm = mesh(new THREE.SphereGeometry(0.36, 24, 24), mainMat, s * 1.02, 0.12, 0.18);
                arm.scale.set(0.85, 1.1, 0.85);
                g.add(arm);
                const leg = mesh(new THREE.SphereGeometry(0.42, 24, 24), mainMat, s * 0.52, -1.02, 0.22);
                leg.scale.set(1, 0.8, 1.15);
                g.add(leg);
            }
            // پاپیون روی سر
            const bow = new THREE.Group();
            for (const s of [-1, 1]) {
                const wing = mesh(new THREE.SphereGeometry(0.26, 20, 20), this.blushMat, s * 0.28, 0, 0);
                wing.scale.set(1.2, 0.75, 0.55);
                wing.material = this.mainMat;
                bow.add(wing);
            }
            bow.add(mesh(new THREE.SphereGeometry(0.13, 16, 16), this.blushMat, 0, 0, 0.06));
            bow.position.set(0.42, 2.05, 0.32);
            bow.rotation.z = -0.35;
            g.add(bow);
        }

        if (this.type === "mug") {
            // بدن ماگ
            const body = mesh(new THREE.CylinderGeometry(0.92, 0.8, 1.55, 48), mainMat, 0, -0.25, 0);
            g.add(body);
            // لبه
            g.add(mesh(new THREE.TorusGeometry(0.92, 0.09, 16, 48), creamMat, 0, 0.53, 0).rotateX(Math.PI / 2));
            // دسته
            const handle = mesh(new THREE.TorusGeometry(0.45, 0.13, 16, 40, Math.PI * 1.25), mainMat, 1.05, -0.18, 0);
            handle.rotation.z = -Math.PI / 2.15;
            g.add(handle);
            // نعلبکی
            const saucer = mesh(new THREE.CylinderGeometry(1.35, 1.25, 0.1, 48), creamMat, 0, -1.12, 0);
            g.add(saucer);
            // صورت
            for (const s of [-1, 1]) {
                g.add(mesh(new THREE.SphereGeometry(0.1, 16, 16), darkMat, s * 0.3, -0.12, 0.9));
                const blush = mesh(new THREE.SphereGeometry(0.14, 16, 16), blushMat, s * 0.52, -0.3, 0.82);
                blush.scale.set(1, 0.65, 0.35);
                g.add(blush);
            }
            g.add(mesh(new THREE.SphereGeometry(0.08, 12, 12), blushMat, 0, -0.32, 0.92));
        }

        if (this.type === "bag") {
            // بدن کیف
            const body = mesh(new THREE.SphereGeometry(1.15, 40, 40), mainMat, 0, -0.1, 0);
            body.scale.set(1.15, 1, 0.62);
            g.add(body);
            // درپوش
            const flap = mesh(new THREE.SphereGeometry(1.12, 40, 40, 0, Math.PI * 2, 0, Math.PI / 2.2), creamMat, 0, 0.42, 0.08);
            flap.scale.set(1.16, 0.75, 0.66);
            g.add(flap);
            // قفل قلبی
            const lock = mesh(new THREE.SphereGeometry(0.2, 20, 20), this.blushMat, 0, -0.12, 0.72);
            lock.scale.set(1.15, 1, 0.5);
            g.add(lock);
            // دسته‌ی بالایی
            const handle = mesh(new THREE.TorusGeometry(0.55, 0.11, 16, 40, Math.PI), mainMat, 0, 1.05, 0);
            g.add(handle);
            // بند
            const strap = mesh(new THREE.TorusGeometry(1.35, 0.09, 12, 48, Math.PI), mainMat, 0, 0.25, -0.15);
            strap.rotation.x = Math.PI / 2.6;
            g.add(strap);
            // چشم‌ها
            for (const s of [-1, 1]) {
                g.add(mesh(new THREE.SphereGeometry(0.09, 14, 14), darkMat, s * 0.34, 0.18, 0.68));
            }
        }

        this.productGroup.add(g);
        this.productGroup.position.y = this.type === "mug" ? -0.15 : 0;
    }

    // ---------- تعامل ----------
    bindDrag() {
        const el = this.container;
        let lastX = 0, lastY = 0;

        const down = (e) => {
            this.dragging = true;
            lastX = e.clientX ?? e.touches?.[0]?.clientX ?? 0;
            lastY = e.clientY ?? e.touches?.[0]?.clientY ?? 0;
            this.idle = 0;
        };
        const move = (e) => {
            if (!this.dragging) return;
            const x = e.clientX ?? e.touches?.[0]?.clientX ?? 0;
            const y = e.clientY ?? e.touches?.[0]?.clientY ?? 0;
            this.targetRotY += (x - lastX) * 0.0075;
            this.targetRotX = THREE.MathUtils.clamp(this.targetRotX + (y - lastY) * 0.004, -0.5, 0.5);
            lastX = x; lastY = y;
            this.idle = 0;
        };
        const up = () => { this.dragging = false; };

        el.addEventListener("pointerdown", down);
        window.addEventListener("pointermove", move);
        window.addEventListener("pointerup", up);
        el.addEventListener("touchstart", down, { passive: true });
        window.addEventListener("touchmove", move, { passive: true });
        window.addEventListener("touchend", up);
    }

    // ---------- API عمومی ----------
    setColor(hex) {
        this.targetColor.set(hex);
    }

    setType(type) {
        if (type === this.type) return;
        this.type = type;
        this.buildProduct();
    }

    setText(text) {
        if (!text || !text.trim()) {
            this.label.visible = false;
            return;
        }
        this.label.material.map = makeTextTexture(text.trim());
        this.label.material.needsUpdate = true;
        this.label.visible = true;
    }

    // ---------- حلقه‌ی انیمیشن ----------
    animate() {
        requestAnimationFrame(() => this.animate());

        const dt = Math.min(this.clock.getDelta(), 0.05);
        const t = this.clock.elapsedTime;

        if (!this.dragging) {
            this.idle += dt;
            if (this.idle > 2 && this.autoRotate && !this.reducedMotion) {
                this.targetRotY += dt * 0.22;
            }
        }

        this.productGroup.rotation.y += (this.targetRotY - this.productGroup.rotation.y) * 0.08;
        this.productGroup.rotation.x += (this.targetRotX - this.productGroup.rotation.x) * 0.08;

        if (!this.reducedMotion) {
            this.productGroup.position.y = (this.type === "mug" ? -0.15 : 0) + Math.sin(t * 1.1) * 0.08;

            this.decor.children.forEach((sprite) => {
                const { baseY, speed, phase } = sprite.userData;
                sprite.position.y = baseY + Math.sin(t * speed + phase) * 0.18;
            });
            this.decor.rotation.y = t * 0.05;
        }

        // نرم‌کردن تغییر رنگ
        this.mainMat.color.lerp(this.targetColor, 0.08);

        this.renderer.render(this.scene, this.camera);
    }
}
