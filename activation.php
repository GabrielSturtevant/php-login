<?php

include_once "passwords.php";
$pass = new password();

include_once "connection.php";
$conn = new connection();

$info = $_SERVER['QUERY_STRING'];
$info = convert_uudecode(base64_decode($info));
parse_str($info, $result);

$activation_code = $result['code'];
$username = urldecode($result['username']);

$stored_hash = $conn->get_activation_hash($username);

if(password_verify($activation_code, $stored_hash)){
    $conn->activate_account($username);
    $message = $message . "Activation Successful<br/>";
}else {
    $message = $message . "Activation was not successful";
}
?>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <title>Activation</title>
    <script src="js/functions.js"></script>

</head>

<body>
<?php include "templates/home.php"?>
<h1><?php echo $message ?></h1>
<?php include "template/footer.php"; ?>
</body>
</html>
