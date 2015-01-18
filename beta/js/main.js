//timeout variable
var wait;
var requestajax = null;
$(document).ready(function(e) {
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
				zipquery: request.term
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
		showOneMessage: true
	});
	$("#spec_contact").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onAjaxFormComplete: send_mail_contact,
		onBeforeAjaxFormValidation: load_ajax_d,
		showOneMessage: true,
		promptPosition : "topRight:-120"
	});
	$("#login_form").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onAjaxFormComplete: login_user_function,
		onBeforeAjaxFormValidation: load_ajax_d,
		showOneMessage: true,
		promptPosition : "topRight:-120"
	});
	$("#add_services").validationEngine({
		ajaxFormValidation: true,
		ajaxFormValidationMethod: 'post',
		onAjaxFormComplete: add_service_function,
		onBeforeAjaxFormValidation: load_ajax_d,
		showOneMessage: true
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
	$(".add_dispo").on("click", function() {
		var lastID = $(".dispo_field:last").attr("data-IDF");
		lastID++;
		var html = '<span data-IDF="'+lastID+'" class="dispo_field">' +
            	'Le <select id="dispoday['+lastID+']" name="dispoday['+lastID+']">' +
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
                ' entre <input autocomplete="off" size="5" maxlength="5" name="dispostart['+lastID+']" value="19:00" class="validate[required] timepicker" id="dispostart['+lastID+']" type="text"> et <input autocomplete="off" maxlength="5" name="dispoend['+lastID+']" name="dispoend['+lastID+']" class="validate[required,timeCheck[dispostart{'+lastID+'}]] timepicker" value="21:00" size="5" type="text"> <a class="remove_dispo" data-idf="'+lastID+'">Effacer</a>' +
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
});
function load_ajax_d(form, options) {
	$form_b = $(form);
	if($form_b.find("#loader_ajax").length < 1) {
		$form_b.append('<div id="loader_ajax">Chargement en cours...</div>');
	}
	return true;
}
function send_mail_contact(status, form, json, options) {
	if(json[0] == true) {
		$form_b = $(form);
		$form_b.html('<center><br><b>Message envoyé</b><br><br></center>');
		return true;
	} else{
		return false;	
	}
}
function add_user_function(status, form, json, options) {
	$form_b = $(form);
	$form_b.html('<center><br><b>Vous êtes maintenant enregistrer !</b><br><br></center>');
	return true;
}
function add_service_function(status, form, json, options) {
	$form_b = $(form);
	$form_b.html('<center><br><b>Service ajouté !</b><br><br></center>');
	return true;
}
function login_user_function(status, form, json, options) {
	$form_b = $(form);
	if(json[0] == true) {
		var page = document.location.href;
		document.location.replace(page.replace("?logout", "").replace("#", ""));
	} else {
		$form_b.find("#loader_ajax").remove();
		$form_b.validationEngine('showPrompt', json[1], 'error', "center", false)
	}
	return true;
}
function ZipFill(json) {
	$("input[name='cityname']").val(json[2]);	
}
function navbar_padding() {
	var h = $("nav").height() + parseInt($("nav").css("margin-bottom").replace("px", ""));
	$("body").css("padding-top", h+"px");
}
