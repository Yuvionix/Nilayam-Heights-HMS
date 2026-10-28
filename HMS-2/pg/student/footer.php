    </main>

    <script>
        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const pgSidebar = document.getElementById('pgSidebar');
        if (sidebarToggle && pgSidebar) {
            sidebarToggle.addEventListener('click', function() {
                pgSidebar.classList.toggle('collapsed');
            });
        }

        // Profile Popover Toggle
        const studentBadge = document.getElementById('studentBadge');
        const profilePopover = document.getElementById('profilePopover');
        const profileChevron = document.getElementById('profileChevron');
        if (studentBadge && profilePopover) {
            studentBadge.addEventListener('click', function(e) {
                e.stopPropagation();
                const isShown = profilePopover.style.display === 'flex';
                profilePopover.style.display = isShown ? 'none' : 'flex';
                if (profileChevron) {
                    profileChevron.style.transform = isShown ? 'rotate(0deg)' : 'rotate(180deg)';
                }
            });
            document.addEventListener('click', function() {
                profilePopover.style.display = 'none';
                if (profileChevron) profileChevron.style.transform = 'rotate(0deg)';
            });
        }
    </script>
</body>
</html>
