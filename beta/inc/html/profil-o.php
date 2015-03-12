<div class="header_profil">
<div class="row">
<div class="top edit">
<div class="col-md-2 col-md-offset-3">

  <img src="<?php echo $user_->avatar; ?>">
</div>
<div class="infos col-md-4">
  <p class="nom">
     <span class="field" id="prenom"><?php echo ucfirst($user_->firstname); ?></span>
     <span class="field" id="nom"> <?php echo ucfirst($user_->lastname); ?></span>

  </p>
  <p class="">
     <?php
       
        echo $user_->age." ans ";
     ?><span class="field" id="city"><img src="img/profil/location.png" alt=""><?php echo $user_->city;?></span>
  </p>
  <div class="field" id="tags" class="col-lg-8 col-md-8 tags">
     <?php $tags = $user_->tags_uncrypt($user_->tags);
	 echo empty($tags) ? 'Pas de tags...' : $tags; ?>
  </div>
</div>
</div>
<?php if(isset($_GET['id']) && $_GET['id'] != $user->ID ) { ?>
<div class="text-left col-lg-2">
<p class="btn talk-button">Envoyer un message</p>
</div>
<?php } ?>
<div class="row">
<p  id="description" class="text-justify field description col-md-6 col-md-offset-3">
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

<div class="profiltitle">
<p>Notes et commentaires</p>
</div>
<div class="pictodown">
<img src="img/profil/down.png" alt="" class="down">
</div>
<div class="row notes">
<div class="text-justify col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1 note">
<p>Blabla 1<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean massa nibh, commodo ut eleifend nec, rutrum vel lacus. Morbi congue, nibh a venenatis tempus, nulla ligula molestie orci, ac euismod erat urna quis ante. Vivamus velit felis, porta ut suscipit a, suscipit at arcu.</p>
</div>
<div class="text-justify col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1 note">
<p>Blabla 2<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean massa nibh, commodo ut eleifend nec, rutrum vel lacus. Morbi congue, nibh a venenatis tempus, nulla ligula molestie orci, ac euismod erat urna quis ante. Vivamus velit felis, porta ut suscipit a, suscipit at arcu.</p>
</div>
<div class="text-justify col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1 note">
<p>Blabla 3<br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean massa nibh, commodo ut eleifend nec, rutrum vel lacus. Morbi congue, nibh a venenatis tempus, nulla ligula molestie orci, ac euismod erat urna quis ante. Vivamus velit felis, porta ut suscipit a, suscipit at arcu.</p>
</div>
</div>