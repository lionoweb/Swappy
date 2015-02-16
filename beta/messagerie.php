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
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Swappy.fr - Messagerie</title>
      <link rel="icon" href="img/favicon.png">
      <link rel="stylesheet" href="css/jquery-ui.css">
      <link rel="stylesheet" href="css/jquery.datetimepicker.css">
      <link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
      <link rel="stylesheet" href="css/template.css" type="text/css"/>
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <link rel="stylesheet" href="css/main.css">
      <script src="js/jquery.js"></script>
      <script src="js/jquery-ui.js"></script>
      <script src="js/ValidationEngine/languages/jquery.validationEngine-fr.js"></script>
      <script src="js/ValidationEngine/jquery.validationEngine.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <script src="js/main.js"></script>
      <style>
	  	.mess_t:hover {
			cursor:pointer;
			background:#DFDFDF;
		}
		.mess_t {
			background:#E4E4E4;	
			padding:3px;
			border-bottom:1px solid #FFF;
		}
		.mess_t.active {
			background:#54C0DD;
		}
		.other {
			background:#54C0DD; 
			display:block; 
			width:100%; 
			padding:6px;
			padding-left:40px;
			padding-right:120px;
		}
		.me {
			 background:#FFF; 
			 display:block; 
			width:100%; 
			padding:6px;
			padding-left:120px;
			padding-right:40px;
			text-align:right;
		}
		.message_box {
			margin-top:40px;
			height:590px;
			border:1px solid #CCC;	
			padding-top:0px !important;
		}
		#list_m {
			width:220px; 
			min-width:220px; 
			display:inline-block;
			height:100%;
			min-height:100%;
			overflow:auto;
			border-right:1px solid #CCC;
			position:relative;
			z-index:2;
		}
		#content_m {
			width:100%; 
			margin-left:-221px;
			display:inline-block;
			height:100%;
			min-height:100%;
			position:relative;
			padding-left:221px;
			vertical-align:top;
		}
		.msg:first-child {
			margin-top:0px !important;	
		}
		.inner_m {
			height:340px; 
			overflow:auto; 
			max-height:340px;
			min-height:340px;
		}
		.header_m {
			color:#54C0DD;
			font-size:20px;
			border-bottom:1px solid #CCC;
			height:86px;
			min-height:86px;
		}
		.header_m .return_list {
			display:none;	
		}
		.header_m span {
			padding:14px;
			padding-left:30px;
			display:inline-block;	
		}
		.header_m .m_for {
			color:#000;
			font-size:14px;
			padding-left:15px;
			display:inline-block;
		}
		.message_box textarea {
			margin-bottom:6px;
		}
		.message_box button, .message_box input {
			margin-right:8px;	
		}
		#list_m .input-group {
			border:1px solid #CCC;	
			border-bottom:2px solid #CCC;	
		}
		@media (max-width:701px){
			.header_m .return_list {
				display:inline-block;	
				width:60px;
				height:85px;
				background:#1864EB;
				line-height:85px;
				font-size:16px;
				text-align:center;
				vertical-align:top;
				color:#FFF;
			}
			.header_m .return_list:hover {
				cursor:pointer;	
			}
			.header_m span {
				padding:14px;
				padding-left:14px !important;
				display:inline-block;	
			}
			#content_m {
				display:none;	
				margin-left:0px !important;
				padding-left:0px !important;
			}
			#content_m.montre {
				display:inline-block;	
			}
			#list_m {
				width:100%;	
				min-width:100%;
			}
			.mess_t.active {
				background:#E4E4E4 !important;
			}
			#list_m.cache {
				display:none;	
			}
		}
	  </style>
   </head>
   <body role="document">
      <div id="wrap">
         <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
               <!-- Brand and toggle get grouped for better mobile display -->
               <div class="navbar-header">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="index.php" title="Retour à l'accueil"><img width="127" height="47" src="img/logonav.png" class="max"><img width="50" height="47" src="img/logo_min.png" class="min"></a>
                  <span class="brand-title">Messagerie</span>
               </div>
               <form class="navbar-form navbar-left search_navbar" method="get" role="search">
                  <div class=" input-group">
                     <input id="searchbar" name="searchbar" type="text" class="form-control" placeholder="Rechercher">
                     <span class="input-group-btn">
                     <button title="Rechercher" type="submit" class="btn btn-default"></button>
                     </span>
                  </div>
               </form>
               <!-- Collect the nav links, forms, and other content for toggling -->
               <div class="collapse navbar-collapse" id="navbar">
                  <ul class="nav navbar-nav">
                     <li><a href="services.php">Services</a></li>
                     <li><a href="propose.php">Je propose</a></li>
                     <li><a href="ccm.php">Comment ça marche ?</a></li>
                     <li><a href="apropos.php">A propos</a></li>
                  </ul>
                  <ul class="nav navbar-nav navbar-right">
                     <?php echo $user->navbar(); ?>
                  </ul>
               </div>
               <!-- /.navbar-collapse -->
            </div>
            <!-- /.container-fluid -->
         </nav>
         <div class="container main" role="main">
<div class="message_box col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1"><!--
--><div id="list_m">
<div class=" input-group">
<span class="input-group-btn">
                     <button class="btn btn-default bluesearch" type="submit" title="Rechercher"></button>
                     </span>
                     <input type="text" placeholder="Rechercher" class="form-control ui-autocomplete-input" name="searchbar" id="searchbar" autocomplete="off">
                     
                  </div>
</div><!--
--><div id="content_m" style=""><!--
	--><div class="header_m"><div class="return_list">Retour</div><span></span></div>
    <div class="inner_m">
    	
    </div>
    <div class="form_m">
    <form id='message_send' action="inc/send_mess.php" method="post">
    	<textarea name="message_r" placeholder="Votre message" rows="4" class="form-control validate[required]"></textarea>
        <input type="hidden" value="" name="ID_Converse">
        <input type="submit" value="envoyer">
        <button>Fixer un rendez-vous</button>
        </form>
        <div class="clear"></div>
    </div>
</div>
<script>
var fst_l = 1;
$(document).ready(function(e) {
    load_list();
	$("#message_send").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onAjaxFormComplete: message_send_function,
		onBeforeAjaxFormValidation: load_ajax_d,
		showOneMessage: true,
		promptPosition : "topLeft"
	});
	$(".return_list").on("click", function(e) {
		e.preventDefault();
		$("#list_m.cache").removeClass("cache");	
		$("#content_m.montre").removeClass("montre");
	});
});
function message_send_function(status, form, json, options) {
	if(json[0] == true) {
		$("#loader_ajax").remove();
		$form_b = $(form);
		$form_b.find("textarea[name='message_r']").val("");
		$(".inner_m").scrollTop($(".inner_m").scrollHeight);
		load_content($form_b.find("input[name='ID_Converse']").val(), $(".mess_t.active").html());
		
		return true;
	} else{
		return false;	
	}
}
function load_list() {
	$.getJSON("inc/send_mess.php?list_message", function(data) {
		var for_ = "";
		$(".mess_t").remove();
		$.each(data, function(index, value) {
				if(value.For > 0) {
					for_ = '<a target="_blank" href="annonce.php?id='+value.For+'">'+value.Title+'</a>';	
				} else {
					for_ = '<i>discuter</i>';
				}
				$("#list_m").append('<div data-id="'+value.ID+'" class="mess_t">'+value.Name+'<br><span class="m_for">Pour : '+for_+'</span></div>');
		});
		event_click();
		if(fst_l == 1) {
			load_content();
			fst_l = 0;	
		}
	});
}
function load_content(id, title) {
	if(typeof(id) == "undefined") {
		var id = $(".mess_t:first").attr("data-ID");
		var title = $(".mess_t:first").html();
		$(".mess_t:first").addClass("active");
	}	else {
		$("#list_m:not(.cache)").addClass("cache");	
		$("#content_m:not(.montre)").addClass("montre");	
	}
	$(".inner_m").html('<center>Chargement...</center>');
	$.getJSON("inc/send_mess.php?get_message="+id, function(data) {
		$(".inner_m").html('');
		$(".header_m span").html(title);
		$.each(data, function(index, value) {
			if(typeof(value.Message) != "undefined") {
				var c = 'other';
				if(value.Author == "ME") {
					c = 'me';
				}
				if(value.Author == "BOT") {
					c = 'bot';
				}
				$(".inner_m").append('<div class="'+c+' msg">'+value.Message+'</div>');
			}
		});
		$("input[name='ID_Converse']").val(id);
		$("input[name='message_r']").val("");
		if(data.count == 0) {
			$(".nav-h .mess_count").removeClass("red").html("0");
			$(".dropdown-toggle .mess_count").remove();
		} else {
			$(".nav-h .mess_count:not(.red)").addClass("red");
			$(".nav-h .mess_count").html(data.count);
			if($(".dropdown-toggle .mess_count").length < 1) {
				$("<span class=''>"+data.count+"</span>").insertBefore(".dropdown-toggle .caret");
			} else {
				$(".dropdown-toggle .mess_count").html(data.count);
			}
		}
	});
}
function event_click() {
	$(".mess_t").off("click");
	$(".mess_t").on("click", function(e) {
		 var target  = $(e.target);
		if( target.is('a') ) {
			return true;
		} else {
			$(".mess_t.active").removeClass("active");
			$(this).addClass("active");
			load_content($(this).attr("data-id"), $(this).html());
		}
});
}
</script>        </div></div>
      </div>
      <!-- END DIV ID WRAP-->
      <footer id="footer">
         <img src="img/footer.png" width="30" height="18">
         <div class="container-fluid">
            <a href="mentions-legales.php">Mentions légales</a> - <a href="cgu.php">CGU</a> | <a href="contact.php" class="active">Contact</a>
            <hr>
            <p>Copyright &copy; Swappy.fr. Tous droits réservés</p>
         </div>
      </footer>
</body>
</html>