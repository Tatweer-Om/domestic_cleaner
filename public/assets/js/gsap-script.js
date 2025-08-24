// Register GSAP plugins
gsap.registerPlugin(TextPlugin, ScrollTrigger, ScrollSmoother, SplitText);

/* ----------- Rolling Text Animation ------------ */
document.querySelectorAll(".rolling-text").forEach(el => {
  const text = el.dataset.text || el.textContent.trim();

  el.innerHTML = "";
  const wrapper = document.createElement("span");
  wrapper.className = "text-wrapper";

  const line1 = document.createElement("span");
  line1.className = "text-line";
  line1.textContent = text;

  const line2 = document.createElement("span");
  line2.className = "text-line";
  line2.textContent = text;

  wrapper.appendChild(line1);
  wrapper.appendChild(line2);
  el.appendChild(wrapper);

  el.addEventListener("mouseenter", () => {
    gsap.to(wrapper, { yPercent: -50, duration: 0.4, ease: "power2.out" });
  });

  el.addEventListener("mouseleave", () => {
    gsap.to(wrapper, { yPercent: 0, duration: 0.4, ease: "power2.out" });
  });
});

/* ----------- Text Opacity Animation ------------ */
document.querySelectorAll(".text-opacity-animation").forEach(el => {
  el.innerHTML = el.textContent
    .split("")
    .map(char => (char === " " ? " " : `<span style="opacity:0.3">${char}</span>`))
    .join("");

  const letters = el.querySelectorAll("span");

  gsap.timeline({
    scrollTrigger: {
      trigger: el,
      start: "top 90%",
      end: "bottom 60%",
      scrub: true,
      markers: false
    }
  }).to(letters, {
    opacity: 1,
    stagger: 0.05,
    ease: "power1.out",
    duration: 0.3
  });
});

/* ----------- SplitText Line Animation ------------ */
document.querySelectorAll(".splittext-line").forEach(splitTextLine => {
  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: splitTextLine,
      start: "top 90%",
      end: "bottom 60%",
      scrub: false,
      markers: false,
      toggleActions: "play none none none"
    }
  });

  const itemSplitted = new SplitText(splitTextLine, { type: "lines" });
  gsap.set(splitTextLine, { perspective: 400 });

  tl.from(itemSplitted.lines, {
    duration: 1,
    delay: 0.5,
    opacity: 0,
    rotationX: -80,
    force3D: true,
    transformOrigin: "top center -50",
    stagger: 0.1
  });
});

/* ----------- SplitText chars animation on .poort-text ------------ */
window.addEventListener("load", () => {
  const poortTexts = document.querySelectorAll(".poort-text");
  if (poortTexts.length === 0) return;

  poortTexts.forEach(el => {
    el.split = new SplitText(el, { type: "lines,words,chars", linesClass: "poort-line" });
    gsap.set(el, { perspective: 600 });

    if (el.classList.contains("poort-in-right")) {
      gsap.set(el.split.chars, { opacity: 0, x: 100, ease: "back.out" });
    }
    if (el.classList.contains("poort-in-left")) {
      gsap.set(el.split.chars, { opacity: 0, x: -100, ease: "circ.out" });
    }
    if (el.classList.contains("poort-in-up")) {
      gsap.set(el.split.chars, { opacity: 0, y: 80, ease: "circ.out" });
    }
    if (el.classList.contains("poort-in-down")) {
      gsap.set(el.split.chars, { opacity: 0, y: -80, ease: "circ.out" });
    }

    el.anim = gsap.to(el.split.chars, {
      scrollTrigger: {
        trigger: el,
        start: "top 90%"
      },
      x: 0,
      y: 0,
      rotateX: 0,
      scale: 1,
      opacity: 1,
      duration: 0.6,
      stagger: 0.02
    });
  });
});

/* ----------- Image Scroll Animation ------------ */
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".new_img-animet").forEach(el => {
    const image = el.querySelector("img");
    if (!image) return;

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: el,
        start: "top 50%",
        toggleActions: "play none none none",
        markers: false
      }
    });

    tl.set(el, { autoAlpha: 1 });
    tl.from(el, { xPercent: -100, duration: 5, ease: "power2.out" });
    tl.from(image, { xPercent: 100, duration: 5, ease: "power2.out" }, "<");
  });
});

/* ----------- Mousemove Parallax for .image-move and .image-move2 ------------ */
document.addEventListener("mousemove", e => {
  const depth = 200;
  const moveX = (e.pageX - window.innerWidth / 2) / depth;
  const moveY = (e.pageY - window.innerHeight / 2) / depth;

  ["image-move", "image-move2"].forEach(cls => {
    gsap.utils.toArray(`.${cls}`).forEach((el, i) => {
      gsap.to(el, {
        x: moveX * (i + 1),
        y: moveY * (i + 1),
        duration: 0.5,
        ease: "power2.out"
      });
    });
  });
});

/* ----------- wpo-transforming-left-img slider ------------ */
const container = document.querySelector('.wpo-transforming-left-img');
const slider = document.querySelector('.slider');

if (container && slider) {
  container.style.setProperty('--position', '50%');
  slider.addEventListener('input', e => {
    container.style.setProperty('--position', `${e.target.value}%`);
  });
}

/* ----------- HoverButton Class ------------ */
class HoverButton {
  constructor(el) {
    this.el = el;
    this.hover = false;
    this.calculatePosition();
    this.attachEventsListener();
  }

  attachEventsListener() {
    window.addEventListener('mousemove', e => this.onMouseMove(e));
    window.addEventListener('resize', () => this.calculatePosition());
  }

  calculatePosition() {
    gsap.set(this.el, { x: 0, y: 0, scale: 1 });
    const box = this.el.getBoundingClientRect();
    this.x = box.left + box.width / 2;
    this.y = box.top + box.height / 2;
    this.width = box.width;
    this.height = box.height;
  }

  onMouseMove(e) {
    let hover = false;
    const hoverArea = this.hover ? 0.7 : 0.5;
    const x = e.clientX - this.x;
    const y = e.clientY - this.y;
    const distance = Math.sqrt(x * x + y * y);

    if (distance < this.width * hoverArea) {
      hover = true;
      if (!this.hover) this.hover = true;
      this.onHover(e.clientX, e.clientY);
    }

    if (!hover && this.hover) {
      this.onLeave();
      this.hover = false;
    }
  }

  onHover(x, y) {
    gsap.to(this.el, {
      x: (x - this.x) * 0.4,
      y: (y - this.y) * 0.4,
      scale: 1.15,
      ease: 'power2.out',
      duration: 0.4
    });
    this.el.style.zIndex = 10;
  }

  onLeave() {
    gsap.to(this.el, {
      x: 0,
      y: 0,
      scale: 1,
      ease: 'elastic.out(1.2, 0.4)',
      duration: 0.7
    });
    this.el.style.zIndex = 1;
  }
}

/* ----------- Parallax Buttons (btn-wrapper and btn-move) ------------ */
let all_btn = gsap.utils.toArray(".btn-wrapper");
if (all_btn.length === 0) {
  all_btn = gsap.utils.toArray("#btn-wrapper");
}

const all_btn_cirlce = gsap.utils.toArray(".btn-move");

function parallaxIt(e, target, movement) {
  const rect = e.currentTarget.getBoundingClientRect();
  const relX = e.pageX - rect.left - window.pageXOffset;
  const relY = e.pageY - rect.top - window.pageYOffset;

  gsap.to(target, {
    x: ((relX - rect.width / 2) / rect.width) * movement,
    y: ((relY - rect.height / 2) / rect.height) * movement,
    duration: 0.5,
    ease: "power2.out"
  });
}

all_btn.forEach((btn, i) => {
  btn.addEventListener("mousemove", e => parallaxIt(e, all_btn_cirlce[i], 80));
  btn.addEventListener("mouseleave", () => {
    gsap.to(all_btn_cirlce[i], { x: 0, y: 0, duration: 0.5, ease: "power2.out" });
  });
});

/* ----------- Moving Cursor Movement ------------ */
document.querySelectorAll('.moving-cursor-wrap').forEach(container => {
  const floatCursor = container.querySelector('.moving-cursor');
  let mouseX = 0, mouseY = 0;
  let isMoving = false;

  container.addEventListener('mouseenter', () => {
    floatCursor.style.opacity = '1';
    floatCursor.style.transform = 'scale(1)';
  });

  container.addEventListener('mousemove', e => {
    const rect = container.getBoundingClientRect();
    mouseX = e.clientX - rect.left - 75;
    mouseY = e.clientY - rect.top - 75;
    isMoving = true;
  });

  function updateCursor() {
    if (isMoving) {
      floatCursor.style.left = `${mouseX}px`;
      floatCursor.style.top = `${mouseY}px`;
      isMoving = false;
    }
    requestAnimationFrame(updateCursor);
  }

  updateCursor();

  container.addEventListener('mouseleave', () => {
    floatCursor.style.opacity = '0';
    floatCursor.style.transform = 'scale(0)';
  });
});
