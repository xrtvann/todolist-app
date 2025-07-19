document.addEventListener("DOMContentLoaded", function () {
  selectTaskCategory();
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
