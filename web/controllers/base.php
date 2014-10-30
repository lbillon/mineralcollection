<?php

/*
 * This file is part of the CRUD Admin Generator project.
 *
 * Author: Jon Segador <jonseg@gmail.com>
 * Web: http://crud-admin-generator.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../src/app.php';

require_once __DIR__.'/Acquisitions/index.php';
require_once __DIR__.'/Adresses/index.php';
require_once __DIR__.'/BRGMExploitations/index.php';
require_once __DIR__.'/BRGMGisementsGitesIndices/index.php';
require_once __DIR__.'/Communes/index.php';
require_once __DIR__.'/Departements/index.php';
require_once __DIR__.'/Echanges/index.php';
require_once __DIR__.'/Echantillons/index.php';
require_once __DIR__.'/Etats/index.php';
require_once __DIR__.'/Manifestations/index.php';
require_once __DIR__.'/Mineraux/index.php';
require_once __DIR__.'/Pays/index.php';
require_once __DIR__.'/Personnes/index.php';
require_once __DIR__.'/Regions/index.php';
require_once __DIR__.'/Sites/index.php';
require_once __DIR__.'/SitesGeoLocalisation/index.php';
require_once __DIR__.'/SortiesCollection/index.php';
require_once __DIR__.'/SortiesSurTerrain/index.php';



$app->match('/', function () use ($app) {

    return $app['twig']->render('ag_dashboard.html.twig', array());
        
})
->bind('dashboard');


$app->run();
