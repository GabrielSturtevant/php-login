<?php
	session_start();
	$message = $_SESSION['redirect_message'];
	header('refresh:3, index.php');
?>

<?php include "templates/headder.php"; ?>
<title>Redirect</title>

<?php include "templates/home.php"; ?>
<div class="jumbotron">
	<h3><?php echo $message; ?></h3>
</div>
<?php include "templates/footer.php"; ?>
