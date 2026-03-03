(() => {
  const STORAGE_KEY = "admin_sidebar_collapsed";
  const toggleButton = document.querySelector("[data-sidebar-toggle]");
  const backdrop = document.querySelector("[data-sidebar-backdrop]");

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
})();
