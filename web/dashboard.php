<?php
$pageTitle = 'HappinessXchange - Dashboard';
require_once('header.php');
?>

<?php
if($_POST) {
?>
	<script>
	  jQuery.getJSON("http://happiness-app.ap01.aws.af.cm/user/update_user/?gender=<?php echo $_POST['gender']; ?>&dob=<?php echo $_POST['dob']; ?>&interest=<?php echo $_POST['interest']; ?>&about_me=<?php echo $_POST['about_me']; ?>&callback=?", function(data) {
		  console.log(data);
	  });
	</script>
<?php
}
?>

	<script>
	  jQuery.getJSON("http://happiness-app.ap01.aws.af.cm/user/?callback=?", function(data) {
		  console.log(data);
		  document.getElementById('profile-name').value = data.display_name;
		  document.getElementById('profile-email').value = data.email;
		  document.getElementById('profile-gender').value = data.gender;
		  document.getElementById('profile-dob').value = data.dob;
		  document.getElementById('profile-interests').value = data.interest;
		  if(data.username) {
			document.getElementById("profileImg").src = 'https://graph.facebook.com/'+data.username+'/picture?type=large';
		  	document.getElementById('fbProfileLink').innerHTML = '<a href="http://facebook.com/'+data.username+'/" target="_blank">View Facebook Profile</a>';
		  }
	  });
	</script>
      <div class="dashboard container">
        <div class="row-fluid top-item">
            <div class="span7">
                <div class="logoLong"></div>
            </div>
            <div class="span5 menu">
              <ul>
              	<li><div class="menu-profile"></div><a href="/dashboard">My Profile</a></li>
                <li><div class="menu-mobile"></div><a href="javascript:alert('Coming soon...');">Download Mobile App</a></li>
                <li><div class="menu-logout"></div><a href="/logout">Log Out</a></li>
              </ul>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span4 friendslist">
				<?php require_once('friendlist.php'); ?>
            </div>
            <div class="span8">
			<?php
			switch(isset($_GET['type'])) {
				case 'give':
            		$getFile = 'give-gift.php';
				break;
				case 'receive':
            		$getFile = 'receive-gift.php';
				break;
				default:
            		$getFile = 'profile.php';
				break;
			}
			require_once($getFile);
            ?>
            </div>
        </div>
      </div>
<?php
require_once('footer.php');
?>