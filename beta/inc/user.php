<?php 
// CLASS PAGE
    class page {
        //ENCODER MAIL POUR PROTECTION ROBOTS SPAM
        function encode_mail($mail, $n) {
            $r = array("ASC" => "", "UTF" => "");
            for($i=0;$i<strlen($mail);$i++) {
                $r['ASC'] .= "&#".ord($mail[$i]).";";
                $r['UTF'] .= "%".dechex(ord($mail[$i]));    
            }
            return $r[$n];
        }
        //FONCTION POUR RETROUVER VALEUR DANS TABLEAU
        function in_array_r($reg, $arr) {
            $return = false;
            foreach($arr as $key => $value) {
                if(preg_match($reg, trim($value))) {
                    $return = true;
                    break;
                }
            }
            return $return;
        }
        //FONCTION POUR REMPLACER LES ACCENTS
        function stripAccents($str){
            $str = htmlentities($str, ENT_NOQUOTES, 'utf-8');
            $str = preg_replace('#&([A-za-z])(?:uml|circ|tilde|acute|grave|cedil|ring);#', '\1', $str);
            $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
            $str = preg_replace('#&[^;]+;#', '', $str);
            return $str;
        }
        // ###### HTML ####### //
        
        //AFFICHAGE SELECT DATE DE NAISSANCE
        function birthdate($selectd="",$selectm="",$selecty="") {
            if(is_array($selectd)) {
                $selecty = $selectd[0];    
                $selectm = $selectd[1];
                $selectd = $selectd[2];
            }
            $htmld = '<select id="day" name="day" class="form-control birthday">';
            for($i=1;$i<32;$i++) { 
                $o = $i; 
                if($o < 10) $o = "0".$o;
                $htmld .= '    <option value="'.$o.'">'.$o.'</option>'; 
            } 
            $htmld .= '</select>
            ';
            $htmld = preg_replace('/ value\=\"'.$selectd.'\"/', ' selected value="'.$selectd.'"', $htmld);
            $htmlm = '<select name="month" class="form-control birthmonth">';
            for($i=1;$i<13;$i++) { 
                $o = $i; 
                   if($o < 10) $o = "0".$o; 
                $htmlm .= '    <option value="'.$o.'">'.$o.'</option>'; 
            }    
            $htmlm .= '</select>
            ';
            $htmlm = preg_replace("/ value\=\"".$selectm."\"/", " selected value=\"".$selectm."\"", $htmlm);
            $htmly = '<select name="year" class="form-control birthyear">';
            for($i=(date("Y")-18);$i>1919;$i--) { 
                $htmly .= '    <option value="'.$i.'">'.$i.'</option>'; 
            }
            $htmly .= '</select>';
            $htmly = preg_replace("/ value\=\"".$selecty."\"/", " selected value=\"".$selecty."\"", $htmly);
            return $htmld.$htmlm.$htmly;
        }
        //AFFICHAGE DES META TAG DE REFERENCEMENT
        function meta_tag($img, $description_g="", $description="", $title_page="", $more_tags="") {
            if(is_array($img)) {
                $description_g = $img[1];
                $description = $img[2];
                $title_page = $img[3];
                $more_tags = $img[4];
                $img = $img[0];
            }
        $description_g = preg_replace('/[\s]+/', ' ',  $description_g);
        $description = preg_replace("/[\s]+/", " ", $description);
         $tag = 'absences, vacances, arroser, jardin, effectuer, rondes, nourrir, animaux, aide, menagere, nettoyage, repassage, garde, promenade, toilettage, automobile, changement, pieces,  reparations, vidange, accompagnement, ecole, activite, enfant, bricolage, monter, meuble, travaux, manuels, reparations, coaching, conseils, cuisine, decoration, jeux, sport, conseils, cours, particuliers, langues, matieres, scientifiques, musique, courses, demarches, administratives, faire, demenagement, festivites, preparation , fete, animation, musicale, bar, restauration, informatique, assistance, maintenance, reparation, redaction, documents, soins, beaute, coiffure, epilation, manucure, massage, entretien, habitat, electricite, jardinage, maconnerie, peinture, plomberie, swappy, echange, gratuit, services, sel, bricolage, baby-sitting, troc, partage, entraide, communaute, utilisateur, swappeur, non-lucratif, generosite, entre-aide, amitie, temps, rencontre, competences, particuliers, entraide, annonce, garder';
        if(!empty_($more_tags)) {
            $more = preg_split("/( |\'|\,|\.|\-|[\s]|[\s+])/", strtolower($this->stripAccents(trim($more_tags))));
            $al = preg_split("/\, /", $tag);
            foreach($more as $key => $value) {
                if(strlen($value) > 2) {
                    if(!$this->in_array_r("/".preg_replace("/(.*?)s$/", "$1", trim($value))."($|s$)/", $al)) {
                        $tag .= ', '.trim($value);
                    }
                }
            }
        }
        if(empty_($img)) {
            $img="image.jpg";
        }
        if(preg_match("/\//", $img)) {
            $base = basename($img);
            $dir = dirname($img);
            $makef = 0;
            if($dir == "img/user/upload") {
                $dir = "img/social/{TYPE}/user/";
                $makef = 1;
            }
            $list = array("facebook", "google", "twitter");
            if($makef == 1) {
                for($i=0;$i<count($list);$i++) {
                    $file = preg_replace("/\{TYPE\}/", $list[$i], $dir.$base);
                    $im = $img_ = "";
                    if(!file_exists($file)) {
                        $im = imagecreatefromstring(file_get_contents($img));
                        if ($im !== false) {
                            if($list[$i] == "google") {
                                $w = $h = 190;
                            }
                            if($list[$i] == "facebook") {
                                $w = $h = 210;
                            }
                            if($list[$i] == "twitter") {
                                $w = $h = 180;
                            }
                            $img_ = imagecreatetruecolor($w,$h);
                            imagecopyresampled($img_,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));
                            imagejpeg($img_,$file,100); 
                            imagedestroy($img_);
                        }
                    }
                    ${substr($list[$i],0,1)."img"} = ''.URL_SITE.''.FOLDER_.$file;
                }
            } else {
                $gimg = ''.URL_SITE.''.FOLDER_."img/social/google/".basename($img)."";
                $fimg = ''.URL_SITE.''.FOLDER_."img/social/facebook/".basename($img)."";
                $timg = ''.URL_SITE.''.FOLDER_."img/social/twitter/".basename($img)."";
            }
            
        } else {
            $gimg = ''.URL_SITE.''.FOLDER_."img/social/google/".$img."";
            $fimg = ''.URL_SITE.''.FOLDER_."img/social/facebook/".$img."";
            $timg = ''.URL_SITE.''.FOLDER_."img/social/twitter/".$img."";
        }
        if(empty_($title_page)) {
            $title_page = "Échanges de services gratuits entre particuliers";
        }
        if(empty_($description_g)) {
            $description_g = 'Swappy, la plateforme d’échanges de services gratuits entre particuliers ! Proposez/recherchez des services sur ce nouveau site d\'annonces dédié à l\'entraide.';    
        } 
        if(empty_($description)) {
            $description = $description_g;    
        }
        $ogurl = basename($_SERVER['REQUEST_URI']);
        $html = '<meta name="description" content="'.$description_g.'">
            <meta name="keywords" content="'.$tag.'">
            <!-- Schema.org markup for Google+ -->
            <meta itemprop="name" content="Swappy.fr - '.$title_page.'">
            <meta itemprop="description" content="'.$description_g.'">
            <meta itemprop="image" content="'.$gimg.'">
            <!-- Twitter Card data -->
            <meta name="twitter:card" content="summary">
            <meta name="twitter:url" content="'.URL_SITE.''.FOLDER_.'">
            <meta name="twitter:site" content="@_Swappy">
            <meta name="twitter:title" content="Swappy.fr - '.$title_page.'">
            <meta name="twitter:description" content="'.$description_g.'">
            <meta name="twitter:image" content="'.$timg.'">
            <!-- Open Graph data -->
            <meta property="og:locale" content="fr_FR">
            <meta property="og:title" content="Swappy.fr - '.$title_page.'">
            <meta property="og:type" content="website">
            <meta property="og:url" content="'.URL_SITE.''.FOLDER_.$ogurl.'">
            <meta property="og:image" content="'.$fimg.'">
            <meta property="og:description" content="'.$description_g.'">
            <meta property="og:site_name" content="Swappy.fr - '.$title_page.'">
        ';
            return $html;
        }
    }
    //CITY CLASS
    class city {
        private $mysql;
        function __construct($mysql) {
            $this->mysql = $mysql;
        }
        //SUPPRESSION ACCENT POUR RECHERCHE
        function wd_remove_accents($str, $charset='utf-8')
        {
            $str = htmlentities($str, ENT_NOQUOTES, $charset);
            $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
            $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
            $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
            return $str;
        }
        //RECUPERATION VILLE VIA CODE POSTALE
        function getCity($zip) {
            $result = "";
            $select = $this->mysql->prepare("SELECT `Real_Name` FROM `french_city` WHERE `ZipCode` = :zip LIMIT 0, 1");    
            $select->execute(array(":zip" => $zip));
            $data = $select->fetch(PDO::FETCH_OBJ);
            if(isset($data->Real_Name) && !empty_($data->Real_Name)) {
                $result = $data->Real_Name;
            }
            return $result;
        }
        //RECUPERATION COORDONNEES VIA BDD
        function getPositionDB($zip, $name="") {
            $name_s = "";
            $replace = array(":zipcode" => $zip);
            if(!empty_($name)) {
                $name_s = " AND `Real_Name` = :name";    
                $replace[":name"] = $name;
            }
            $result = array("lat" => false, "lon" => false);
            $select = $this->mysql->prepare("SELECT `Lon`, `Lat` FROM `french_city` WHERE `ZipCode` = :zipcode".$name_s." LIMIT 0, 1");    
            $select->execute($replace);
            $data = $select->fetch(PDO::FETCH_OBJ);
            if(isset($data->Lon)) {
                $result['lat'] = $data->Lat;
                $result['lon'] = $data->Lon;
            }
            return $result;
        }
        //RECUPERER INFORMATIONS VILLE VIA SON NOM
        function getLocationByName($name) {
            $replace = array();
            $city = preg_replace("/\|| /", "-", $name);
            $result = array("name" => false, "ID" => false, "lat" => false, "lon" => false, "zipcode" => false);
            $where = $order = "";
            $l = explode("|", $name);
                for($i=0;$i<count($l);$i++) {
                    $prpn = ":value".$i;
                    $w = $this->wd_remove_accents($l[$i]);
                    if(strlen($w)  > 2 && !is_numeric($w)) {
                        //NORMAL
                        $replace[$prpn] = $w;
                        //FIRST CASE
                        $replace[$prpn."f"] = "^".$w;
                        //FIRST AND LAST
                        $replace[$prpn."l"] = "^".$w."$";
                        $where .= ' OR (UPPER(`Name`) REGEXP '.$prpn.')';
                        $order .= ' + (CASE WHEN UPPER(`Name`) REGEXP '.$prpn.' THEN 1.8 ELSE 0 END) + (CASE WHEN UPPER(`Name`) REGEXP '.$prpn.'f THEN 1.2 ELSE 0 END) + (CASE WHEN UPPER(`Name`) REGEXP '.$prpn.'l THEN 1.3 ELSE 0 END)';
                    } else if(is_numeric($w)) { //DEPARTEMENT FIX
                        //NORMAL
                        $replace[$prpn] = $w;
                        //LAST CASE
                        $replace[$prpn."l"] = $w."$";
                        $where .= ' OR (UPPER(`ZipCode`) REGEXP '.$prpn.')';
                        $order .= ' + (CASE WHEN UPPER(`ZipCode`) REGEXP '.$prpn.' THEN 1.3 ELSE 0 END) + (CASE WHEN UPPER(`ZipCode`) REGEXP '.$prpn.'l THEN 0.5 ELSE 0 END)';
                    }
                }
                $where .= '';
                if(!empty_($where)) { $where = substr($where, 4, (strlen($where)-1)); }
                if(!empty_($order)) { $order = substr($order, 3, (strlen($order))); }
                $query = "SELECT `Real_Name`, `Name`, `ID`, `Lat`, `Lon`, `ZipCode` FROM `french_city` WHERE ".$where." GROUP BY `ID` ORDER BY ".$order." DESC, `ZipCode` ASC LIMIT 0, 1";
                $select = $this->mysql->prepare($query);
                $select->execute($replace);
                $data = $select->fetch(PDO::FETCH_OBJ);
                $total = $select->rowCount();
                if($total > 0) {
                    similar_text(strtoupper($this->wd_remove_accents(preg_replace("/\|/", "-", $city))), strtoupper($this->wd_remove_accents($data->Name)), $percent);    
                    $length = strlen($data->Name);
                    //MATCH VERIFICATION + WITH NUMBERS LETTERS
                    if($percent > 65 && strlen($city) <= ($length + 4) && strlen($city) >= ($length - 4)) {
                        $result = array("Name" => $data->Real_Name, "ID" => $data->ID, "Lat" => $data->Lat, "Lon" => $data->Lon, "ZipCode" => $data->ZipCode);
                    }
                }
            return $result;    
        }
        //RECUPERATION COORDONNEES ---- GOOGLE
        function getPosition($adresse, $zip, $city="") {
            //Initiation des variables de sortie
            $coords['lat'] = $coords['lon'] = '';
            if($city == "") {
                $city_ = trim($zip." ".$this->getCity($zip));
            } else {
                $city_ = trim($zip." ".$city);
            }
            $addr = $city_;
            if(!empty_($adresse)) {
                //Si on a une adresse complete on va utiliser GOOGLE API pour une meilleur localisation
                $addr = $adresse." ".$city_;
                
                $url='http://maps.googleapis.com/maps/api/geocode/xml?region=FR&address='.$addr.', France&sensor=false';
                $xml = @simplexml_load_file($url);
                $coords['status'] = @$xml->status;
                //On verifie que GOOGlE a bien trouver quelque chose ou si le quota de recherche est depasser...
                if($coords['status'] == 'OK') {    
                
                    //On verifie que GOOGLE a bien trouver une rue "route"
                    if($xml->result->address_component[1]->type == "route") {
                        
                        //On verifie la similarité entre le resultat et la recherche
                        $result_street = $xml->result->address_component[0]->long_name." ".$xml->result->address_component[1]->long_name." ".$xml->result->address_component[6]->long_name." ".$xml->result->address_component[2]->long_name;
                        similar_text(strtoupper($this->wd_remove_accents($result_street)), strtoupper($this->wd_remove_accents($adresse)), $percent);
                        if(number_format($percent, 0) < 42) {
                            //Si la similitude des adresse est inférieur à 35% par sécurité on va juste ce basé sur le code postal
                            $coords = $this->getPosition("", $zip, $city);
                        } else {
                            //Si c'est OK on renvoie les informations
                            $coords['lat'] = $xml->result->geometry->location->lat;
                            $coords['lon'] = $xml->result->geometry->location->lng;
                        }
                    } else {
                        //Si le resultat ne signale pas avoir trouver une rue, on va utiliser la BDD
                        $coords = $this->getPositionDB($zip, $city);
                    }
                } else {
                    // Si GOOGLE n'a rien trouvé, on va utiliser la BDD
                    $coords = $this->getPositionDB($zip, $city);
                }
            } else {
                //Si on a pas une adresse complete on va utiliser la BDD
                $coords = $this->getPositionDB($zip, $city);
            }
            //On renvoie
            return $coords;
        }
    }
    //USER CLASS
    class user {
        public $ID, 
            $cryptID,
            $login, 
            $lastname, 
            $firstname, 
            $email, 
            $phone, 
            $avatar, 
            $zipcode, 
            $street, 
            $street2,
            $mailoption, 
            $admin, 
            $gender, 
            $birthdate, 
            $city, 
            $lat,
            $logged, 
            $age,
            $description,
            $tags,
            $globalnote,
            $globalvote,
            $fullname,
            $title,
        $lon;
        public $chat_;
        private $mysql, 
            $cookies,
            $password;
        function __construct($mysql, $id="") {
            $this->mysql = $mysql;
            if(empty_($id)) {
                $this->find_sess();
                if(isset($_GET['logout'])) {
                    $this->logout();
                }
            } else {
                $this->load_user_data($id, $this->crypt_sess($id), false);
            }
            $rand = rand(0,11);
            if($rand > 8) { $this->auto_(); }
        }
        //SCRIPT AUTOMATIQUE
        function auto_() {
            //RDV
            $select = @$this->mysql->prepare("SELECT * FROM `appointment` WHERE `Date` <= '".date("Y-m-d H:i:s", strtotime("-1 hour"))."' AND `State` = '1' LIMIT 0, 8");
            @$select->execute();
            $r = @$select->rowCount();
            if(!is_numeric($r)) { $r = 0; }
            if($r > 0) {
                if(file_exists("inc/chat.php")) {
                @require_once("inc/chat.php");    
                } else if(file_exists("chat.php")) {
                @require_once("chat.php");    
                } else if(file_exists("../inc/chat.php")) {
                @require_once("../inc/chat.php");
                }
                $chat = new chat($this->mysql, $this);
            }
            while($data = $select->fetch(PDO::FETCH_OBJ)) {
                $other = $data->User;
                if($other == $this->ID) {
                    $other = $data->Owner_Service;
                }
                $cc = $chat->isset_conversation($other, $data->Service);
                if($cc != false) {
                    $ss = $this->mysql->prepare("UPDATE `appointment` SET `State` = '2' WHERE `ID` = '".$data->ID."'");
                    $ss->execute();
                    $mess = 'La date du rendez-vous est passé. Confirmez-vous que votre rendez-vous à eu lieu ?<br><i><a data-id="'.$data->ID.'" class="valid-this-date">Oui</a>&nbsp;&nbsp;&nbsp;&nbsp;<a data-id="'.$data->ID.'" class="refuse-this-date">Non</a>';
                    $chat->send_reply($mess, $cc, $data->User);
                    $up = $this->mysql->prepare("UPDATE `conversation` SET `Status` = '2' WHERE `ID` = '".$cc."'");
                    $up->execute();
                }
            }
            //USER NON ACTIVE DEPUIS 4 MOIS
            $select = @$this->mysql->prepare("DELETE FROM `users` WHERE `Created` <= '".date("Y-m-d H:i:s", strtotime("-4 month"))."' AND `Validation` = '0' ORDER BY `Created` ASC LIMIT 20");
            @$select->execute();
            //RENDEZ-VOUS OUBLIE
            $select = @$this->mysql->prepare("SELECT * FROM `appointment` WHERE `Date` <= '".date("Y-m-d H:i:s", strtotime("-2 month"))."' AND `State` != '5' LIMIT 0, 8");
            @$select->execute();
            $r = @$select->rowCount();
            if(!is_numeric($r)) { $r = 0; }
            if($r > 0) {
                if(file_exists("inc/chat.php")) {
                @require_once("inc/chat.php");    
                } else if(file_exists("chat.php")) {
                @require_once("chat.php");    
                } else if(file_exists("../inc/chat.php")) {
                @require_once("../inc/chat.php");
                }
                $chat = new chat($this->mysql, $this);
            }
            while($data = $select->fetch(PDO::FETCH_OBJ)) {
                $other = $data->User;
                if($other == $this->ID) {
                    $other = $data->Owner_Service;
                }
                $cc = $chat->isset_conversation($other, $data->Service);
                if($cc != false) {
                    $del = $this->mysql->prepare("DELETE `appointment` WHERE `ID` = '".$data->ID."'");
                    $del->execute();
                    $mess = 'Suite a aucune réponse sur ce rendez-vous depuis 2mois... Nous l\'annulons.';
                    $chat->send_reply($mess, $cc, $data->User);
                    $chat->send_reply($mess, $cc, $data->Owner_Service);
                    $up = $this->mysql->prepare("UPDATE `conversation` SET `Status` = '0' WHERE `ID` = '".$cc."'");
                    $up->execute();
                }
            }
        }
        //RECUPERATION AGE UTILISATEUR
        function getAge($date) {
            $arr1 = explode('/', date('d/m/Y', strtotime($date)));
            $arr2 = explode('/', date('d/m/Y'));
            if(($arr1[1] < $arr2[1]) || (($arr1[1] == $arr2[1]) && ($arr1[0] <= $arr2[0]))) {
                return $arr2[2] - $arr1[2];
            } else {
                return $arr2[2] - $arr1[2] - 1;
            }
        }
        //RECUPERATION NOTE GLOBAL
        function getglnote($id) {
            $select = $this->mysql->prepare("SELECT SUM(`Note`) AS `total`, COUNT(*) AS `nb` FROM `notations` WHERE `Owner_Service` = '".$id."'");
            $select->execute();
            $data = $select->fetch(PDO::FETCH_OBJ);
            $total = @round($data->total/$data->nb);
            return array($total, $data->nb);
        }
        //PROTECTION PAGE JUSTE POUR UTILISATEURS
        function onlyUsers() {
            if((!$this->logged && !isset($_GET['logout']))) {
                header("HTTP/1.1 403 Unauthorized" );
                header("Location: index.php?unlogged&p=".preg_replace("/[\?|\&]logout/", "", basename($_SERVER['REQUEST_URI']))."");
            } else if (!$this->logged && isset($_GET['logout'])) {
                header("HTTP/1.1 403 Unauthorized" );
                header("Location: index.php");    
            }
        }
        //PROTECTION PAGE JUSTE POUR VISITEURS
        function onlyVisitors() {
            if($this->logged) {
                if(preg_match("/inscription\.php/", $_SERVER['REQUEST_URI']) || isset($_GET['logout'])) {
                    header("HTTP/1.1 403 Unauthorized" );
                    header("Location: index.php");    
                } else {
                    header("HTTP/1.1 403 Unauthorized" );
                    header("Location: index.php?needunlogged");    
                }
            }
        }
        //PROTECTION PAGE JUSTE POUR ADMIN
        function onlyAdmin() {
            $this->onlyUsers();
            if($this->admin == 0) {
                header("HTTP/1.1 403 Unauthorized" );
                header("Location: index.php?noadmin");    
            }
        }
        //COMPTER MESSAGE NON LU
        function list_messages() {
            $t = 0;
            $select = $this->mysql->prepare("SELECT `conversation_reply`.`ID` FROM `conversation_reply` INNER JOIN `conversation` ON `conversation_reply`.`C_ID` = `conversation`.`ID` WHERE (`conversation`.`User_One` = :id OR `conversation`.`User_Two` = :id) AND `conversation_reply`.`Author` != :id AND `conversation_reply`.`Seen` = '0' AND (`conversation_reply`.`BotTo` = '0' OR `conversation_reply`.`BotTo` = '".$this->ID."')");    
            $select->execute(array(":id" => $this->ID));
            $t = $select->rowCount();
            return $t;
        }
        //CRYPTER VALEUR COOKIE|SESSION
        function crypt_sess($ID) {
            $step = base64_encode($ID."__SWAP");
            $total = strlen($step);
            $hs = round($total/4);
            $firstpart = substr($step, 0, $hs);
            $secondpart = substr($step, $hs, $hs);
            $thirdpart = substr($step, $hs*2, $hs);
            $fourthpart = substr($step, $hs*3, $hs);
            $mixed = $thirdpart.$secondpart.$fourthpart.$firstpart;
            $mixed = preg_replace("/\=/", "_", $mixed);
            $output = base64_encode($mixed);
            return $output;
        }
        //DECRYPTER COOKIE|SESSION
        function uncrypt_sess($sess) {
            $step = base64_decode($sess);
            $step = preg_replace("/\_/", "=", $step);
            $total = strlen($step);
            $hs = round($total/4);
            $firstpart = substr($step, 0, $hs);
            $secondpart = substr($step, $hs, $hs);
            $thirdpart = substr($step, $hs*2, $hs);
            $fourthpart = substr($step, $hs*3, $hs);
            $mixed = $fourthpart.$secondpart.$firstpart.$thirdpart;
            $output = base64_decode($mixed);
            $output = preg_replace("/\_\_SWAP/", "", $output);
            return $output;
        }
        //CRYPTER LIEN DE MOT DE PASSE PERDU
        function crypt_remind($mail, $ID, $pass) {
            $step = base64_encode($ID."__SWAP".$mail."__SWAP".$pass."__SWAP".time());
            $total = strlen($step);
            $hs = round($total/4);
            $firstpart = substr($step, 0, $hs);
            $secondpart = substr($step, $hs, $hs);
            $thirdpart = substr($step, $hs*2, $hs);
            $fourthpart = substr($step, $hs*3, $hs);
            $mixed = $thirdpart.$secondpart.$fourthpart.$firstpart;
            $mixed = preg_replace("/\=/", "_", $mixed);
            $output = base64_encode($mixed);
            return $output;
        }
        //DECRYPTER LIEN DE MOT DE PASSE PERDU
        function uncrypt_remind($sess) {
            $step = base64_decode($sess);
            $step = preg_replace("/\_/", "=", $step);
            $total = strlen($step);
            $hs = round($total/4);
            $firstpart = substr($step, 0, $hs);
            $secondpart = substr($step, $hs, $hs);
            $thirdpart = substr($step, $hs*2, $hs);
            $fourthpart = substr($step, $hs*3, $hs);
            $mixed = $fourthpart.$secondpart.$firstpart.$thirdpart;
            $output = base64_decode($mixed);
            $output = explode("__SWAP", $output);
            return $output;
        }
        //RECHERCHE COOKIE OU SESSION
        function find_sess() {
            if(isset($_COOKIE['user_swappy']) && !empty_($_COOKIE['user_swappy'])) {
                $this->load_user_data($this->uncrypt_sess($_COOKIE['user_swappy']), $_COOKIE['user_swappy']);
            } else if(isset($_SESSION['user_swappy']) && !empty_($_SESSION['user_swappy'])) {
                $this->load_user_data($this->uncrypt_sess($_SESSION['user_swappy']), $_SESSION['user_swappy']);
            } else {
                $this->logged = false;    
            }
        }
        //DECONNEXION
        function logout() {
            setcookie("user_swappy", "", time() - (60*60*60), "/");
            $_SESSION['user_swappy'] = "";
            unset($_COOKIE['user_swappy']);
            unset($_SESSION['user_swappy']);
            $this->unload_user_data();
        }
        //CONNEXION
        function flogin($POST) {
            $arr = array();
            $select = $this->mysql->prepare("SELECT `ID`,`Password`,`Validation` FROM `users` WHERE `Login` = :login");
            $select->execute(array(":login" =>  strtolower($POST['login_form_'])));
            $data = $select->fetch(PDO::FETCH_OBJ);
            $total = $select->rowCount();
            if($total > 0) {
                if($data->Password != md5(trim($POST['password_form']))) {
                    $arr = array(false, "Mauvais mot de passe");
                } else if($data->Validation == 0)  {
                    //NOT VALIDATED
                    $arr = array(false, "Ce compte n'a pas été validé via votre boite mail.");
                } else {
                    //CONNECTED
                    if(isset($POST['remember_me'])) {
                        setcookie("user_swappy", $this->crypt_sess($data->ID), time() + (60*60*60), "/");
                    } else {
                        $_SESSION['user_swappy'] = $this->crypt_sess($data->ID);
                    }
                    $arr = array(true);
                }
            } else {
                $arr = array(false, "Mauvais login");    
            }
            return $arr;
        }
        //CHARGEMENT DES INFOS DE L'UTILISATEUR DANS LA CLASS
        function load_user_data($ID, $crypt, $me=true) {            
            $select = $this->mysql->prepare("SELECT *, COUNT(*) AS `exist` FROM `users` WHERE `ID` = :ID");
            $select->execute(array(":ID" => $ID));
            $data = $select->fetch(PDO::FETCH_OBJ);
            if($data->exist < 1) {
                //WRONG
                if($me == true) {
                    $this->logout();
                } else {
                    if(!preg_match("/inc\//", $_SERVER['PHP_SELF'])) {
                        header("HTTP/1.0 404 Not Found");
                        header("Location: 404.php");
                    }
                }
            } else {
                //OK
                if(isset($_GET['id'])) {
                    $this->title = "Profil de ".ucfirst($data->FirstName)." ".ucfirst($data->LastName);
                } else {
                    $this->title = "Mon profil";
                }
                $this->ID = $ID;
                $this->age = $this->getAge($data->Birthdate);
                $this->cryptID = $crypt;
                $this->admin = $data->Admin;
                $this->password = $data->Password;
                $this->avatar = $data->Avatar;
                $this->login = strtolower($data->Login);
                $this->email = strtolower($data->Email);
                $this->firstname = ucfirst(trim($data->FirstName));
                $this->lastname = ucfirst(trim($data->LastName));
                $this->phone = $data->Phone;
                $this->street = $data->Street;
                $this->city = $data->City;
                $this->zipcode = $data->ZipCode;
                $this->mailoption = $data->MailOption;
                $this->gender = strtoupper($data->Gender);
                $this->birthdate = $data->Birthdate;
                $this->lon = $data->Lon;
                $this->lat = $data->Lat;
                $this->tags = $data->Tags;
                $this->description = ucfirst(trim($data->Desc));
                $vote = $this->getglnote($ID);
                $this->globalnote = $vote[0];
                $this->globalvote = $vote[1];
                $this->fullname = ucfirst(trim($data->FirstName))." ".ucfirst(trim($data->LastName));
                if($me == true) {
                    $this->logged = true;
                } else {
                    $this->logged = false;
                }
            }
        }
        //RETIRER INFORMATIONS UTILISATEURS DE LA CLASS
        function unload_user_data() {
            $this->ID = false;
            $this->age = false;
            $this->password = false;
            $this->cryptID = false;
            $this->admin = false;
            $this->avatar = false;
            $this->login = false;
            $this->email = false;
            $this->firstname = false;
            $this->lastname = false;
            $this->phone = false;
            $this->street = false;
            $this->city = false;
            $this->zipcode = false;
            $this->mailoption = false;
            $this->gender = false;
            $this->birthdate = false;
            $this->lon = false;
            $this->lat = false;
            $this->logged = false;
            $this->description = false;
            $this->tags = false;
            $this->globalnote = false;
            $this->globalvote = false;
            $this->fullname = false;
            $this->title = false;
        }
        //GENERATION META TAG
        function meta() {
            return array ($this->avatar, $this->description == "" ? 'Pas de description...' : ucfirst($this->description), "", $this->title, $this->description.", utilisateur, perso, profil, ".$this->tags.", ".$this->city.", ".$this_->zipcode);
        }
        //EDITION UTILISATEUR
        function edit_user($POST) {
            $avatar = $this->avatar;
            $return = array(false);
            $allow = -1;
            $set = "";
            $replace = array();
            if(!$this->logged) {
                $return = array(false, "Vous n'êtes pas connecté");
            } else {
                if(empty_($POST['nom'])) {
                    $allow = 0;
                    $return = array(false, "Le champ Nom est requis");
                }
                if(trim($POST['nom']) != $this->lastname) {
                    $set .= ' `LastName` = :nom,';
                    $replace[':nom'] = ucfirst(trim($POST['nom']));
                    $allow == -1 ? $allow = 1 : $allow = $allow;
                }
                if(empty_($POST['prenom'])) {
                    $allow = 0;
                    $return = array(false, "Le champ Prenom est requis");
                }
                if(trim($POST['prenom']) != $this->firstname) {
                    $set .= ' `FirstName` = :prenom,';
                    $replace[':prenom'] = ucfirst(trim($POST['prenom']));
                    $allow == -1 ? $allow = 1 : $allow = $allow;
                }
                if(isset($_POST['mail'])) {
                    $mailo = trim($_POST['mail']);
                    if($mailo == "on") { $mailo = 1; } else { $mailo = 0; }
                } else {
                    $mailo = 0;
                }
                if($mailo != $this->mailoption) {
                    $set .= ' `MailOption` = :mailopt,';
                    $replace[':mailopt'] = trim($mailo);
                    $allow == -1 ? $allow = 1 : $allow = $allow;
                }
                if(empty_($POST['gender'])) {
                    $allow = 0;
                    $return = array(false, "Le champ Sexe est requis");
                } else if(!preg_match("/M|F/", strtoupper(trim($_POST['gender'])))) {
                    $allow = 0;
                    $return = array(false, "Impossible de savoir si vous êtes un homme ou une femme");
                }
                if(trim($POST['gender']) != $this->gender) {
                    $set .= ' `Gender` = :gender,';
                    $replace[':gender'] = strtoupper(trim($POST['gender']));
                    if(preg_match('/\/(user\/M|user\/F)\.jpg/', $avatar)) {
                        $avatar = "img/user/".trim($POST['gender']).".jpg";
                        $set .= ' `Avatar` = :avatar,';
                        $replace[':avatar'] = $avatar;
                    }
                    $allow == -1 ? $allow = 1 : $allow = $allow;
                }
                if(empty_($POST['year']) || empty_($POST['month']) || empty_($POST['day'])) {
                    $allow = 0;
                    $return = array(false, "Le champ Date de naissance est requis au complet");
                }
                $birth = $POST['year']."-".$POST['month']."-".$POST['day'];
                if($birth != $this->birthdate) {
                    $set .= ' `Birthdate` = :birth,';
                    $replace[':birth'] = $birth;
                    if($this->getAge($POST['year']."-".$POST['month']."-".$POST['day']) < 18) {
                        $allow = 0;
                        $return = array(false, "Vous ne pouvez pas être mineur !");
                    } else {
                        $allow == -1 ? $allow = 1 : $allow = $allow;
                    }
                }
                if(trim($POST['street']) != $this->street || trim($POST['zipcode']) != $this->zipcode) {                
                    $city = new city($this->mysql);
                    $c = $city->getPosition($POST['street'], $POST['zipcode'], $POST['cityname']);
                    $set .= ' `Street` = :street,';
                    $replace[':street'] = trim($POST['street']);
                    $set .= ' `ZipCode` = :zipcode,';
                    $replace[':zipcode'] = trim($POST['zipcode']);
                    $set .= ' `City` = :city,';
                    $replace[':city'] = trim($POST['cityname']);
                    $set .= ' `Lat` = :lat, `Lon` = :lon,';
                    $replace[':lat'] = $c['lat'];
                    $replace[':lon'] = $c['lon'];
                    $allow == -1 ? $allow = 1 : $allow = $allow;
                }
                if(trim($POST['tags']) != $this->tags) {
                    $set .= ' `Tags` = :tags,';
                    $replace[':tags'] = trim($POST['tags']);
                    $allow == -1 ? $allow = 1 : $allow = $allow;
                }
                if(trim($POST['description']) != $this->description) {
                    $set .= ' `Desc` = :desc,';
                    $replace[':desc'] = ucfirst(trim($POST['description']));
                    $allow == -1 ? $allow = 1 : $allow = $allow;
                }
                if(trim($POST['phone']) != $this->phone) {
                    $set .= ' `Phone` = :phone,';
                    $replace[':phone'] = trim($POST['phone']);
                    $allow == -1 ? $allow = 1 : $allow = $allow;
                }
                $mdp = trim($POST['mdp']);
                $rmdp = trim($POST['r_mdp']);
                $mail_ = trim(strtolower($POST['email']));
                if($mail_ != $this->email || (!empty_($mdp) && !empty_($rmdp))) {            
                    $amdp = trim($POST['a_mdp']);
                    if(empty_($amdp)) {
                        $allow = 0;
                        $return = array(false, "Pour changer email et/ou mot de passe, vous devez entrer votre mot de passe actuel !", "a_mdp");
                    } else if(md5($amdp) != $this->password) {
                        $allow = 0;
                        $return = array(false, "Mauvais mot de passe", "a_mdp");
                    }
                    if(($allow == 1 || $allow == -1) && $mail_ != $this->email) {
                        if(empty_($POST['email'])) {
                            $allow = 0;
                            $return = array(false, "Le champ Adresse email est requis");
                        } else {
                            $mm = $this->issetEmail($mail_, "");
                            if($mm[1] == true) {
                                $allow = 0;
                                $return = array(false, "Cette adresse email est déjà utilisée !", "email");
                            } else {
                                $set .= ' `Email` = :email,';
                                $replace[':email'] = $mail_;
                                $allow == -1 ? $allow = 1 : $allow = $allow;
                            }
                        }
                    }
                    if($allow == 1 && (!empty_($mdp) && !empty_($rmdp))) {
                        if($mdp == $rmdp) {
                            $set .= ' `Password` = :password,';
                            $replace[':password'] = md5($mdp);
                            $allow == -1 ? $allow = 1 : $allow = $allow;
                        } else {
                            $allow = 0;
                            $return = array(false, "Les mots de passe ne sont pas identique", "mdp");
                        }
                    }
                }
                if($allow == 1) {
                    if(!empty_($set)) {
                        $q = substr($set, 0, strlen($set)-1);
                        $select = $this->mysql->prepare("UPDATE `users` SET".$q." WHERE `ID` = '".$this->ID."'");
                        $select->execute($replace);
                    }
                    $return = array(true, $avatar);
                }
            }
            return $return;
        }
        //AJOUT UTILISATEUR
        function add_user($POST) {
            $arr = array(false);
            if(empty_($POST['zipcode'])) {
                $arr = array(false, "Le champ Code Postal est requis");
            } else if(empty_($POST['cityname'])) {
                $arr = array(false, "Une erreur à eu lieu avec votre Code Postal");
            } else if($this->issetEmail(strtolower($POST['email']), false) == false) {
                $arr = array(false, "Cet adresse email est déjà utilisé");
            } else if($this->issetLogin(strtolower($POST['login']), false) == false) {
                $arr = array(false, "Ce login est déjà utilisé");
            } else if(empty_($POST['login'])) {
                $arr = array(false, "Le champ Identifiant est requis");
            } else if(empty_($POST['password'])) {
                $arr = array(false, "Le champ Mot de passe est requis");
            } else if(empty_($POST['password_r'])) {
                $arr = array(false, "Le champ Retaper Mot de passe est requis");
            } else if(empty_($POST['lastname'])) {
                $arr = array(false, "Le champ Nom est requis");
            } else if(empty_($POST['firstname'])) {
                $arr = array(false, "Le champ Prenom est requis");
            } else if(empty_($POST['email'])) {
                $arr = array(false, "Le champ Adresse e-mail est requis");
            } else if(empty_($POST['gender'])) {
                $arr = array(false, "Le champ Sexe est requis");
            } else if(!preg_match("/M|F/", strtoupper(trim($_POST['gender'])))) {
                $arr = array(false, "Impossible de savoir si vous êtes un homme ou une femme");
            } else if(empty_($POST['day']) || empty_($POST['month']) || empty_($POST['year'])) {
                $arr = array(false, "Le champ Date de naissance est requis au complet");
            } else if($this->getAge($POST['year']."-".$POST['month']."-".$POST['day']) < 18) {
                $arr = array(false, "Vous êtes mineur, vous n'avez pas le droit de vous inscrire");
            } else if(!isset($_POST['accept'])) {
                $arr = array(false, "Vous devez accepter nos conditions générales d'utilisation et mentions légales.");
            } else if(trim($POST['password']) != trim($POST['password_r'])) {
                $arr = array(false, "Les mots de passe ne sont pas identique");
            } else if(strlen(trim($POST['login'])) < 4) {
                $arr = array(false, "Votre idenfiant doit comporter au minimum 5 caractères");
            } else if(strlen(trim($POST['password'])) < 5) {
                $arr = array(false, "Votre mot de passe doit comporter au minimum 6 caractères");
            } else if(!filter_var(trim($POST['email']), FILTER_VALIDATE_EMAIL)) {
                $arr = array(false, "Votre adresse email est incorrect");
            } else if(preg_match('/\s/', trim($POST['login']))) {
				$arr = array(false, "Votre login ne doit pas comporter d'espace");
			} else {
                $street = $POST['street'];
                $birthdate = $POST['year']."-".$POST['month']."-".$POST['day'];
                //Creation de la position Lat/Lon
                $city = new city($this->mysql);
                $c = $city->getPosition($POST['street'], $POST['zipcode'], $POST['cityname']);
                $select = $this->mysql->prepare("INSERT INTO `users` (`ID`, `Login`, `Password`, `Email`, `Created`, `Avatar`, `LastName`, `FirstName`, `Gender`, `Birthdate`, `Street`, `ZipCode`, `City`, `Lat`, `Lon`, `Phone`, `Desc`, `Tags`, `Admin`, `MailOption`, `Validation`) VALUES (NULL, :login, :password, :email, CURRENT_TIMESTAMP, :avatar, :lastname, :firstname, :gender, :birthdate, :street, :zipcode, :city, :lat, :lon, :phone, '', '', '0', '0', '0');");

                $replace = array(":login" => strtolower($POST['login']),
                    ":password" => md5(trim($POST['password'])), 
                    ":email" => strtolower(trim($POST['email'])), 
                    ":lastname" => ucfirst(trim($POST['lastname'])), 
                    ":firstname" => ucfirst(trim($POST['firstname'])), 
                    ":gender" => strtoupper($POST['gender']),
                    ":birthdate" => $birthdate,
                    ":street" => $street,
                    ":zipcode" => $POST['zipcode'],
                    ":city" => $POST['cityname'],
                    ":lat" => $c['lat'],
                    ":lon" => $c['lon'],
                    ":phone" => trim($POST['phone']),
                    ":avatar" => "img/user/".strtoupper($POST['gender']).".jpg"
                );
                if(!$select->execute($replace)) {
                    $arr = array(false);
                } else {
                    //SEND MAIL VALIDATION
                    $mail = new mailer();
                    $hash = $this->make_link_validation($replace[':email'], $replace[':login']);
                    $mail->send_validation($replace[':email'], $replace[':login'], $replace[':firstname']." ".$replace[':lastname'], $hash);
                    $arr = array(true);
                }
            }
            return $arr;
            } 
            //CREATION DU LIEN DE VALIDATION
            function make_link_validation($email, $login) {
                $email = strtolower($email);
                $login = strtolower($login);
                $c_login = md5($login);
                $c_email = base64_encode($email);
                $hash = base64_encode($c_login."-==-".$c_email);
                return $hash;
            }
            //VERIFICATION SI LOGIN EXISTE DANS BDD
            function issetLogin($login, $id) {
                $arr = array (false, false);
                $select = $this->mysql->prepare("SELECT COUNT(*) AS `total` FROM `users` WHERE `Login` = :login");
                $select->execute(array(":login" => $login));
                $data = $select->fetch(PDO::FETCH_OBJ);
                if($id == false) {
                    if($data->total > 0) {
                        $arr = false;
                    } else {
                        $arr = true;    
                    }
                } else {
                    if($data->total > 0) {
                        $arr = array($id, false);
                    } else {
                        $arr = array($id, true);    
                    }
                }
                return $arr;
        }
        //VERIFICATION SI EMAIL EXISTE DANS BDD
        function issetEmail($email, $id) {
            $select = $this->mysql->prepare("SELECT COUNT(*) AS `total` FROM `users` WHERE `Email` = :email");
            $select->execute(array(":email" => $email));
            $data = $select->fetch(PDO::FETCH_OBJ);
            if($id == false) {
                if($data->total > 0) {
                    $arr = false;
                } else {
                    $arr = true;    
                }    

            } else {
                if($data->total > 0) {
                    $arr = array($id, false);
                } else {
                    $arr = array($id, true);    
                }    
            }
            return $arr;
        }
        //VERIFICATION SI CODE POSTALE EXISTE DANS BDD
        function issetZipCode($zipcode, $id) {
            $arr = array($id, true, array());
            $select = $this->mysql->prepare("SELECT `ID`,`Real_Name` FROM `french_city` WHERE `ZipCode` = :zipcode");
            $select->execute(array(":zipcode" => $zipcode));
            $total = $select->rowCount();
            while($data = $select->fetch(PDO::FETCH_OBJ)) {
                if($total == 1) {
                    $arr = array($id, true, $data->Real_Name);
                } else if($total > 1) {
                    array_push($arr[2], $data->Real_Name);
                }
            }
            if($total == 0) {
                $arr = array($id, false, "Ville inconnu");
            }
            return $arr;
        }
        //ENVOIE MAIL MOT DE PASSE PERDU
        function remind_mail($POST) {
            $arr = array();
            $mail = strtolower(trim($POST['email']));
            $select = $this->mysql->prepare("SELECT `Validation`, `Login`, `FirstName`, `LastName`, `Password`, `Email`, `ID`, COUNT(*) AS `total` FROM `users` WHERE `Email` = :email");
            $select->execute(array(":email" => $mail));
            $data = $select->fetch(PDO::FETCH_OBJ);
            if($data->total == 1) {
                if($data->Validation == 1) {
                    $mail_ = new mailer();
                    $hash = $this->crypt_remind($mail, $data->ID, $data->Password);
                    $arr = $mail_->send_remind($hash, $mail, $data->Login, ucfirst($data->FirstName)." ".ucfirst($data->LastName));
                } else {
                    $arr = array(false, "Ce compte n'a pas été validé via votre boite mail.");
                }
            } else {
                $arr = array(false, "Cette adresse email n'est pas relié à un compte.");
            }
            return $arr;
        }
        //MODIFICATION MOT DE PASSE PERDU
        function remind_account($POST) {
            $arr = array();
            if(empty_($POST['hash'])) {
                $arr = array(false, "Votre lien de changement de mot de passe est incorrect");
            } else if(empty_($POST['password'])) {
                $arr = array(false, "Le champs Mot de passe est requis");
            } else if(empty_($POST['password_r'])) {
                $arr = array(false, "Le champs Retaper mot de passe est requis");
            } else if(trim($POST['password_r']) != trim($POST['password'])) {
                $arr = array(false, "Les mots de passes ne sont pas identiques");
            } else if(strlen(trim($POST['password']))) {
                $arr = array(false, "Votre mot de passe doit contenir au moins 6 caractères");
            } else {
                $info = $this->uncrypt_remind(trim($POST['hash']));
                $select = $this->mysql->prepare("UPDATE `users` SET `Password` = :password WHERE `ID` = :ID AND `Email` = :email");
                if($select->execute(array(":password" => md5(trim($POST['password'])), ":ID" => $info[0], ":email" => $info[1]))) {
                    $arr = array(true);
                } else {
                    $arr = array(false, "Une erreur à eu lieu au moment du changement du mot de passe... Veuillez réessayer.");
                }
            }
            return $arr;    
        }
        //POUR EVITER QUE LE MEME LIEN DE MOT DE PASSE PERDU SERVE A L'INFINI
        function prevent_ex_remind($id, $pass, $time) {
            $arr = array(false);
            if($time <= strtotime("-1 week")) {
                $arr = array(false, "Désolé, mais ce lien avait une validé limité à 1 semaine...<br>Veuillez re-faire une demande de mot de passe perdu.");
            } else {
                $select = $this->mysql->prepare("SELECT `Password` FROM `users` WHERE `ID` = '".$id."'");
                $select->execute();    
                $data = $select->fetch(PDO::FETCH_OBJ);
                if($data->Password == $pass) {
                    $arr = array(true, "");
                } else {
                    $arr = array(false, "Désolé, mais ce lien a déjà servis pour la modification de votre mot de passe.");
                }
            }
            return $arr;
        }
        //UPLOAD AVATAR
        function change_avatar($file) {
            $arr = array(false,"Une erreur a eu lieu...");
            $handle = new Upload($file);
            $list = array("facebook", "google", "twitter");
            if ($handle->uploaded) {
                $handle->image_resize            = true;
                $handle->image_x                 = 130;
                $handle->image_y                 = 130;
                $handle->image_ratio_crop      = true;
                $dir_dest = '../img/user/upload/';
                $dir_pics = 'img/user/upload/';
                $handle->Process($dir_dest);
        
                if ($handle->processed) {
                    $arr = array(true, ''.$dir_pics.'' . $handle->file_dst_name . '');
                    //
                    if(!preg_match("/(user\/M|user\/F)\.jpg/", $this->avatar)) {
                        unlink("../".$this->avatar);
                        for($i=0;$i<count($list);$i++) {
                            if($list[$i] == "google") {
                                $w = $h = 190;
                            }
                            if($list[$i] == "facebook") {
                                $w = $h = 210;
                            }
                            if($list[$i] == "twitter") {
                                $w = $h = 180;
                            }
                            @unlink("../".preg_replace("/img\/user\/upload\//", "img/social/".$list[$i]."/user/", $this->avatar));
                            $handle_ = new Upload($file);
                            if ($handle_->uploaded) {
                                $handle_->image_resize            = true;
                                $handle_->image_x                 = $h;
                                $handle_->image_y                 = $w;
                                $handle_->image_ratio_crop      = true;
                                $dir_dest_ = '../img/social/'.$list[$i].'/user/';
                                $handle_->Process($dir_dest_);
                            }
                        }
                    }
                    $up = $this->mysql->prepare("UPDATE `users` SET `avatar` = '".$dir_pics.'' . $handle->file_dst_name . "' WHERE `ID` = '".$this->ID."'");
                    $up->execute();
                } else {
                    $arr = array(false, 'Une erreur a eu lieu...');
                    
                }
            }
            return $arr;
        } 
        //RECUPERATION DU NOM COMPLET
        function get_name($id) {
            $name = "";
            $select = $this->mysql->prepare("SELECT `FirstName`, `LastName` FROM `users` WHERE `ID`= :id");
            $select->execute(array(":id" => $id));
            $data = $select->fetch(PDO::FETCH_OBJ);
            $name = ucfirst($data->FirstName)." ".ucfirst($data->LastName);
            return $name;
        }
        //AFFICHAGE DU STATUT DES RDV
        function state_m($state,$name,$whoami) {
            $ret = "";
            if($whoami == 0) {
                $b = " votre";
                $c = "";    
            } else {
                $b = "";
                $c = " de ".$name;    
            }
            if($state == "1") {
                $ret = "En attente du rendez-vous.";    
            }
            if($state == "2") {
                $ret = "En attente de".$b." confirmation que le rendez-vous a eu lieu".$c.".";    
            }
            if($state == "3") {
                $ret = "En attente de".$b." confirmation que le rendez-vous a eu lieu".$c.".";    
            }
            if($state == "4") {
                $ret = "En attente de".$b." confirmation que le rendez-vous a eu lieu".$c.".";    
            }
            if($state == "5") {
                $ret = "Rendez-vous terminé et finalisé.";    
            }
            return $ret;
        }
        
        // #### HTML #### //
        
        //AFFICHAGE DE LA BAR DE MENU
        function navbar() {
            $html = '';
            if(!$this->logged) {
                if(preg_match("/inscription\.php/", $_SERVER['REQUEST_URI'])) {
                    $html .= '<li class="active"><a href="inscription.php">Inscription <span class="sr-only">(current)</span></a></li>';
                } else {
                    $html .= '<li><a href="inscription.php">Inscription</a></li>';
                }
             } 
             if(!$this->logged) {
                $html .= '<li class="dropdown">';
                 $html .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Connexion <span class="caret"></span></a>
                        <div class="dropdown-menu login-menu">
                            <div id="login_section">
                                <form action="inc/login_.php" method="post" class="login_form">
                                    <span class="hidden_">Se connecter :</span>
                                    <input id="login_form_" name="login_form_" class="validate[required,minSize[5]] form-control" placeholder="Identifiant" type="text" size="30">
                                    <input type="password" id="password_form" name="password_form" placeholder="Mot de passe" class="validate[required,minSize[6]] form-control" size="30">
                                    <label class="string optional" for="remember_me">
                                        <input id="remember_me" type="checkbox" name="remember_me" checked> Se souvenir de moi
                                    </label>
                                    <input class="btn btn-primary" type="submit" name="commit" value="Se connecter">
                                    <div class="remind"><a class="remind_link">Mot de passe perdu ?</a></div>
                                </form>        
                            </div>
                            <div id="remind_section">
                                <form class="remind_form" action="inc/login_.php" method="post" accept-charset="UTF-8">
                                    <span class="hidden_">Mot de passe perdu :</span>
                                    <input id="user_username" placeholder="Email" class="form-control validate[required,email]" type="text" name="remind[email]" size="30">
                                    <input class="btn btn-primary" type="submit" name="commit" value="Recuperer">
                                    <div class="remind"><a class="remind_link">Je m\'en souviens !</a></div><div class="clear"></div>
                                </form>
                            </div>
                        </div>';
                 } else { 
                     $p_bl = array("profil", "messagerie", "rendez-vous", "proposition");
                    $fixed_n = array("false", "", "");
                    if(preg_grep("/".preg_replace("/(.*?)\.php.*/", "$1", basename($_SERVER['REQUEST_URI']))."/", $p_bl)) {
                        $fixed_n = array("true", " visible"," open");
                    }
                     $html .= '<li class="dropdown hf'.$fixed_n[2].'">';
                     $get = preg_replace("/.*?\.php(.*?)/", "$1", $_SERVER['REQUEST_URI']);
                     if(empty_($get)) {
                        $logout = "?logout"; 
                     } else {
                        $logout = "?".$get."&logout";     
                     }
                     $total_m = $this->list_messages();
                     if($total_m > 0) {
                         $message_name = '<span class="mess_count red">'.$total_m.'</span> ';
                         $message_list = '<span class="mess_count red">'.$total_m.'</span>';
                     } else {
                        $message_name = '';
                        $message_list = '<span class="mess_count">0</span>'; 
                     }
                     $logout = preg_replace("/\&\&/", "&", $logout);
                    $html .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="'.$fixed_n[0].'"><img src="'.$this->avatar.'" height="40" width="40"> '.$this->firstname.' '.$message_name.'<span class="caret"></span></a>
                            <ul class="dropdown-menu nav-h'.$fixed_n[1].'"><!--
                                --><li><a href="profil.php">Mon profil</a></li><!--
                                --><li><a href="proposition.php">Mes propositions</a></li><!--
                                --><li><a href="rendez-vous.php">Mes rendez-vous</a></li><!--
                                --><li><a href="messagerie.php">Messagerie '.$message_list.'</a></li><!--
                                --><li><a href="'.$logout.'">Se déconnecter</a></li><!--
                            --></ul>';
                            if($fixed_n[0] == "true") {
                                $html = preg_replace('/\<li\>\<a href\=\"'.basename($_SERVER['PHP_SELF']).'/', '<li class="active"><a href="'.basename($_SERVER['PHP_SELF']).'', $html);    
                            }
                } 
            $html .= "</li>";
            return $html;
        }
        //RECUPERATION LISTE DES COMMENTAIRES
        function list_com($for="",$limit=true) {
            if($limit != true) {
                $lim =    ' LIMIT 0, 7';
            } else {
                $lim = '';
            }
            if($for == "") {
                $ad = " ORDER BY `notations`.`Date` DESC".$lim;    
            } else {
                $ad = " AND `Service` = '".$for."' ORDER BY `notations`.`Date` DESC".$lim;
            }
            $html = '';
            $select = $this->mysql->prepare("SELECT `users`.`ID` AS `UserID`, `users`.`LastName`, `users`.`FirstName`, `services`.`ID`, `notations`.`Date`, `services`.`Title`, `type`.`Name`, `notations`.`Message`, `notations`.`Note` FROM `notations` INNER JOIN `users` ON `notations`.`By` = `users`.`ID` INNER JOIN `services` ON `notations`.`Service` = `services`.`ID` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` WHERE `Owner_Service` = '".$this->ID."'".$ad);
            $select->execute();
            $i = 0;
            $class = "col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1";
            if($limit == false) {
                $class = "";
            }
            while($data = $select->fetch(PDO::FETCH_OBJ)) {
                if($i<6) {
                $title = $com = "";
                $title = $data->Title;
                $com = $data->Message;
                if($com == "") {
                    $com = "Pas de commentaire...";
                } 
                if($title == "") {
                    $title = $data->Name;
                }
                $html .= '<div class="'.$class.' note">';
                $html .= '<span class="title">Critique de <a href="profil-'.$data->UserID.'.php">'.ucfirst($data->FirstName).' '.ucfirst($data->LastName).'</a> ';
                if($for == "") {
                    $html .= 'pour <a href="annonce-'.$data->ID.'.php">'.$title.'</a>';
                }
                $html .= '</span>';
                $c = 20 * $data->Note;
                $html .= '<br><div title="'.$data->Note.' étoile(s)" class="star-rating rating-xs rating-active"><div class="rating-container rating-gly-star" data-content=""><div class="rating-stars" data-content="" style="width: '.$c.'%;"></div><input data-step="1" data-max="5" data-min="0" class="rating form-control hide" id="input-1"></div></div>';
                $html .= '<p>'.ucfirst($com).'</p>';
                $html .= '<i>le '.date("d/m/Y \à H:i", strtotime($data->Date)).'</i>';
                $html .= '<div class="clear"></div></div>';
                } else {
                    break;    
                }
                $i++;
            }
            if($html == "") {
                $html = "<div class='col-xs-12 black-text text-center'>Pas de notes & commentaires...</div>";
            } else {
            if($select->rowCount() == 7 && $limit == true) {
                $html .= '<center><a class="open-all-com col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1">Voir tous les commentaires</a></center>';
            }
            }
            return $html;
        }
        //AFFICHAGE DE LA LISTE DES RENDEZ-VOUS
        function list_rdv($past) {
            if($past == true) {
                $sh = "= '1'";    
            } else {
                $sh = "> 1";    
            }
            if(file_exists("inc/chat.php")) {
                @require_once("inc/chat.php");    
                } else if(file_exists("chat.php")) {
                @require_once("chat.php");    
                } else if(file_exists("../inc/chat.php")) {
                @require_once("../inc/chat.php");
                }
                $this->chat_ = new chat($this->mysql, $this);
                $month = date("m", strtotime($date));
            $year = date("Y", strtotime($date));
            $day = date("d", strtotime($date));
            $html = '';
            $select = $this->mysql->prepare("SELECT * FROM `appointment` WHERE (`User` = :id OR `Owner_Service` = :id) AND `State` ".$sh."");
            $select->execute(array( ":id" => $this->ID));
            while($data = $select->fetch(PDO::FETCH_OBJ)) {
                $serv = $this->chat_->service_title($data->Service);
                $other = $data->User; 
                $whoask = 'C\'est vous qui proposez ce service.';
                $who = 1;
                if($other == $this->ID) {
                    $other = $data->Owner_Service;
                    $whoask = 'Vous avez demandez ce service.';
                    $who = 0;
                }
                $ss = $this->mysql->prepare("SELECT `french_city`.`Real_Name` FROM `services` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` WHERE `services`.`ID` = '".$data->Service."'");
                $ss->execute();
                $city = $ss->fetch(PDO::FETCH_OBJ);
                $nom = $this->get_name($other);
                $state = $this->state_m($data->State,$nom,$who);
                if($data->State < 5) {
                    $conv = '<a href="messagerie.php#select-'.$this->chat_->isset_conversation($other, $data->Service).'">'.$state.'</a>';
                } else {
                    $conv = $state;
                }
                $this->state_m($data->Service, $this->ID, $other);
                $html .= '<tr><td ><a href="annonce-'.$data->Service.'.php">'.$serv.'</a><br><i>'.$whoask.'</i></td><td> avec <a href="profil-'.$other.'.php">'.$nom.'</a><br><i>'.$conv.'</i></td><td>'.date("d/m/Y \à H:i", strtotime($data->Date)).'</td></tr>';
            }
            if($html == "") {
                $html = "<tr><td class='nordv' colspan='3'><center>Pas de rendez-vous...</center></td></tr>";    
            }
            return $html;
        }
        //AFFICHAGE CONTENU MODAL CALENDRIER DYNAMIQUE
        function list_mod_cal($date) {
            if(file_exists("inc/chat.php")) {
                @require_once("inc/chat.php");    
                } else if(file_exists("chat.php")) {
                @require_once("chat.php");    
                } else if(file_exists("../inc/chat.php")) {
                @require_once("../inc/chat.php");
                }
                $this->chat_ = new chat($this->mysql,$this);
            $month = date("m", strtotime($date));
            $year = date("Y", strtotime($date));
            $day = date("d", strtotime($date));
            $html = '';
            $select = $this->mysql->prepare("SELECT * FROM `appointment` WHERE EXTRACT(MONTH FROM `Date`) = :month AND EXTRACT(DAY FROM `Date`) = :day AND EXTRACT(YEAR FROM `Date`) = :year AND (`User` = :id OR `Owner_Service` = :id) AND `State` > 0");
            $select->execute(array(":month" => $month, ":day" => $day, ":year" => $year, ":id" => $this->ID));
            while($data = $select->fetch(PDO::FETCH_OBJ)) {
                $serv = $this->chat_->service_title($data->Service);
                $other = $data->User; 
                $whoask = 'C\'est vous qui proposez ce service.';
                $who = 1;
                if($other == $this->ID) {
                    $other = $data->Owner_Service;
                    $whoask = 'Vous avez demandez ce service.';
                    $who = 0;
                }
                $nom = $this->get_name($other);
                $state = $this->state_m($data->State,$nom,$who);
                $conv = $this->chat_->isset_conversation($other, $data->Service);
                $this->state_m($data->Service, $this->ID, $other);
                if($html != "") {
                    $html .= "<hr>";
                }
                $html .= 'Le '.date("d/m/Y \à H:i", strtotime($data->Date))." avec <a href='profil-".$other.".php'>".$nom."</a> pour <a href='annonce-".$data->Service.".php'>".$serv."</a><br><i>".$whoask."</i><br><b>Statut :</b> ".$state." - <a href='messagerie.php#select-".$conv."'>Voir la conversation</a>";
            }
            return $html;
        }
        //CREATION DU JSON POUR LE CALENDRIER DYNAMIQUE
        function json_calendar($year, $month) {
            $arr = array();
            if($month<10) {
                $month = "0".$month;    
            }
            
            $select = $this->mysql->prepare("SELECT * FROM `appointment` WHERE EXTRACT(MONTH FROM `Date`) = :month AND EXTRACT(YEAR FROM `Date`) = :year AND (`User` = :id OR `Owner_Service` = :id) AND `State` > 0");
            $select->execute(array(":month" => $month, ":year" => $year, ":id" => $this->ID));
            while($data = $select->fetch(PDO::FETCH_OBJ)) {
                $arr[] = array("date"=> date("Y-m-d", strtotime($data->Date)), "badge" => false, "title" => "Mes rendez-vous pour le ".date("d/m/Y", strtotime($data->Date)), "body"=>$this->list_mod_cal($data->Date));    
            }
            return $arr;
        }
        //LISTE BADGE ET SERVICES
        function listing_badge_s() {
            $list = array();
            $html = "";
            $htm = array();
            $select = $this->mysql->prepare("SELECT `categories`.`ID` AS `CatID`, `categories`.`Name`, `services`.`ID`, `services`.`Title`, `type`.`Name` AS `TypeName` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` WHERE `By` = '".$this->ID."' ORDER BY `categories`.`ID` ASC , `services`.`Created` DESC");
            $select->execute();
            while($data = $select->fetch(PDO::FETCH_OBJ)) {
                if(!in_array($data->CatID, $list)) {
                    $list[] = $data->CatID;    
                    $html .= '<div title="Voir les annonces pour ce type de service : '.$data->Name.'" data-id="'.$data->CatID.'" class="badge_"><img width="85" height="85" src="img/services/round/'.$data->CatID.'.png" alt="'.$data->Name.'" ><div class="inner_b">'.$data->Name.'</div></div>';
                    $htm[$data->CatID.""] = '';
                }
                $title = $data->Title;
                if($title == "") {
                    $title = $data->TypeName;
                }
                $htm[$data->CatID.""] .= '- <a title="Voir ce service" href="annonce-'.$data->ID.'.php">'.ucfirst($title).'</a><br>';
            }
            foreach(array_keys($htm) as $key){
                $html .= '<div class="listing-s" data-s="'.$key.'">'.$htm[$key].'</div>';
            }
            if($html == "") {
                $html = '<div class="black-text text-center nobadge">Cet utilisateur ne propose pas de service...</div>';    
            }
            return $html;
        }
        //AFFICHAGE MODAL DE REDIRECTION CAR PAR ACCESS A LA PAGE
        function modal_location_c($GET) {
            $title = "";
            $text = "";
            $extra = '';
            if(isset($_GET['needunlogged']) && $this->logged) {
                $title = "Se déconnecter pour y accéder";
                $text = "Désolé, mais la page à laquelle vous souhaitiez accéder n'est pas accessible en tant qu'utilisateur connecté.<br><br>Veuillez vous déconnecté pour l'afficher.";
            }
            if(isset($_GET['unlogged']) && !$this->logged) {
                $title = "Se connecter pour y accéder";
                $text = "Désolé, mais la page à laquelle vous souhaitiez accéder n'est pas accessible en tant que visiteur.<br><br>Veuillez vous inscrire/connecter pour l'afficher.<div id='clone_login'></div>";
                $extra = " $('#login_section').clone(true).appendTo('#clone_login');
            $('#remind_section').clone(true).appendTo('#clone_login');
            $(\"#clone_login .login_form\").append(\"<input type='hidden' name='to_url' value='".@basename(@$_GET['p'])."'>\");
            $('#clone_login').append('<a href=\"inscription.php\" class=\"hidden_ notsigned\">Pas encore inscrit ?</a><div class=\"clear\"></div>');";
            }
            if(isset($_GET['noadmin']) && $this->admin < 1) {
                $title = "Être administrateur pour y accéder";
                $text = "Désolé, mais la page à laquelle vous souhaitiez accéder est accessible uniquement pour les administrateurs du site.";
            }
            $html = '<div id="modal_alert" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Page inaccessible</h4>
      </div>
      <div class="modal-body">
      '.$text.'
      </div>
    </div>
  </div>
</div> <script>$(\'#modal_alert\').modal(\'show\');'.$extra.' $("#modal_alert").on("hidden.bs.modal", function(e) {
                $(this).remove();
            });</script>';
            if(!empty_($text)) {
                echo $html;
            }
        }
        //DECRYPTER TAGS VENANT DE LA BDD
        function tags_uncrypt($tags) {
            $html = "";
            $l = preg_split('/\,/', $tags);    
            for($i=0;$i<count($l);$i++) {
                $c = trim($l[$i]);
                if(!empty_($c)) {
                    $html .= "<span class='tag label label-info'>".ucfirst(strtolower($c))."</span>";
                }
            }
            if($html == "" && !empty_($tags)) {
                $html = "<span class='tag label label-info'>".ucfirst(strtolower($tags))."</span>";
            }
            return $html;
        }
        //VALIDATION COMPTE
        function validate_account($hash) {
            $html = "";
            $hash = base64_decode(trim($hash));
            $split = explode("-==-", $hash);
            $select = $this->mysql->prepare("SELECT `Validation`, `Login`, COUNT(*) AS `total` FROM `users` WHERE `Email` = :email");
            $select->execute(array(":email" => base64_decode($split[1])));
            $data = $select->fetch(PDO::FETCH_OBJ);
            if($data->total > 0) {
                if(md5($data->Login) == $split[0]) {
                     if($data->Validation == 0) {
                        $select = $this->mysql->prepare("UPDATE `users` SET `Validation` = '1' WHERE `Email` = :email");
                        if(!$select->execute(array(":email" => base64_decode($split[1])))) {
                            $html = 'Désolé, une erreur à eu lieu au moment de l\'activation.<br> Veuillez réessayer plus tard...';
                        } else {
                            $html = 'Vous êtes bien enregistré comme nouvel utilisateur de Swappy. À vous d\'échanger !';
                        }
                    } else {
                        $html = 'Votre compte à déjà été activé !';    
                    }
                } else {
                    $html = 'Désolé, il semblerait que le lien soit incorrecte.<br> Veuillez vous inscrire à nouveau.';    
                }
            } else {
                $html = 'Désolé, il semblerait que le lien soit incorrecte.<br> Veuillez vous inscrire à nouveau.';    
            }
            return $html;
            
        }
        function inscription_page() {
            if(isset($_GET['remind'])) { 
                $mm = $this->uncrypt_remind($_GET['remind']);
                $cc = $this->prevent_ex_remind($mm[0], $mm[2], $mm[3]);
                if($cc[0] == false) {
                    echo '<div id="user_add" class="col-md-6 col-md-offset-3">
               <div colspan="2" class="header_inscription">Changement du mot de passe</div>
               <div class="greyback change_mdp text-center">
                  <p>'.$cc[1].'</p>
               </div>
            </div>';
                } else {
                    return include("inc/html/remind.php");
                }
            } else if(isset($_GET['validation'])) {
                echo '<div id="user_add" class="col-md-6 col-md-offset-3">
               <div colspan="2" class="header_inscription">Validation d\'inscription</div>
               <div class="greyback change_mdp text-center">
                  '.$this->validate_account($_GET['validation']).'
               </div>
            </div>';
            } else {
                $page_ = new page();
                return include("inc/html/inscription.php");
            }
        }
        function profil_page($user) {
            $user_ = $this;
            if(isset($_GET['id'])) { 
                return include("inc/html/profil-o.php");
            } else {
                $page_ = new page();
                return include("inc/html/profil.php");
            }
        }
        function button($user) {
            if(isset($_GET['id']) && $_GET['id'] != $user ) {
                $html = '<div class="text-left xs-center">
                            <p class="btn talk-button">Envoyer un message</p>
                        </div>
                        <div class="text-left report_div xs-center">
                            <p class="btn report-button">Signaler ce profil</p>
                        </div>';
             } else if(isset($_GET['id']) && $_GET['id'] == $user) {
                $html = '<div class="text-left xs-center">
                            <a href="profil.php" class="btn edit-button">Modifier mon profil</a>
                        </div>';
            }
            return $html;    
        }
        //LISTE DES SERVICES DE L'UTILISATEURS
        function list_services_edit() {
            $html = "";
            $return = array();
            $select = $this->mysql->prepare("SELECT `services`.`ID`, `categories`.`ID` AS `CatType`, `services`.`Title`, `services`.`Type`, `type`.`Name` AS `TypeName`, `services`.`By`, `services`.`Description`, `services`.`Distance`, `services`.`Disponibility`, `services`.`Created`, `services`.`City`, `services`.`Lat`, `services`.`Lon`, `french_city`.`Real_Name` AS `CityName` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` INNER JOIN `categories` ON `type`.`Categorie` = `categories`.`ID` INNER JOIN `french_city` ON `services`.`City` = `french_city`.`ID` INNER JOIN `users` ON `services`.`By` = `users`.`ID` WHERE `users`.`ID` = :ID ORDER BY `services`.`Created` DESC");
            $select->execute(array(":ID" => $this->ID));
            while($data = $select->fetch(PDO::FETCH_OBJ)) {
                $id = $data->ID;
                if(empty_($data->Title)) {
                    $title = $data->TypeName;
                } else {
                    $title = $data->Title;
                }
                $html .= '<tr class="bloc_services" data-ids="'.$data->ID.'">
                              <td class="picto"><a title="'.$title.'" href="annonce-'.$data->ID.'.php">
                                <img alt="" class="fullfit" src="img/services/'.$data->CatType.'.jpg">
                            </a></td>
                              <td class="desc_services">
                                <a title="'.$title.'" href="annonce-'.$data->ID.'.php">
                                    <div class="fullfit">
                                        <h1 class="serv_title">'.$title.'</h1>
                                        <p>
                                            '.$data->Description.'
                                        </p>
                                        <div class="location">'.$data->CityName.'</div>
                                    </div>
                                </a>
                              </td>
                              <td class="delete">
                                <a title="Supprimer ce service" class="delete_serv" data-id="'.$data->ID.'" href="#">
                                      <img src="img/proposition/delete.png" alt="" width="25">
                                </a>
                              </td>
                              <td class="edit">
                                <a title="Modifier ce service" href="propose.php?edit='.$data->ID.'">
                                      <img src="img/proposition/edit.png" alt="" width="25">
                                </a>
                              </td>
                        </tr>';
                    
                     //FIN
            }
            if(empty_($html)) {
                $html .= '<tr class="bloc_services">
                              <td colspan="4"><center>Vous n\'avez pas de services</center></td>
                          </tr>';

            }
            return $html;
        }
    }  ?>