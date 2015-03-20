<div class="header_profil">
<div class="row">
<div class="top edit">
<div class="col-md-2 col-md-offset-3">
<img width="130" height="130" alt="Avatar de <?php echo ucfirst($user_->firstname); ?> <?php echo ucfirst($user_->lastname); ?>" src="<?php echo $user_->avatar; ?>">
</div>
<div class="infos col-md-4">
  	<p class="nom">
     <span class="field" id="prenom"><?php echo ucfirst($user_->firstname); ?></span>
     <span class="field" id="nom"> <?php echo ucfirst($user_->lastname); ?></span>
	</p>
  	<p class="">
     	<?php echo $user_->age." ans "; ?>
     	<span class="field" id="city"><img src="img/profil/location.png" alt=""><?php echo $user_->city;?></span>
  	</p>
  	<div id="tags" class="col-lg-8 col-md-8 tags nopadd">
     <?php $tags = $user_->tags_uncrypt($user_->tags);
	 echo empty($tags) ? 'Pas de tags...' : $tags; ?>
  	</div>
    <div class="info col-lg-12 col-md-12 nopadd rate">
    	Note moyenne : <div class="star-rating rating-xs rating-active" title="<?php echo $user_->globalnote; ?> étoile(s)"><div data-content="" class="rating-container rating-gly-star"><div style="width: <?php echo ($user_->globalnote*20); ?>%;" data-content="" class="rating-stars"></div><input id="input-1" class="rating form-control hide" data-min="0" data-max="5" data-step="1"></div> <span>[<?php echo $user_->globalvote; ?> vote(s)]</span></div>
    </div>
</div>
</div>
<?php if(isset($_GET['id']) && $_GET['id'] != $user->ID ) { ?>
<div class="text-left col-lg-2">
	<p class="btn talk-button">Envoyer un message</p>
</div>
<?php } else if(isset($_GET['id']) && $_GET['id'] == $user->ID) { ?>
<div class="text-left col-lg-2">
	<a href="profil.php" class="btn">Modifier mon profil</a>
</div>
<?php } ?>
<div class=" col-md-6 col-md-offset-3">
	<p id="description" class="text-justify description_">
  		<?php $desc = trim($user_->description); echo $desc = "" ? 'Pas de description' : ucfirst($desc); ?>
	</p>
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