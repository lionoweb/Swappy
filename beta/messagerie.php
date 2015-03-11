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
			position:relative;
		}
		.mess_t.active {
			background:#54C0DD;
		}
		.other {
			background:#54C0DD; 
			display:block; 
			width:78%; 
			padding:6px;
			padding-left:40px;
			padding-right:9%;
			border-radius:10px;
			margin-top:8px;
			margin-bottom:8px;
			margin-left:8px;
			margin-right:16%;
			clear:both;
			float:left;
		}
		.me {
			clear:both;
			float:right;
			 background:#FFF; 
			 display:block; 
			width:78%; 
			padding:6px;
			padding-left:9%;
			padding-right:40px;
			text-align:right;
			background:#CCC;
			border-radius:10px;
			margin-left:16%;
			margin-top:8px;
			margin-bottom:8px;
			margin-right:8px;
		}
		.message_box {
			margin-top:40px;
			height:490px;
			min-height:490px;
			border:1px solid #CCC;	
			padding-top:0px !important;
		}
		#list_m input, #list_m button {
			margin-top:0px !important;	
			margin-bottom:0px !important;
			margin-left:0px !important;	
			margin-right:0px !important;
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
			height:240px; 
			overflow:auto; 
			max-height:240px;
			min-height:240px;
		}
		.header_m {
			color:#54C0DD;
			font-size:20px;
			border-bottom:1px solid #CCC;
			height:auto;
			min-height:86px;
			position:relative;
		}
		.header_m .return_list {
			display:none;	
			position:absolute;
			left:0px;
			top:0px;
		}
		.header_m > span {
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
		.header_m .mess_count {
			display:none !important;	
		}
		.message_box textarea {
			margin-bottom:6px;
		}
		.message_box button, .message_box input {
			margin-right:8px;
			margin-top:4px;	
			margin-bottom:3px;
		}
		#list_m .input-group {
			border:1px solid #CCC;	
			border-bottom:2px solid #CCC;	
		}
		.mess_t .mess_count {
			position:absolute;
			right:4px;
			top:50%;
			margin-top:-9px;
			display:none;
		}
		.mess_t .mess_count.red {
			display:block !important;
		}
		.msg .time {
			font-size:10px;
			font-style:italic;
			display:block;	
		}
		.delete_m {
			position:absolute;
			top:2px;
			right:3px;
			font-size:11px;
			color:#000;
			width:15px;
			height:15px;
			text-align:center;
			line-height:15px;
		}
		.delete_m:hover {
			color:#000;
			text-decoration:none !important;
			border:1px solid #262626;
			border-radius:50%;
		}
		@media (max-width:381px){
			.message_box {
				height:530px;
				min-height:530px;		
			}
		}
		@media (max-width:701px){
			.header_m .return_list {
				display:inline-block;	
				width:60px;
				height:100%;
				background:#1864EB;
				
				font-size:16px;
				text-align:center;
				color:#FFF;
			}
			.header_m .return_list:hover {
				cursor:pointer;	
			}
			.header_m > span {
				padding:14px;
				padding-left:74px !important;
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
		#modal_delete .delete_m {
			display:none !important;	
		}
		#modal_delete a {
			text-decoration:none;	
		}
		.cancel_modal, .valid_modal {
			margin-left:45px;
			margin-right:45px;
			margin-top:14px;
			display:inline-block;	
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
               <form class="navbar-form navbar-left search_navbar" action="services.php" method="get" role="search">
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
var last_m = false;
var ajax_c = false;
$(document).ready(function(e) {
    load_list();
	$("#message_send button").on("click", function(e) {
		e.preventDefault();
		//SHOW POPUP
	});
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
	$("#list_m input").on("keyup", function(e) {
		var va = $(this).val();
		if(va.length >= 3) {
			$(this).addClass("onsearch");
			load_list(va);
		} else if(va == "") {
			$(this).removeClass("onsearch");
			load_list("");
		} else if($(this).hasClass("onsearch")) {
			$(this).removeClass("onsearch");
			load_list("");
		}
	});
});
function message_send_function(status, form, json, options) {
	if(json[0] == true) {
		$("#loader_ajax").remove();
		$form_b = $(form);
		$form_b.find("textarea[name='message_r']").val("");
		load_content($form_b.find("input[name='ID_Converse']").val(), $(".mess_t.active").html(), false);
		load_list();
		return true;
	} else{
		return false;	
	}
}
function load_list(search_) {
	if(typeof(search_) == "undefined") {
		var search_ = "";
	} 
	if(typeof(ajax_call) == "object") { ajax_call.abort(); }
	ajax_call = $.getJSON("inc/send_mess.php?list_message=&search="+search_, function(data) {
		var for_ = "";
		var active = "";
		var count = '<span class="mess_count"></span>';
		$(".mess_t").remove();
		$.each(data, function(index, value) {
			active = "";
			count = "";
				if(value.For > 0) {
					for_ = '<a target="_blank" href="annonce.php?id='+value.For+'">'+value.Title+'</a>';	
				} else {
					for_ = '<i>discuter</i>';
				}
				if(value.Count > 0) {
					count = '<span class="mess_count red">'+value.Count+'</span>';
				}
				if(value.ID == last_m) {
					active = " active";
				}
				$("#list_m").append('<div data-state="'+value.Status+'" data-id="'+value.ID+'" class="mess_t'+active+'"><a href="profil.php?id='+value.UserID+'">'+value.Name+'</a><br><span class="m_for">Pour : '+for_+'</span>'+count+'<a title="Supprimer cette conversation" class="delete_m">X</a></div>');
		});
		delete_click();
		event_click();
		if(fst_l == 1) {
			load_content();
			fst_l = 0;	
		}
	});

}
function load_content(id, title, sc) {
	if(typeof(sc) == "undefined") {
		var sc = false;
	}
	var ccc = "false";
	if(typeof(id) == "undefined" || id == "") {
		var hash = window.location.hash;
		if(hash.match(/\#select\-/)) {
			window.location.hash = "";
			var id = hash.replace(/\#select\-/, "");
			if($(".mess_t[data-id='"+id+"']").length > 0) {
				last_m = id;
				var title = $(".mess_t[data-id='"+id+"']").html();
				$(".mess_t[data-id='"+id+"']").addClass("active");
			} else {
				var id = $(".mess_t:first").attr("data-ID");
				last_m = id;
				var title = $(".mess_t:first").html();
				$(".mess_t:first").addClass("active");
			}
		} else {
			var id = $(".mess_t:first").attr("data-ID");
			last_m = id;
			var title = $(".mess_t:first").html();
			$(".mess_t:first").addClass("active");
		}
	} else {
		$("#list_m:not(.cache)").addClass("cache");	
		$("#content_m:not(.montre)").addClass("montre");	
		last_m = id;
	}
	if($(".mess_t").length < 1) {
		$(".inner_m").html('<center><br>Vous n\'avez aucun message</center>');
		$(".header_m span").html('');
		$(".form_m, .header_m").css("display", "none");
		ccc = 0;
	} else {
		var state = $(".mess_t[data-id='"+id+"']").attr("data-state");
		$(".form_m, .header_m").css("display", "");
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
					$(".inner_m").append('<div class="'+c+' msg">'+value.Message+'<span class="time">'+value.TimeText+'</span></div>');
				}
			});
			if(sc == false) {
				$(".inner_m").scrollTop($(".inner_m")[0].scrollHeight);
			}
			ccc = data.count;
		});
	}
	if(state == "0") {
		$(".form_m button").html("FIXER UN RENDEZ-VOUS");
		$(".form_m button").css("display", "");
	}
	if(state == "1") {
		$(".form_m button").html("CHANGER LE RENDEZ-VOUS");
		$(".form_m button").css("display", "");
	}
	if(state == "2") {
		$(".form_m button").html("");
		$(".form_m button").attr("disabled");
		$(".form_m button").css("display", "none");
	}
	if(state == "3") {
		$(".form_m button").html("");
		$(".form_m button").attr("disabled");
		$(".form_m button").css("display", "none");
	}
	mess_count(ccc, id);
}
function mess_count(data, id) {
	if(data != "false") {
		$('div[data-id="'+id+'"] .mess_count').removeClass("red");
		$("input[name='ID_Converse']").val(id);
		$("input[name='message_r']").val("");
		if(data == 0) {
			$(".nav-h .mess_count").removeClass("red").html("0");
			$(".dropdown-toggle .mess_count").remove();
		} else {
			$(".nav-h .mess_count:not(.red)").addClass("red");
			$(".nav-h .mess_count").html(data);
			if($(".dropdown-toggle .mess_count").length < 1) {
				$("<span class=''>"+data+"</span>").insertBefore(".dropdown-toggle .caret");
			} else {
				$(".dropdown-toggle .mess_count").html(data);
			}
		}
	}
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
function delete_click() {
	$(".delete_m").on("click", function(e) {
		var id = $(this).parent().attr("data-id");
		e.preventDefault();
		$("#modal_delete").remove();
		$("body").append('<div id="modal_delete" data-id="'+id+'" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-md"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="exampleModalLabel">Supprimer cette conversation ?</h4></div><div class="modal-body"><center><u>Êtes-vous sûr de vouloir effacer la conversation :</u> <br><br>'+$(this).parent().html()+'<br><button class="btn btn-success valid_modal">Oui</button> <button class="cancel_modal btn btn-danger">Non</button></center></div></div></div></div>');
		$('#modal_delete .valid_modal').on("click", function(e) {
			e.preventDefault();
			var id = $(this).parents("#modal_delete").attr("data-id");
			$.getJSON("inc/send_mess.php?delete="+id, function(data) {
				if(data == "true") {
					$("#modal_delete").remove();
					fst_l = 1;
					load_list();
				} else {
					$("#modal_delete").remove();
				}
			});
		});
		$("#modal_delete .cancel_modal").on("click", function(e) {
			e.preventDefault();
			$('#modal_delete').modal('hide');
			$('#modal_delete').remove();
		});
		$('#modal_delete').modal('show');
		$("#modal_delete").on("hidden.bs.modal", function(e) {
			$(this).remove();
		});
	});
}
</script>     </div></div>
      </div>
      <!-- END DIV ID WRAP-->
      <footer id="footer">
         <img src="img/footer.png" width="30" height="18">
         <div class="container-fluid">
            <a href="cgu.php">CGU - Mentions légales</a> | <a href="contact.php" class="active">Contact</a>
            <hr>
            <p>Copyright &copy; Swappy.fr. Tous droits réservés</p>
         </div>
      </footer>
</body>
</html>