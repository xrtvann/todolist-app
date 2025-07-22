document.addEventListener("DOMContentLoaded", function () {
  selectTaskCategory();

  // Handle window resize for responsive sidebar
  window.addEventListener("resize", function () {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");
    const isMobile = window.innerWidth < 1024;

    if (!isMobile) {
      // On desktop, hide overlay and ensure sidebar is visible
      overlay.classList.add("hidden");
      sidebar.classList.remove("-translate-x-full");
    } else {
      // On mobile, ensure sidebar starts hidden
      sidebar.classList.add("-translate-x-full");
      overlay.classList.add("hidden");
    }
  });

  // Initial setup for mobile
  if (window.innerWidth < 1024) {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.add("-translate-x-full");
  }
});

function toggleUserMenu() {
  const userMenuDropdown = document.getElementById("userMenuDropdown");
  userMenuDropdown.classList.toggle("hidden");

  document.addEventListener("click", function (event) {
    if (
      !userMenuDropdown.contains(event.target) &&
      !document.getElementById("userMenuButton").contains(event.target)
    ) {
      userMenuDropdown.classList.add("hidden");
    }
  });
}

function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("sidebarOverlay");

  // Check if we're on mobile (less than lg breakpoint)
  const isMobile = window.innerWidth < 1024;

  if (isMobile) {
    // Mobile behavior: slide in/out with overlay
    const isOpen = !sidebar.classList.contains("-translate-x-full");

    if (isOpen) {
      // Close sidebar
      sidebar.classList.add("-translate-x-full");
      overlay.classList.add("hidden");
    } else {
      // Open sidebar
      sidebar.classList.remove("-translate-x-full");
      overlay.classList.remove("hidden");
    }
  } else {
    // Desktop behavior: collapse/expand
    sidebar.classList.toggle("w-72");
    sidebar.classList.toggle("w-20");

    // Toggle smooth visibility of sidebar text
    const sidebarTexts = document.querySelectorAll(".sidebar-text");
    sidebarTexts.forEach((el) => {
      el.classList.toggle("opacity-0");
      el.classList.toggle("w-0");
      el.classList.toggle("overflow-hidden");
      el.classList.toggle("transition-all");
      el.classList.toggle("duration-300");
    });
  }
}

function closeSidebar() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("sidebarOverlay");

  // Only for mobile - close sidebar and hide overlay
  sidebar.classList.add("-translate-x-full");
  overlay.classList.add("hidden");
}
function showModal(type, menu) {
  const modalType = `${type}-${menu}-modal`;
  const modal = document.getElementById(modalType);
  if (modal) {
    modal.classList.remove("hidden");
  }
}

function showEditModal(entity, data = {}) {
  showModal("update", entity);

  setTimeout(() => {
    if (entity === "category") {
      document.getElementById("updateCategoryID").value = data.id || "";
      document.getElementById("updateCategoryName").value = data.name || "";
    }

    if (entity === "task") {
      document.getElementById("updateTaskID").value = data.id || "";
      document.getElementById("updateTaskName").value = data.name || "";
      setUpdateDropdown(data.category_id, data.category_name);
    }
  }, 100);
}

function closeModal(type, menu) {
  const modalType = `${type}-${menu}-modal`;
  const modal = document.getElementById(modalType);

  if (modal) {
    console.log(modal);
    modal.classList.add("hidden");
  }
}

function selectTaskCategory() {
  const btn = document.getElementById("dropdownBtn");
  const list = document.getElementById("dropdownList");
  const selected = document.getElementById("dropdownSelected");
  const hiddenInput = document.getElementById("taskCategory");

  if (!btn || !list || !selected || !hiddenInput) return;

  btn.addEventListener("click", function (e) {
    e.stopPropagation();
    list.classList.toggle("hidden");
  });

  list.querySelectorAll("li").forEach(function (item) {
    item.addEventListener("click", function () {
      selected.textContent = this.textContent;
      hiddenInput.value = this.getAttribute("data-value");
      list.classList.add("hidden");
    });
  });

  // Close dropdown if click outside
  document.addEventListener("click", function () {
    list.classList.add("hidden");
  });
}

function setUpdateDropdown(selectedValue, selectedText) {
  const selected = document.getElementById("updateDropdownSelected");
  const hiddenInput = document.getElementById("updateTaskCategory");

  if (selected && hiddenInput) {
    selected.textContent = selectedText;
    hiddenInput.value = selectedValue;
  }
}

// Event listener untuk klik pada list
document.querySelectorAll("#updateDropdownList li").forEach(function (item) {
  item.addEventListener("click", function () {
    setUpdateDropdown(this.getAttribute("data-value"), this.textContent);
    document.getElementById("updateDropdownList").classList.add("hidden");
  });
});

// Saat tombol dropdown diklik
document
  .getElementById("updateDropdownBtn")
  .addEventListener("click", function (e) {
    e.stopPropagation();
    document.getElementById("updateDropdownList").classList.toggle("hidden");
  });

// Tutup dropdown jika klik di luar
document.addEventListener("click", function () {
  document.getElementById("updateDropdownList").classList.add("hidden");
});
