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
                <li><div class="menu-mobile"></div><a href="#">Download Mobile App</a></li>
                <li><div class="menu-logout"></div><a href="/logout">Log Out</a></li>
              </ul>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span4 friendslist">
                <h4>Friends</h4>
                <div class="row-fluid">
                    <div class="span2"><img src="images/nouser.jpg"/></div>
                    <div class="span8"><a href="#">Muthukumar</a><br/><span>Woodlans,Singapore</span></div>
                    <div class="span2"><img class="giftbox" src="images/favicon.png"/></div>
                </div>
                <div class="row-fluid">
                    <div class="span2"><img src="images/nouser.jpg"/></div>
                    <div class="span8"><a href="#">Vincent Lau</a><br/><span>Texas,USA</span> </div>
                    <div class="span2"><img class="giftbox" src="images/favicon.png"/></div>
                </div>
                <div class="row-fluid">
                    <div class="span2"><img src="images/nouser.jpg"/></div>
                    <div class="span8"><a href="#">Christopher Lz</a><br/><span>Texas,USA</span></div>
                    <div class="span2"><img class="giftbox" src="images/favicon.png"/></div>
                </div>
                <div class="row-fluid">
                    <div class="span2"><img src="images/nouser.jpg"/></div>
                    <div class="span8"><a href="#">Bing Han</a> <br/><span>Alsert,NZ</span></div>
                    <div class="span2"><img class="giftbox" src="images/favicon.png"/></div>
                </div>
            </div>
            <div class="span8">
			<?php
            require_once('profile.php');
            ?>
            </div>
        </div>
      </div>
<?php
require_once('footer.php');
?>