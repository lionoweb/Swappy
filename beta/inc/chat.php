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
			$select = $this->mysql->prepare("SELECT `User_One`, `User_Two` FROM `conversation` WHERE (`User_One` = :me OR `User_Two` = :me) AND `ID` = :id AND `Status` = '0'");
			
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
		function isset_conversation_id($id) {
			$t = false;
			$select = $this->mysql->prepare("SELECT `ID`, COUNT(*) AS `total` FROM `conversation` WHERE (`User_One` = :me OR `User_Two` = :me) AND `ID` = :id AND `Status` = '0'");
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
			$select = $this->mysql->prepare("SELECT `ID`, COUNT(*) AS `total` FROM `conversation` WHERE ((`User_One` = :me AND `User_Two` = :id) OR (`User_Two` = :me AND `User_One` = :id)) AND `ServiceFor` = :for AND `Status` = '0'");
			$select->execute(array(":me" => $this->user->ID, ":id" => $id, ":for" => $for));
			$data = $select->fetch(PDO::FETCH_OBJ);
			if($data->total > 0) {
				$t = $data->ID;
			} else {
				$t = false;
			}
			return $t;	
		}
		function make_conversation($id, $for) {
			$i = 0;
			$select = $this->mysql->prepare("INSERT INTO `conversation` (`ID`, `User_One`, `User_Two`, `ServiceFor`, `Timestamp`, `Status`, `HiddenFor`) VALUES (NULL, :me, :id, :for, :time, '0', '0');");
			$select->execute(array(":me" => $this->user->ID, ":id" => $id, ":for" => $for, ":time" => time()));
			$last_id = $this->mysql->lastInsertId();
			if(!empty($last_id) && $last_id) {
				$i = $last_id;	
			}
			return $i;	
		}
		function send_reply($message, $id) {
			$r = false;
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
				$select = $this->mysql->prepare("INSERT INTO `conversation_reply` (`ID`, `C_ID`, `Author`, `Time`, `Message`, `Seen`, `HiddenFor`) VALUES (NULL, :conversation, :me, :time, :message, '0', '0');");
				if($select->execute(array(":me" => $this->user->ID, ":conversation" => $id, ":message" => $message, ":time" => time()))) {
					$r = true;
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
			$select = $this->mysql->prepare("UPDATE `conversation_reply` INNER JOIN `conversation` ON `conversation_reply`.`C_ID` = `conversation`.`ID` SET `Seen` = '1' WHERE (`conversation`.`User_One` = :me OR `conversation`.`User_Two` = :me) AND `conversation_reply`.`Seen` = '0' AND `conversation_reply`.`Author` != :me AND `conversation`.`ID` = :id");	
			$select->execute(array(":me" => $this->user->ID, ":id" => $id));
		}
		function unread_message($id) {
			$t = 0;
			$select = $this->mysql->prepare("SELECT `ID` FROM `conversation_reply` WHERE `Author` != :id AND `Seen` = '0' AND `C_ID` = :idc");	
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
			$array = array(false);
			$user = $this->who_ami($id);
			$list = $this->getHidden($id);
			if($list['MasterH'] != "false") {
				$value = 0;
				$oppo = 0;
				if($user == "One") {
					$value = 1;
					$oppo = 2;
				} else if($user == "Two") {
					$value = 2;
					$oppo = 1;
				}
				while (($val = current($list)) !== FALSE) {
					if(key($list) == "MasterH") { 
						if($val == 0) {
							$this->mysql->query("UPDATE `conversation` SET `HiddenFor` = '".$value."' WHERE `ID` = '".$id."'");
						} else if($val == $oppo) {
							$this->mysql->query("DELETE FROM `conversation` WHERE `ID` = '".$id."'");
						}
					} else {
						if($val == 0) {
							$this->mysql->query("UPDATE `conversation_reply` SET `HiddenFor` = '".$value."' WHERE `ID` = '".key($list) ."'");
						} else if($val == $oppo) {
							$this->mysql->query("DELETE FROM `conversation_reply` WHERE `ID` = '".key($list) ."'");
						}
					}
       				next($list);
				}
				$array = array(true);
			} else {
				$array = array(false, "Vous ne semblez faire partie de cette conversation.");
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
						$val = 2;
					} else if($user == "One") {
						$val = 1;
					}
					if($data->HiddenFor != $val) {
					$array[] = array("Name" => ucfirst($data->FirstName).' '.ucfirst($data->LastName), "For" => $data->ServiceFor, "Title" => $this->service_title($data->ServiceFor), "UserID" => $data->UserID, "ID" => $data->ID, "Status" =>$data->Status, "Count" => $this->unread_message($data->ID));
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
				$and = " AND ( `HiddenFor` = '0' OR `HiddenFor` = '1' ) ";
			} else if($user == "One") {
				$and = " AND ( `HiddenFor` = '0' OR `HiddenFor` = '2' ) ";
			}
			$select = $this->mysql->prepare("SELECT * FROM `conversation_reply` WHERE `C_ID` = :id".$and." ORDER BY `Time` ASC");
			$select->execute(array(":id" => $id));
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				$me = $data->Author;
				if($data->Author == $this->user->ID) {
					$me = "ME";
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
		function prepare_popup($user, $service=false) {
			$for = "";
			$isset = "";
			$isset_ = "";
			if($service != false) {
				$for = "le service <i>\"".$service->title."\"</i>";
				$for_ = '<input type="hidden" name="for" value="'.$service->ID.'" >'.$for.'';
				$conv = $this->isset_conversation($user->ID, $service->ID);
				if($conv != false) {
					$isset = " - <a href='messagerie.php#select-".$conv."'>Cette conversation a déjà commencé</a>";
					$k = $this->content_conv($conv);
					$g = count($k);
					for($o=0;$o<$g-1;$o++) {
							$isset_ .= ''.$k[$o]['Message'];
						
					}
					if($isset_ == "") {
						$isset = "";
					}
				}
			} else {
				$for = "discuter";	
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
			<textarea rows="4" name="message" class="form-control validate[required]">Bonjour, je suis intéressé par votre service.</textarea>
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