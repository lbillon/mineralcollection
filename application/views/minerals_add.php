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
      
    <script type="text/javascript" src="<?php echo asset_url(); ?>fancyBox-v2.1.5/lib/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="<?php echo asset_url(); ?>fancyBox-v2.1.5/source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url(); ?>fancyBox-v2.1.5/source/jquery.fancybox.css?v=2.1.5" media="screen" />
      
   <script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
</script>

  </head>
  <body>
  
  <div class="container-fluid">
      <div class="row">
         <?php echo $output; ?>
      </div>
  </div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
   <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script-->
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
    
    
    
  </body>
</html>
