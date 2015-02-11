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
<script src="js/jquery.js"></script>
      <script src="js/jquery-ui.js"></script>
      <style>
	  	.mess_t:hover {
			cursor:pointer;
			background:#1A6BE8;
		}
		.mess_t {
			background:#59E7FF;	
			border:1px solid black;
			padding:3px;
		}
		.other {
			float:right; background:#CCC; display:block; width:60%; min-width:60%; margin:4px;
		}
		.me {
			float:left; background:#FCC; display:block; width:60%; min-width:60%; margin:4px;
		}
	  </style>
</head>

<body>
<div id="list_m" style="width:250px; min-width:250px; float:left;">
</div>
<div id="content_m" style="width:450px; float:left;">
    <div class="inner_m" style="height:400px; overflow:auto; max-height:400px; border:1px solid black;">
    	
    </div>
</div>
<div style="clear:both;"></div>
<script>
var fst_l = 1;
$(document).ready(function(e) {
    load_list();
});
function load_list() {
	$.getJSON("inc/send_mess.php?list_message", function(data) {
		var for_ = "";
		$.each(data, function(index, value) {
			if(value.For > 0) {
				for_ = '<a href="annonce.php?id='+value.For+'">'+value.Title+'</a>';	
			} else {
				for_ = '<i>discuter</i>';
			}
			$("#list_m").append('<div data-id="'+value.ID+'" class="mess_t">'+value.Name+'<br>Pour : '+for_+'</div>');
		});
		event_click();
		if(fst_l == 1) {
			load_content();
			fst_l = 0;	
		}
	});
}
function load_content(id) {
	if(typeof(id) == "undefined") {
		var id = $(".mess_t:first").attr("data-ID");
	}
	$.getJSON("inc/send_mess.php?get_message="+id, function(data) {
		$(".inner_m").html('');
		$.each(data, function(index, value) {
			var c = 'other';
			if(value.Author == "ME") {
				c = 'me';
			}
			$(".inner_m").append('<div class="'+c+'">'+value.Message+'</div>');
		});
	});
}
function event_click() {
	$(".mess_t").off("click");
	$(".mess_t").on("click", function(e) {
		 var target  = $(e.target);
		if( target.is('a') ) {
			return true;
		} else {
			load_content($(this).attr("data-id"));
		}
});
}
</script>
</body>
</html>