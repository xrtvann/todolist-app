
// toggle user menu button
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

function showEditModal(entity, data = {} ){

  showModal("update", entity);


  

  setTimeout(() => {
    if (entity === "category") {
      document.getElementById("updateCategoryID").value = data.id || "";
      document.getElementById("updateCategoryName").value = data.name || "";
    } 
    
    if (entity === "task") {
      document.getElementById("updateTaskID").value = data.id || "";
      document.getElementById("updateTaskName").value = data.name || "";
      document.getElementById("updateTaskDescription").value = data.description || "";      
    } 
  }, 100);
}

function closeModal(type, menu) {
  const modalType = `${type}-${menu}-modal`;
  const modal = document.getElementById(modalType);
  if (modal) {
    modal.classList.add("hidden");
  }
}
