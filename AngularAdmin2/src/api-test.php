<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>API Tests</title>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>" />
<link rel="stylesheet" href="assets/css/utility.css?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>" rel="stylesheet" />
<script src="//code.jquery.com/jquery-3.1.1.min.js?v=<?php echo rand(1000000000000000000000, 9000000000000000000000) ?>"></script>
</head>

<body>
<style>
h4 { color: red; }
</style>
<?php
  if (file_exists('assets/js/general.js.php')) include 'assets/js/general.js.php';

$then = strtotime("2017-05-26");
$now = time(); // or your date as well
$datediff = $now - $then;

$days = floor($datediff / (60 * 60 * 24));
$months = floor($days / 30);

echo 'days: ' . $days . '  months: ' . $months;
?>   
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-6" style="margin-top:10px;">
      <div class="well">
        <h4 class="no-margin-top">API Tests</h4>
        <select name="test" id="test" class="form-control">
          <option>------ Select Test ------</option>
          <option disabled>────────────────</option>
          <option value="form4">getAdminCompanyById()</option>
          <option value="form5">updateAdminCompany()</option>          
          <option disabled>────────────────</option>
          <option value="form1">getAllAdminAccessLevels()</option>
          <option value="form2">getAdminAccessLevelById()</option>
          <option value="form3">updateAdminAccessLevel()</option>
          <option disabled>────────────────</option>
          <option value="form6">getAllAdminUsers()</option>
          <option value="form7">getAdminUserById()</option>
          <option value="form8">getAdminUserByEmail()</option>
          <option value="form9">addAdminUser()</option>
          <option value="form10">updateAdminUser()</option>
          <option value="form11">updateAdminUserAvatar()</option>
          <option value="form12">deleteAdminUser()</option>
          <option disabled>────────────────</option> 
        </select>  
      </div>
   
      <div id="formContainer">
        <div class="well">
          <form style="display:none;" id="form1" class="form" action="http://zloaded.com/api/getAllAdminAccessLevels">
            <h4 class="no-margin-top">getAllAdminAccessLevels()</h4>
            <p><label>CompanyID <input class="form-control" type="text" name="cID" id="cID" value="1" /></label></p>
            <p><label><input type="submit" name="button" id="button" value="Submit" /></label></p>
            <input type="hidden" value="" id="" />
          </form>    
          
          <form style="display:none;" id="form2" class="form" action="http://zloaded.com/api/getAdminAccessLevelById">
            <h4 class="no-margin-top">getAdminAccessLevelById()</h4>
            <p><label>Company ID <input class="form-control" type="text" name="cID" id="cID" value="1" /></label></p>
            <p><label>Level ID <input class="form-control" type="text" name="id" id="id" value="1" /></label></p>
            <p><label><input type="submit" name="button" id="button" value="Submit" /></label></p>
          </form> 

          <form style="display:none;" id="form3" class="form" action="http://zloaded.com/api/updateAdminAccessLevel">
            <h4 class="no-margin-top">updateAdminAccessLevel()</h4>
            <p><label>Company ID <input class="form-control" type="text" name="cID" id="cID" value="1" /></label></p>
            <p><label>Level ID <input class="form-control" type="text" name="id" id="id" value="1" /></label></p>
            <p><label>Title <input class="form-control" type="text" name="title" id="title" value="Head Honcho" /></label></p>
            <p><label><input type="submit" name="button" id="button" value="Submit" /></label></p>
          </form> 

          <form style="display:none;" id="form4" class="form" action="http://zloaded.com/api/getAdminCompanyById">
            <h4 class="no-margin-top">getAdminCompanyById()</h4>
            <p><label>Company ID <input class="form-control" type="text" name="id" id="id" value="1" /></label></p>
            <p><label><input type="submit" name="button" id="button" value="Submit" /></label></p>
          </form>  

          <form style="display:none;" id="form5" class="form" action="http://zloaded.com/api/updateAdminCompany">
            <h4 class="no-margin-top">updateAdminCompany()</h4>
            <p><label>Company ID <input class="form-control" type="text" name="id" id="id" value="1" /></label></p>
            <p><label>Legal Name <input class="form-control" type="text" name="legalName" id="legalName" value="Acme, Inc" /></label></p>
            <p><label>Trade Name <input class="form-control" type="text" name="tradeName" id="tradeName" value="Big Bird Diner" /></label></p>
            <p><label>Address 1 <input class="form-control" type="text" name="address1" id="address1" value="123 Mystery Dr." /></label></p>
            <p><label>Address 1 <input class="form-control" type="text" name="address2" id="address2" value="Route #3" /></label></p>
            <p><label>Address 1 <input class="form-control" type="text" name="city" id="city" value="Jacksonville" /></label></p>
            <p><label>Address 1 <input class="form-control" type="text" name="state" id="state" value="FL" /></label></p>
            <p><label>Address 1 <input class="form-control" type="text" name="zip" id="zip" value="32218" /></label></p>
            <p><label>Address 1 <input class="form-control" type="text" name="www" id="www" value="www.mystore.com" /></label></p>
            <p><label>Address 1 <input class="form-control" type="text" name="contactName" id="contactName" value="Jack Tester" /></label></p>
            <p><label>Address 1 <input class="form-control" type="text" name="contactPhone" id="contactPhone" value="904 714-0759" /></label></p>
            <p><label>Address 1 <input class="form-control" type="text" name="contactFax" id="contactFax" value="904 777-8888" /></label></p>
            <p><label>Address 1 <input class="form-control" type="text" name="contactEmail" id="contactEmail" value="jack@mystore.com" /></label></p>
            <p><label><input type="submit" name="button" id="button" value="Submit" /></label></p>
          </form> 

          <form style="display:none;" id="form6" class="form" action="http://zloaded.com/api/getAllAdminUsers">
            <h4 class="no-margin-top">getAllAdminUsers()</h4>
            <p><label>Company ID <input class="form-control" type="text" name="cID" id="cID" value="1" /></label></p>
            <p><label><input type="submit" name="button" id="button" value="Submit" /></label></p>
          </form>   

          <form style="display:none;" id="form7" class="form" action="http://zloaded.com/api/getAdminUserById">
            <h4 class="no-margin-top">getAdminUserById()</h4>
            <p><label>Company ID <input class="form-control" type="text" name="cID" id="cID" value="1" /></label></p>
            <p><label>Admin ID <input class="form-control" type="text" name="id" id="id" value="1" /></label></p>
            <p><label><input type="submit" name="button" id="button" value="Submit" /></label></p>
          </form>               

          <form style="display:none;" id="form8" class="form" action="http://zloaded.com/api/getAdminUserByEmail">
            <h4 class="no-margin-top">getAdminUserByEmail()</h4>
            <p><label>Company ID <input class="form-control" type="text" name="cID" id="cID" value="1" /></label></p>
            <p><label>E-Mail <input class="form-control" type="text" name="email" id="email" value="jt2@test.com" /></label></p>
            <p><label><input type="submit" name="button" id="button" value="Submit" /></label></p>
          </form> 

          <form style="display:none;" id="form9" class="form" action="http://zloaded.com/api/addAdminUser">
            <h4 class="no-margin-top">addAdminUser()</h4>
            <p><label>Company ID <input class="form-control" type="text" name="cID" id="cID" value="1" /></label></p>
            <p><label>E-Mail <input class="form-control" type="text" name="email" id="email" value="jane@mystore.com" /></label></p>
            <p><label>Password <input class="form-control" type="text" name="password" id="password" value="pass1234" /></label></p>
            <p><label>First Name <input class="form-control" type="text" name="firstName" id="firstName" value="Jane" /></label></p>
            <p><label>Last Name <input class="form-control" type="text" name="lastName" id="lastName" value="Tester" /></label></p>
            <p><label><input type="submit" name="button" id="button" value="Submit" /></label></p>
          </form>        

          <form style="display:none;" id="form10" class="form" action="http://zloaded.com/api/updateAdminUser">
            <h4 class="no-margin-top">updateAdminUser()</h4>
            <p><label>Company ID <input class="form-control" type="text" name="cID" id="cID" value="1" /></label></p>
            <p><label>User ID <input class="form-control" type="text" name="id" id="id" value="3" /></label></p>
            <p><label>E-Mail <input class="form-control" type="text" name="email" id="email" value="fred@mystore.com" /></label></p>
            <p><label>Password <input class="form-control" type="text" name="password" id="password" value="pass1234" /></label></p>
            <p><label>First Name <input class="form-control" type="text" name="firstName" id="firstName" value="Fred" /></label></p>
            <p><label>Last Name <input class="form-control" type="text" name="lastName" id="lastName" value="Tester" /></label></p>
            <p><label><input type="submit" name="button" id="button" value="Submit" /></label></p>
          </form>

          <form style="display:none;" id="form11" class="aform" method="post" action="http://zloaded.com/api/updateAdminUserAvatar" enctype="multipart/form-data">
            <h4 class="no-margin-top">updateAdminUserAvatar()</h4>
            <p><label>Company ID <input class="form-control" type="text" name="cID" id="cID" value="1" /></label></p>
            <p><label>User ID <input class="form-control" type="text" name="id" id="id" value="3" /></label></p>
            <p><label>Avatar <input type="file" name="file" id="avatar" /></label></p>  
            <p><label><input type="submit" name="button" id="button" value="Submit" /></label></p>
          </form> 

          <form style="display:none;" id="form12" class="form" action="http://zloaded.com/api/deleteAdminUser">
            <h4 class="no-margin-top">deleteAdminUser()</h4>
            <p><label>Company ID <input class="form-control" type="text" name="cID" id="cID" value="1" /></label></p>
            <p><label>User ID <input class="form-control" type="text" name="id" id="id" value="6" /></label></p>
            <p><label><input type="submit" name="button" id="button" value="Submit" /></label></p>
          </form>         

        </div>
      </div>
    </div>
    <div class="col-sm-6" style="margin-top:10px;">
      <div class="well">
        <h4 class="no-margin-top">Results</h4>
        <div style="white-space: pre"><pre id="results"></pre></div>
      </div>
    </div>
  </div>
</div>
<script>
$( document ).ready(function() {
  $( "#test" ).change(function() {
    var selected = $('#test').val();
    $('.form').hide();
    $('#results').empty();
    $('#' + selected).show();
  });   

  $( ".form" ).submit(function(e) {
    e.preventDefault();
    window.scrollTo(0,0);
    var form = $(this);
    var id = form.attr('id');
    var url = $('#' + id).attr('action');
    var data = $('#' + id).serialize();
    var sendData = $.ajax({
          type: 'POST',
          url: url,
          data: data,
          dataType: "json",
          success: function(result) { 
            $('#results').text(JSON.stringify(result, null, 2));
          },
          error: function(result) {
            $('#results').text(JSON.stringify(result, null, 2));
          }
    });
  });
});  
</script> 
</body>
</html>
