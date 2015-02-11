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
		function list_message() {
			$array = array();
			$select = $this->mysql->prepare("SELECT `conversation`.`ID`, `conversation`.`ServiceFor`, `users`.`LastName`, `users`.`FirstName` FROM `conversation` INNER JOIN `conversation_reply` ON `conversation`.`ID` = `conversation_reply`.`C_ID` INNER JOIN `users` ON CASE WHEN `conversation`.`User_One` != :me THEN `conversation`.`User_One` = `users`.`ID` ELSE `conversation`.`User_Two` = `users`.`ID` END WHERE (`conversation`.`User_One` = :me OR `conversation`.`User_Two` = :me) GROUP BY `conversation`.`ID` ORDER BY `conversation_reply`.`Time` DESC, `conversation`.`Timestamp` DESC ");
			$select->execute(array(":me" => $this->user->ID));
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				$array[] = array("Name" => ucfirst($data->FirstName).' '.ucfirst($data->LastName), "For" => $data->ServiceFor, "Title" => $this->service_title($data->ServiceFor), "ID" => $data->ID);
			}
			return $array;
		}
		function content_conv($id) {
			$array = array();
			$select = $this->mysql->prepare("SELECT * FROM `conversation_reply` WHERE `C_ID` = :id ORDER BY `Time` ASC");
			$select->execute(array(":id" => $id));
			while($data = $select->fetch(PDO::FETCH_OBJ)) {
				$me = $data->Author;
				if($data->Author == $this->user->ID) {
					$me = "ME";
				}
				$html .= $array[] = array("ID" => $data->ID, "Message" => $data->Message, "Author" => $me, "Time" => $data->Time);
			}
			$this->set_read($id);
			return $array;
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
			if($service != false) {
				$for = "le service <i>\"".$service->title."\"</i>";
			} else {
				$for = "";	
			}
			$html = '';
			$html = '<div id="modal_chat" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
	<form id="send_message" action="inc/send_mess.php" method="post">
	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Envoyer un message à :<input type="hidden" value="'.$user->cryptID.'" name="to"><span class="chat_title">'.$user->firstname.' '.$user->lastname.'</span><span class="chat_for">Pour : <input type="hidden" name="for" value="'.$service->ID.'" >'.$for.'</span></h4>
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