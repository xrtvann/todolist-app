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
  const wrapper = document.getElementById("wrapper");

  sidebar.classList.toggle("-translate-x-full");
  sidebar.classList.toggle("translate-x-0");
  sidebar.classList.toggle("w-72");
}
