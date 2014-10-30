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


require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../src/app.php';

use Symfony\Component\Validator\Constraints as Assert;

$app->match('/Sites/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
    $start = 0;
    $vars = $request->query->all();
    $qsStart = (int)$vars["start"];
    $search = $vars["search"];
    $order = $vars["order"];
    $columns = $vars["columns"];
    $qsLength = (int)$vars["length"];    
    
    if($qsStart) {
        $start = $qsStart;
    }    
	
    $index = $start;   
    $rowsPerPage = $qsLength;
       
    $rows = array();
    
    $searchValue = $search['value'];
    $orderValue = $order[0];
    
    $orderClause = "";
    if($orderValue) {
        $orderClause = " ORDER BY ". $columns[(int)$orderValue['column']]['data'] . " " . $orderValue['dir'];
    }
    
    $table_columns = array(
		'SiteId', 
		'SiteNom', 
		'SiteType', 
		'MinerauxPresents', 
		'FossilesPresents', 
		'AutreInteret', 
		'SiteDescrGen', 
		'SiteLocalisation', 
		'SiteAccès', 
		'CommuneId', 
		'DepartementId', 
		'RegionId', 
		'EtatId', 
		'ISO2', 
		'RepSitePhotos', 

    );
    
    $whereClause = "";
    
    $i = 0;
    foreach($table_columns as $col){
        
        if ($i == 0) {
           $whereClause = " WHERE";
        }
        
        if ($i > 0) {
            $whereClause =  $whereClause . " OR"; 
        }
        
        $whereClause =  $whereClause . " " . $col . " LIKE '%". $searchValue ."%'";
        
        $i = $i + 1;
    }
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `Sites`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `Sites`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
    $rows_sql = $app['db']->fetchAll($find_sql, array());

    foreach($rows_sql as $row_key => $row_sql){
        for($i = 0; $i < count($table_columns); $i++){

		$rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];


        }
    }    
    
    $queryData = new queryData();
    $queryData->start = $start;
    $queryData->recordsTotal = $recordsTotal;
    $queryData->recordsFiltered = $recordsTotal;
    $queryData->data = $rows;
    
    return new Symfony\Component\HttpFoundation\Response(json_encode($queryData), 200);
});

$app->match('/Sites', function () use ($app) {
    
	$table_columns = array(
		'SiteId', 
		'SiteNom', 
		'SiteType', 
		'MinerauxPresents', 
		'FossilesPresents', 
		'AutreInteret', 
		'SiteDescrGen', 
		'SiteLocalisation', 
		'SiteAccès', 
		'CommuneId', 
		'DepartementId', 
		'RegionId', 
		'EtatId', 
		'ISO2', 
		'RepSitePhotos', 

    );

    $primary_key = "SiteId";	

    return $app['twig']->render('Sites/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('Sites_list');



$app->match('/Sites/create', function () use ($app) {
    
    $initial_data = array(
		'SiteNom' => '', 
		'SiteType' => '', 
		'MinerauxPresents' => '', 
		'FossilesPresents' => '', 
		'AutreInteret' => '', 
		'SiteDescrGen' => '', 
		'SiteLocalisation' => '', 
		'SiteAccès' => '', 
		'CommuneId' => '', 
		'DepartementId' => '', 
		'RegionId' => '', 
		'EtatId' => '', 
		'ISO2' => '', 
		'RepSitePhotos' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('SiteNom', 'text', array('required' => true));
	$form = $form->add('SiteType', 'text', array('required' => true));
	$form = $form->add('MinerauxPresents', 'text', array('required' => true));
	$form = $form->add('FossilesPresents', 'text', array('required' => true));
	$form = $form->add('AutreInteret', 'text', array('required' => true));
	$form = $form->add('SiteDescrGen', 'textarea', array('required' => true));
	$form = $form->add('SiteLocalisation', 'textarea', array('required' => true));
	$form = $form->add('SiteAccès', 'textarea', array('required' => true));
	$form = $form->add('CommuneId', 'text', array('required' => false));
	$form = $form->add('DepartementId', 'text', array('required' => false));
	$form = $form->add('RegionId', 'text', array('required' => false));
	$form = $form->add('EtatId', 'text', array('required' => false));
	$form = $form->add('ISO2', 'text', array('required' => true));
	$form = $form->add('RepSitePhotos', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `Sites` (`SiteNom`, `SiteType`, `MinerauxPresents`, `FossilesPresents`, `AutreInteret`, `SiteDescrGen`, `SiteLocalisation`, `SiteAccès`, `CommuneId`, `DepartementId`, `RegionId`, `EtatId`, `ISO2`, `RepSitePhotos`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['SiteNom'], $data['SiteType'], $data['MinerauxPresents'], $data['FossilesPresents'], $data['AutreInteret'], $data['SiteDescrGen'], $data['SiteLocalisation'], $data['SiteAccès'], $data['CommuneId'], $data['DepartementId'], $data['RegionId'], $data['EtatId'], $data['ISO2'], $data['RepSitePhotos']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Sites created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Sites_list'));

        }
    }

    return $app['twig']->render('Sites/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('Sites_create');



$app->match('/Sites/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Sites` WHERE `SiteId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('Sites_list'));
    }

    
    $initial_data = array(
		'SiteNom' => $row_sql['SiteNom'], 
		'SiteType' => $row_sql['SiteType'], 
		'MinerauxPresents' => $row_sql['MinerauxPresents'], 
		'FossilesPresents' => $row_sql['FossilesPresents'], 
		'AutreInteret' => $row_sql['AutreInteret'], 
		'SiteDescrGen' => $row_sql['SiteDescrGen'], 
		'SiteLocalisation' => $row_sql['SiteLocalisation'], 
		'SiteAccès' => $row_sql['SiteAccès'], 
		'CommuneId' => $row_sql['CommuneId'], 
		'DepartementId' => $row_sql['DepartementId'], 
		'RegionId' => $row_sql['RegionId'], 
		'EtatId' => $row_sql['EtatId'], 
		'ISO2' => $row_sql['ISO2'], 
		'RepSitePhotos' => $row_sql['RepSitePhotos'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('SiteNom', 'text', array('required' => true));
	$form = $form->add('SiteType', 'text', array('required' => true));
	$form = $form->add('MinerauxPresents', 'text', array('required' => true));
	$form = $form->add('FossilesPresents', 'text', array('required' => true));
	$form = $form->add('AutreInteret', 'text', array('required' => true));
	$form = $form->add('SiteDescrGen', 'textarea', array('required' => true));
	$form = $form->add('SiteLocalisation', 'textarea', array('required' => true));
	$form = $form->add('SiteAccès', 'textarea', array('required' => true));
	$form = $form->add('CommuneId', 'text', array('required' => false));
	$form = $form->add('DepartementId', 'text', array('required' => false));
	$form = $form->add('RegionId', 'text', array('required' => false));
	$form = $form->add('EtatId', 'text', array('required' => false));
	$form = $form->add('ISO2', 'text', array('required' => true));
	$form = $form->add('RepSitePhotos', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `Sites` SET `SiteNom` = ?, `SiteType` = ?, `MinerauxPresents` = ?, `FossilesPresents` = ?, `AutreInteret` = ?, `SiteDescrGen` = ?, `SiteLocalisation` = ?, `SiteAccès` = ?, `CommuneId` = ?, `DepartementId` = ?, `RegionId` = ?, `EtatId` = ?, `ISO2` = ?, `RepSitePhotos` = ? WHERE `SiteId` = ?";
            $app['db']->executeUpdate($update_query, array($data['SiteNom'], $data['SiteType'], $data['MinerauxPresents'], $data['FossilesPresents'], $data['AutreInteret'], $data['SiteDescrGen'], $data['SiteLocalisation'], $data['SiteAccès'], $data['CommuneId'], $data['DepartementId'], $data['RegionId'], $data['EtatId'], $data['ISO2'], $data['RepSitePhotos'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Sites edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Sites_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('Sites/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('Sites_edit');



$app->match('/Sites/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Sites` WHERE `SiteId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `Sites` WHERE `SiteId` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'Sites deleted!',
            )
        );
    }
    else{
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );  
    }

    return $app->redirect($app['url_generator']->generate('Sites_list'));

})
->bind('Sites_delete');






