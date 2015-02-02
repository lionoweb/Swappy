//timeout variable
var wait;
var requestajax = null;
$(document).ready(function(e) {
	$(window).on("orientationchange", function() {
		navbar_padding();
	});
	$(window).on("resize", function() {
		navbar_padding();
	});
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
				document.location.replace("search.php?searchbar=&type="+ui.item.val);	
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
	   if($("#remind_section").css("display") == "none") {
		   $("#remind_section").css("display", "block");
		   $("#login_section").css("display", "none");
	   } else {
		   $("#remind_section").css("display", "none");
		   $("#login_section").css("display", "block");
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
	$("#user_remind").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onAjaxFormComplete: remind_change_function,
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
	$("#login_form").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onAjaxFormComplete: login_user_function,
		onBeforeAjaxFormValidation: load_ajax_d,
		showOneMessage: true,
		promptPosition : "topLeft"
	});
	$("#remind_form").validationEngine({
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
            	'&bull; <select id="dispoday['+lastID+']" class="form-control days" name="dispoday['+lastID+']">' +
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
});
function load_ajax_d(form, options) {
	$form_b = $(form);
	if($form_b.find("#loader_ajax").length < 1) {
		$form_b.append('<div id="loader_ajax"><p><img src="img/icon/loading.gif" alt="" > Envoie en cours...</p></div>');
	}
	return true;
}
function send_mail_contact(status, form, json, options) {
	if(json[0] == true) {
		$form_b = $(form);
		$form_b.html('<div id="message_ajax"><p>Message envoyé</p></div>');
		$(document).scrollTop(0);
		return true;
	} else{
		return false;	
	}
}
function add_user_function(status, form, json, options) {
	$form_b = $(form);
	$form_b.html('<div id="message_ajax"><p>Vous êtes maintenant inscrit !<br><br><i>Vous allez recevoir d\'ici quelques instant un mail de confirmation pour activer votre compte.</i><br><br>Verifiez dans vos indisérable en cas de non reception.</p></div>');
	$(document).scrollTop(0);
	return true;
}
function remind_change_function(status, form, json, options) {
	$form_b = $(form);
	if(json[0] == true) {
		$form_b.html('<div id="message_ajax"><p>Votre mot de passe a été changé !<br><br><i>Vous pouvez dès à présent vous connecter avec ce nouveau mot de passe.</i></p></div>');
	} else {
		$form_b.find("#loader_ajax").remove();
		$form_b.validationEngine('showPrompt', json[1], 'error', "topLeft", false)
	}
	return true;
}
function add_service_function(status, form, json, options) {
	$form_b = $(form);
	$form_b.html('<div id="message_ajax"><p>Service ajouté !</p></div>');
	$(document).scrollTop(0);
	return true;
}
function login_user_function(status, form, json, options) {
	$form_b = $(form);
	if(json[0] == true) {
		var page = document.location.href;
		document.location.replace(page.replace("?logout", "").replace("#", ""));
	} else {
		$form_b.find("#loader_ajax").remove();
		$form_b.validationEngine('showPrompt', json[1], 'error', "topLeft", false)
	}
	return true;
}
function remind_user_function(status, form, json, options) {
	$form_b = $(form);
	if(json[0] == true) {
		$form_b.find("#loader_ajax").remove();
		$("#modal_alert").remove();
		$("body").append('<div id="modal_alert" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-md"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="exampleModalLabel">Mot de passe perdu</h4></div><div class="modal-body">Un mail vous a été envoyer contenant un lien afin de changer le mot de passe de votre compte.<br><br><i>N\'oubliez pas de vérifier dans vos indisérable en cas de non-reception</i></div></div></div></div>');
		$('#modal_alert').modal('show');
		$("#modal_alert").on("hidden.bs.modal", function(e) {
			$(this).remove();
		});
	} else {
		$form_b.find("#loader_ajax").remove();
		$form_b.validationEngine('showPrompt', json[1], 'error', "topLeft", false)
	}
	return true;
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
	if($("#login_form").length > 0) {
		//NOT LOGGED
		$('nav li a[href$="propose.php"]').off();
		$('nav li a[href$="propose.php"]').on("click", function(e) {
			e.preventDefault();
			$("#modal_alert").remove();
			$("body").append('<div id="modal_alert" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-md"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="exampleModalLabel">Page inaccessible</h4></div><div class="modal-body">Désolé, mais la page à laquelle vous souhaitiez afficher n\'est pas accessible en tant que visiteur.<br><br>Veuillez vous inscrire/connecter pour l\'afficher.<div id="clone_login"></div></div></div></div></div>');
			$('#modal_alert').modal('show');
			$("#login_form").clone(true);
			$("#modal_alert").on("hidden.bs.modal", function(e) {
				$(this).remove();
			});
		});
	} else {
		$('nav li a[href$="propose.php"]').off();
	}
}
