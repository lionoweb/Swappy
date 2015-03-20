<?php
/*
	Conversation : HiddenFor
	0 -> Visible for all
	1 -> Hidded for user_one
	2 -> hidded for user_two
	3 -> Hidded for all --> Delete
	------------------------------
	Conversation_reply : HiddenFor
	0 -> Visible for all
	1 -> Hidded for user_one
	2 -> hidded for user_two
	3 -> Hidded for all --> Delete
	ServiceFor :
	0 --> Just Talk
*/
	class chat {
		private $mysql;
		private $user;
		function __construct($mysql, $user) {
			$this->mysql = $mysql;
			$this->user = $user;
		}
		function getHidden($id) {
			$return = array("MasterH"=>"false");
			$select = $this->mysql->prepare("SELECT `conversation`.`HiddenFor` AS `MasterH`, `conversation_reply`.`HiddenFor` AS `Hidden`, `conversation_reply`.`ID` AS `ID` FROM `conversation` INNER JOIN `conversation_reply` ON `conversation`.`ID` = `conversation_reply`.`C_ID` WHERE (`conversation`.`User_One` = :me OR `conversation`.`User_Two` = :me) AND `conversation`.`ID` = :id");
			$select->execute(array(":me" => $this->user->ID, ":id" => $id));
			$i = 0;
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				if($i == 0) {
					$return["MasterH"] = $data->MasterH;
				}
				$return[''.$data->ID] = "".$data->Hidden;
				$i++;
			}
			return $return;
		}
		function getstatus($id) {
			$return = "null";
			$select = $this->mysql->prepare("SELECT `Status` FROM `conversation` WHERE (`User_One` = :me OR `User_Two` = :me) AND `ID` = :id");
			$select->execute(array(":me" => $this->user->ID, ":id" => $id));
			$data = $select->fetch(PDO::FETCH_OBJ);
			$return = $data->Status;
			return $return;
		}
		function who_ami($id) {
			$return = "null";
			$select = $this->mysql->prepare("SELECT `User_One`, `User_Two` FROM `conversation` WHERE (`User_One` = :me OR `User_Two` = :me) AND `ID` = :id");
			
			$select->execute(array(":me" => $this->user->ID, ":id" => $id));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->User_One == $this->user->ID && $data->User_Two != $this->user->ID) {
				$return = "One";
			}
			if($data->User_One != $this->user->ID && $data->User_Two == $this->user->ID) {
				$return = "Two";
			}
			return $return;	
		}
		function who_iso($id) {
			$return = "null";
			$select = $this->mysql->prepare("SELECT `User_One`, `User_Two` FROM `conversation` WHERE (`User_One` = :me OR `User_Two` = :me) AND `ID` = :id");
			
			$select->execute(array(":me" => $this->user->ID, ":id" => $id));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->User_One == $this->user->ID && $data->User_Two != $this->user->ID) {
				$return = $data->User_Two;
			}
			if($data->User_One != $this->user->ID && $data->User_Two == $this->user->ID) {
				$return = $data->User_One;
			}
			return $return;	
		}
		function who_isserv($id) {
			$select = $this->mysql->query("SELECT `By` FROM `services` WHERE `ID` = '".$id."'");
			$data = $select->fetch(PDO::FETCH_OBJ);
			return $data->By;
		}
		function isset_conversation_id($id) {
			$t = false;
			$select = $this->mysql->prepare("SELECT `ID`, COUNT(*) AS `total` FROM `conversation` WHERE (`User_One` = :me OR `User_Two` = :me) AND `ID` = :id");
			$select->execute(array(":me" => $this->user->ID, ":id" => $id));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->total > 0) {
				$t = $data->ID;
			} else {
				$t = false;
			}
			return $t;	
		}
		function isset_conversation($id, $for) {
			$t = false;
			$select = $this->mysql->prepare("SELECT `ID`, COUNT(*) AS `total` FROM `conversation` WHERE ((`User_One` = :me AND `User_Two` = :id) OR (`User_Two` = :me AND `User_One` = :id)) AND `ServiceFor` = :for");
			$select->execute(array(":me" => $this->user->ID, ":id" => $id, ":for" => $for));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->total > 0) {
				$t = $data->ID;
			} else {
				$t = false;
			}
			return $t;	
		}
		function has_voted($id, $owner) {
			$select = $this->mysql->prepare("SELECT COUNT(*) AS `nb` FROM `notations` WHERE `By` = :id AND `Service` = :serv AND `Owner_Service` = :oserv ");
			$select->execute(array(":id" => $this->user->ID, ":serv" => $id, ":oserv" => $owner));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->nb < 1) {
				return false;
			} else {
				return true;
			}
		}
		function make_conversation($id, $for) {
			$i = 0;
			$status = 0;
			if($for == 0) {
				$status = 3;
			}
			$select = $this->mysql->prepare("INSERT INTO `conversation` (`ID`, `User_One`, `User_Two`, `ServiceFor`, `Timestamp`, `Status`, `HiddenFor`) VALUES (NULL, :me, :id, :for, :time, :status, '0');");
			$select->execute(array(":me" => $this->user->ID, ":id" => $id, ":for" => $for, ":time" => time(), ":status" => $status));
			$last_id = $this->mysql->lastInsertId();
			if(!empty($last_id) && $last_id) {
				$i = $last_id;	
			}
			return $i;	
		}
		function send_reply($message, $id, $bot=false) {
			$r = false;
			$me = $this->user->ID;
			$bott = "0";
			if($bot != false) {
				$me = 0;
				$bott = "'".$bot."'";
			}
			if(!empty($message)) {
				$l = $this->getHidden($id);
				$who = $this->who_ami($id);
				if($l['MasterH'] != 0) {
					if($l['MasterH'] == 2 && $who == "Two") {
						$this->mysql->query("UPDATE `conversation` SET `HiddenFor` = '0' WHERE `ID` = '".$id."'");
					} 
					if($l['MasterH'] == 1 && $who == "One") {
						$this->mysql->query("UPDATE `conversation` SET `HiddenFor` = '0' WHERE `ID` = '".$id."'");
					} 
				}
				$time = time();
				$select = $this->mysql->prepare("INSERT INTO `conversation_reply` (`ID`, `C_ID`, `Author`, `Time`, `Message`, `Seen`, `HiddenFor`, `BotTo`) VALUES (NULL, :conversation, :me, :time, :message, '0', '0', ".$bott.");");
				if($select->execute(array(":me" => $me, ":conversation" => $id, ":message" => $message, ":time" => $time))) {
					$r = true;
					$other_m =  $this->who_iso($id);
					if($bot != false) {
						$other_m = $bot;
					}
					if($bot != $this->user->ID) {
						$uu = new user($this->mysql, $other_m);
						if($uu->mailoption == 1) {
						if(file_exists("inc/mail.php")) {
						@require_once("inc/mail.php");	
					} else if(file_exists("mail.php")) {
						@require_once("mail.php");	
					} else if(file_exists("../inc/mail.php")) {
						@require_once("../inc/mail.php");
					}
						$mail = new mailer();
						@$mail->newmessage($uu->email,$time,$id,$uu->firstname." ".$uu->lastname, $this->user->firstname." ".$this->user->lastname, $this->user->ID);
					}
					}
				}
			}
			return $r;
		}
		function service_title($id) {
			$title = "";
			if($id > 0) {
				$select = $this->mysql->prepare("SELECT `services`.`Title`, `type`.`Name` FROM `services` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` WHERE `services`.`ID`= :id");
				$select->execute(array(":id" => $id));
				$data = $select->fetch(PDO::FETCH_OBJ);
				if(empty($data->Title)) {
					$title = $data->Name;	
				} else {
					$title = $data->Title;
				}
			} else {
				$title = ucfirst("discuter");	
			}
			return $title;
		}
		function set_read($id) {
			$select = $this->mysql->prepare("UPDATE `conversation_reply` INNER JOIN `conversation` ON `conversation_reply`.`C_ID` = `conversation`.`ID` SET `Seen` = '1' WHERE (`conversation`.`User_One` = :me OR `conversation`.`User_Two` = :me) AND `conversation_reply`.`Seen` = '0' AND `conversation_reply`.`Author` != :me AND `conversation`.`ID` = :id AND (`conversation_reply`.`BotTo` = '0' OR `conversation_reply`.`BotTo` = :me)");	
			$select->execute(array(":me" => $this->user->ID, ":id" => $id));
		}
		function unread_message($id) {
			$t = 0;
			$select = $this->mysql->prepare("SELECT `ID` FROM `conversation_reply` WHERE `Author` != :id AND `Seen` = '0' AND `C_ID` = :idc AND (`BotTo` = '0' OR `BotTo` = '".$this->user->ID."')");	
			$select->execute(array(":id" => $this->user->ID, ":idc" => $id));
			$t = $select->rowCount();
			return $t;	
		}
		function preg_accent($w) {
			if(preg_match("/E|É|È|Ê|Ë|e|é|è|ê|ë/", $w)) {
				$w = preg_replace("/E|É|È|Ê|Ë|e|é|è|ê|ë/","(E|É|È|Ê|Ë)", $w);	
			}
			if(preg_match("/A|À|Á|Â|Ä|a|à|á|â|ä/", $w)) {
				$w = preg_replace("/A|À|Á|Â|Ä|a|à|á|â|ä/","(A|À|Á|Â|Ä)", $w);	
			}
			if(preg_match("/C|Ç|c|ç/", $w)) {
				$w = preg_replace("/C|Ç|c|ç/","(C|Ç)", $w);	
			}
			return $w;
		}
		function clause_search($input) {
			$replace = array();
			$out = '';
			$where = '';
			$order = '';
			$input = preg_replace("/ |\-|\'/", "{}" , $input);
			$l = explode("{}", $input);
			for($i=0;$i<count($l);$i++) {
				$prpn = ":value".$i;
				$w = $this->preg_accent(strtoupper($l[$i]));
				if(strlen($w) > 1) {
					$replace[$prpn] = $w;
					$where .= ' OR (UPPER(`users`.`LastName`) REGEXP '.$prpn.') OR (UPPER(`users`.`FirstName`) REGEXP '.$prpn.') OR (UPPER(`services`.`Title`) REGEXP '.$prpn.') OR (UPPER(`type`.`Name`) REGEXP '.$prpn.')';
					$order .= ' + (CASE WHEN UPPER(`users`.`LastName`) REGEXP '.$prpn.' THEN 1.1 ELSE 0 END) + (CASE WHEN UPPER(`users`.`FirstName`) REGEXP '.$prpn.' THEN 1.2 ELSE 0 END) + (CASE WHEN UPPER(`services`.`Title`) REGEXP '.$prpn.' THEN 1.3 ELSE 0 END) + (CASE WHEN UPPER(`type`.`Name`) REGEXP '.$prpn.' THEN 1 ELSE 0 END)';
				}
			}
			if(!empty($where)) { $where = substr($where, 4, (strlen($where)-1)); }
			if(!empty($order)) { $order = substr($order, 3, (strlen($order))); }
			$out = '('.$where.') GROUP BY `type`.`ID`  ORDER BY '.$order. ' DESC, `conversation`.`Timestamp` DESC';
			return array($out, $replace);
		}
		function delete($id) {
			$delete = "";
			$update = "";
			$dlt = 0;
			$array = array(false);
			$user = $this->who_ami($id);
			$list = $this->getHidden($id);
			if($list['MasterH'] != "false") {
				$value = 0;
				$oppo = 0;
				if($user == "One") {
					$value = 2;
					$oppo = 1;
				} else if($user == "Two") {
					$value = 1;
					$oppo = 2;
				}
				$status = $this->getstatus($id);
				if($status == "0" || $status == "3") {
				while (($val = current($list)) !== FALSE) {
					if(key($list) == "MasterH") { 
						if($val == 0) {
							$this->mysql->query("UPDATE `conversation` SET `HiddenFor` = '".$value."' WHERE `ID` = '".$id."'");
						} else if($val == $oppo) {
							$dlt = 1;
							$this->mysql->query("DELETE FROM `conversation` WHERE `ID` = '".$id."'");
						}
					} else {
						if($val == 0 && $dlt == 0) {
							$this->mysql->query("UPDATE `conversation_reply` SET `HiddenFor` = '".$value."' WHERE `ID` = '".key($list) ."'");
						} else if($val == $oppo || $dlt == 1) {
							$this->mysql->query("DELETE FROM `conversation_reply` WHERE `ID` = '".key($list) ."'");
						}
					}
       				next($list);
				}
				$array = array(true);
				} else {
					$array = array(false,"Un rendez-vous est en cours... Vous ne pouvez pas effacer cette conversation");
				}
			} else {
				$array = array(false, "Vous ne semblez pas faire partie de cette conversation.");
			}
			return $array;
		}
		function list_message($search="") {
			$array = array();
			if($search == "") {
				$select = $this->mysql->prepare("SELECT `conversation`.`ID`, MAX(`conversation_reply`.`Time`) AS `LastTime`, `conversation`.`ServiceFor`, `conversation`.`HiddenFor`, `users`.`LastName`, `users`.`FirstName`, `users`.`ID` AS `UserID`, `conversation`.`Status` FROM `conversation` INNER JOIN `conversation_reply` ON `conversation`.`ID` = `conversation_reply`.`C_ID` INNER JOIN `users` ON CASE WHEN `conversation`.`User_One` != :me THEN `conversation`.`User_One` = `users`.`ID` ELSE `conversation`.`User_Two` = `users`.`ID` END WHERE (`conversation`.`User_One` = :me OR `conversation`.`User_Two` = :me) GROUP BY `conversation`.`ID` ORDER BY `LastTime` DESC, `conversation`.`Timestamp` DESC ");
				$select->execute(array(":me" => $this->user->ID));
			} else {
				$clause = $this->clause_search($search);
				$select = $this->mysql->prepare("SELECT `conversation`.`ID`, `conversation`.`ServiceFor`, `users`.`LastName`, `users`.`FirstName`, `conversation`.`HiddenFor`, `users`.`ID` AS `UserID`, `conversation`.`Status` FROM `conversation` INNER JOIN `users` ON CASE WHEN `conversation`.`User_One` != :me THEN `conversation`.`User_One` = `users`.`ID` ELSE `conversation`.`User_Two` = `users`.`ID` END INNER JOIN `services` ON `conversation`.`ServiceFor` = `services`.`ID` INNER JOIN `type` ON `services`.`Type` = `type`.`ID` WHERE (`conversation`.`User_One` = :me OR `conversation`.`User_Two` = :me) AND ".$clause[0]."");
				$clause[1][":me"] = $this->user->ID;
				$select->execute($clause[1]);
			}
			$i=0;
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
					$user = $this->who_ami($data->ID);
					$val = "";
					if($user == "Two") {
						$val = 1;
					} else if($user == "One") {
						$val = 2;
					}
					$serv="0";
					if($data->ServiceFor != 0) {
						$uu = $this->who_isserv($data->ServiceFor);
						if($uu == $this->user->ID) {
							$serv = "1";
						} else {
							$serv = "0";
						}
					}
					if($data->HiddenFor != $val) {
					$array[] = array("Name" => ucfirst($data->FirstName).' '.ucfirst($data->LastName), "For" => $data->ServiceFor, "Title" => $this->service_title($data->ServiceFor), "UserID" => $data->UserID, "ID" => $data->ID, "Status" =>$data->Status, "Count" => $this->unread_message($data->ID), "Button" => $serv);
				}
				$i++;
			}
			return $array;
		}
		function content_conv($id) {
			$array = array();
			$user = $this->who_ami($id);
			$and = "";
			if($user == "Two") {
				$and = " AND ( `HiddenFor` = '0' OR `HiddenFor` = '2' )";
			} else if($user == "One") {
				$and = " AND ( `HiddenFor` = '0' OR `HiddenFor` = '1' )";
			}
			$select = $this->mysql->prepare("SELECT * FROM `conversation_reply` WHERE `C_ID` = :id".$and." AND (`BotTo` = '0' OR `BotTo` = '".$this->user->ID."') ORDER BY `Time` ASC");
			$select->execute(array(":id" => $id));
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				$me = $data->Author;
				if($data->Author == $this->user->ID) {
					$me = "ME";
				} else if($data->Author == 0) {
					$me = "BOT";
				}
				$array[] = array("ID" => $data->ID, "Message" => $data->Message, "Author" => $me, "Time" => $data->Time, "TimeText" => "Le ".date("d/m/y \à H:i", $data->Time));
			}
			$this->set_read($id);
			$array["count"] = $this->user->list_messages();
			return $array;
		}
		function send_r($user, $POST) {
			$f_for = "";
			$allow = 0;
			$arr = array(false);
			if($this->user->logged) {
				if(!empty($user->ID) && $user->ID != false) {
					$allow = 1;
				} else {
					$allow = 0;
					$arr = array(false, "Destinataire inexistant");
				}
			} else {
				$allow = 0;
				$arr = array(false, "Vous n'êtes pas connecté");	
			}
			if($allow == 1) {
				$conver = $this->isset_conversation_id($POST['ID_Converse']);
				if($conver == false) {
					$arr = array(false, "Cette conversation n'existe pas");
				} else {
					if($this->who_ami($POST['ID_Converse']) != "null") {
						if($this->send_reply($POST['message_r'], $conver)) {
							$arr = array(true, $POST['message_r']);
						}
					} else {
						$arr = array(false, "Vous ne faites pas partie de cette conversation");
					}
				}
			}
			return $arr;	
		}
		function send($user, $POST, $for) {
			$f_for = "";
			$allow = 0;
			$arr = array(false);
			if($this->user->logged) {
				if(!empty($user->ID) && $user->ID != false) {
					if(gettype($for) == "object") {
						if(!empty($for->ID) && $for->ID != false) {
							if($user->ID == $for->by) {
								$allow = 1;
								$f_for = $for->ID;
							} else {
								$allow = 0;
								$arr = array(false, "Le service selectionné ne semble pas appartenir à l'utilisateur");
							}
						} else {
							$allow = 0;
							$arr = array(false, "Erreur dans la récupération des infos du service");
						}
					} else {
						if(empty($for)) {
							$allow = 0;
							$arr = array(false, "Erreur dans la récupération des infos du service");
						} else {
							$allow = 1;
							$f_for = $for;	
						}
					}
				} else {
					$allow = 0;
					$arr = array(false, "Destinataire inexistant");
				}
			} else {
				$allow = 0;
				$arr = array(false, "Vous n'êtes pas connecté");	
			}
			if($allow == 1) {
				$conver = $this->isset_conversation($user->ID, $f_for);
				if($conver == false) {
					$conver = $this->make_conversation($user->ID, $f_for);
				} 
				if($this->send_reply($POST['message'], $conver)) {
					$arr = array(true);
				}
			}
			return $arr;	
		}
		function serv_infos($id) {
			$select = $this->mysql->query("SELECT `services`.`By`, `services`.`ID` FROM `conversation` INNER JOIN `services` ON `conversation`.`ServiceFor` = `services`.`ID` WHERE `conversation`.`ID` = '".$id."'");
			$data = $select->fetch(PDO::FETCH_OBJ);
			$arr = array("By" => $data->By, "ID" => $data->ID);
			return $arr;
		}
		function ask_for_com($id, $cc, $user, $hash) {
			$mess = 'Nous espèrons que ce service fût satisfaisant.<br>Vous pouvez dès à présent noté l\'utilisateur ainsi que son service en <a href="annonce.php?vote='.$hash.'" class="note-this-date">cliquant ici</a>';
			$this->send_reply($mess, $cc, $user);
		}
		function make_vote_h($id, $date) {
			$h = base64_encode($id."///".$date);
			return $h;
		}
		function valid_a($id, $cc) {
			$a = false;
			$id = trim($id);
			$cc = trim($cc);
			$select = $this->mysql->prepare("SELECT *,COUNT(*) AS `nb` FROM `appointment` WHERE `ID` = :id");
			$select->execute(array(":id" => $id));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->nb > 0 && $this->isset_conversation_id($cc) != false) {
				$other = $this->who_iso($cc);
				$infos = $this->serv_infos($cc);
				if($other_ == $infos["By"]) {
					$other = $this->user->ID;
				}
				if($data->State == 0) {
					$a = true;
					$ss = $this->mysql->prepare("UPDATE `appointment` SET `State` = '1' WHERE `ID` = :id");
					$ss->execute(array(":id" => $id));
					//SEND TO OTHER
					$mess = $this->user->login.' a accepté la date du rendez-vous.<br><i>Vous ne pouvez plus changer la date à moins d\'<a data-id="'.$id.'" class="refuse-this-date">Annuler</a>.</i>';
					$this->send_reply($mess, $cc, $other);
					//SEND TO ME
					$mess = 'Vous avez accepté la date du rendez-vous.<br><i>Vous pouvez toujours <a data-id="'.$id.'" class="refuse-this-date">Annuler</a> si vous le souhaitez.';
					$this->send_reply($mess, $cc, $this->user->ID);
					//EDIT STATUS
					$ss = $this->mysql->prepare("UPDATE `conversation` SET `Status` = '2' WHERE `ID` = :id");
					$ss->execute(array(":id" => $cc));
					//SEND MAIL
					if(file_exists("inc/mail.php")) {
						@require_once("inc/mail.php");	
					} else if(file_exists("mail.php")) {
						@require_once("mail.php");	
					} else if(file_exists("../inc/mail.php")) {
						@require_once("../inc/mail.php");
					}

						$mail = new mailer();
						$oo = new user($this->mysql, $data->Owner_Service);
						$mm = new user($this->mysql, $data->User);
						@$mail->send_validation_rdv($oo, $mm, $infos['ID'], $this->service_title($infos['ID']),$data->Date,$cc);
					}
				if($data->State == 2) {
					$a = true;
					$ss = $this->mysql->prepare("UPDATE `appointment` SET `State` = '4' WHERE `ID` = :id");
					$ss->execute(array(":id" => $id));
					//SEND TO OTHER
					$mess = $this->user->login.' a confirmé que le rendez-vous a eu lieu. Confirmer ?<br><a data-id="'.$id.'" class="valid-this-date">Oui</a>&nbsp;&nbsp;&nbsp;&nbsp;<a data-id="'.$id.'" class="refuse-this-date">Non</a>';
					$this->send_reply($mess, $cc, $other);
					//SEND TO ME
					$mess = 'Vous avez confirmé que le rendez-vous a bien eu lieu.';
					$this->send_reply($mess, $cc, $this->user->ID);
					//EDIT STATUS
				}
				if($data->State == 3) {
					$a = true;
					$ss = $this->mysql->prepare("DELETE `appointment` WHERE `ID` = :id");
					$ss->execute(array(":id" => $id));
					//SEND TO OTHER
					$mess = $this->user->login.' a confirmé que le rendez-vous n\'a pas eu lieu.';
					$this->send_reply($mess, $cc, $other);
					//SEND TO ME
					$mess = 'Vous avez confirmé que le rendez-vous n\'a pas eu lieu.';
					$this->send_reply($mess, $cc, $this->user->ID);
					//EDIT STATUS
					$ss = $this->mysql->prepare("UPDATE `conversation` SET `Status` = '0' WHERE `ID` = :id");
					$ss->execute(array(":id" => $cc));
				}
				if($data->State == 4) {
					$a = true;
					$ss = $this->mysql->prepare("UPDATE `appointment` SET `State` = '5' WHERE `ID` = :id");
					$ss->execute(array(":id" => $id));
					//SEND TO OTHER
					$mess = $this->user->login.' a confirmé que le rendez-vous a bien eu lieu.';
					$this->send_reply($mess, $cc, $other);
					//SEND TO ME
					$mess = 'Vous avez confirmé que le rendez-vous a bien eu lieu.';
					$this->send_reply($mess, $cc, $this->user->ID);
					//EDIT STATUS
					$ss = $this->mysql->prepare("UPDATE `conversation` SET `Status` = '0' WHERE `ID` = :id");
					$ss->execute(array(":id" => $cc));
					if(!$this->has_voted($data->Service, $data->Owner_Service)) {
						$this->ask_for_com($id, $cc, $data->User, $this->make_vote_h($id, $data->Date));
					}
				}
			}
			return $a;
		}
		//State 2 : after rendez-vous
		//State 3 : pas fait
		//State 4 : fait 
		//State 5 : validé
		function refuse_a($id, $cc) {
			$a = false;
			$id = trim($id);
			$cc = trim($cc);
			$select = $this->mysql->prepare("SELECT *,COUNT(*) AS `nb` FROM `appointment` WHERE `ID` = :id");
			$select->execute(array(":id" => $id));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->nb > 0 && $this->isset_conversation_id($cc) != false) {
				$other = $this->who_iso($cc);
				$infos = $this->serv_infos($cc);
				if($other_ == $infos["By"]) {
					$other = $this->user->ID;
				}
				if($data->State == 0) {
					$a = true;
					$ss = $this->mysql->prepare("DELETE FROM `appointment` WHERE `ID` = :id");
					$ss->execute(array(":id" => $id));
					//SEND TO OTHER
					$mess = $this->user->login.' a refusé la date du rendez-vous.';
					$this->send_reply($mess, $cc, $other);
					//SEND TO ME
					$mess = 'Vous avez refusé le rendez-vous';
					$this->send_reply($mess, $cc, $this->user->ID);
					//EDIT STATUS
					$ss = $this->mysql->prepare("UPDATE `conversation` SET `Status` = '0' WHERE `ID` = :id");
					$ss->execute(array(":id" => $cc));
				}
				if($data->State == 1) {
					$a = true;
					$ss = $this->mysql->prepare("DELETE FROM `appointment` WHERE `ID` = :id");
					$ss->execute(array(":id" => $id));
					//SEND TO OTHER
					$mess = $this->user->login.' a annulé le rendez-vous.';
					$this->send_reply($mess, $cc, $other);
					//SEND TO ME
					$mess = 'Vous avez annulé le rendez-vous';
					$this->send_reply($mess, $cc, $this->user->ID);
					//EDIT STATUS
					$ssi = $this->mysql->prepare("UPDATE `conversation` SET `Status` = '0' WHERE `ID` = :id");
					$ssi->execute(array(":id" => $cc));
				}
				if($data->State == 2) {
					$a = true;
					//SEND TO OTHER
					$mess = $this->user->login.' a signalé que le rendez-vous n\'a pas eu lieu. Confirmer ?<br><a data-id="'.$id.'" class="valid-this-date">Oui</a>&nbsp;&nbsp;&nbsp;&nbsp;<a data-id="'.$id.'" class="refuse-this-date">Non</a>';
					$this->send_reply($mess, $cc, $other);
					//SEND TO ME
					$mess = 'Vous avez signalé que le rendez-vous n\'a pas eu lieu...';
					$this->send_reply($mess, $cc, $this->user->ID);
					//EDIT STATUS
					$ss = $this->mysql->prepare("UPDATE `appointment` SET `State` = '3' WHERE `ID` = :id");
					$ss->execute(array(":id" => $id));
				}
				if($data->State == 3) {
					$a = true;
					//SEND TO OTHER
					$mess = $this->user->login.' a signalé que le rendez-vous a bien eu lieu. Confirmer ?<br><a data-id="'.$id.'" class="valid-this-date">Oui</a>&nbsp;&nbsp;&nbsp;&nbsp;<a data-id="'.$id.'" class="refuse-this-date">Non</a>';
					$this->send_reply($mess, $cc, $other);
					//SEND TO ME
					$mess = 'Vous avez signalé que le rendez-vous a pourtant eu lieu...';
					$this->send_reply($mess, $cc, $this->user->ID);
					//EDIT STATUS
					$ss = $this->mysql->prepare("UPDATE `appointment` SET `State` = '4' WHERE `ID` = :id");
					$ss->execute(array(":id" => $id));
				}
				if($data->State == 4) {
					$a = true;
					//SEND TO OTHER
					$mess = $this->user->login.' a signalé que le rendez-vous n\'a pas eu lieu. Confirmer ?<br><a data-id="'.$id.'" class="valid-this-date">Oui</a>&nbsp;&nbsp;&nbsp;&nbsp;<a data-id="'.$id.'" class="refuse-this-date">Non</a>';
					$this->send_reply($mess, $cc, $other);
					//SEND TO ME
					$mess = 'Vous avez signalé que le rendez-vous n\'a pourtant pas eu lieu...';
					$this->send_reply($mess, $cc, $this->user->ID);
					//EDIT STATUS
					$ss = $this->mysql->prepare("UPDATE `appointment` SET `State` = '3' WHERE `ID` = :id");
					$ss->execute(array(":id" => $id));
				}
			}
			return $a;
		}
		function make_date($POST) {
			$arr = array(false);
			$id = $POST['ID_C'];
			$date = $POST['date'];
			$hour = $POST['hour'];
			$a_date = date("Y-m-d");
			$a_hour = date("H:i");
			$sah = preg_split("/\:/", $a_hour);
			$sh = preg_split("/\:/", $hour);
			$infos = $this->serv_infos($id);
			$other = $this->who_iso($id);
			$other_ = $other;
			if($other_ == $infos["By"]) {
				$other_ = $this->user->ID;
			}
			$st = $this->getstatus($id);
			if($a_date == $date && (((int)$sh[0] < (int)$sah[0]) || ((int)$sh[0] == (int)$sah[0] && (int)$sh[1] <= (int)$sah[1]))) {
				$arr = array(false, "Vous ne pouvez pas prendre de rendez-vous dans le passé !");
			} else {
				$w = $this->who_ami($id);
				if($w == "null") {
					$arr = array(false, "Vous ne faites pas partie de cette conversation !");
				} else if($infos["By"] != $this->user->ID) {
					$arr = array(false, "Vous n'êtes pas le propriétaire du service !");
				} else if($st == "3" || $st == "2") {
					$arr = array(false, "La prise de rendez-vous est actuellement impossible pour cette conversation/service.");
				} else {
					$isa = $this->isset_appoint($id, $infos['ID'], $other_);
					if($isa == "null") {
						$select = $this->mysql->prepare("INSERT INTO `appointment` (`ID`, `User`, `Service`, `Owner_Service`, `Date`, `State`) VALUES (NULL, '".$other."', '".$infos["ID"]."', '".$infos["By"]."', :date, '0');");
						$select->execute(array(":date" => $date." ".$hour.":00"));
						$last_id = $this->mysql->lastInsertId();
						//SEND TO OTHER
						$mess = $this->user->login.' a enregistré un rendez-vous avec vous le : '.date("d/m/Y", strtotime($date)). " à ".$hour.'<br><a data-id="'.$last_id.'" class="valid-this-date">Valider</a>&nbsp;&nbsp;&nbsp;&nbsp;<a data-id="'.$last_id.'" class="refuse-this-date">Refuser</a>';
						$this->send_reply($mess, $id, $other);
						//SEND TO ME
						$mess = 'Vous avez enregistré un rendez-vous pour le : '.date("d/m/Y", strtotime($date)). " à ".$hour.'<br><i>Vous devez maintenant attendre la confirmation de votre interlocuteur...</i>';
						$this->send_reply($mess, $id, $this->user->ID);
						//EDIT STATUS
						$this->mysql->query("UPDATE `conversation` SET `Status` = '1' WHERE `ID` = '".$id."'");
						$arr = array(true);
					} else {
						$select = $this->mysql->prepare("UPDATE `appointment` SET `Date` = :date WHERE `ID` = '".$isa."'");
						$select->execute(array(":date" => $date." ".$hour.":00"));
						//SEND TO OTHER
						$mess = $this->user->login.' a modifié le rendez-vous avec vous, il est maintenant pour le : '.date("d/m/Y", strtotime($date)). " à ".$hour.'<br><a data-id="'.$isa.'" class="valid-this-date">Valider</a>&nbsp;&nbsp;&nbsp;&nbsp;<a data-id="'.$isa.'" class="refuse-this-date">Refuser</a>';
						$this->send_reply($mess, $id, $other);
						//SEND TO ME
						$mess = 'Vous avez modifié le rendez-vous pour le : '.date("d/m/Y", strtotime($date)). " à ".$hour.'<br><i>Vous devez maintenant attendre la confirmation de votre interlocuteur...</i>';
						$this->send_reply($mess, $id, $this->user->ID);
						//EDIT STATUS
						$this->mysql->query("UPDATE `conversation` SET `Status` = '1' WHERE `ID` = '".$id."'");
						$arr = array(true);
					}
				}
			}
			return $arr;
		}
		function isset_appoint($id, $for, $user) {
			$o = "null";
			$select = $this->mysql->query("SELECT `ID`, COUNT(*) AS `nb` FROM `appointment` WHERE `Service` = '".$for."' AND `User` = '".$user."' AND (`State` = '0' OR `State` = '1' )");
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->nb > 0) {
				$o = $data->ID;
			}
			return $o;
		}
		function modal_date($id) {
			$w = $this->who_ami($id);
			if($w == "One") {
				$ww = "Two";
			} else {
				$ww = "One";
			}
			$select = $this->mysql->query("SELECT `conversation`.`ServiceFor`, `users`.`FirstName`, `users`.`LastName`, `users`.`login` FROM `conversation` INNER JOIN `users` ON `conversation`.`User_".$ww."` = `users`.`ID` WHERE `conversation`.`ID` = '".$id."'");
			$data = $select->fetch(PDO::FETCH_OBJ);
			$with = $data->FirstName." ".$data->LastName;
			$for = $this->service_title($data->ServiceFor);
			$other = $this->who_iso($id);
			$infos = $this->serv_infos($id);
			if($other_ == $infos["By"]) {
				$other = $this->user->ID;
			}
			$isa = $this->isset_appoint($id, $data->ServiceFor, $other);
			$time = "15:00";
			$date = date("Y-m-d");
			if($isa != "null") {
				$sel = $this->mysql->query("SELECT * FROM `appointment` WHERE `ID` = '".$isa."'");
				$dat = $sel->fetch(PDO::FETCH_OBJ);
				$tt = preg_split("/ /",$dat->Date);
				$date = $tt[0];
				$timt = preg_split("/\:/", $tt[1]);
				$time = $timt[0].":".$timt[1];
			}
			$html = '';
			$html .= '<h4 class="modal-title" id="exampleModalLabel">Fixer un rendez-vous ?</h4></div><div class="modal-body"><form method="post" id="m_date_modal" action="inc/send_mess.php"><div class="col-sm-12 tit">Vous souhaitez fixer un rendez-vous avec '.$with.' pour : '.$for.'</div><div class="col-sm-6"><input name="date" type="hidden" value="'.$date.'" id="date"><label for="datepicker" class="label-control">Selectionnez le jour :</label><div id="datepicker"></div></div><div class="col-sm-6"><label class="label-control" for="hour">A quel heure : </label> <input type="hidden" name="ID_C" value="'.$id.'"> <input id="hour" name="hour" class="form-control validate[required] time" value="'.$time.'" type="text"><input type="submit" class="btn" value="Envoyer"></div><div class="clear"></div></form>';
return $html;
		}
		function prepare_popup($user, $service=false) {
			$for = "";
			$isset = "";
			if($service != false) {
				$stxt = "Bonjour, je suis intéressé par votre service.";
				$for = "le service <i>\"".$service->title."\"</i>";
				$for_ = '<input type="hidden" name="for" value="'.$service->ID.'" >'.$for.'';
				$conv = $this->isset_conversation($user->ID, $service->ID);
				if($conv != false) {
					$isset = " - <a href='messagerie.php#select-".$conv."'>Cette conversation a déjà commencé</a>";
				}
			} else {
				$stxt = "Salut, ";
				$for = "discuter";	
				$for_ = '<input type="hidden" name="for" value="talk" >'.$for.'';
				$conv = $this->isset_conversation($user->ID, 'talk');
				if($conv != false) {
					$isset = " - <a href='messagerie.php#select-".$conv."'>Cette conversation a déjà commencé</a>";
				}
			}
			$html = '';
			$html = '<div id="modal_chat" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
	<form id="send_message" action="inc/send_mess.php" method="post">
	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Envoyer un message à :<input type="hidden" value="'.$user->cryptID.'" name="to"><span class="chat_title">'.$user->firstname.' '.$user->lastname.'</span><span class="chat_for">Pour : '.$for_.''.$isset.'</span></h4>
      </div>
      <div class="modal-body">
      	<div class="inner_form">
			<textarea rows="4" name="message" class="form-control validate[required]">'.$stxt.'</textarea>
			<input type="submit" class="form-control" value="Envoyer">
		</div>
		<div class="clear"></div>
		<div class="result_form">
		
		</div>
	  </div>
	  </form>
    </div>
  </div>
</div>';
			echo $html; 
		}
	}
?>