(() => {
  const STORAGE_KEY = "admin_sidebar_collapsed";
  const toggleButton = document.querySelector("[data-sidebar-toggle]");
  const backdrop = document.querySelector("[data-sidebar-backdrop]");
  const backToTop = document.querySelector("[data-back-to-top]");
  const pageLoader = document.querySelector("[data-page-loader]");
  const prefersReducedMotion = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  const apply = (collapsed) => {
    document.body.classList.toggle("sidebar-collapsed", collapsed);
  };

  const initial = localStorage.getItem(STORAGE_KEY) === "1";
  apply(initial);

  if (toggleButton) {
    toggleButton.addEventListener("click", () => {
      const isMobile = window.matchMedia("(max-width: 991.98px)").matches;
      if (isMobile) {
        document.body.classList.toggle("sidebar-open");
        return;
      }

      const next = !document.body.classList.contains("sidebar-collapsed");
      localStorage.setItem(STORAGE_KEY, next ? "1" : "0");
      apply(next);
    });
  }

  if (backdrop) {
    backdrop.addEventListener("click", () => {
      document.body.classList.remove("sidebar-open");
    });
  }

  // Close sidebar on outside click (mobile).
  document.addEventListener("click", (e) => {
    const isMobile = window.matchMedia("(max-width: 991.98px)").matches;
    if (!isMobile) return;
    if (!document.body.classList.contains("sidebar-open")) return;

    const sidebar = document.querySelector(".admin-sidebar");
    if (!sidebar) return;

    const clickedToggle = e.target && e.target.closest && e.target.closest("[data-sidebar-toggle]");
    if (clickedToggle) return;

    const clickedInsideSidebar = sidebar.contains(e.target);
    if (!clickedInsideSidebar) {
      document.body.classList.remove("sidebar-open");
    }
  });

  // Back to top + smooth scroll.
  if (backToTop) {
    let ticking = false;

    const updateBackToTop = () => {
      ticking = false;
      const show = window.scrollY > 320;
      backToTop.classList.toggle("show", show);
    };

    window.addEventListener(
      "scroll",
      () => {
        if (ticking) return;
        ticking = true;
        window.requestAnimationFrame(updateBackToTop);
      },
      { passive: true }
    );

    updateBackToTop();

    backToTop.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: prefersReducedMotion ? "auto" : "smooth" });
    });
  }

  // Page loader for navigations (lightweight UX).
  const setLoading = (on) => {
    if (!pageLoader) return;
    document.body.classList.toggle("page-loading", on);
  };

  window.addEventListener("pageshow", () => setLoading(false));

  document.addEventListener("click", (e) => {
    const a = e.target && e.target.closest ? e.target.closest("a") : null;
    if (!a) return;
    if (a.hasAttribute("data-no-loader")) return;

    const href = a.getAttribute("href") || "";
    if (!href || href.startsWith("#") || href.startsWith("javascript:")) return;
    if (a.target && a.target.toLowerCase() === "_blank") return;
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

    // Same-origin only.
    try {
      const url = new URL(href, window.location.href);
      if (url.origin !== window.location.origin) return;
    } catch {
      return;
    }

    setLoading(true);
  });

  // Animated counters (dashboard stats).
  const animateCounter = (el) => {
    if (el.dataset.counterDone === "1") return;

    const raw = (el.textContent || "").trim();
    const target = Number(raw.replace(/,/g, ""));
    if (!Number.isFinite(target)) return;

    el.dataset.counterDone = "1";
    if (prefersReducedMotion) return;

    const start = 0;
    const durationMs = 850;
    const startTime = performance.now();
    const formatter = new Intl.NumberFormat(undefined);

    const tick = (t) => {
      const p = Math.min(1, (t - startTime) / durationMs);
      const eased = 1 - Math.pow(1 - p, 3);
      const current = Math.round(start + (target - start) * eased);
      el.textContent = formatter.format(current);
      if (p < 1) window.requestAnimationFrame(tick);
    };

    el.textContent = "0";
    window.requestAnimationFrame(tick);
  };

  const counterEls = Array.from(document.querySelectorAll("[data-counter]"));
  if (counterEls.length) {
    if ("IntersectionObserver" in window) {
      const io = new IntersectionObserver(
        (entries) => {
          for (const entry of entries) {
            if (entry.isIntersecting) animateCounter(entry.target);
          }
        },
        { threshold: 0.35 }
      );

      counterEls.forEach((el) => io.observe(el));
    } else {
      counterEls.forEach(animateCounter);
    }
  }
})();
