<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mineral Collection</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

      <?php
      foreach($css_files as $file): ?>
          <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
      <?php endforeach; ?>
      <?php foreach($js_files as $file): ?>
          <script src="<?php echo $file; ?>"></script>
      <?php endforeach; ?>
      <link type="text/css" rel="stylesheet" href="<?php echo asset_url(); ?>app/css/app.css" />
  </head>
  <body>
  <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
          <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#">Mineral Collection</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                  <li><a href="<?php echo site_url('/')?>">Home</a></li>
                  <li><a href='<?php echo site_url('minerals/map')?>'>Map</a></li>
                        <li class="dropdown">
          		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Entities <span class="caret"></span></a>
          		<ul class="dropdown-menu" role="menu">                   
                                 <li><a href='<?php echo site_url('minerals/Adresses')?>'>Adresses</a></li>
                                 <li><a href='<?php echo site_url('minerals/Acquisitions')?>'>Acquisitions</a></li>
                                 <li><a href='<?php echo site_url('minerals/Communes')?>'>Communes</a></li>
                                 <li><a href='<?php echo site_url('minerals/Departements')?>'>Departements</a></li>
                                 <li><a href='<?php echo site_url('minerals/Echanges')?>'>Echanges</a></li>
                                 <li><a href='<?php echo site_url('minerals/Echantillons')?>'>Echantillons</a></li>
                                 <li><a href='<?php echo site_url('minerals/Etats')?>'>Etats</a></li>
                                 <li><a href='<?php echo site_url('minerals/Manifestations')?>'>Manifestations</a></li>
                                  <li><a href='<?php echo site_url('minerals/Mineraux')?>'>Mineraux</a></li>
                                  <li><a href='<?php echo site_url('minerals/Pays')?>'>Pays</a></li>
                                  <li><a href='<?php echo site_url('minerals/Personnes')?>'>Personnes</a></li>
                                  <li><a href='<?php echo site_url('minerals/Regions')?>'>Regions</a></li>
                                  <li><a href='<?php echo site_url('minerals/sites')?>'>Sites</a></li>
<!--                                  <li><a href='--><?php //echo site_url('minerals/SitesGeoLocalisation')?><!--'>SitesGeoLocalisation</a></li>-->
                                  <li><a href='<?php echo site_url('minerals/SortiesCollection')?>'>SortiesCollection</a></li>
                                  <li><a href='<?php echo site_url('minerals/SortiesSurTerrain')?>'>SortiesSurTerrain</a></li>
                   </ul>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                  <li><a href="#">GSE 2014</a></li>
              </ul>
          </div><!--/.nav-collapse -->
      </div><!--/.container-fluid -->
  </nav>
  <div class="container-fluid">
      <div class="row">
      	
         <?php echo $output; ?>
      </div>
  </div>


    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    
    
		<!-- Angularjs and other dependencies -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular-route.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular-sanitize.js"></script>
    <script src="https://rawgithub.com/gsklee/ngStorage/master/ngStorage.js"></script>
   
    <script src="<?php echo asset_url(); ?>app/js/lodash.min.js"></script>
    <script src="https://rawgit.com/angular-ui/angular-google-maps/2.0.7/dist/angular-google-maps.min.js"></script>
    
    <script src="<?php echo asset_url(); ?>app/js/map.js"></script>
    
    <!-- Added By Hafiz Saqib Javed for fancy box display to add parent from child --> 
    <script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
</script>
    
  </body>
</html>
