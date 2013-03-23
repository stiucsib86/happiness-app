<?php
$pageTitle = 'HappinessXchange - Logout';
require_once('header.php');
?>
	<script>
	  jQuery.getJSON("http://happiness-app.ap01.aws.af.cm/auth/logout/?callback=?", function(data) {
		  window.location='/';
	  });
	</script>
      <div class="container">
        <div class="row-fluid">
            <div class="span12">
                <h4>Logging out...</h4>
            </div>
      </div>
<?php
require_once('footer.php');
?>