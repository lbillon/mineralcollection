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
      <style type='text/css'>
          body
          {
              font-family: Arial;
              font-size: 14px;
          }
          a {
              color: blue;
              text-decoration: none;
              font-size: 14px;
          }
          a:hover
          {
              text-decoration: underline;
          }
          
			.angular-google-map-container { height: 600px; }
      </style>
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
                  <li><a href='<?php echo site_url('minerals/sites')?>'>Sites</a></li>
                  <li><a href='<?php echo site_url('map_endpoints/test')?>'>A test endpoint</a></li>

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


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    
    
		<!-- Angularjs and other dependencies -->
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular-route.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular-sanitize.js"></script>
   
    <script src="<?php echo asset_url(); ?>app/js/lodash.min.js"></script>
    <script src="https://rawgit.com/angular-ui/angular-google-maps/2.0.7/dist/angular-google-maps.min.js"></script>
    
    <script src="<?php echo asset_url(); ?>app/js/map.js"></script>
    
    

  </body>
</html>