document.getElementById("sidebarToggle").addEventListener("click", function () {
    let sidebar = document.getElementById("dashboardSidebar");
    sidebar.classList.toggle("d-none");
    sidebar.classList.toggle("d-flex");
});
