<form id="user_remind" class="col-md-6 col-md-offset-3" action="inc/user_.php" method="post">
	<div colspan="2" class="header_inscription">Changement du mot de passe</div>
	<div class="greyback change_mdp">
		<div class="form-group">
			<label for="email" class="control-label col-xs-12 col-sm-2 col-md-4">Email :</label>
			<div class="col-xs-12 col-sm-10 col-md-8">
				<input autocomplete="off" value="<?php echo $mm[1]; ?>" class="liketext form-control fullwidth" disabled type="text" id="email" name="email">
				<input autocomplete="off" value="<?php echo $_GET['remind']; ?>" type="hidden" name="hash" >
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
				<input autocomplete="off" data-validation-engine="validate[required,minSize[6],equalsPASS[password]]" class="form-control" type="password" id="password_r" name="password_r">
			</div>
		</div>
		<div class="form-group">
			<input type="submit" class="form-control" value="Modifier">
		</div>
	</div>
</form>