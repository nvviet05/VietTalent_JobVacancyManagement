document.addEventListener('DOMContentLoaded', function() {
    var mobileToggle = document.querySelector('[data-mobile-menu-toggle]');
    var mobileNav = document.querySelector('[data-mobile-nav]');
    if (mobileToggle && mobileNav) {
        mobileToggle.addEventListener('click', function() {
            mobileNav.classList.toggle('active');
        });
    }

    var sidebarToggle = document.querySelector('[data-sidebar-toggle]');
    var sidebar = document.getElementById('dashboardSidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }

    document.querySelectorAll('[data-role-input]').forEach(function(input) {
        input.addEventListener('change', toggleCompanyField);
    });
    toggleCompanyField();
});

function toggleCompanyField() {
    var selectedRole = document.querySelector('[data-role-input]:checked');
    var companyField = document.getElementById('companyField');
    if (!companyField) return;

    if (selectedRole && selectedRole.value === 'employer') {
        companyField.classList.remove('is-hidden');
    } else {
        companyField.classList.add('is-hidden');
    }
}
