      <!-- scroll to top btn -->
      <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
    </div>

    <?php 
    if (file_exists(DIR_FS_ADMIN . 'assets/js/general.js.php')) include(DIR_FS_ADMIN . 'assets/js/general.js.php'); 
    ?>

    <script>
      $(document).ready(function() {
        App.init();
        if (window.location.pathname.indexOf('index.php')>-1) DashboardV2.init();
      });
    </script>       
  </body>
</html>