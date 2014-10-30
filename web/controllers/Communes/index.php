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

$app->match('/Communes/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'CommuneId', 
		'DepartementId', 
		'CommuneACTUAL', 
		'CommuneCHEFLIEU', 
		'CommuneCDC', 
		'CommuneRANG', 
		'CommuneREG', 
		'CommuneDEP', 
		'CommuneCOM', 
		'CommuneAR', 
		'CommuneCT', 
		'CommuneMODIF', 
		'CommunePOLE', 
		'CommuneTNCC', 
		'CommuneARTMAJ', 
		'CommuneNCC', 
		'CommuneARTMIN', 
		'CommuneNCCENR', 
		'CommuneARTICLCT', 
		'CommuneNCCCT', 
		'CodePostal', 
		'CommuneCodeINSEE', 
		'ISO2', 
		'SystemeGeodesique', 
		'Projection', 
		'Unite', 
		'latitude', 
		'longitude', 
		'eloignement', 
		'altitude', 

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
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `Communes`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `Communes`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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

$app->match('/Communes', function () use ($app) {
    
	$table_columns = array(
		'CommuneId', 
		'DepartementId', 
		'CommuneACTUAL', 
		'CommuneCHEFLIEU', 
		'CommuneCDC', 
		'CommuneRANG', 
		'CommuneREG', 
		'CommuneDEP', 
		'CommuneCOM', 
		'CommuneAR', 
		'CommuneCT', 
		'CommuneMODIF', 
		'CommunePOLE', 
		'CommuneTNCC', 
		'CommuneARTMAJ', 
		'CommuneNCC', 
		'CommuneARTMIN', 
		'CommuneNCCENR', 
		'CommuneARTICLCT', 
		'CommuneNCCCT', 
		'CodePostal', 
		'CommuneCodeINSEE', 
		'ISO2', 
		'SystemeGeodesique', 
		'Projection', 
		'Unite', 
		'latitude', 
		'longitude', 
		'eloignement', 
		'altitude', 

    );

    $primary_key = "CommuneId";	

    return $app['twig']->render('Communes/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('Communes_list');



$app->match('/Communes/create', function () use ($app) {
    
    $initial_data = array(
		'DepartementId' => '', 
		'CommuneACTUAL' => '', 
		'CommuneCHEFLIEU' => '', 
		'CommuneCDC' => '', 
		'CommuneRANG' => '', 
		'CommuneREG' => '', 
		'CommuneDEP' => '', 
		'CommuneCOM' => '', 
		'CommuneAR' => '', 
		'CommuneCT' => '', 
		'CommuneMODIF' => '', 
		'CommunePOLE' => '', 
		'CommuneTNCC' => '', 
		'CommuneARTMAJ' => '', 
		'CommuneNCC' => '', 
		'CommuneARTMIN' => '', 
		'CommuneNCCENR' => '', 
		'CommuneARTICLCT' => '', 
		'CommuneNCCCT' => '', 
		'CodePostal' => '', 
		'CommuneCodeINSEE' => '', 
		'ISO2' => '', 
		'SystemeGeodesique' => '', 
		'Projection' => '', 
		'Unite' => '', 
		'latitude' => '', 
		'longitude' => '', 
		'eloignement' => '', 
		'altitude' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('DepartementId', 'text', array('required' => false));
	$form = $form->add('CommuneACTUAL', 'text', array('required' => false));
	$form = $form->add('CommuneCHEFLIEU', 'text', array('required' => false));
	$form = $form->add('CommuneCDC', 'text', array('required' => false));
	$form = $form->add('CommuneRANG', 'text', array('required' => false));
	$form = $form->add('CommuneREG', 'text', array('required' => true));
	$form = $form->add('CommuneDEP', 'text', array('required' => true));
	$form = $form->add('CommuneCOM', 'text', array('required' => true));
	$form = $form->add('CommuneAR', 'text', array('required' => false));
	$form = $form->add('CommuneCT', 'text', array('required' => true));
	$form = $form->add('CommuneMODIF', 'text', array('required' => false));
	$form = $form->add('CommunePOLE', 'text', array('required' => false));
	$form = $form->add('CommuneTNCC', 'text', array('required' => false));
	$form = $form->add('CommuneARTMAJ', 'text', array('required' => false));
	$form = $form->add('CommuneNCC', 'text', array('required' => false));
	$form = $form->add('CommuneARTMIN', 'text', array('required' => false));
	$form = $form->add('CommuneNCCENR', 'text', array('required' => false));
	$form = $form->add('CommuneARTICLCT', 'text', array('required' => false));
	$form = $form->add('CommuneNCCCT', 'text', array('required' => false));
	$form = $form->add('CodePostal', 'text', array('required' => true));
	$form = $form->add('CommuneCodeINSEE', 'text', array('required' => true));
	$form = $form->add('ISO2', 'text', array('required' => false));
	$form = $form->add('SystemeGeodesique', 'text', array('required' => true));
	$form = $form->add('Projection', 'text', array('required' => true));
	$form = $form->add('Unite', 'text', array('required' => true));
	$form = $form->add('latitude', 'text', array('required' => true));
	$form = $form->add('longitude', 'text', array('required' => true));
	$form = $form->add('eloignement', 'text', array('required' => true));
	$form = $form->add('altitude', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `Communes` (`DepartementId`, `CommuneACTUAL`, `CommuneCHEFLIEU`, `CommuneCDC`, `CommuneRANG`, `CommuneREG`, `CommuneDEP`, `CommuneCOM`, `CommuneAR`, `CommuneCT`, `CommuneMODIF`, `CommunePOLE`, `CommuneTNCC`, `CommuneARTMAJ`, `CommuneNCC`, `CommuneARTMIN`, `CommuneNCCENR`, `CommuneARTICLCT`, `CommuneNCCCT`, `CodePostal`, `CommuneCodeINSEE`, `ISO2`, `SystemeGeodesique`, `Projection`, `Unite`, `latitude`, `longitude`, `eloignement`, `altitude`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['DepartementId'], $data['CommuneACTUAL'], $data['CommuneCHEFLIEU'], $data['CommuneCDC'], $data['CommuneRANG'], $data['CommuneREG'], $data['CommuneDEP'], $data['CommuneCOM'], $data['CommuneAR'], $data['CommuneCT'], $data['CommuneMODIF'], $data['CommunePOLE'], $data['CommuneTNCC'], $data['CommuneARTMAJ'], $data['CommuneNCC'], $data['CommuneARTMIN'], $data['CommuneNCCENR'], $data['CommuneARTICLCT'], $data['CommuneNCCCT'], $data['CodePostal'], $data['CommuneCodeINSEE'], $data['ISO2'], $data['SystemeGeodesique'], $data['Projection'], $data['Unite'], $data['latitude'], $data['longitude'], $data['eloignement'], $data['altitude']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Communes created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Communes_list'));

        }
    }

    return $app['twig']->render('Communes/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('Communes_create');



$app->match('/Communes/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Communes` WHERE `CommuneId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('Communes_list'));
    }

    
    $initial_data = array(
		'DepartementId' => $row_sql['DepartementId'], 
		'CommuneACTUAL' => $row_sql['CommuneACTUAL'], 
		'CommuneCHEFLIEU' => $row_sql['CommuneCHEFLIEU'], 
		'CommuneCDC' => $row_sql['CommuneCDC'], 
		'CommuneRANG' => $row_sql['CommuneRANG'], 
		'CommuneREG' => $row_sql['CommuneREG'], 
		'CommuneDEP' => $row_sql['CommuneDEP'], 
		'CommuneCOM' => $row_sql['CommuneCOM'], 
		'CommuneAR' => $row_sql['CommuneAR'], 
		'CommuneCT' => $row_sql['CommuneCT'], 
		'CommuneMODIF' => $row_sql['CommuneMODIF'], 
		'CommunePOLE' => $row_sql['CommunePOLE'], 
		'CommuneTNCC' => $row_sql['CommuneTNCC'], 
		'CommuneARTMAJ' => $row_sql['CommuneARTMAJ'], 
		'CommuneNCC' => $row_sql['CommuneNCC'], 
		'CommuneARTMIN' => $row_sql['CommuneARTMIN'], 
		'CommuneNCCENR' => $row_sql['CommuneNCCENR'], 
		'CommuneARTICLCT' => $row_sql['CommuneARTICLCT'], 
		'CommuneNCCCT' => $row_sql['CommuneNCCCT'], 
		'CodePostal' => $row_sql['CodePostal'], 
		'CommuneCodeINSEE' => $row_sql['CommuneCodeINSEE'], 
		'ISO2' => $row_sql['ISO2'], 
		'SystemeGeodesique' => $row_sql['SystemeGeodesique'], 
		'Projection' => $row_sql['Projection'], 
		'Unite' => $row_sql['Unite'], 
		'latitude' => $row_sql['latitude'], 
		'longitude' => $row_sql['longitude'], 
		'eloignement' => $row_sql['eloignement'], 
		'altitude' => $row_sql['altitude'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('DepartementId', 'text', array('required' => false));
	$form = $form->add('CommuneACTUAL', 'text', array('required' => false));
	$form = $form->add('CommuneCHEFLIEU', 'text', array('required' => false));
	$form = $form->add('CommuneCDC', 'text', array('required' => false));
	$form = $form->add('CommuneRANG', 'text', array('required' => false));
	$form = $form->add('CommuneREG', 'text', array('required' => true));
	$form = $form->add('CommuneDEP', 'text', array('required' => true));
	$form = $form->add('CommuneCOM', 'text', array('required' => true));
	$form = $form->add('CommuneAR', 'text', array('required' => false));
	$form = $form->add('CommuneCT', 'text', array('required' => true));
	$form = $form->add('CommuneMODIF', 'text', array('required' => false));
	$form = $form->add('CommunePOLE', 'text', array('required' => false));
	$form = $form->add('CommuneTNCC', 'text', array('required' => false));
	$form = $form->add('CommuneARTMAJ', 'text', array('required' => false));
	$form = $form->add('CommuneNCC', 'text', array('required' => false));
	$form = $form->add('CommuneARTMIN', 'text', array('required' => false));
	$form = $form->add('CommuneNCCENR', 'text', array('required' => false));
	$form = $form->add('CommuneARTICLCT', 'text', array('required' => false));
	$form = $form->add('CommuneNCCCT', 'text', array('required' => false));
	$form = $form->add('CodePostal', 'text', array('required' => true));
	$form = $form->add('CommuneCodeINSEE', 'text', array('required' => true));
	$form = $form->add('ISO2', 'text', array('required' => false));
	$form = $form->add('SystemeGeodesique', 'text', array('required' => true));
	$form = $form->add('Projection', 'text', array('required' => true));
	$form = $form->add('Unite', 'text', array('required' => true));
	$form = $form->add('latitude', 'text', array('required' => true));
	$form = $form->add('longitude', 'text', array('required' => true));
	$form = $form->add('eloignement', 'text', array('required' => true));
	$form = $form->add('altitude', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `Communes` SET `DepartementId` = ?, `CommuneACTUAL` = ?, `CommuneCHEFLIEU` = ?, `CommuneCDC` = ?, `CommuneRANG` = ?, `CommuneREG` = ?, `CommuneDEP` = ?, `CommuneCOM` = ?, `CommuneAR` = ?, `CommuneCT` = ?, `CommuneMODIF` = ?, `CommunePOLE` = ?, `CommuneTNCC` = ?, `CommuneARTMAJ` = ?, `CommuneNCC` = ?, `CommuneARTMIN` = ?, `CommuneNCCENR` = ?, `CommuneARTICLCT` = ?, `CommuneNCCCT` = ?, `CodePostal` = ?, `CommuneCodeINSEE` = ?, `ISO2` = ?, `SystemeGeodesique` = ?, `Projection` = ?, `Unite` = ?, `latitude` = ?, `longitude` = ?, `eloignement` = ?, `altitude` = ? WHERE `CommuneId` = ?";
            $app['db']->executeUpdate($update_query, array($data['DepartementId'], $data['CommuneACTUAL'], $data['CommuneCHEFLIEU'], $data['CommuneCDC'], $data['CommuneRANG'], $data['CommuneREG'], $data['CommuneDEP'], $data['CommuneCOM'], $data['CommuneAR'], $data['CommuneCT'], $data['CommuneMODIF'], $data['CommunePOLE'], $data['CommuneTNCC'], $data['CommuneARTMAJ'], $data['CommuneNCC'], $data['CommuneARTMIN'], $data['CommuneNCCENR'], $data['CommuneARTICLCT'], $data['CommuneNCCCT'], $data['CodePostal'], $data['CommuneCodeINSEE'], $data['ISO2'], $data['SystemeGeodesique'], $data['Projection'], $data['Unite'], $data['latitude'], $data['longitude'], $data['eloignement'], $data['altitude'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Communes edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Communes_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('Communes/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('Communes_edit');



$app->match('/Communes/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Communes` WHERE `CommuneId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `Communes` WHERE `CommuneId` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'Communes deleted!',
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

    return $app->redirect($app['url_generator']->generate('Communes_list'));

})
->bind('Communes_delete');






