//timeout variable
var wait;
var requestajax = null;
$(document).ready(function(e) {
	$.ajaxSetup({ cache: false });
	$(window).on("orientationchange", function() {
		navbar_padding();
	});
	$(window).on("resize", function() {
		navbar_padding();
	});
	$(".popup_message").on("click", function(e) {
		e.preventDefault();
		$("#modal_chat").modal('show');
		$("#modal_chat").on("hidden.bs.modal", function(e) {
			$("#modal_chat .result_form").hide();
			$("#modal_chat .inner_form").show();
		});
	});
	if($('.tags-input').length > 0) {
		$('.tags-input').tagsinput({
			confirmKeys: [13, 44, 32, 188]
		});
	}
	 $( "#searchbar" ).autocomplete({
		 delay:280, 
		source: function( request, response ) {
			$.ajax({
				url: "inc/search.php",
				dataType: "jsonp",
				data: {
				searchquery: request.term
				},
				success: function( data ) {
					response(data);
				}
			});
		},
		minLength: 3,
		select: function( event, ui ) {
			if(ui.item.val) {
				document.location.replace("services.php?searchbar=&type="+ui.item.val);	
			}
			if(ui.item.userID) {
				document.location.replace("profil.php?id="+ui.item.userID);	
			}
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	});
	$( "#zipbar" ).autocomplete({
		 delay:280,
		source: function( request, response ) {
			$.ajax({
				url: "inc/search.php",
				dataType: "jsonp",
				data: {
				zipquery: request.term,
				extra: $("#zipbar").attr("data-s")
				},
				success: function( data ) {
					response(data);
				}
			});
		},
		minLength: 3,
		select: function( event, ui ) {
			
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	});
	navbar_padding();
	$(".remind_link").bind("click", function() {
	   if($(this).parents("#clone_login, .login-menu").find("#remind_section").css("display") == "none") {
		   $(this).parents("#clone_login, .login-menu").find("#remind_section").css("display", "block");
		   $(this).parents("#clone_login, .login-menu").find("#login_section").css("display", "none");
	   } else {
		   $(this).parents("#clone_login, .login-menu").find("#remind_section").css("display", "none");
		   $(this).parents("#clone_login, .login-menu").find("#login_section").css("display", "block");
	   }
   });
	$("#user_add").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onAjaxFormComplete: add_user_function,
		onBeforeAjaxFormValidation: load_ajax_d,
		showOneMessage: true,
		promptPosition : "topLeft"
	});
	$("#edit_user").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onAjaxFormComplete: edit_user_function,
		onBeforeAjaxFormValidation: load_ajax_d,
		showOneMessage: true,
		promptPosition : "topLeft"
	});
	$("#user_remind").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onAjaxFormComplete: remind_change_function,
		onBeforeAjaxFormValidation: load_ajax_d,
		showOneMessage: true,
		promptPosition : "topLeft"
	});
	$("#modal_chat #send_message").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onAjaxFormComplete: send_popup_message,
		onBeforeAjaxFormValidation: load_ajax_d,
		showOneMessage: true,
		promptPosition : "topLeft"
	});
	$("#spec_contact").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onAjaxFormComplete: send_mail_contact,
		onBeforeAjaxFormValidation: load_ajax_d,
		showOneMessage: true,
		promptPosition : "topLeft"
	});
	$(".login_form").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onAjaxFormComplete: login_user_function,
		onBeforeAjaxFormValidation: load_ajax_d,
		showOneMessage: true,
		promptPosition : "topLeft"
	});
	$(".remind_form").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onAjaxFormComplete: remind_user_function,
		onBeforeAjaxFormValidation: load_ajax_d,
		showOneMessage: true,
		promptPosition : "topLeft"
	});
	$("#spec_propose").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onAjaxFormComplete: add_service_function,
		onBeforeAjaxFormValidation: load_ajax_d,
		showOneMessage: true,
		promptPosition : "topLeft"
	});
	if($('.timepicker').length > 0) {
		$('.timepicker').datetimepicker({
			datepicker:false,
			format:'H:i',
			lang: "fr",
			value: $(this).val()
		});
	}
	$('.remove_dispo').on("click", function() {
			var i = $(this).attr("data-IDF");
			$(".dispo_field[data-IDF='"+i+"']").remove();
		});
	$(".add_dispo").on("click", function(e) {
		e.preventDefault();
		var lastID = $(".dispo_field:last").attr("data-IDF");
		lastID++;
		var html = '<span data-IDF="'+lastID+'" class="dispo_field">' +
            	'<select id="dispoday['+lastID+']" class="form-control days" name="dispoday['+lastID+']">' +
                	'<option value="all">Tous les jours</option>' +
                    '<option value="weekend">Le week-end</option>' +
                	'<option value="lun">Lundi</option>' +
                    '<option value="mar">Mardi</option>' +
                    '<option value="mer">Mercredi</option>' +
                    '<option value="jeu">Jeudi</option>' +
                    '<option value="ven">Vendredi</option>' +
                    '<option value="sam">Samedi</option>' +
                    '<option value="dim">Dimanche</option>' +
                '</select>' +
                ' <span class="toline-xs">entre <input autocomplete="off" size="5" maxlength="5" name="dispostart['+lastID+']" value="19:00" class="validate[required] time timepicker form-control" id="dispostart['+lastID+']" type="text"> et <input autocomplete="off" maxlength="5" name="dispoend['+lastID+']" name="dispoend['+lastID+']" class="time form-control validate[required,timeCheck[dispostart{'+lastID+'}]] timepicker" value="21:00" size="5" type="text"> <a class="remove_dispo" data-idf="'+lastID+'">Effacer</a></span>' +
            '</span>';
		$(html).insertAfter(".dispo_field:last");
		$('.timepicker').datetimepicker({
  			datepicker:false,
  			format:'H:i',
			lang: "fr",
			value: $(this).val()
		});
		$('.remove_dispo').on("click", function() {
			var i = $(this).attr("data-IDF");
			$(".dispo_field[data-IDF='"+i+"']").remove();
		});
	});
	modal_prevent();
	//AUTO LOAD
	if(window.location.hash.match(/\#chat/gi)) {
		window.location.hash = "";
		$("#modal_chat").modal("show");	
	}
});
function load_ajax_d(form, options) {
	$form_b = $(form);
	if($form_b.find("#loader_ajax").length < 1) {
		$form_b.append('<div id="loader_ajax"><p><img src="img/icon/loading.gif" alt="" > envoie en cours...</p></div>');
	}
	return true;
}
function send_mail_contact(status, form, json, options) {
	if(json[0] == true) {
		$form_b = $(form);
		$form_b.html('<div id="message_ajax"><p>Votre message a bien été envoyé à l’équipe de Swappy.</p></div>');
		$(document).scrollTop(0);
		return true;
	} else{
		return false;	
	}
}
function add_user_function(status, form, json, options) {
	$form_b = $(form);
	if(json[0] == true) {
	$form_b.html('<div id="message_ajax"><p>Bienvenue au sein de la communauté Swappy, vous êtes enfin inscrit ! Vous allez recevoir un mail afin de confirmer votre inscription.<br><br>Vérifiez dans vos indésirables en cas de non réception.</p></div>');
	$(document).scrollTop(0);
	return true;
	} else {
		$form_b.find("#loader_ajax").remove();
		return false;
	}
}
function edit_user_function(status, form, json, options) {
	$form_b = $(form);
	if(json[0] == true) {
		$form_b.find("#loader_ajax").remove();
		$("#avatar_u").attr("src", json[1]);
		$(".dropdown-toggle img").attr("src", json[1]);
		$ff = $form_b.find("input[type='submit']");
		$ff.validationEngine('showPrompt', "Modifications éfféctuées !", 'pass', "topLeft", false, true);
		return true;
	} else {
		$form_b.find("#loader_ajax").remove();
		if(typeof(json[2]) != "undefined") {
			$ff = $form_b.find("#"+json[2]+"");
			$ff.validationEngine('showPrompt', json[1], 'error', "topLeft", true, true);
		} else {
			$form_b.validationEngine('showPrompt', json[1], 'error', "topLeft", false, true);
		}
		return false;
	}
}
function remind_change_function(status, form, json, options) {
	$form_b = $(form);
	if(json[0] == true) {
		$form_b.html('<div id="message_ajax"><p>Votre mot de passe a été changé !<br><br><i>Vous pouvez dès à présent vous connecter avec votre nouveau mot de passe.</i></p></div>');
		return true;
	} else {
		$form_b.find("#loader_ajax").remove();
		$form_b.validationEngine('showPrompt', json[1], 'error', "topLeft", false, true);
		return false;
	}
	
}
function add_service_function(status, form, json, options) {
	$form_b = $(form);
	if(json[0] == true) {
	$form_b.html('<div id="message_ajax"><p>Votre service a bien été ajouté. Vous pouvez le retrouver dans votre profil rubrique “Mes propositions”.</p></div>');
	$(document).scrollTop(0);
	return true;
	} else {
		$form_b.find("#loader_ajax").remove();
		$(document).scrollTop(0);
		return false;
	}
}
function send_popup_message(status, form, json, options) {
	$form_b = $(form);
	if(json[0] == true) {
		$form_b.find("#loader_ajax").remove();
		$form_b.find('.result_form').html('<b>Message envoyé !</b>').show();
		$form_b.find('.inner_form').hide();
		$form_b.trigger("reset");
		setTimeout(function() { $("#modal_chat").modal("hide"); }, 15000);
		return true;
	} else {
		$form_b.find("#loader_ajax").remove();
		$form_b.validationEngine('showPrompt', json[1], 'error', "topLeft", false, true);
		return false;
	}
	
}
function login_user_function(status, form, json, options) {
	$form_b = $(form);
	var page = "";
	if(json[0] == true) {
		if($form_b.find("input[name='to_url']").length > 0 && $form_b.find("input[name='to_url']").val() != "") {
			page = $form_b.find("input[name='to_url']").val();
		} else {
			page = document.location.href;
		}
		page = page.replace("?logout", "").replace("&logout", "").replace("&&", "");
		if(!page.match(/\#chat/gi)) {
			page = page.replace("#", "");
		}
		document.location.replace(page);
		return true;
	} else {
		$form_b.find("#loader_ajax").remove();
		$form_b.validationEngine('showPrompt', json[1], 'error', "topLeft", false, true);
		return false;
	}
}
function remind_user_function(status, form, json, options) {
	$form_b = $(form);
	if(json[0] == true) {
		$form_b.find("#loader_ajax").remove();
		$("#modal_alert").remove();
		$("body").append('<div id="modal_alert" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-md"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="exampleModalLabel">Mot de passe perdu</h4></div><div class="modal-body">Un mail vous a été envoyé contenant un lien afin de changer le mot de passe de votre compte.<br><br><i>N\'oubliez pas de vérifier dans vos indésirables en cas de non réception</i></div></div></div></div>');
		$('#modal_alert').modal('show');
		$("#modal_alert").on("hidden.bs.modal", function(e) {
			$(this).remove();
		});
		return true;
	} else {
		$form_b.find("#loader_ajax").remove();
		$form_b.validationEngine('showPrompt', json[1], 'error', "topLeft", false, true);
		return false;
	}
}
function ZipFill(json) {
	var $parent;
	if(isArray(json[2])) {
		if($("input[name='cityname']").length > 0) {
			$parent = $("input[name='cityname']").parent();
			$("input[name='cityname']").remove();
			$parent.append("<select name='cityname' class='form-control auto_width'></select>");
		}
		$("select[name='cityname']").html("");
		for(i=0;i<json[2].length;i++) {
			$("select[name='cityname']").append('<option value="'+json[2][i]+'">'+json[2][i]+'</option>');
		}
	} else {
		if($("select[name='cityname']").length > 0) {
			$parent = $("select[name='cityname']").parent();
			$("select[name='cityname']").remove();
			$parent.append("<input class='liketext' type='text' readonly name='cityname'>");
		}
		$("input[name='cityname']").val(json[2]);
	}
}
function navbar_padding() {
	if($(document).width() > 767) {
		var ww = $("#navbar").width();
		var whe = $(".navbar-header").width();
		//alert(ww);
		var wm = $(".navbar-form").width() + $(".nav.navbar-nav:not(.navbar-right)").width() + $(".nav.navbar-nav.navbar-right").width();
		if((wm+2) >= (ww-whe-15)) {
			if($(".search_navbar:not(.moved-group)").length > 0) {
				$(".search_navbar:not(.moved-group)").addClass("moved-group");
				$("nav").height("100px");
			}
		} else {
			if($(".search_navbar.moved-group").length > 0) {
				$(".search_navbar.moved-group").removeClass("moved-group");
				$("nav").height("");
			}
		}
	} else {
		if($(".search_navbar.moved-group").length > 0) {
			$(".search_navbar.moved-group").removeClass("moved-group");
			$("nav").css("height", "");
		}
	}
	var h = $("nav").height() + parseInt($("nav").css("margin-bottom").replace("px", ""));
	$("body").css("padding-top", h+"px");
	var wh = $(window).height();
	var wwh = ($(document).height()-($("nav").height()-$("footer").height()-40));
	if(wh > wwh && ((wh - wwh) < -131)) {
		$("#wrap").css("height", "100%");
	} else {
		$("#wrap").removeAttr("style");
	}
}
function isArray(obj) {
    return (obj.constructor.toString().indexOf("Array") != -1);
}
function modal_prevent() {
	if($(".login_form").length > 0) {
		//NOT LOGGED
		$('nav li a[href$="propose.php"], .interesse .popup_message, .link-profil').off();
		$('nav li a[href$="propose.php"], .interesse .popup_message, .link-profil').on("click", function(e) {
			var to = "";
			if($(this).attr("href") && $(this).attr("href") != "") {
				to = $(this).attr("href");	
			} else if($(this).hasClass("popup_message")) {
				var page_url = document.location.toString();
				page_url = page_url.replace(/\#(.*?)/gi, "");
				to = page_url+"&#chat";
			}
			e.preventDefault();
			$("#modal_alert").remove();
			$("body").append('<div id="modal_alert" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-md"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="exampleModalLabel">Se connecter pour y accéder</h4></div><div class="modal-body">Désolé, mais la page à laquelle vous souhaitiez accéder n\'est pas accessible en tant que visiteur.<br><br>Veuillez vous inscrire/connecter pour l\'afficher.<div id="clone_login"></div></div></div></div></div>');
			$('#modal_alert').modal('show');
			$("#login_section").clone(true).appendTo("#clone_login");
			$("#remind_section").clone(true).appendTo("#clone_login");
			$("#clone_login").append('<a href="inscription.php" class="hidden_ notsigned">Pas encore inscrit ?</a><div class="clear"></div>');
			$("#clone_login .login_form").append("<input type='hidden' name='to_url' value='"+to+"'>");
			$("#modal_alert").on("hidden.bs.modal", function(e) {
				$(this).remove();
			});
		});
	} else {
		$('nav li a[href$="propose.php"]').off();
		$('.interesse .popup_message').off();
		$(".interesse .popup_message").on("click", function(e) {
			e.preventDefault();
			$("#modal_chat").modal('show');
			$("#modal_chat").on("hidden.bs.modal", function(e) {
				$("#modal_chat .result_form").hide();
				$("#modal_chat .inner_form").show();
			});
	});
	}
}
