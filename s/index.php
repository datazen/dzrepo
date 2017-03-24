<?php include_once("servi_special.html"); ?>
<script lang="javascript">
  var apiBaseUrl = '<?php echo getenv("API_BASE_URL") ?:'' ?>' ;
  if(apiBaseUrl=='') {
      apiBaseUrl = 'http://127.0.0.1:9090/api/1.0';
    // apiBaseUrl = 'https://api.ser.vi/api/1.0';
  }

  if (screen.width > 699) {
      document.location = "http://home.ser.vi";
  }

</script>
