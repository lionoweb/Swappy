function load_ajax_d(e){return $form_b=$(e),$form_b.find("#loader_ajax").length<1&&$form_b.append('<div id="loader_ajax"><p><img src="img/icon/loading.gif" alt="" > envoie en cours...</p></div>'),!0}function send_mail_contact(e,a,t){return 1==t[0]?($form_b=$(a),$form_b.html('<div id="message_ajax"><p>Votre message a bien été envoyé à l’équipe de Swappy.</p></div>'),$(document).scrollTop(0),!0):!1}function add_user_function(e,a,t){return $form_b=$(a),1==t[0]?($form_b.html('<div id="message_ajax"><p>Bienvenue au sein de la communauté Swappy, vous êtes enfin inscrit ! Vous allez recevoir un mail afin de confirmer votre inscription.<br><br>Vérifiez dans vos indésirables en cas de non réception.</p></div>'),$(document).scrollTop(0),!0):($form_b.find("#loader_ajax").remove(),!1)}function vote_function(e,a,t){return $form_b=$(a),1==t[0]?($form_b.parent().html('<div id="message_ajax"><p>Merci. Nous vous remercions d\'avoir pris le soin de noter ce service</p></div>'),$(document).scrollTop(0),!0):($form_b.find("#loader_ajax").remove(),!1)}function edit_user_function(e,a,t){return $form_b=$(a),1==t[0]?($form_b.find("#loader_ajax").remove(),$("#avatar_u").attr("src",t[1]),$(".dropdown-toggle > img:first").attr("src",t[1]),$ff=$form_b.find("input[type='submit']"),$ff.validationEngine("showPrompt","Modifications éfféctuées !","pass","topLeft",!1,!0),!0):($form_b.find("#loader_ajax").remove(),"undefined"!=typeof t[2]?($ff=$form_b.find("#"+t[2]),$ff.validationEngine("showPrompt",t[1],"error","topLeft",!0,!0)):$form_b.validationEngine("showPrompt",t[1],"error","topLeft",!1,!0),!1)}function remind_change_function(e,a,t){return $form_b=$(a),1==t[0]?($form_b.html('<div id="message_ajax"><p>Votre mot de passe a été changé !<br><br><i>Vous pouvez dès à présent vous connecter avec votre nouveau mot de passe.</i></p></div>'),!0):($form_b.find("#loader_ajax").remove(),$form_b.validationEngine("showPrompt",t[1],"error","topLeft",!1,!0),!1)}function add_service_function(e,a,t){return $form_b=$(a),1==t[0]?($form_b.html($('input[name="ID_EDIT"]').length<1?'<div id="message_ajax"><p>Votre service a bien été ajouté. Vous pouvez le retrouver dans votre profil rubrique “Mes propositions”.</p></div>':'<div id="message_ajax"><p>Votre service a bien été modifié. Vous pouvez retrouver les modifications dans votre profil rubrique “Mes propositions”.</p></div>'),$(document).scrollTop(0),!0):($form_b.find("#loader_ajax").remove(),$form_b.find('input[type="submit"]').validationEngine("showPrompt",t[1],"error","topLeft",!1,!0),$(document).scrollTop(0),!1)}function send_popup_message(e,a,t){return $form_b=$(a),1==t[0]?($form_b.find("#loader_ajax").remove(),$form_b.find(".result_form").html("<b>Message envoyé !</b>").show(),$form_b.find(".inner_form").hide(),$form_b.trigger("reset"),setTimeout(function(){$("#modal_chat").modal("hide")},15e3),!0):($form_b.find("#loader_ajax").remove(),$form_b.validationEngine("showPrompt",t[1],"error","topLeft",!1,!0),!1)}function send_report_message(e,a,t){return $form_b=$(a),1==t[0]?($form_b.find("#loader_ajax").remove(),$form_b.find(".result_form").html("<b>Signalisation envoyé !</b>").show(),$form_b.find(".inner_form").hide(),$form_b.trigger("reset"),setTimeout(function(){$("#modal_report").modal("hide")},15e3),!0):($form_b.find("#loader_ajax").remove(),$form_b.validationEngine("showPrompt",t[1],"error","topLeft",!1,!0),!1)}function login_user_function(e,a,t){$form_b=$(a);var o="";return 1==t[0]?(o=$form_b.find("input[name='to_url']").length>0&&""!=$form_b.find("input[name='to_url']").val()?$form_b.find("input[name='to_url']").val():document.location.href,o=o.replace("?logout","").replace("&logout","").replace("&&",""),o.match(/\#chat/gi)||(o=o.replace("#","")),document.location.replace(o),!0):($form_b.find("#loader_ajax").remove(),$form_b.validationEngine("showPrompt",t[1],"error","topLeft",!1,!0),!1)}function remind_user_function(e,a,t){return $form_b=$(a),1==t[0]?($form_b.find("#loader_ajax").remove(),$("#modal_alert").remove(),$("body").append('<div id="modal_alert" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-md"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="exampleModalLabel">Mot de passe perdu</h4></div><div class="modal-body">Un mail vous a été envoyé contenant un lien afin de changer le mot de passe de votre compte.<br><br><i>N\'oubliez pas de vérifier dans vos indésirables en cas de non réception</i></div></div></div></div>'),$("#modal_alert").modal("show"),$("#modal_alert").on("hidden.bs.modal",function(){$(this).remove()}),!0):($form_b.find("#loader_ajax").remove(),$form_b.validationEngine("showPrompt",t[1],"error","topLeft",!1,!0),!1)}function ZipFill(e){var a;if(isArray(e[2]))for($("input[name='cityname']").length>0&&(a=$("input[name='cityname']").parent(),$("input[name='cityname']").remove(),a.append("<select name='cityname' class='form-control auto_width'></select>")),$("select[name='cityname']").html(""),i=0;i<e[2].length;i++)$("select[name='cityname']").append('<option value="'+e[2][i]+'">'+e[2][i]+"</option>");else $("select[name='cityname']").length>0&&(a=$("select[name='cityname']").parent(),$("select[name='cityname']").remove(),a.append("<input class='liketext' type='text' readonly name='cityname'>")),$("input[name='cityname']").val(e[2])}function navbar_padding(){if($(document).width()>767){var e=$("#navbar").width(),a=$(".navbar-header").width(),t=$(".navbar-form").width()+$(".nav.navbar-nav:not(.navbar-right)").width()+$(".nav.navbar-nav.navbar-right").width();t+2>=e-a-15?$(".search_navbar:not(.moved-group)").length>0&&($(".search_navbar:not(.moved-group)").addClass("moved-group"),$("nav").height("100px")):$(".search_navbar.moved-group").length>0&&($(".search_navbar.moved-group").removeClass("moved-group"),$("nav").height(""))}else{var o=$(".navbar-header").height()+$(".search_navbar").height()+20+16;$(".navbar-collapse.collapse.in").css("max-height",$(window).height()-o+"px"),$(".search_navbar.moved-group").length>0&&($(".search_navbar.moved-group").removeClass("moved-group"),$("nav").css("height",""))}var n=$("nav").height()+parseInt($("nav").css("margin-bottom").replace("px",""));$("body").css("padding-top",n+"px");var i=$(window).height(),s=$(document).height()-($("nav").height()-$("footer").height()-40);i>s&&-131>i-s?$("#wrap").css("height","100%"):$("#wrap").removeAttr("style")}function isArray(e){return-1!=e.constructor.toString().indexOf("Array")}function modal_prevent(){$(".login_form").length>0?($('nav li a[href$="propose.php"], .interesse .popup_message, .talk_button, .popup_report, .report-button').off(),$('nav li a[href$="propose.php"], .interesse .popup_message, .talk-button, .popup_report, .report-button').on("click",function(e){var a="";if($(this).attr("href")&&""!=$(this).attr("href"))a=$(this).attr("href");else if($(this).hasClass("popup_message")){var t=document.location.toString();t=t.replace(/\#(.*?)/gi,""),a=t+"&#chat"}e.preventDefault(),$("#modal_alert").remove(),$("body").append('<div id="modal_alert" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-md"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="exampleModalLabel">Se connecter pour y accéder</h4></div><div class="modal-body">Désolé, mais la page à laquelle vous souhaitiez accéder n\'est pas accessible en tant que visiteur.<br><br>Veuillez vous inscrire/connecter pour l\'afficher.<div id="clone_login"></div></div></div></div></div>'),$("#modal_alert").modal("show"),$("#login_section").clone(!0).appendTo("#clone_login"),$("#remind_section").clone(!0).appendTo("#clone_login"),$("#clone_login").append('<a href="inscription.php" class="hidden_ notsigned">Pas encore inscrit ?</a><div class="clear"></div>'),$("#clone_login .login_form").append("<input type='hidden' name='to_url' value='"+a+"'>"),$("#modal_alert").on("hidden.bs.modal",function(){$(this).remove()})})):($('nav li a[href$="propose.php"]').off(),$(".interesse .popup_message").off(),$(".interesse .popup_message").on("click",function(e){e.preventDefault(),$("#modal_chat").modal("show"),$("#modal_chat").on("hidden.bs.modal",function(){$("#modal_chat .result_form").hide(),$("#modal_chat .inner_form").show()})}))}function message_send_function(e,a,t){return 1==t[0]?($("#loader_ajax").remove(),$form_b=$(a),$form_b.find("textarea[name='message_r']").val(""),load_content($form_b.find("input[name='ID_Converse']").val(),$(".mess_t.active").html(),!1,!0)&&load_list(),!0):($("#loader_ajax").remove(),!1)}function date_send_function(e,a,t){return $form_b=$(a),1==t[0]?($("#loader_ajax").remove(),$("#m_date_modal").validationEngine("detach"),$("#modal_date").modal("hide"),load_content($("input[name='ID_Converse']").val(),$(".mess_t.active").html(),!1)&&load_list("load_with_reset_button"),!0):($("#loader_ajax").remove(),$form_b.validationEngine("showPrompt",t[1],"error","topLeft",!1,!0),!1)}function load_list(e){if("undefined"==typeof e)var e="";var a=!1;"load_with_reset_button"==e&&(e="",a=!0),"object"==typeof ajax_call&&ajax_call.abort(),""==e&&$.ajaxSetup({async:!1}),ajax_call=$.getJSON("inc/send_mess.php?list_message=&search="+e,function(e){var a="",t="",o='<span class="mess_count"></span>';$(".mess_t").remove(),$.each(e,function(e,n){t="",o="",a=n.For>0?'<a title="Voir l\'annonce" target="_blank" href="annonce-'+n.For+'.php">'+n.Title+"</a>":"<i>discuter</i>",n.Count>0&&(o='<span class="mess_count red">'+n.Count+"</span>"),n.ID==last_m&&(t=" active"),$("#list_m").append('<div data-b="'+n.Button+'" data-state="'+n.Status+'" data-id="'+n.ID+'" class="mess_t'+t+'"><a href="profil-'+n.UserID+'.php">'+n.Name+'</a><br><span class="m_for">Pour : '+a+"</span>"+o+'<a title="Supprimer cette conversation" class="delete_m">X</a></div>')}),delete_click(),event_click(),1==fst_l&&(load_content(),fst_l=0)}),$.ajaxSetup({async:!0}),1==a&&button_f(0,!0)}function load_content(e,a,t,o){if("undefined"==typeof t)var t=!1;if("undefined"==typeof o)var o=!1;if("undefined"==typeof e||""==e){var n=window.location.hash;if(n.match(/\#select\-/)){$("#content_m").addClass("montre"),$("#list_m").addClass("cache");var e=n.replace(/\#select\-/,"");if($(".mess_t[data-id='"+e+"']").length>0){last_m=e;var a=$(".mess_t[data-id='"+e+"']").html();$(".mess_t[data-id='"+e+"']").addClass("active")}else{var e=$(".mess_t:first").attr("data-ID");last_m=e;var a=$(".mess_t:first").html();$(".mess_t:first").addClass("active")}}else{var e=$(".mess_t:first").attr("data-ID");last_m=e;var a=$(".mess_t:first").html();$(".mess_t:first").addClass("active")}}else $("#list_m:not(.cache)").addClass("cache"),$("#content_m:not(.montre)").addClass("montre"),last_m=e;if($(".mess_t").length<1){$(".inner_m").html("<center><br>Vous n'avez aucun message</center>"),$(".header_m span").html(""),$(".form_m, .header_m").css("display","none");var e=0;mess_count(0,e)}else{var i=$(".mess_t[data-id='"+e+"']").attr("data-state"),s=$(".mess_t[data-id='"+e+"']").attr("data-b");$(".form_m, .header_m").css("display",""),0==o&&$(".inner_m").html('<center><img src="css/images/loading.gif" alt=""> Chargement...</center>'),$.ajaxSetup({async:!1}),$.getJSON("inc/send_mess.php?get_message="+e,function(o){$(".inner_m").html(""),$(".header_m span").html(a),mess_count(o.count,e),$.each(o,function(e,a){if("undefined"!=typeof a.Message){var t="other";"ME"==a.Author&&(t="me"),"BOT"==a.Author&&(t="bot"),$(".inner_m").append('<div class="'+t+' msg">'+a.Message+'<span class="time">'+a.TimeText+"</span></div>")}}),0==t&&$(".inner_m").scrollTop($(".inner_m")[0].scrollHeight)}),$.ajaxSetup({async:!0}),$(".bot:last .valid-this-date").each(function(){$(this).addClass("actived"),$(this).on("click",function(){$(this).prepend('<img class="loader_link_m" alt="" src="css/images/loading.gif">'),$.ajax({url:"inc/send_mess.php",data:"valid="+$(this).attr("data-id")+"&cc="+$("input[name='ID_Converse']").val(),method:"GET",success:function(e){$(".loader_link_m").remove(),"true"==e&&load_content($("input[name='ID_Converse']").val(),$(".mess_t.active").html(),!1)&&load_list("load_with_reset_button")}})})}),$(".bot:last .refuse-this-date").each(function(){$(this).addClass("actived"),$(this).on("click",function(){$(this).prepend('<img alt="" class="loader_link_m" src="css/images/loading.gif">'),$.ajax({url:"inc/send_mess.php",data:"refuse="+$(this).attr("data-id")+"&cc="+$("input[name='ID_Converse']").val(),method:"GET",success:function(e){$(".loader_link_m").remove(),"true"==e&&load_content($("input[name='ID_Converse']").val(),$(".mess_t.active").html(),!1)&&load_list("load_with_reset_button")}})})})}return $(".form_m button").off("click"),1==s?button_f(i,!1,e):($(".form_m button").html(""),$(".form_m button").attr("disabled"),$(".form_m button").css("display","none")),$("input[name='ID_Converse']").val(e),window.location.hash="select-"+e,!0}function mess_count(e,a){"false"!=e&&($('div[data-id="'+a+'"] .mess_count').removeClass("red"),$("input[name='message_r']").val(""),0==e?($(".navbar-header .mess_count").removeClass("red").html("0"),$(".nav-h .mess_count").removeClass("red").html("0"),$(".dropdown-toggle .mess_count").remove()):($(".nav-h .mess_count:not(.red)").addClass("red"),$(".navbar-header .mess_count:not(.red)").addClass("red"),$(".nav-h .mess_count").html(e),$(".navbar-header .mess_count").html(e),$(".dropdown-toggle .mess_count").length<1?$("<span class=''>"+e+"</span>").insertBefore(".dropdown-toggle .caret"):$(".dropdown-toggle .mess_count").html(e)))}function event_click(){$(".mess_t").off("click"),$(".mess_t a").on("click",function(e){e.preventDefault()}),$(".mess_t").on("click",function(e){"delete_m"==e.target.className?e.preventDefault():($(".mess_t.active").removeClass("active"),$(this).addClass("active"),load_content($(this).attr("data-id"),$(this).html()),load_list("load_with_reset_button"))})}function make_date(){var e=$("input[name='ID_Converse']").val(),a=$(".mess_t[data-id='"+e+"']");$("#m_date_modal").validationEngine("detach"),"1"!=a.attr("data-b")||"0"!=a.attr("data-state")&&"1"!=a.attr("data-state")?$("#modal_date").modal("hide"):($("#modal_date").remove(),$.ajax({url:"inc/send_mess.php",type:"GET",dataType:"html",data:"make_date="+e,success:function(a){$("body").append('<div id="modal_date" data-id="'+e+'" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-md"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+a+"</div></div></div></div>"),$("#modal_date").modal("show"),$("#modal_date").on("hidden.bs.modal",function(){$("#m_date_modal").validationEngine("detach"),$(this).remove()});var t=$("#date").val();$("#datepicker").datepicker({altField:"#date",minDate:0,maxDate:"+1Y"}),$("#datepicker").datepicker("setDate",t),$("#hour").datetimepicker({datepicker:!1,format:"H:i",lang:"fr",value:$("#hour").val()}),$("#m_date_modal").validationEngine({ajaxFormValidation:!0,ajaxFormValidationMethod:"post",onAjaxFormComplete:date_send_function,onBeforeAjaxFormValidation:load_ajax_d,showOneMessage:!0,promptPosition:"topLeft"})}}))}function delete_click(){$(".delete_m").on("click",function(e){var a=$(this).parent().attr("data-id");e.preventDefault(),$("#modal_delete").remove(),$("body").append('<div id="modal_delete" data-id="'+a+'" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-md"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="exampleModalLabel">Supprimer cette conversation ?</h4></div><div class="modal-body"><center><u>Êtes-vous sûr de vouloir effacer la conversation :</u> <br><br>'+$(this).parent().html()+'<br><button class="btn btn-success valid_modal">Oui</button> <button class="cancel_modal btn btn-danger">Non</button></center></div></div></div></div>'),$("#modal_delete .valid_modal").on("click",function(e){e.preventDefault();var a=$(this).parents("#modal_delete").attr("data-id");$.getJSON("inc/send_mess.php?delete="+a,function(e){if("true"==e)$("#modal_delete").modal("hide"),fst_l=1,load_list();else{var a=JSON.parse(JSON.stringify(e));$("#modal_delete").modal("hide"),$("#message_send").validationEngine("showPrompt",a[1],"error","topLeft",!1,!0)}})}),$("#modal_delete .cancel_modal").on("click",function(e){e.preventDefault(),$("#modal_delete").modal("hide")}),$("#modal_delete").modal("show"),$("#modal_delete").on("hidden.bs.modal",function(){$(this).remove()})})}function button_f(e,a,t){if($(".form_m button").off("click"),1==a&&(e=$(".mess_t.active").attr("data-state")),"undefined"==typeof t)var t=$(".mess_t.active").attr("data-id");var o=$(".mess_t[data-id='"+t+"']").attr("data-b");"1"==o&&("0"==e&&($(".form_m button").html("FIXER UN RENDEZ-VOUS"),$(".form_m button").css("display",""),$(".form_m button").on("click",function(e){e.preventDefault(),make_date($(this))})),"1"==e&&($(".form_m button").html("CHANGER LE RENDEZ-VOUS"),$(".form_m button").css("display",""),$(".form_m button").on("click",function(e){e.preventDefault(),make_date($(this))})),"2"==e&&($(".form_m button").html(""),$(".form_m button").attr("disabled"),$(".form_m button").css("display","none")),"3"==e&&($(".form_m button").html(""),$(".form_m button").attr("disabled"),$(".form_m button").css("display","none")))}function blink_p(){clearTimeout(twinkle_);var e=document.title;document.title=e==title_page_?"Vous avez "+c_message+" nouveau message(s) !":title_page_,twinkle_=setTimeout(function(){blink_p()},1200)}function update_mess_count(){clearTimeout(time_out_m),$(".login_form").length<1&&$.getJSON("inc/add_user.php?count_mess",function(e){if(parseInt(e)<1)$(".nav-h .mess_count").removeClass("red"),$(".navbar-header .mess_count").removeClass("red"),$(".dropdown-toggle > .mess_count").remove(),clearTimeout(twinkle_),document.title=title_page_,c_message=0;else{var a=parseInt($(".nav-h .mess_count").html());a!=parseInt(e)&&(c_message=parseInt(e),clearTimeout(twinkle_),0==focuset&&blink_p()),$(".nav-h .mess_count").addClass("red"),$(".navbar-header .mess_count").addClass("red"),$("nav .dropdown-toggle > .mess_count").length<1?$("<span class='mess_count red'>"+e+"</span>").insertBefore("nav .dropdown-toggle > .caret"):$("nav .dropdown-toggle > .mess_count").html(e)}time_out_m=setTimeout(function(){update_mess_count()},4e4),$(".nav-h .mess_count").html(e),$(".navbar-header .mess_count").html(e),url_page.match(/messagerie\.php/)&&load_content($("input[name='ID_Converse']").val(),$(".mess_t.active").html(),!1,!0)&&load_list("load_with_reset_button")})}function i_avatar(){$(".uploader-button").hide(),$(".uploader-file-input").hide(),$(".uploader-progress ").show(),$(".uploader-side").css("opacity","1"),$("#avatar_u").css("opacity","0.7"),$(".progress-bar").attr("aria-valuenow","0"),$(".progress-bar").css("width","0%"),$(".progress-bar").html("0%")}function wait_change_a(){$(".progress-bar").attr("aria-valuenow","100"),$(".progress-bar").css("width","100%"),$(".progress-bar").html("100%")}function c_avatar(){$(".uploader-button").show(),$(".uploader-file-input").show(),$(".uploader-progress ").hide(),$(".uploader-side").css("opacity",""),$("#avatar_u").css("opacity",""),$(".progress-bar").attr("aria-valuenow","0"),$(".progress-bar").css("width","0%"),$(".progress-bar").html("0%")}function open_all_coms(){var e="";url_page.match(/annonce(.*?)\.php/gi)&&(e=url_page.replace(/(.*?)annonce\-(.*?)\.php([a-z]|\?|\&|\#|)/gi,"$2")),url_page.match(/annonce\.php/)&&(e=url_page.replace(/(.*?)annonce\.php(.*?)id=(.*?)([a-z]|\?|\&|\#|)/gi,"$3")),$("#modal_coms").remove(),$.ajax({url:"inc/add_services.php",type:"GET",dataType:"html",data:"list_coms="+e,success:function(e){$("body").append('<div id="modal_coms" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-md"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="exampleModalLabel">Tous les commentaires</h4></div><div class="modal-body notes">'+e+'<div class="clear"></div></div></div></div></div>'),$("#modal_coms").modal("show"),$("#modal_coms").on("hidden.bs.modal",function(){$(this).remove()})}})}function elipse_fix(){var e=$(".brand-title").width()-126,a=$(".brand-title").html(),t=a.length,o=3*t;o>e&&$(".brand-title").addClass("text-left")}var wait,requestajax=null,fst_l=1,last_m=!1,ajax_c=!1,time_out_m,url_page=""+window.location.href,title_page_="",twinkle_,focuset=!0,c_message=0;$(document).ready(function(){if($.ajaxSetup({cache:!1}),elipse_fix(),modal_prevent(),navbar_padding(),$("#searchbar").autocomplete({delay:280,source:function(e,a){$.ajax({url:"inc/search.php",dataType:"jsonp",data:{searchquery:e.term},success:function(e){a(e)}})},minLength:3,select:function(e,a){a.item.val&&document.location.replace("services.php?searchbar=&type="+a.item.val),a.item.userID&&document.location.replace("profil-"+a.item.userID+".php")},open:function(){$(this).removeClass("ui-corner-all").addClass("ui-corner-top")},close:function(){$(this).removeClass("ui-corner-top").addClass("ui-corner-all")}}),$(".login_form").length<1){c_message=parseInt($(".nav-h .mess_count").html());var e="";c_message>0&&(e=" red"),time_out_m=setTimeout(function(){update_mess_count()},49999),$(".navbar-header").append('<span class="mess_count'+e+'">'+c_message+"</span>")}else c_message=0,$(".login_form").validationEngine({ajaxFormValidation:!0,ajaxFormValidationMethod:"post",onAjaxFormComplete:login_user_function,onBeforeAjaxFormValidation:load_ajax_d,showOneMessage:!0,promptPosition:"topLeft"}),$(".remind_form").validationEngine({ajaxFormValidation:!0,ajaxFormValidationMethod:"post",onAjaxFormComplete:remind_user_function,onBeforeAjaxFormValidation:load_ajax_d,showOneMessage:!0,promptPosition:"topLeft"}),$(".remind_link").bind("click",function(){"none"==$(this).parents("#clone_login, .login-menu").find("#remind_section").css("display")?($(this).parents("#clone_login, .login-menu").find("#remind_section").css("display","block"),$(this).parents("#clone_login, .login-menu").find("#login_section").css("display","none")):($(this).parents("#clone_login, .login-menu").find("#remind_section").css("display","none"),$(this).parents("#clone_login, .login-menu").find("#login_section").css("display","block"))});$(document).on("focusout",function(){focuset=!1}),$(document).on("focusin",function(){focuset=!0,clearTimeout(twinkle_),document.title=title_page_}),title_page_=document.title,$(window).on("orientationchange",function(){navbar_padding()}),$(window).on("resize",function(){navbar_padding()}),$(["img/icon/loading.gif"]).preload(),url_page.match(/services\.php/gi)&&$("#zipbar").autocomplete({delay:280,source:function(e,a){$.ajax({url:"inc/search.php",dataType:"jsonp",data:{zipquery:e.term,extra:$("#zipbar").attr("data-s")},success:function(e){a(e)}})},minLength:3,select:function(){},open:function(){$(this).removeClass("ui-corner-all").addClass("ui-corner-top")},close:function(){$(this).removeClass("ui-corner-top").addClass("ui-corner-all")}}),url_page.match(/propose\.php/gi)&&($(".timepicker").length>0&&$(".timepicker").datetimepicker({datepicker:!1,format:"H:i",lang:"fr",value:$(this).val()}),$(".remove_dispo").on("click",function(){var e=$(this).attr("data-IDF");$(".dispo_field[data-IDF='"+e+"']").remove()}),$(".add_dispo").on("click",function(e){e.preventDefault();var a=$(".dispo_field:last").attr("data-IDF");a++;var t='<span data-IDF="'+a+'" class="dispo_field"><select id="dispoday['+a+']" class="form-control days" name="dispoday['+a+']"><option value="all">Tous les jours</option><option value="weekend">Le week-end</option><option value="lun">Lundi</option><option value="mar">Mardi</option><option value="mer">Mercredi</option><option value="jeu">Jeudi</option><option value="ven">Vendredi</option><option value="sam">Samedi</option><option value="dim">Dimanche</option></select> <span class="toline-xs">entre <input autocomplete="off" size="5" maxlength="5" name="dispostart['+a+']" value="19:00" class="validate[required] time timepicker form-control" id="dispostart['+a+']" type="text"> et <input autocomplete="off" maxlength="5" name="dispoend['+a+']" name="dispoend['+a+']" class="time form-control validate[required,timeCheck[dispostart{'+a+'}]] timepicker" value="21:00" size="5" type="text"> <a class="remove_dispo" data-idf="'+a+'">Effacer</a></span></span>';$(t).insertAfter(".dispo_field:last"),$(".timepicker").datetimepicker({datepicker:!1,format:"H:i",lang:"fr",value:$(this).val()}),$(".remove_dispo").on("click",function(){var e=$(this).attr("data-IDF");$(".dispo_field[data-IDF='"+e+"']").remove()})}),$("#spec_propose").validationEngine({ajaxFormValidation:!0,ajaxFormValidationMethod:"post",onAjaxFormComplete:add_service_function,onBeforeAjaxFormValidation:load_ajax_d,showOneMessage:!0,promptPosition:"topLeft"})),(url_page.match(/annonce\.php/gi)||url_page.match(/annonce(.*?)\.php/gi))&&$("#note_form").validationEngine({ajaxFormValidation:!0,ajaxFormValidationMethod:"post",onAjaxFormComplete:vote_function,onBeforeAjaxFormValidation:load_ajax_d,showOneMessage:!0,promptPosition:"topLeft"}),(url_page.match(/profil\.php/gi)||url_page.match(/profil(.*?)\.php/gi))&&($("#edit_user").validationEngine({ajaxFormValidation:!0,ajaxFormValidationMethod:"post",onAjaxFormComplete:edit_user_function,onBeforeAjaxFormValidation:load_ajax_d,showOneMessage:!0,promptPosition:"topLeft"}),$(".tags-input").length>0&&$(".tags-input").tagsinput({confirmKeys:[13,44,32,188]}),$(".badge_").on("click",function(){$('.badge_:not([data-id="'+$(this).attr("data-id")+'"])').removeClass("glow"),$('.listing-s:not([data-s="'+$(this).attr("data-id")+'"])').hide(),$('.listing-s[data-s="'+$(this).attr("data-id")+'"]').toggle(),$(this).toggleClass("glow")}),$("#upload_b").on("change",function(){var e=new FormData,a=this.files[0],t=5259999;a.size>t?$("#upload_ba").validationEngine("showPrompt","Votre fichier ne doit pas dépasser les 5Mo","error","topLeft",!1,!0):(e.append("file-avatar",a),$.ajax({url:"inc/add_user.php",type:"POST",data:e,processData:!1,contentType:!1,success:function(e){wait_change_a();var a=JSON.parse(e);1==a[0]?($("#avatar_u").attr("src",""),$("#avatar_u").attr("src",a[1]),$("#avatar_u").on("load",function(){c_avatar()}),$(".dropdown-toggle > img:first-child").attr("src",a[1])):$("#upload_ba").validationEngine("showPrompt",a[1],"error","topLeft",!1,!0)},xhr:function(){var e=$.ajaxSettings.xhr();return e.upload.onloadstart=function(){i_avatar()},e.upload.onprogress=function(e){var a=Math.round(e.loaded/e.total*100);$(".progress-bar").attr("aria-valuenow",a),$(".progress-bar").css("width",a+"%"),$(".progress-bar").html(a+"%")},e.upload.onload=function(){wait_change_a()},e.upload.onabort=function(){c_avatar()},e.upload.onerror=function(){c_avatar()},e}}))})),url_page.match(/proposition\.php/gi)&&$(".delete_serv").on("click",function(e){e.preventDefault();var a=$(this).attr("data-id");$("#modal_delete").remove(),$("body").append('<div id="modal_delete" data-id="'+a+'" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"><div class="modal-dialog modal-md"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="exampleModalLabel">Supprimer cette conversation ?</h4></div><div class="modal-body"><center>Êtes-vous sûr de vouloir effacer le service : <b>'+$(this).parents("tr").find(".serv_title").html()+'</b><br><button class="btn btn-success valid_modal">Oui</button> <button class="cancel_modal btn btn-danger">Non</button></center></div></div></div></div>'),$("#modal_delete .valid_modal").on("click",function(e){e.preventDefault(),$.getJSON("inc/add_services.php?delete="+a,function(e){"true"==e?($("#modal_delete").modal("hide"),$('tr[data-ids="'+a+'"]').remove(),$("tr.bloc_services").length<1&&$(".list_serv tbody").append('<tr class="bloc_services"><td colspan="4"><center>Vous n\'avez pas de services</center></td></tr>')):$("#modal_delete").modal("hide")})}),$("#modal_delete .cancel_modal").on("click",function(e){e.preventDefault(),$("#modal_delete").modal("hide")}),$("#modal_delete").modal("show"),$("#modal_delete").on("hidden.bs.modal",function(){$(this).remove()})}),url_page.match(/rendez\-vous\.php/gi)&&$("#my-calendar").zabuto_calendar({ajax:{url:"inc/add_user.php?json_cal",modal:!0}}),url_page.match(/messagerie\.php/gi)&&(load_list(),$(["css/images/loading.gif"]).preload(),window.location.hash.match(/\#chat/gi)&&(window.location.hash="",$("#modal_chat").modal("show")),$("#message_send").validationEngine({ajaxFormValidation:!0,ajaxFormValidationMethod:"post",onBeforeAjaxFormValidation:function(){$("#message_send textarea").val("")},onAjaxFormComplete:message_send_function,showOneMessage:!0,promptPosition:"topLeft"}),$(".return_list").on("click",function(e){e.preventDefault(),$("#list_m.cache").removeClass("cache"),$("#content_m.montre").removeClass("montre"),window.location.hash=""}),$("#list_m input").on("keyup",function(){var e=$(this).val();e.length>=3?($(this).addClass("onsearch"),load_list(e)):""==e?($(this).removeClass("onsearch"),load_list("")):$(this).hasClass("onsearch")&&($(this).removeClass("onsearch"),load_list(""))})),url_page.match(/contact\.php/gi)&&$("#spec_contact").validationEngine({ajaxFormValidation:!0,ajaxFormValidationMethod:"post",onAjaxFormComplete:send_mail_contact,onBeforeAjaxFormValidation:load_ajax_d,showOneMessage:!0,promptPosition:"topLeft"}),url_page.match(/inscription\.php/gi)&&($("#user_remind").validationEngine({ajaxFormValidation:!0,ajaxFormValidationMethod:"post",onAjaxFormComplete:remind_change_function,onBeforeAjaxFormValidation:load_ajax_d,showOneMessage:!0,promptPosition:"topLeft"}),$("#user_add").validationEngine({ajaxFormValidation:!0,ajaxFormValidationMethod:"post",onAjaxFormComplete:add_user_function,onBeforeAjaxFormValidation:load_ajax_d,showOneMessage:!0,promptPosition:"topLeft"})),(url_page.match(/cgu\.php/gi)||url_page.match(/ccm\.php/gi)||url_page.match(/apropos\.php/gi)||url_page.match(/contact\.php/gi))&&$(".link_mail").on("click",function(e){e.preventDefault(),document.location.replace("mailto:"+$(this).attr("data-hash"))}),(url_page.match(/profil\.php/gi)||url_page.match(/annonce\.php/gi)||url_page.match(/annonce(.*?)\.php/gi)||url_page.match(/profil(.*?)\.php/gi))&&($(".open-all-com").on("click",function(e){e.preventDefault(),open_all_coms()}),$("#modal_chat #send_message").validationEngine({ajaxFormValidation:!0,ajaxFormValidationMethod:"post",onAjaxFormComplete:send_popup_message,onBeforeAjaxFormValidation:load_ajax_d,showOneMessage:!0,promptPosition:"topLeft"}),$(".popup_message, .talk-button").on("click",function(e){e.preventDefault(),$("#modal_chat").modal("show"),$("#modal_chat").on("hidden.bs.modal",function(){$("#modal_chat .result_form").hide(),$("#modal_chat .inner_form").show()})}),$("#modal_report #send_report").validationEngine({ajaxFormValidation:!0,ajaxFormValidationMethod:"post",onAjaxFormComplete:send_report_message,onBeforeAjaxFormValidation:load_ajax_d,showOneMessage:!0,promptPosition:"topLeft"}),$(".popup_report, .report-button").on("click",function(e){e.preventDefault(),$("#modal_report").modal("show"),$("#modal_report").on("hidden.bs.modal",function(){$("#modal_report .result_form").hide(),$("#modal_report .inner_form").show()
})}))}),$.fn.preload=function(){this.each(function(){$("<img/>")[0].src=this})};