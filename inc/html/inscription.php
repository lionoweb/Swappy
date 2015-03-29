<div class="header_propose">
	<p class="col-md-6 col-md-offset-3 top">Inscrivez-vous</p>
	<p class="col-md-6 col-md-offset-3 bot">Inscrivez-vous et commencez dès maintenant à échanger sur swappy!</p>
</div>
<div colspan="2" class="title_propose">Inscription</div>
<div class="greyback">
<form id="user_add" class="col-md-6 col-md-offset-3" action="inc/user_.php" method="post">
	<div class="form-group">
		<label for="login" class="control-label col-xs-12 col-sm-2 col-md-4">Identifiant*</label>
		<div class="col-xs-12 col-sm-10 col-md-8">
			<input autocomplete="off" data-validation-engine="validate[required,minSize[5],ajax[ajaxLoginCallPHP]] " class="form-control" autofocus data-key="true" type="text" id="login" name="login">
		</div>
	</div>
	<div class="form-group">
		<label for="password" class="control-label col-xs-12 col-sm-2 col-md-4">Mot de passe*</label>
		<div class="col-xs-12 col-sm-10 col-md-8">
			<input autocomplete="off" data-validation-engine="validate[required,minSize[6]]" class="form-control" id="password" type="password" name="password">
		</div>
	</div>
	<div class="form-group">
		<label for="password_r" class="control-label col-xs-12 col-sm-2 col-md-4">Retaper mot de passe*</label>
		<div class="col-xs-12 col-sm-10 col-md-8">
			<input autocomplete="off" data-validation-engine="validate[required,minSize[6],equalsPASS[password]] " class="form-control" type="password" id="password_r" name="password_r">
		</div>
	</div>
	<div class="form-group">
		<label for="email" class="control-label col-xs-12 col-sm-2 col-md-4">Adresse e-mail*</label>
		<div class="col-xs-12 col-sm-10 col-md-8">
			<input autocomplete="off" data-validation-engine="validate[required,custom[email],ajax[ajaxEmailCallPHP]]" class="form-control" data-key="true" id="email" type="text" name="email"> 
		</div>
	</div>
	<div class="form-group">
		<label for="lastname" class="control-label col-xs-12 col-sm-2 col-md-4">Nom*</label>
		<div class="col-xs-12 col-sm-10 col-md-8">
			<input autocomplete="off" data-validation-engine="validate[required]" class="form-control" type="text" id="lastname" name="lastname">
		</div>
	</div>
	<div class="form-group">
		<label for="firstname" class="control-label col-xs-12 col-sm-2 col-md-4">Prénom*</label>
		<div class="col-xs-12 col-sm-10 col-md-8">
			<input autocomplete="off" data-validation-engine="validate[required]" class="form-control" type="text" id="firstname" name="firstname">
		</div>
	</div>
	<div class="form-group">
		<label for="gender" class="control-label col-xs-12 col-sm-2 col-md-4">Sexe*</label>
		<div class="col-xs-12 col-sm-10 col-md-8">
			<select name="gender" class="form-control">
				<option value="M">Homme</option>
				<option value="F">Femme</option>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="phone" class="control-label col-xs-12 col-sm-2 col-md-4">Numéro de téléphone</label>
		<div class="col-xs-12 col-sm-10 col-md-8">
			<input maxlength="10" data-validation-engine="validate[optional,custom[phone],custom[onlyNumberSp],maxSize[10],minSize[10]]" class="form-control" id="phone" autocomplete="off" type="text" name="phone">
		</div>
	</div>
	<div class="form-group">
		<label for="day" class="control-label col-xs-12 col-sm-2 col-md-4">Date de naissance* :</label>
		<div class="col-xs-12 col-sm-10 col-md-8">
			<?php /* user.php | line 35 */  echo $page_->birthdate(); ?>
		</div>
	</div>
	<div class="form-group">
		<label for="street" class="control-label col-xs-12 col-sm-2 col-md-4">Adresse :</label>
		<div class="col-xs-12 col-sm-10 col-md-8">
			<input autocomplete="off" type="text" id="street" name="street" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label for="zipcode" class="control-label col-xs-12 col-sm-2 col-md-4">Code Postal* :</label>
		<div class="col-xs-12 col-sm-10 col-md-8">
			<input data-validation-engine="validate[required,custom[onlyNumberSp],minSize[5],maxSize[5],ajax[ajaxZipCodeCallPHP]]" class="zipcode form-control" autocomplete="off" maxlength="5" type="text" id="zipcode" name="zipcode" placeholder="Ex : 94500"> <input autocomplete="off" type="text" readonly class="form-control liketext" name="cityname">
		</div>
	</div>
	<div class="form-group">
		<input id="accept" data-validation-engine="validate[required]" type="checkbox" name="accept">
		<label for="accept" class="col-md-10 col-md-offset-1 lu inline-xs">J'ai lu et j'accepte les <a target="_blank" href="cgu.php">conditions générales d'utilisation et les mentions légales</a> du site Swappy</label>
	</div>
	<div class="form-group col-sm-4 col-sm-offset-8 col-xs-12">
		<input type="submit" class="form-control col-md-4" value="S'enregistrer">
	</div>
</form>