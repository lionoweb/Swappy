<?php
   session_start();
   require_once("inc/mysql.php");
   require_once("inc/user.php");
   require_once("inc/chat.php");
   $user = new user($mysql);
   if(isset($_GET['logout'])) {
   	$user->logout();
   }
   $user->onlyUsers();
   $chat = new chat($mysql, $user); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Chat BETA</title>
</head>

<body>
<?php echo $chat->list_message(); ?>
</body>
</html>