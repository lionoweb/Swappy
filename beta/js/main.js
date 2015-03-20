//timeout variable
var wait;
var requestajax = null;
var fst_l = 1;
var last_m = false;
var ajax_c = false;
var time_out_m;
var url_page = ""+window.location.href;
var title_page_ = "";
var twinkle_;
var focuset = true;
var c_message;
$(document).ready(function(e) {
	$(".link_mail").on("click", function(e) {
		e.preventDefault();
		document.location.replace("mailto:"+$(this).attr("data-hash"));
	});
	if($(".login_form").length < 1) {
		c_message = parseInt($(".nav-h .mess_count").html());
		var cc = "";
		if(c_message > 0) {
			 cc = " red";
		}
		$(".navbar-header").append('<span class="mess_count'+cc+'">'+c_message+'</span>');
	} else {
		c_message = 0;
	}
	$(".open-all-com").on("click", function(e) {
		e.preventDefault();
		open_all_coms();
	});
	$(document).on("focusout", function() {
		focuset = false;
	});
	$(document).on("focusin", function() {
		focuset = true;
		clearTimeout(twinkle_);
		document.title = title_page_;
	});
	title_page_ = document.title;
	$.ajaxSetup({ cache: false });
	time_out_m = setTimeout(function() { update_mess_count() }, 49999);
	$(window).on("orientationchange", function() {
		navbar_padding();
	});
	$(window).on("resize", function() {
		navbar_padding();
	});
	if(url_page.match(/messagerie\.php/)) {
		$(['css/images/loading.gif']).preload();
		load_list();
	}
	$("#upload_b").on("change", function(e) {
	var formData = new FormData();
	var filen = this.files[0];
	var maxfi = 5259999;
	if(filen.size > maxfi) {
		$("#upload_ba").validationEngine('showPrompt', "Votre fichier ne doit pas dépasser les 5Mo", 'error', "topLeft", false, true);
	} else {
		formData.append('file-avatar',filen);

$.ajax({
       url : 'inc/add_user.php',
       type : 'POST',
       data : formData,
       processData: false,  // tell jQuery not to process the data
       contentType: false,  // tell jQuery not to set contentType
       success : function(data) {
           c_avatar();
		   var js = JSON.parse(data);
		   if(js[0] == true) {
				$("#avatar_u").attr("src", js[1]);
				$(".dropdown-toggle > img:first-child").attr("src", js[1]);
		   } else {
			   $("#upload_ba").validationEngine('showPrompt', js[1], 'error', "topLeft", false, true);
		   }
       },
	   xhr: function(){
        // get the native XmlHttpRequest object
        var xhr = $.ajaxSettings.xhr() ;
		xhr.upload.onloadstart = function(){ i_avatar(); } ;
        // set the onprogress event handler
        xhr.upload.onprogress = function(evt){ var c = Math.round(evt.loaded/evt.total*100); $(".progress-bar").attr("aria-valuenow", c); $(".progress-bar").css("width", c+"%"); $(".progress-bar").html(c+"%"); } ;
        // set the onload event handler
        xhr.upload.onload = function(){ c_avatar(); } ;
		xhr.upload.onabort = function(){ c_avatar(); } ;
		xhr.upload.onerror = function(){ c_avatar(); } ;
        // return the customized object
        return xhr ;
    } 
});
	}
});
	$(".delete_serv").on("click", function(e) {
		e.preventDefault();
		var id = $(this).attr("data-id");
		$("#modal_delete").remove();
		$("body").append('<div id="modal_delete" data-id="'+id+'" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-md"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="exampleModalLabel">Supprimer cette conversation ?</h4></div><div class="modal-body"><center>Êtes-vous sûr de vouloir effacer le service : <b>'+$(this).parents("tr").find(".serv_title").html()+'</b><br><button class="btn btn-success valid_modal">Oui</button> <button class="cancel_modal btn btn-danger">Non</button></center></div></div></div></div>');
		$('#modal_delete .valid_modal').on("click", function(e) {
			e.preventDefault();
			$.getJSON("inc/add_services.php?delete="+id, function(data) {
				if(data == "true") {
					$('#modal_delete').modal('hide');
					$('tr[data-ids="'+id+'"]').remove();
					if($("tr.bloc_services").length < 1) {
						$(".list_serv tbody").append('<tr class="bloc_services"><td colspan="4"><center>Vous n\'avez pas de services</center></td></tr>');
					}
				} else {
					$('#modal_delete').modal('hide');
				}
			});
		});
		$("#modal_delete .cancel_modal").on("click", function(e) {
			e.preventDefault();
			$('#modal_delete').modal('hide');
		});
		$('#modal_delete').modal('show');
		$("#modal_delete").on("hidden.bs.modal", function(e) {
			$(this).remove();
		});
	});
	$(".badge_").on("click", function(e) {
		$('.badge_:not([data-id="'+$(this).attr("data-id")+'"])').removeClass("glow");
		$('.listing-s:not([data-s="'+$(this).attr("data-id")+'"])').hide();
		$('.listing-s[data-s="'+$(this).attr("data-id")+'"]').toggle();
		$(this).toggleClass("glow");
	});
	$(".popup_message, .talk-button").on("click", function(e) {
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
	$("#note_form").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onAjaxFormComplete: vote_function,
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
	$("#message_send").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onBeforeAjaxFormValidation: function() { $("#message_send textarea").val(""); },
		onAjaxFormComplete: message_send_function,
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
function vote_function(status, form, json, options) {
	$form_b = $(form);
	if(json[0] == true) {
	$form_b.parent().html('<div id="message_ajax"><p>Merci. Nous vous remercions d\'avoir pris le soin de noter ce service</p></div>');
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
		$(".dropdown-toggle > img:first").attr("src", json[1]);
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
		if($('input[name="ID_EDIT"]').length < 1) {
	$form_b.html('<div id="message_ajax"><p>Votre service a bien été ajouté. Vous pouvez le retrouver dans votre profil rubrique “Mes propositions”.</p></div>');
		} else {
			$form_b.html('<div id="message_ajax"><p>Votre service a bien été modifié. Vous pouvez retrouver les modifications dans votre profil rubrique “Mes propositions”.</p></div>');
		}
	$(document).scrollTop(0);
	return true;
	} else {
		$form_b.find("#loader_ajax").remove();
		$form_b.find('input[type="submit"]').validationEngine('showPrompt', json[1], 'error', "topLeft", false, true);
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
		$('nav li a[href$="propose.php"], .interesse .popup_message, .talk_button').off();
		$('nav li a[href$="propose.php"], .interesse .popup_message, .talk-button').on("click", function(e) {
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
function message_send_function(status, form, json, options) {
	if(json[0] == true) {
		$("#loader_ajax").remove();
		$form_b = $(form);
		$form_b.find("textarea[name='message_r']").val("");
		if(load_content($form_b.find("input[name='ID_Converse']").val(), $(".mess_t.active").html(), false, true)) {
			load_list();
		}
		return true;
	} else{
		$("#loader_ajax").remove();
		return false;	
	}
}
function date_send_function(status, form, json, options) {
	$form_b = $(form);
	if(json[0] == true) {
		$("#loader_ajax").remove();
		$('#m_date_modal').validationEngine('detach');
		$("#modal_date").modal("hide");
		if(load_content($("input[name='ID_Converse']").val(), $(".mess_t.active").html(), false)) {
			load_list("load_with_reset_button");
		}
		return true;
	} else{
		$("#loader_ajax").remove();
		$form_b.validationEngine('showPrompt', json[1], 'error', "topLeft", false, true);
		return false;	
	}
}
function load_list(search_) {
	if(typeof(search_) == "undefined") {
		var search_ = "";
	} 
	var button = false;
	if(search_ == "load_with_reset_button") {
		search_ = "";
		button = true;
	} 
	if(typeof(ajax_call) == "object") { ajax_call.abort(); }
	if(search_ == "") {
		$.ajaxSetup({'async': false});
	}
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
				$("#list_m").append('<div data-b="'+value.Button+'" data-state="'+value.Status+'" data-id="'+value.ID+'" class="mess_t'+active+'"><a href="profil.php?id='+value.UserID+'">'+value.Name+'</a><br><span class="m_for">Pour : '+for_+'</span>'+count+'<a title="Supprimer cette conversation" class="delete_m">X</a></div>');
		});
		delete_click();
		event_click();
		if(fst_l == 1) {
			load_content();
			fst_l = 0;	
		}
	});
	$.ajaxSetup({'async': true});
	if(button == true) {
		button_f(0, true);
	}
}

function load_content(id, title, sc, nl) {
	if(typeof(sc) == "undefined") {
		var sc = false;
	}
	if(typeof(nl) == "undefined") {
		var nl = false;
	}
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
		var id = 0;
		mess_count(0, id);
	} else {
		var state = $(".mess_t[data-id='"+id+"']").attr("data-state");
		var serv = $(".mess_t[data-id='"+id+"']").attr("data-b");
		$(".form_m, .header_m").css("display", "");
		if(nl == false) { $(".inner_m").html('<center><img src="css/images/loading.gif" alt="Chargement"> Chargement...</center>'); }
		$.ajaxSetup({'async': false});
		$.getJSON("inc/send_mess.php?get_message="+id, function(data) {
			$(".inner_m").html('');
			$(".header_m span").html(title);
			mess_count(data.count, id);
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
		});
		$.ajaxSetup({'async': true});
		$(".bot:last .valid-this-date").each(function() {
			$(this).addClass("actived");
			$(this).on("click", function(e) {
				$(this).prepend('<img class="loader_link_m" src="css/images/loading.gif">');
				$.ajax({url:"inc/send_mess.php", data:"valid="+$(this).attr("data-id")+"&cc="+$("input[name='ID_Converse']").val(), method: "GET", success: function(data) {
					$(".loader_link_m").remove();
					if(data == "true") { if(load_content($("input[name='ID_Converse']").val(), $(".mess_t.active").html(), false)) {
						
						load_list("load_with_reset_button");
					} }
				}});
			});
		});
		$(".bot:last .refuse-this-date").each(function() {
			$(this).addClass("actived");
			$(this).on("click", function(e) {
				$(this).prepend('<img class="loader_link_m" src="css/images/loading.gif">');
				$.ajax({url:"inc/send_mess.php", data:"refuse="+$(this).attr("data-id")+"&cc="+$("input[name='ID_Converse']").val(), method: "GET", success: function(data) {
					$(".loader_link_m").remove();
					if(data == "true") { if(load_content($("input[name='ID_Converse']").val(), $(".mess_t.active").html(), false)) {					
						load_list("load_with_reset_button");
					} }
				}});
			});
		});
		
	}
	$(".form_m button").off("click");
	if(serv == 1) {
		button_f(state, false, id);
	} else {
		$(".form_m button").html("");
		$(".form_m button").attr("disabled");
		$(".form_m button").css("display", "none");
	}
	$("input[name='ID_Converse']").val(id);
	return true;
}
function mess_count(data, id) {
	if(data != "false") {
		$('div[data-id="'+id+'"] .mess_count').removeClass("red");
		$("input[name='message_r']").val("");
		if(data == 0) {
			$(".navbar-header .mess_count").removeClass("red").html("0");
			$(".nav-h .mess_count").removeClass("red").html("0");
			$(".dropdown-toggle .mess_count").remove();
		} else {
			$(".nav-h .mess_count:not(.red)").addClass("red");
			$(".navbar-header .mess_count:not(.red)").addClass("red");
			$(".nav-h .mess_count").html(data);
			$(".navbar-header .mess_count").html(data);
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
			load_list("load_with_reset_button");
		}
});
}
function make_date(e) {
	var id = $("input[name='ID_Converse']").val();
	var $lis = $(".mess_t[data-id='"+id+"']");
	$('#m_date_modal').validationEngine('detach');
	if($lis.attr("data-b") == "1" && ($lis.attr("data-state") == "0" || $lis.attr("data-state") == "1")) {
		$("#modal_date").remove();
		$.ajax({
			url : 'inc/send_mess.php',
			type : 'GET',
			dataType : 'html',
			data : 'make_date='+id, 
			success : function(data){ 
				$("body").append('<div id="modal_date" data-id="'+id+'" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-md"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data+'</div></div></div></div>');
				$('#modal_date').modal('show');
				$("#modal_date").on("hidden.bs.modal", function(e) {
					$('#m_date_modal').validationEngine('detach');
					$(this).remove();
				});
				var date_s = $("#date").val();
				$("#datepicker").datepicker({altField: "#date",minDate: 0, maxDate: "+1Y"});
				$("#datepicker").datepicker('setDate', date_s);
				 $('#hour').datetimepicker({
					datepicker:false,
					format:'H:i',
					lang: "fr",
					value: $("#hour").val()
				});
				$("#m_date_modal").validationEngine({
					ajaxFormValidation: true,
					ajaxFormValidationMethod: 'post',
					onAjaxFormComplete: date_send_function,
					onBeforeAjaxFormValidation: load_ajax_d,
					showOneMessage: true,
					promptPosition : "topLeft"
				});
           }
    });	
	} else {
		$('#modal_date').modal('hide');
	}
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
					$('#modal_delete').modal('hide');
					fst_l = 1;
					load_list();
				} else {
					var js = JSON.parse(JSON.stringify(data));
					$('#modal_delete').modal('hide');
					$("#message_send").validationEngine('showPrompt', js[1], 'error', "topLeft", false, true);
				}
			});
		});
		$("#modal_delete .cancel_modal").on("click", function(e) {
			e.preventDefault();
			$('#modal_delete').modal('hide');
		});
		$('#modal_delete').modal('show');
		$("#modal_delete").on("hidden.bs.modal", function(e) {
			$(this).remove();
		});
	});
}
function button_f(state, dd, id) {
	$(".form_m button").off("click");
	if(dd == true) {
		state = $(".mess_t.active").attr("data-state");
	}
	if(typeof(id) == "undefined") {
		var id = $(".mess_t.active").attr("data-id");
	}
	var serv = $(".mess_t[data-id='"+id+"']").attr("data-b");
	if(serv == "1") {
		if(state == "0") {
			$(".form_m button").html("FIXER UN RENDEZ-VOUS");
			$(".form_m button").css("display", "");
			$(".form_m button").on("click", function(e) {
				e.preventDefault();
				make_date($(this));
			});
		}
		if(state == "1") {
			$(".form_m button").html("CHANGER LE RENDEZ-VOUS");
			$(".form_m button").css("display", "");
			$(".form_m button").on("click", function(e) {
				e.preventDefault();
				make_date($(this));
			});
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
	}
}
function blink_p() {
	clearTimeout(twinkle_);
	var p = document.title;
	if(p == title_page_) {
		document.title = "Vous avez "+c_message+" nouveau message(s) !";
	} else {
		document.title = title_page_;
	}
	twinkle_ = setTimeout(function() { blink_p(); }, 1200);
}
function update_mess_count() {
	clearTimeout(time_out_m);
	if($(".login_form").length < 1) {
		//LOGGED
		$.getJSON("inc/add_user.php?count_mess", function(data) {
			if(parseInt(data) < 1) {
				$(".nav-h .mess_count").removeClass("red");
				$(".navbar-header .mess_count").removeClass("red");
				$(".dropdown-toggle > .mess_count").remove();
				clearTimeout(twinkle_);
				document.title = title_page_;
				c_message = 0;
			} else {
				var c = parseInt($(".nav-h .mess_count").html());
				if(c != parseInt(data)) {
					c_message = parseInt(data);
					clearTimeout(twinkle_);
					if(focuset == false) {
						blink_p();
					}
				}
				$(".nav-h .mess_count").addClass("red");
				$(".navbar-header .mess_count").addClass("red");
				if($("nav .dropdown-toggle > .mess_count").length < 1) {
					$("<span class='mess_count red'>"+data+"</span>").insertBefore("nav .dropdown-toggle > .caret");
				} else {
					$("nav .dropdown-toggle > .mess_count").html(data);
				}
			}
			time_out_m = setTimeout(function() { update_mess_count() }, 40000);
			$(".nav-h .mess_count").html(data);
			$(".navbar-header .mess_count").html(data);

			if(url_page.match(/messagerie\.php/)) {
				if(load_content($("input[name='ID_Converse']").val(), $(".mess_t.active").html(), false, true)) {
						load_list("load_with_reset_button");
				} 
			}
		});
	}
}
function i_avatar() {
	$(".uploader-button").hide();
	$(".uploader-file-input").hide();
	$(".uploader-progress ").show();
	$(".uploader-side").css("opacity", "1");
	$("#avatar_u").css("opacity", "0.7");
	$(".progress-bar").attr("aria-valuenow", "0"); 
	$(".progress-bar").css("width", "0%"); 
	$(".progress-bar").html("0%");
}
function c_avatar() {
	$(".uploader-button").show();
	$(".uploader-file-input").show();
	$(".uploader-progress ").hide();
	$(".uploader-side").css("opacity", "");
	$("#avatar_u").css("opacity", "");
	$(".progress-bar").attr("aria-valuenow", "0"); 
	$(".progress-bar").css("width", "0%"); 
	$(".progress-bar").html("0%");
}
$.fn.preload = function() {
    this.each(function(){
        $('<img/>')[0].src = this;
    });
}
function open_all_coms() {
	var for_ = "";
	if(url_page.match(/annonce\.php/)) {
		for_ = url_page.replace(/(.*?)annonce\.php(.*?)id=(.*?)([a-z]|\?|\&|\#|)/gi, "$3");
	}
	$("#modal_coms").remove();
		$.ajax({
			url : 'inc/add_services.php',
			type : 'GET',
			dataType : 'html',
			data : 'list_coms='+for_, 
			success : function(data){ 
				$("body").append('<div id="modal_coms" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-md"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="exampleModalLabel">Tous les commentaires</h4></div><div class="modal-body notes">'+data+'<div class="clear"></div></div></div></div></div>');
				$('#modal_coms').modal('show');
				$("#modal_coms").on("hidden.bs.modal", function(e) {
					$(this).remove();
				});
				
           }
    });	
} 