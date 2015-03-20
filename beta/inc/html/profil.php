<div class="header_profil">
	<div class="row">
		<div class="top edit">
        	<form method="post" action="inc/add_user.php" id="edit_user">
                <div class="col-md-2 col-md-offset-3 col-sm-3 col-sm-offset-2 uploader-controls">
                    <img id="avatar_u" alt="Mon avatar" src="<?php echo $user_->avatar; ?>">
                    <div class="uploader-side">
            <button type="button" id="upload_ba" class="btn uploader-button">Changer avatar</button>
            <div class="uploader-file-input">
              <input type="file" id="upload_b" accept="image/*">
            </div>
            <div style="display: none;" class="progress uploader-progress ">
              <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar active progress-bar-striped">0%</div>
            </div>
          </div>
                    <a class="see_profile" alt="Voir ma page profil" href="profil.php?id=<?php echo $user_->ID; ?>">Voir ma page profil</a>
                </div>
                <div class="infos col-sm-4">
					<div class="nom form-group">
     					<label for="prenom" class="control-label">Prénom : </label>
                        <input autocomplete="off" data-validation-engine="validate[required]" type="text" class="form-control" id="prenom" name="prenom" value="<?php echo ucfirst($user_->firstname); ?>">
     				</div>
     				<div class="nom form-group">
     					<label for="nom" class="control-label">Nom : </label>
                        <input autocomplete="off" data-validation-engine="validate[required]" type="text" class="form-control" id="nom" name="nom" value="<?php echo ucfirst($user_->lastname); ?>">
					</div>
              	</div>
  				<div class="col-md-6 col-md-offset-3 col-sm-7 col-sm-offset-2">
  					<div class="form-group col-sm-8">
  						<?php $dd= preg_split("/\-/", $user_->birthdate); ?>
                     	<label for="day" class="control-label">Date de naissance :</label><br>
                     	<select id="day" name="day" class="form-control birthday">
                        	<?php for($i=1;$i<32;$i++) { 
                           		$o = $i; 
                           		if($o < 10) $o = "0".$o;
						   		$c = "";
								if($i == $dd[2]) { $c="selected "; }
                           		echo '<option '.$c.'value="'.$o.'">'.$o.'</option>'; 
                           	} ?>
                        </select> 
                        <select name="month" class="form-control birthmonth">
                        	<?php for($i=1;$i<13;$i++) { 
                           		$o = $i; 
                           		if($o < 10) $o = "0".$o; 
						   		$c = "";
								if($o == $dd[1]) { $c="selected "; }
                           		echo '<option '.$c.'value="'.$o.'">'.$o.'</option>'; 
                           } ?>
                        </select> 
                        <select name="year" class="form-control birthyear">
							<?php for($i=(date("Y")-18);$i>1919;$i--) { 
								$c = "";
								if($i == $dd[0]) { $c="selected "; }
								echo '<option '.$c.'value="'.$i.'">'.$i.'</option>'; 
                          	} ?>
                        </select>
                	</div>
					<div class="form-group col-sm-4">
  						<label for="gender" class="control-label">Sexe : </label>
  						<select name="gender" id="gender" class="form-control">
                        	<option <?php if($user_->gender == "M") { ?> selected <?php } ?> value="M">Homme</option>
                           	<option <?php if($user_->gender == "F") { ?> selected <?php } ?> value="F">Femme</option>
                        </select>
                	</div>
                  	<div class="form-group col-sm-8">
                     	<label for="street" class="control-label">Adresse :</label>
						<input autocomplete="off" value="<?php echo $user_->street; ?>" type="text" id="street" name="street" class="form-control">
					</div>
                    <div class="form-group col-sm-4">
                    	<label for="phone" class="control-label">Numéro de téléphone :</label>
                        <input maxlength="10" data-validation-engine="validate[optional,custom[phone],custom[onlyNumberSp],maxSize[10],minSize[10]]" class="form-control" id="phone" autocomplete="off" value="<?php echo $user_->phone; ?>" type="text" name="phone">
                    </div>
     				<div class="form-group col-sm-8">
     					<label for="zipcode" class="control-label">Code Postal* : </label>
                   		<input data-validation-engine="validate[required,custom[onlyNumberSp],minSize[5],maxSize[5],ajax[ajaxZipCodeCallPHP]]" class="zipcode form-control" autocomplete="off" value="<?php echo $user_->zipcode; ?>" maxlength="5" type="text" id="zipcode" name="zipcode" placeholder="Ex : 94500">
                        <input autocomplete="off" type="text" value="<?php echo $user_->city; ?>" readonly class="form-control liketext" name="cityname">
                    </div>
                    <div class="form-group col-sm-10">
                    	<label for="description" class="control-label">Description : </label>
                    	<textarea id="description" name="description" autocomplete="off" class="form-control"><?php echo $user_->description; ?></textarea>
                    </div>
  					<div id="tags" class="col-sm-8 form-group">
                    	<label for="tags" class="control-label">Tags : </label>
     					<input id="tags" name="tags" autocomplete="off" value="<?php echo $user_->tags; ?>" type="text" class="form-control tags-input">
  					</div>
                    <div class="col-sm-8 form-group checkbox">
                    	
     					<input <?php if($user_->mailoption == 1) { ?> checked <?php } ?> id="mail" name="mail" autocomplete="off" type="checkbox"><label for="mail" class="control-label">Recevoir un mail à chaque nouveau message</label>
  					</div>
                    <div class="col-sm-12 border-hr">&nbsp;</div>
                    <div class="col-sm-6 form-group">
                    	<label for="email" class="control-label">Email* : </label>
     					<input data-validation-engine="validate[required,custom[email]]" autocomplete="off" value="<?php echo $user_->email; ?>" type="email" class="form-control" id="email" name="email">
  					</div>
                    <div class="row col-sm-12">
                        <div class="col-sm-12 change_pass">
                        	Changer de mot de passe* :
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="mdp" class="control-label">Nouveau mot de passe : </label>
                            <input data-validation-engine="validate[minSize[6]]" name="mdp" id="mdp" autocomplete="off" value="" type="password" class="form-control">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="r_mdp" class="control-label">Retaper mot de passe : </label>
                            <input data-validation-engine="validate[minSize[6],equalsPASS[mdp]] " id="r_mdp" name="r_mdp" autocomplete="off" value="" type="password" class="form-control">
                        </div>
                        <div class="col-sm-offset-6 col-sm-6 form-group">
                            <label for="a_mdp" class="control-label"><u>Mot de passe actuel :</u> </label>
                            <input data-validation-engine="validate[minSize[6]] " id="a_mdp" name="a_mdp" autocomplete="off" value="" type="password" class="form-control">
                        </div>
                        <div class="col-sm-8 aste">* : pour changer d'adresse email ou de mot de passe vous devez entrer votre mot de passe actuel.</div>
                        <div class="form-group col-sm-4  col-xs-12">
                     		<input type="submit" class="form-control col-md-4" value="Modifier">
                  		</div>
                    </div>
				</div>
            </form>
        </div>
	</div>
</div>
<div class="profiltitle">
	<p>Services</p>
</div>
<div class="pictodown">
<img src="img/profil/down.png" alt="" class="down">
</div>
<div class="text-center">
<?php echo $user_->listing_badge_s(); ?>
</div>
<div class="profiltitle">
<p>Notes et commentaires</p>
</div>
<div class="pictodown">
<img src="img/profil/down.png" alt="" class="down">
</div>
<div class="row notes">
<?php echo $user_->list_com(); ?>
</div>