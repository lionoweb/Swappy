<div class="header_profil">
	<div class="row">
		<div class="top edit">
        	<form method="post" action="inc/user_.php" id="edit_user" class="col-xs-12">
                <div class="col-md-2 col-md-offset-3 col-sm-3 col-sm-offset-2 uploader-controls">
                    <img width="130" height="130" id="avatar_u" alt="Mon avatar" src="<?php echo $user_->avatar; ?>">
                    <div class="uploader-side">
            <button type="button" id="upload_ba" class="btn uploader-button">Changer avatar</button>
            <div class="uploader-file-input">
              <input type="file" id="upload_b" accept="image/*">
            </div>
            <div style="display: none;" class="progress uploader-progress ">
              <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar active progress-bar-striped">0%</div>
            </div>
          </div>
                    <a class="see_profile" alt="Voir ma page profil" href="profil-<?php echo $user_->ID; ?>.php">Voir ma page profil</a>
                </div>
                <div class="infos col-sm-5">
					<div class="nom form-group">
     					<label for="prenom" class="control-label">Prénom : </label>
                        <input autocomplete="off" data-validation-engine="validate[required]" type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $user_->firstname; ?>">
     				</div>
     				<div class="nom form-group">
     					<label for="nom" class="control-label">Nom : </label>
                        <input autocomplete="off" data-validation-engine="validate[required]" type="text" class="form-control" id="nom" name="nom" value="<?php echo $user_->lastname; ?>">
					</div>
              	</div>
  				<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
  					<div class="form-group col-sm-8">
                     	<label for="day" class="control-label">Date de naissance :</label><br>
                        <?php echo $page_->birthdate(preg_split("/\-/", $user_->birthdate)); ?>
                	</div>
					<div class="form-group col-sm-4">
  						<label for="gender" class="control-label">Sexe : </label>
  						<select name="gender" id="gender" class="form-control">
                        	<option <?php echo $user_->gender == "M" ? "selected" : "";  ?> value="M">Homme</option>
                           	<option <?php echo $user_->gender == "F" ? "selected" : "";  ?> value="F">Femme</option>
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
     				<div class="form-group col-sm-11">
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
                    	
     					<input <?php echo $user_->mailoption == 1 ? "checked" : ""; ?> id="mail" name="mail" autocomplete="off" type="checkbox"><label for="mail" class="control-label">Recevoir un mail à chaque nouveau message</label>
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
                     		<input type="submit" class="form-control col-md-4" value="Enregistrer">
                  		</div>
                    </div>
				</div>
            </form>
        </div>
	</div>
</div>
<div class="profiltitle first">
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
    <div class="col-lg-6 info text-center rate col-lg-offset-3 col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1 note">
        Note moyenne : 
        <div class="star-rating rating-xs rating-active" title="<?php echo $user_->globalnote; ?> étoile(s)">
            <div data-content="" class="rating-container rating-gly-star">
                <div style="width: <?php echo ($user_->globalnote*20); ?>%;" data-content="" class="rating-stars"></div>
                <input id="input-1" class="rating form-control hide" data-min="0" data-max="5" data-step="1">	
            </div> <span>[<?php echo $user_->globalvote; ?> vote(s)]</span>
        </div>
    </div>
	<?php echo $user_->list_com(); ?>
</div>