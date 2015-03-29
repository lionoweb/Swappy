<div class="header_profil">
	<div class="row">
		<div class="top edit">
			<div class="col-md-2 col-sm-3 col-md-offset-3">
				<img width="130" height="130" alt="Avatar de <?php echo $user_->fullname; ?>" src="<?php echo $user_->avatar; ?>">
			</div>
			<div class="infos col-md-4 col-sm-4 text-left xs-center">
  				<p class="nom">
     				<span class="field" id="prenom"><?php echo $user_->firstname; ?></span>&nbsp;
     				<span class="field" id="nom"> <?php echo $user_->lastname; ?></span>
				</p>
  				<p class="">
     				<?php echo $user_->age." ans "; ?>
     				<span class="field" id="city"><img src="img/profil/location.png" alt=""><?php echo $user_->city;?></span>
  				</p>
  				<div id="tags" class="col-lg-8 col-md-8 col-sm-8 tags nopadd">
     				<?php echo empty_($user_->tags_uncrypt($user_->tags)) ? 'Pas de tags...' : $user_->tags_uncrypt($user_->tags); ?>
  				</div>
    			<div class="info col-lg-12 col-sm-12 col-md-12 nopadd rate">
    				Note moyenne : 
                    <div class="star-rating rating-xs rating-active" title="<?php echo $user_->globalnote; ?> étoile(s)">
                    	<div data-content="" class="rating-container rating-gly-star">
                        <div style="width: <?php echo ($user_->globalnote*20); ?>%;" data-content="" class="rating-stars"></div>
                        <input id="input-1" class="rating form-control hide" data-min="0" data-max="5" data-step="1">
                 	</div> <span>[<?php echo $user_->globalvote; ?> vote(s)]</span></div>
    			</div>
			</div>
		</div>
        <div class="col-md-6 col-md-offset-3">
			<p id="description" class="text-justify description_">
  			<?php echo $user_->description == "" ? 'Pas de description' : ucfirst($user_->description); ?>
			</p>
		</div>
		<?php /* user.php | line 1498 */ echo $user_->button($user); ?>
	</div>
</div>
<div class="profiltitle first">
	<p>Services</p>
</div>
<div class="pictodown">
	<img src="img/profil/down.png" alt="" class="down">
</div>
<div class="text-center">
	<?php /* user.php | line 1353 */ echo $user_->listing_badge_s(); ?>
</div>
<div class="profiltitle">
	<p>Notes et commentaires</p>
</div>
<div class="pictodown">
	<img src="img/profil/down.png" alt="" class="down">
</div>
<div class="row notes">
	<?php /* user.php | line 1197 */ echo $user_->list_com(); ?>
</div>