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

$app->match('/SitesGeoLocalisation/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'SiteGeoId', 
		'SiteId', 
		'Localisation', 
		'SystemeGeodesique', 
		'Projection', 
		'Unité', 
		'Latitude', 
		'Longitude', 
		'Eloignement', 
		'Altitude', 
		'SiteAccès', 

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
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `SitesGeoLocalisation`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `SitesGeoLocalisation`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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

$app->match('/SitesGeoLocalisation', function () use ($app) {
    
	$table_columns = array(
		'SiteGeoId', 
		'SiteId', 
		'Localisation', 
		'SystemeGeodesique', 
		'Projection', 
		'Unité', 
		'Latitude', 
		'Longitude', 
		'Eloignement', 
		'Altitude', 
		'SiteAccès', 

    );

    $primary_key = "SiteGeoId";	

    return $app['twig']->render('SitesGeoLocalisation/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('SitesGeoLocalisation_list');



$app->match('/SitesGeoLocalisation/create', function () use ($app) {
    
    $initial_data = array(
		'SiteId' => '', 
		'Localisation' => '', 
		'SystemeGeodesique' => '', 
		'Projection' => '', 
		'Unité' => '', 
		'Latitude' => '', 
		'Longitude' => '', 
		'Eloignement' => '', 
		'Altitude' => '', 
		'SiteAccès' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('SiteId', 'text', array('required' => true));
	$form = $form->add('Localisation', 'textarea', array('required' => true));
	$form = $form->add('SystemeGeodesique', 'text', array('required' => true));
	$form = $form->add('Projection', 'text', array('required' => true));
	$form = $form->add('Unité', 'text', array('required' => true));
	$form = $form->add('Latitude', 'text', array('required' => true));
	$form = $form->add('Longitude', 'text', array('required' => true));
	$form = $form->add('Eloignement', 'text', array('required' => true));
	$form = $form->add('Altitude', 'text', array('required' => true));
	$form = $form->add('SiteAccès', 'textarea', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `SitesGeoLocalisation` (`SiteId`, `Localisation`, `SystemeGeodesique`, `Projection`, `Unité`, `Latitude`, `Longitude`, `Eloignement`, `Altitude`, `SiteAccès`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['SiteId'], $data['Localisation'], $data['SystemeGeodesique'], $data['Projection'], $data['Unité'], $data['Latitude'], $data['Longitude'], $data['Eloignement'], $data['Altitude'], $data['SiteAccès']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'SitesGeoLocalisation created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('SitesGeoLocalisation_list'));

        }
    }

    return $app['twig']->render('SitesGeoLocalisation/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('SitesGeoLocalisation_create');



$app->match('/SitesGeoLocalisation/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `SitesGeoLocalisation` WHERE `SiteGeoId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('SitesGeoLocalisation_list'));
    }

    
    $initial_data = array(
		'SiteId' => $row_sql['SiteId'], 
		'Localisation' => $row_sql['Localisation'], 
		'SystemeGeodesique' => $row_sql['SystemeGeodesique'], 
		'Projection' => $row_sql['Projection'], 
		'Unité' => $row_sql['Unité'], 
		'Latitude' => $row_sql['Latitude'], 
		'Longitude' => $row_sql['Longitude'], 
		'Eloignement' => $row_sql['Eloignement'], 
		'Altitude' => $row_sql['Altitude'], 
		'SiteAccès' => $row_sql['SiteAccès'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('SiteId', 'text', array('required' => true));
	$form = $form->add('Localisation', 'textarea', array('required' => true));
	$form = $form->add('SystemeGeodesique', 'text', array('required' => true));
	$form = $form->add('Projection', 'text', array('required' => true));
	$form = $form->add('Unité', 'text', array('required' => true));
	$form = $form->add('Latitude', 'text', array('required' => true));
	$form = $form->add('Longitude', 'text', array('required' => true));
	$form = $form->add('Eloignement', 'text', array('required' => true));
	$form = $form->add('Altitude', 'text', array('required' => true));
	$form = $form->add('SiteAccès', 'textarea', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `SitesGeoLocalisation` SET `SiteId` = ?, `Localisation` = ?, `SystemeGeodesique` = ?, `Projection` = ?, `Unité` = ?, `Latitude` = ?, `Longitude` = ?, `Eloignement` = ?, `Altitude` = ?, `SiteAccès` = ? WHERE `SiteGeoId` = ?";
            $app['db']->executeUpdate($update_query, array($data['SiteId'], $data['Localisation'], $data['SystemeGeodesique'], $data['Projection'], $data['Unité'], $data['Latitude'], $data['Longitude'], $data['Eloignement'], $data['Altitude'], $data['SiteAccès'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'SitesGeoLocalisation edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('SitesGeoLocalisation_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('SitesGeoLocalisation/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('SitesGeoLocalisation_edit');



$app->match('/SitesGeoLocalisation/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `SitesGeoLocalisation` WHERE `SiteGeoId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `SitesGeoLocalisation` WHERE `SiteGeoId` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'SitesGeoLocalisation deleted!',
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

    return $app->redirect($app['url_generator']->generate('SitesGeoLocalisation_list'));

})
->bind('SitesGeoLocalisation_delete');






