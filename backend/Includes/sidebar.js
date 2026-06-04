const sidebar = document.getElementById('sidebar');
const content = document.getElementById('content'); // This is correct, assuming your main content div has id="content"
const topbar = document.querySelector('.topbar'); // Get the topbar element
const overlay = document.getElementById('sidebarOverlay');
const toggleBtn = document.getElementById('toggleSidebar');

toggleBtn.addEventListener('click', () => {
    if (window.innerWidth <= 768) {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('d-none');
    } else {
        sidebar.classList.toggle('collapsed');
        content.classList.toggle('collapsed'); // Toggles 'collapsed' on your content div
        topbar.classList.toggle('collapsed'); // Toggles 'collapsed' on your topbar div
    }
});

overlay.addEventListener('click', () => {
    sidebar.classList.remove('show');
    overlay.classList.add('d-none');
});

// Handle window resize to apply collapsed state correctly
window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
        // If resizing to desktop view from mobile, ensure sidebar and content are not "show" and "collapsed" for desktop
        if (sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
            overlay.classList.add('d-none');
        }
        // If sidebar is collapsed on desktop, apply collapsed class to topbar and content
        if (sidebar.classList.contains('collapsed')) {
            topbar.classList.add('collapsed');
            content.classList.add('collapsed'); // Ensure content also gets collapsed class on resize
        } else {
            topbar.classList.remove('collapsed');
            content.classList.remove('collapsed'); // Ensure content removes collapsed class
        }
    } else {
        // If resizing to mobile, ensure desktop collapsed classes are removed from topbar and content
        topbar.classList.remove('collapsed');
        content.classList.remove('collapsed');
    }
});

// Initialize topbar and content state on page load
document.addEventListener('DOMContentLoaded', () => {
    if (window.innerWidth > 768 && sidebar.classList.contains('collapsed')) {
        topbar.classList.add('collapsed');
        content.classList.add('collapsed'); // Ensure content is initialized with collapsed class
    }
});






