<?php
//SERVICES
    class services {
        private $mysql;
        public $ID = "";
        public $title = "";
        public $type = "";
        public $typename = "";
        public $cattype = "";
        public $by = "";
        public $description = "";
        public $image = "";
        public $distance = "";
        public $disponibility = "";
        public $created = "";
        public $city = "";
        public $lat = "";
        public $lon = "";
        public $zip = "";
        public $dispo_ = "";
        public $globalnote = 0;
        public $globalvote = 0;
        function __construct($mysql, $ids="", $user="") {
            $this->mysql = $mysql;
            if(!empty_($ids)) {
                if(isset($_GET['vote'])) {
                    $user->onlyUsers();
                }
                $this->load_service($ids);
            } else if(preg_match("/annonce(.*)\.php/", $_SERVER['PHP_SELF']) && !preg_match("/vote\=/", $_SERVER['QUERY_STRING'])) {
                header("HTTP/1.1 403 Unauthorized" );
                header("Location: services.php");    
            }
        }
        //FAIRE CONTENIR UN SERVICE A L'OBJET
        function load_service($ids) {
            $select = $this->mysql->prepare("SELECT `services`.`ID`, `categories`.`ID` AS `CatType`, `services`.`Title`, `services`.`Type`, `type`.`Name` AS `TypeName`, `services`.`By`, `services`.`Description`, `services`.`Distance`, `services`.`Disponibility`, `services`.`Created`, `services`.`City`, `services`.`Lat`, `services`.`Lon`, `french_city`.`ZipCode`, `french_city`.`Real_Name` AS `CityName` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` WHERE `services`.`ID` = :ID");
            $select->execute(array(":ID" => $ids));
            $data = $select->fetch(PDO::FETCH_OBJ);
            $total = $select->rowCount();
            if($total < 1) {
                if(!preg_match("/inc\//", $_SERVER['PHP_SELF']) && !preg_match("/vote\=/", $_SERVER['QUERY_STRING'])) {
                    header("HTTP/1.0 404 Not Found");
                    header("Location: 404.php");
                }
            } else {
                $this->ID = $data->ID;
                if(empty_($data->Title)) {
                    $this->title = $data->TypeName;
                } else {
                    $this->title = ucfirst(trim($data->Title));
                }
                $this->type = $data->Type;
                $this->typename = $data->TypeName;
                $this->cattype = $data->CatType;
                $this->by = $data->By;
                if(empty_($data->Description)) {
                    $this->description = "L'utilisateur n'a pas fourni de description...";
                } else {
                    $this->description = ucfirst(trim($data->Description));
                }
                $this->image = $data->Image;
                $this->distance = $data->Distance;
                $this->disponibility = $this->dispo_uncrypt_an($data->Disponibility);
                $this->created = $data->Created;
                $this->city = $data->CityName;
                $this->zip = $data->ZipCode;
                $this->lat = $data->Lat;
                $this->lon = $data->Lon;
                $this->dispo_ = $data->Disponibility;
                $vote = $this->getglnote($data->ID);
                $this->globalnote = $vote[0];
                $this->globalvote = $vote[1];
            }
        }
        //CALCUL NOTE
        function getglnote($id) {
            $select = $this->mysql->prepare("SELECT SUM(`Note`) AS `total`, COUNT(*) AS `nb` FROM `notations` WHERE `Service` = '".$id."'");
            $select->execute();
            $data = $select->fetch(PDO::FETCH_OBJ);
            $total = @round($data->total/$data->nb);
            
            return array($total, $data->nb);
        }
        //SUPPRESSION SERVICE
        function delete_serv($id, $user) {
            $array = array(false);
            if($this->own_s($id) != $user) {
                //PAS Proprio
            } else {
                //RDV
                //MESSAGE
                //NOTATION
                //SERVICE
                //REPORT ?
                $ss = $this->mysql->prepare("DELETE FROM `appointment` WHERE `Service` = :id");
                $ss->execute(array(":id" => $id));
                $ss = $this->mysql->prepare("DELETE FROM `conversation_reply` INNER JOIN `conversation` ON `conversation_reply`.`C_ID` = `conversation`.`ID` WHERE `conversation`.`ServiceFor` = :id");
                $ss->execute(array(":id" => $id));
                $ss = $this->mysql->prepare("DELETE FROM `conversation` WHERE `ServiceFor` = :id");
                $ss->execute(array(":id" => $id));
                $ss = $this->mysql->prepare("DELETE FROM `notations` WHERE `Service` = :id");
                $ss->execute(array(":id" => $id));
                $ss = $this->mysql->prepare("DELETE FROM `services` WHERE `ID` = :id");
                $ss->execute(array(":id" => $id));
                $array = array(true);
            }
            return $array;
        }
        //DECRYPTION DU HASH POUR VOTE
        function decrypt_vote_h($hash) {
            $h = base64_decode($hash);
            $c = preg_split("/\/\/\//", $h);    
            $id_a = $c[0];
            $date_a = $c[1];
            return array("ID" => $id_a, "Date" => $date_a);
        }
        //VERIFICATION SI MEMBRE A DEJA VOTE
        function has_voted($id, $owner, $user) {
            $select = $this->mysql->prepare("SELECT COUNT(*) AS `nb` FROM `notations` WHERE `By` = :id AND `Service` = :serv AND `Owner_Service` = :oserv ");
            $select->execute(array(":id" => $user, ":serv" => $id, ":oserv" => $owner));
            $data = $select->fetch(PDO::FETCH_OBJ);
            if($data->nb < 1) {
                return false;
            } else {
                return true;
            }
        }
        //AJOUT NOTE ET COMMENTAIRE
        function add_note($POST, $user, $chat) {
            $arr = array(false);
            $h = $this->decrypt_vote_h($POST['hash']);
            $select = $this->mysql->prepare("SELECT *, COUNT(*) AS `nb` FROM `appointment` WHERE `ID` = :id AND `State` = '5'");
            $select->execute(array(":id" => $h['ID']));
            $data = $select->fetch(PDO::FETCH_OBJ);
            if($data->nb < 1) {
                //PAS DE RDV ENREGISTRE
                $arr = array(false,"Vous n'avez pas eu de rendez-vous pour ce service. Vous ne pouvez donc pas le noter.");
            } else {
                if($data->User != $user->ID) {
                    //PAS LE MEMBRE
                    $arr = array(false, "Désolé, mais vous n'êtes pas autorisé à noter ce service.");
                } else if($data->Date != $h['Date']) {
                    //SECURITE
                    $arr = array(false, "Désolé, mais votre lien est incorrecte.");
                } else if($this->has_voted($data->Service,$data->Owner_Service, $user->ID)) {
                    //DEJA VOTE
                    $arr = array(false, "Vous avez déjà noté pour ce service.");
                } else if(empty_($POST['note'])) {
                    $arr = array(false, "Vous n'avez pas noté");
                } else {
                    //OK
                    $select = $this->mysql->prepare("INSERT INTO `notations` (`ID`, `By`, `Service`, `Owner_Service`, `Note`, `Message`, `Date`) VALUES (NULL, :by, :serv, :owner, :note, :com, :date);");
                    $select->execute(array(":by" => $user->ID, ":serv" => $data->Service, ":owner" => $data->Owner_Service, ":note" => trim($POST['note']), ":com" => trim(strip_tags($POST['com'])), ":date" => date("Y-m-d H:i:s")));
                    $cc = $chat->isset_conversation($data->Owner_Service, $data->Service);
                    if($cc == false) {
                        $cc = $this->make_conversation($user->ID, $data->Service);
                    }
                    $mess = '<b>'.$user->fullname.' a noté votre service :</b><br>Note : '.trim($POST['note']).'/5<br>Commentaire : '.nl2br(trim(strip_tags($POST['com'])));
                    $chat->send_reply($mess, $cc, $data->Owner_Service);
                    $arr = array(true);
                }
            }
            return $arr;
        }
        //RECUPERATION DES COORDONNEE VIA CODE POSTALE
        function get_coord($zip) {
            $array = array("lon" => false, "lat" => false);
            $select = $this->mysql->prepare("SELECT `Lat`, `Lon`, COUNT(*) AS `total` FROM `french_city` WHERE `ZipCode` = :zipcode");
            $select->execute(array(":zipcode" => $zip));
            $data = $select->fetch(PDO::FETCH_OBJ);
            if($data->total > 0) {
                $array['lon'] = $data->Lon;    
                $array['lat'] = $data->Lat;
            }
            return $array;
        }
        //RECUPERATION ID VILLE VIA CODE POSTALE
        function get_cityID($zip) {
            $ID = false;
            $select = $this->mysql->prepare("SELECT `ID`, COUNT(*) AS `total` FROM `french_city` WHERE `ZipCode` = :zipcode");
            $select->execute(array(":zipcode" => $zip));
            $data = $select->fetch(PDO::FETCH_OBJ);
            if($data->total > 0) {
                $ID = $data->ID;    
            }
            return $ID;
        }
        //RECUPERATION DU PROPRIETAIRE DU SERVICE
        function own_s($id) {
            $select = $this->mysql->prepare("SELECT `By` FROM `services` WHERE `ID` = :id");
            $select->execute(array(":id" => $id));
            $data = $select->fetch(PDO::FETCH_OBJ);
            return $data->By;
        }
        function meta() {
            $im = $this->cattype.".jpg";
            $desc = ucfirst($this->description);
            $desc_g = "";
            $title = "Annonce : ".$this->title;
            $key = trim($this->description) == "L'utilisateur n'a pas fourni de description..." ? "" : $this->description.", ".$this->city.", ".$this->zip.", annonce, ".$this->typename.", ".$this->title;
            return array($im, $desc, $desc_g, $title, $key);
        }
        //EDITIONS SERVICES
        function edit_services($POST, $user) {
            $arr = array(true);
            $id_s = trim($POST['ID_EDIT']);
            $prop = $this->own_s($id_s);
            if($prop != $user->ID) {
                $arr =  array(false, "Vous êtes pas le propriétaire du service.");
            } else if(empty_($POST['type'])) {
                $arr = array(false, "Vous n'avez pas renseigné le type de service");
            } else if(empty_($POST['distance'])) {
                $arr = array(false, "Vous n'avez pas renseigné votre distance de déplacement");
            } else if(empty_($POST['ID_EDIT']) || empty_($POST['ID'])) {
                $arr = array(false, "Une erreur d'enregistrement à eu lieu... Le formulaire semble incomplet");
            } else if(empty_($POST['dispoday']) || empty_($POST['dispostart']) || empty_($POST['dispoend'])) {
                $arr = array(false, "Vous n'avez pas renseigné vos disponibilités");
            } else if(empty_($POST['zipcode'])) {
                $arr = array(false, "Vous n'avez pous renseigné le code postal");
            } else {
                //DISPONIBILITE        
                $dispo = $this->dispo_crypt($POST['dispoday'], $POST['dispostart'], $POST['dispoend']);
                $user = new user($this->mysql);
                $ID = $user->ID;
                if($user->zipcode == $POST['zipcode']) {
                    $city = $this->get_cityID($user->zipcode);
                    $lat = $user->lat;
                    $lon = $user->lon;    
                } else {
                    $city = $this->get_cityID($POST['zipcode']);
                    $ar = $this->get_coord($POST['zipcode']);
                    $lat = $ar['lat'];
                    $lon = $ar['lon'];
                }
                $replace = array(":title" => ucfirst(trim($POST['title'])),
                        ":type" => $POST['type'],
                        ":description" => ucfirst(trim($POST['description'])), 
                        ":distance" => $POST['distance'], 
                        ":dispo" => $dispo,
                        ":city" => $city,
                        ":lat" => $lat,
                        ":lon" => $lon,
                        ":ids" => $id_s
                    );
                $select = $this->mysql->prepare("UPDATE `services` SET `Title` = :title, `Type` = :type, `Description` = :description, `Distance` = :distance, `Disponibility` = :dispo, `City` = :city, `Lat` = :lat, `Lon` = :lon WHERE `ID` = :ids");
                $select->execute($replace);
                $arr = array(true);
            }
            return $arr;
        }
        //AJOUT SERVICES
        function add_services($POST, $user) {
            $arr = array(false);
            //DISPONIBILITE        
            $dispo = $this->dispo_crypt($POST['dispoday'], $POST['dispostart'], $POST['dispoend']);
            $user = new user($this->mysql);
            if(empty_($POST['type'])) {
                $arr = array(false, "Vous n'avez pas renseigné le type de service");
            } else if(empty_($POST['distance'])) {
                $arr = array(false, "Vous n'avez pas renseigné votre distance de déplacement");
            } else if(empty_($POST['ID'])) {
                $arr = array(false, "Une erreur d'enregistrement à eu lieu... Le formulaire semble incomplet");
            } else if(empty_($POST['dispoday']) || empty_($POST['dispostart']) || empty_($POST['dispoend'])) {
                $arr = array(false, "Vous n'avez pas renseigné vos disponibilités");
            } else if(empty_($POST['zipcode'])) {
                $arr = array(false, "Vous n'avez pous renseigné le code postal");
            } else {
                $ID = $user->uncrypt_sess($POST['ID']);
                if($user->zipcode == $POST['zipcode']) {
                    $city = $this->get_cityID($user->zipcode);
                    $lat = $user->lat;
                    $lon = $user->lon;    
                } else {
                    $city = $this->get_cityID($POST['zipcode']);
                    $ar = $this->get_coord($POST['zipcode']);
                    $lat = $ar['lat'];
                    $lon = $ar['lon'];
                }
                $replace = array(":title" => ucfirst(trim($POST['title'])),
                        ":type" => $POST['type'], 
                        ":ID" => $ID, 
                        ":description" => ucfirst(trim(strip_tags($POST['description']))), 
                        ":distance" => $POST['distance'], 
                        ":dispo" => $dispo,
                        ":city" => $city,
                        ":lat" => $lat,
                        ":lon" => $lon
                    );
                $select = $this->mysql->prepare("INSERT INTO `services` (`ID`, `Title`, `Type`, `By`, `Description`, `Image`, `Distance`, `Disponibility`, `Created`, `City`, `Lat`, `Lon`) VALUES (NULL, :title, :type, :ID, :description, NULL, :distance, :dispo, CURRENT_TIMESTAMP, :city, :lat, :lon);");
                $select->execute($replace);
                $arr = array(true);
            }
            return $arr;
        }
        //CRYPTER DISPONIBILITE POUR BDD
        function dispo_crypt($day, $start, $end) {
            $txt = "";
            foreach ($day as $name => $val) {
                $txt .= $val."@".$start[$name]."-".$end[$name]."||";
            }
            return substr($txt, 0, strlen($txt)-2);
        }
        //DECRYPTER DISPONIBILITE VENANT DE LA BDD
        function dispo_uncrypt($txt) {
            $out = "";
            $sp = explode("||", $txt);
            $trad = array("lun" => "lundi", "mar" => "mardi", "mer" => "mercredi", "jeu" => "jeudi", "ven" => "vendredi", "sam" => "samedi", "dim" => "dimanche", "all" => "tous les jours", "weekend" => "weekend");
            for($i=0;$i<count($sp);$i++) {
                $d = explode("@", $sp[$i]);
                $h = explode("-", $d[1]);
                $out .= $trad[$d[0]]." de ".$h[0]." à ".$h[1]."<br>";
            }
            return $out;
        }
        //RECUPERATION DU NOM DU TYPE DE SERVICE
        function type_name($id) {
            $select = $this->mysql->prepare("SELECT `Name` FROM `type` WHERE `ID` = :ID");
            $select->execute(array(":ID" => $id));
            $data = $select->fetch(PDO::FETCH_OBJ);
            return $data->Name;
        }
        // ######################################### HTML ############################################ //
		
        //DECRYPTER DISPONIBILITE VENANT DE LA BDD POUR PAGE EDITION SERVICE
        function dispo_uncrypt_edit($txt) {
            $html = '<span data-IDF="{ID}" class="dispo_field">
                        <select id="dispoday[{ID}]" name="dispoday[{ID}]" class="form-control days">';
        $html.= '<option value="all">Tous les jours</option>'.
                           '<option value="weekend">Le week-end</option>'.
                                                '<option value="lun">Lundi</option>'.
                                                '<option value="mar">Mardi</option>'.
                                                '<option value="mer">Mercredi</option>'.
                                                '<option value="jeu">Jeudi</option>'.
                                                '<option value="ven">Vendredi</option>'.
                                                '<option value="sam">Samedi</option>'.
                                                '<option value="dim">Dimanche</option>';
        $html .= '</select>
                        <span class="toline-xs">entre
                        <input size="5" maxlength="5" name="dispostart[{ID}]" value="{START}" class="time form-control validate[required] timepicker" id="dispostart[{ID}]" type="text">
                        et
                        <input maxlength="5" name="dispoend[{ID}]" class="validate[required,timeCheck[dispostart{{ID}}]] form-control timepicker time" value="{END}" size="5" type="text"></span>
                        </span>';
            $out = "";
            $sp = explode("||", $txt);
            $trad = array("lun" => "lundi", "mar" => "mardi", "mer" => "mercredi", "jeu" => "jeudi", "ven" => "vendredi", "sam" => "samedi", "dim" => "dimanche", "all" => "tous les jours", "weekend" => "weekend");
            for($i=0;$i<count($sp);$i++) {
                $d = explode("@", $sp[$i]);
                $h = explode("-", $d[1]);
                $out .= preg_replace('/value\=\"'.$d[0].'\"/', 'value="'.$d[0].'" selected="selected"', preg_replace("/\{END\}/", $h[1], preg_replace("/\{START\}/", $h[0], preg_replace("/\{ID\}/", ($i+1), $html))));
                //$out .= "<span class='disponi'>".ucfirst($trad[$d[0]])." de ".$h[0]." à ".$h[1]."</span><br>";
            }
            return $out;
        }
        //DECRYPTER DISPONIBILITE POUR PROFIL
        function dispo_uncrypt_an($txt) {
            $out = "";
            $sp = explode("||", $txt);
            $trad = array("lun" => "lundi", "mar" => "mardi", "mer" => "mercredi", "jeu" => "jeudi", "ven" => "vendredi", "sam" => "samedi", "dim" => "dimanche", "all" => "tous les jours", "weekend" => "weekend");
            for($i=0;$i<count($sp);$i++) {
                $d = explode("@", $sp[$i]);
                $h = explode("-", $d[1]);
                $out .= "<span class='disponi'>".ucfirst($trad[$d[0]])." de ".$h[0]." à ".$h[1]."</span><br>";
            }
            return $out;
        }
        //LISTING DES CATEGORIES DE SERVICE ET TYPES
        function list_categories($required=false, $selected="") {
            $req = "";
            if($required) {
                $req = 'validate[required] ';
            }
            $html = '<select class="'.$req.'form-control list_type_" id="type" name="type">';
            $html .= '<option value=""></option>';
            $select = $this->mysql->prepare("SELECT * FROM `categories` ORDER BY `Name` ASC");
            $select->execute();
            while($data = $select->fetch(PDO::FETCH_OBJ)) {
                $select_ = $this->mysql->prepare("SELECT `ID`,`Name` FROM `type` WHERE `Categorie` = :ID ORDER BY `Name` ASC");
                $select_->execute(array(":ID" => $data->ID));
                if($data->Name != "Autres") {
                    $html .= '<option disabled="disabled">'.$data->Name.'</option>';
                }
                while($data_ = $select_->fetch(PDO::FETCH_OBJ)) {
                    if($data->Name == "Autres") {
                        $other = $data_->ID;
                    } else {
                        if($data_->Name == "Autres") {
                            $other_ = $data_->ID;
                        } else {
                            $html .= '<option value="'.$data_->ID.'">&emsp;&emsp;'.$data_->Name.'</option>';
                        }
                    }
                }
                if($data->Name != "Autres") {
                    $html .= '<option value="'.$other_.'">&emsp;&emsp;Autres services en '.$data->Name.'</option>';
                }
            } 
            $html .= '<option disabled="disabled">Autres</option><option value="'.$other.'">&emsp;&emsp;Autres...</option>';
            $html .= '</select>    ';
            if($selected != "") {
                $html = preg_replace('/\<option value\=\"'.$selected.'\"/', '<option value="'.$selected.'" selected', $html);
            }
            return $html;
        }
		//AFFICHAGE DU BUTTON PAGE ANNONCE SELON MEMBRE PROPRIETAIRE DU SERVICE OU NON
        function button($services, $user, $me) {
            $html_one = "<br>";
            $html_two = "<br>";
            if(!isset($_GET['vote'])) { 
                if($user!= $me) {
                    $html_one = '<button class="popup_message">Je suis interessé(e)</button>';
                    $html_two = '<button class="popup_report">Signaler ce service</button>';
                } else {
                    $html_one = '<a href="propose.php?edit='.$services.'" class="btn edit_serv_a">Modifier ce service</a>';
                } 
            }
            $html = '<div class="interesse">'.$html_one.'</div><div class="interesse">'.$html_two.'</div>';
            return $html;
           }
		   //AFFICHAGE DESCRIPTION ANNONCE OU FORMULAIRE DE NOTE
        function annonces($user) {
            $swith = false;
            $html = '<p class="col-md-8 col-md-offset-2 description">'.nl2br(ucfirst($this->description)).'</p>';
            if(!isset($_GET['vote'])) { 
                if(isset($_GET[ 'r']) && !empty_($_GET['r'])) { 
                    $r='<a href="services.php?'.base64_decode($_GET['r']). '" class="col-md-3"><img alt="" src="img/annonce/back.png">Retours aux résultats précédents</a>'; 
                } else { 
                    $r='<a href="services.php" class="col-md-3"><img src="img/annonce/back.png">Retour à la page des services</a>'; 
                }
                $html .= $r;
            } else { 
                $vote = $this->page_vote(@$_GET['vote'], $user);
                if(!empty_($vote)) {
                    $switch = true;
                    $html = '<div class="col-md-6 col-md-offset-3 voting">'.$vote.'</div>';
                }
            }
            return $html;
        }
		//REMPLISSAGE DE LA PAGE PROPOSE SI EDIT
        function edit_page($user) {
            $var = (object)array("title", "htitle", "ntitle", "field", "zipcode", "city", "distance", "description", "selected", "button", "dispo");
            if($this->by != $user->ID && isset($_GET['edit'])) {
                header("Location: annonce-".$this->ID.".php");
            } else {
                $var->title = $this->ID ? $this->title : "";
                $var->htitle = $this->ID ? "Modifier : ".$this->title : "Je propose";
                $var->ntitle = $this->ID ? "Modifier une annonce" : "Proposez un service";
                $var->field = $this->ID ? "<input name='ID_EDIT' value='".$this->ID."' type='hidden'>" : "";
                $var->zipcode = $this->ID ? $this->zip : $user->zipcode;
                $var->city = $this->ID ? $this->city : $user->city;
                $var->distance = $this->ID ? $this->distance : "1";
                $var->description = $this->ID ? ($this->description == "L'utilisateur n'a pas fourni de description..." ? "" : $this->description) : "";
                $var->selected = $this->ID ? $this->type : "";
                $var->button = $this->ID ? "Modifier" : "Valider";
                $var->dispo = $this->ID ? $this->dispo_uncrypt_edit($this->dispo_) : '<span data-IDF="1" class="dispo_field">
    <select id="dispoday[1]" name="dispoday[1]" class="form-control days">'.
        '<option value="all">Tous les jours</option>'.
        '<option value="weekend">Le week-end</option>'.
        '<option value="lun">Lundi</option>'.
        '<option value="mar">Mardi</option>'.
        '<option value="mer">Mercredi</option>'.
        '<option value="jeu">Jeudi</option>'.
        '<option value="ven">Vendredi</option>'.
        '<option value="sam">Samedi</option>'.
        '<option value="dim">Dimanche</option>'.
    '</select>
    <span class="toline-xs">entre
        <input size="5" maxlength="5" name="dispostart[1]" value="19:00" class="time form-control validate[required] timepicker" id="dispostart[1]" type="text">
         et
         <input maxlength="5" name="dispoend[1]" class="validate[required,timeCheck[dispostart{1}]] form-control timepicker time" value="21:00" size="5" type="text">
    </span>
</span>';
            }
            return $var;
        }
        //AFFICHAGE DE LA PAGE DE NOTE
        function page_vote($hash="", $user) {
            $html = "";
            if(!empty_($hash)) {
                $h = $this->decrypt_vote_h(trim($hash));
                $select = $this->mysql->prepare("SELECT *, COUNT(*) AS `nb` FROM `appointment` WHERE `ID` = :id AND `State` = '5'");
                $select->execute(array(":id" => $h['ID']));
                $data = $select->fetch(PDO::FETCH_OBJ);
                if(!is_numeric($this->ID)) {
                    //Pas de service detecter
                    $html = "Désolé, mais votre lien est incorrect.";
                } else if($data->nb < 1) {
                    //PAS DE RDV ENREGISTRE
                    $html = "Vous n'avez pas eu de rendez-vous pour ce service...<br>Vous ne pouvez donc pas le noter.";
                } else if($user->ID == $data->Owner_Service) { 
					//PROPRE SERVICE
					$html = "Vous ne pouvez pas noter votre propre service.";
                } else if($data->Service != $this->ID) {
					//Pas la bonne page
                    $html = "Désolé, mais votre lien est incorrect.";
				} else {
                    if($data->User != $user) {
                        //PAS LE MEMBRE
                        $html = "Désolé, mais vous n'êtes pas autorisé à noter ce service.";
                    } else if($data->Date != $h['Date']) {
                        //SECURITE
                        $html = "Désolé, mais votre lien est incorrecte.";
                    } else if($this->has_voted($data->Service,$data->Owner_Service, $user)) {
                        //DEJA VOTE
                        $html = "Vous avez déjà noté ce service.";
                    } else {
                        //OK
                        $html = 'Veuillez attribuer une note à ce service ainsi qu\'un commentaire (optionnel) :<br><br><form  id="note_form" action="inc/services_.php" method="post"><label class="label-control" for="rate">Votre note :</label><input id="input-5" name="note" class="rating validate[required]" data-size="xs" data-show-clear="false" data-step="1" value="1" data-max="5" data-min="0"><label for="com" class="label-control">Votre commentaire :</label><textarea class="form-control" id="com" name="com"></textarea><input type="hidden" name="hash" value="'.$hash.'"><input type="submit" value="Envoyer"></form>';
                    }
                }
            }
            return $html;
        }
    }
?>