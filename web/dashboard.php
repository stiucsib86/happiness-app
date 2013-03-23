<?php
$pageTitle = 'HappinessXchange - Dashboard';
require_once('header.php');
?>
<script>
<?php
if ($_POST) {
	if ($_GET['type'] == 'give' && $_POST['giftFlag'] == 1) {
		?>
			jQuery.getJSON("http://happiness-app.ap01.aws.af.cm/gifting/send/?receiver_fb_id=<?php echo $_GET['id']; ?>&gifting_url=<?php echo urlencode($_POST['productURL']); ?>&callback=?", function(data) {
				console.log(data);
				alert('Congratulations, your friend has been notified!');
			});
		<?php
	} else if ($_GET['type'] == 'receive' && $_POST['giftFlag'] == 1) {
		?>
			jQuery.getJSON("http://happiness-app.ap01.aws.af.cm/gifting/accept/?gifting_id=<?php echo $_GET['gid']; ?>&thankyou_note=<?php echo urlencode($_POST['thankyou_note']); ?>&callback=?", function(data) {
				console.log(data);
				alert('Your friend is pleased that you like the gift!');
			});
		<?php
	} else {
		?>
			jQuery.getJSON("http://happiness-app.ap01.aws.af.cm/user/update_user/?gender=<?php echo $_POST['gender']; ?>&dob=<?php echo $_POST['dob']; ?>&interest=<?php echo $_POST['interest']; ?>&about_me=<?php echo $_POST['about_me']; ?>&callback=?", function(data) {
				console.log(data);
			});
		<?php
	}
}
?>
</script>

<div class="dashboard container">

	<?php require_once 'header_mini.php'; ?>

	<div class="row-fluid top-item">


        <div class="row-fluid">
            <div class="span4 friendslist">
				<?php require_once('friendlist.php'); ?>
            </div>
            <div class="span8">
				<?php
				switch ($_GET['type']) {
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