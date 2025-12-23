        </div> <!-- End Content Wrapper -->
    </div> <!-- End Main Content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // DataTables initialization
            $('.table').DataTable();
            
            // Sidebar dropdown toggle
            $('.sidebar-menu .dropdown-toggle').on('click', function(e) {
                e.preventDefault();
                var $parent = $(this).parent('li');
                var $dropdown = $parent.find('.dropdown-menu');
                
                // Toggle active class
                $parent.toggleClass('active');
                
                // Close other dropdowns
                $('.sidebar-menu li.has-dropdown').not($parent).removeClass('active');
                
                // Prevent default link behavior if dropdown exists
                if ($dropdown.length > 0) {
                    return false;
                }
            });
        });
    </script>
</body>
</html>
