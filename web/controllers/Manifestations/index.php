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

$app->match('/Manifestations/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'ManifestationId', 
		'NomManifestation', 
		'TypeManifestation', 
		'VilleManifestation', 
		'CommuneId', 
		'DateManifestation', 
		'CommentairesManifestation', 

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
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `Manifestations`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `Manifestations`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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

$app->match('/Manifestations', function () use ($app) {
    
	$table_columns = array(
		'ManifestationId', 
		'NomManifestation', 
		'TypeManifestation', 
		'VilleManifestation', 
		'CommuneId', 
		'DateManifestation', 
		'CommentairesManifestation', 

    );

    $primary_key = "ManifestationId";	

    return $app['twig']->render('Manifestations/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('Manifestations_list');



$app->match('/Manifestations/create', function () use ($app) {
    
    $initial_data = array(
		'NomManifestation' => '', 
		'TypeManifestation' => '', 
		'VilleManifestation' => '', 
		'CommuneId' => '', 
		'DateManifestation' => '', 
		'CommentairesManifestation' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('NomManifestation', 'text', array('required' => true));
	$form = $form->add('TypeManifestation', 'text', array('required' => true));
	$form = $form->add('VilleManifestation', 'text', array('required' => true));
	$form = $form->add('CommuneId', 'text', array('required' => false));
	$form = $form->add('DateManifestation', 'text', array('required' => true));
	$form = $form->add('CommentairesManifestation', 'textarea', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `Manifestations` (`NomManifestation`, `TypeManifestation`, `VilleManifestation`, `CommuneId`, `DateManifestation`, `CommentairesManifestation`) VALUES (?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['NomManifestation'], $data['TypeManifestation'], $data['VilleManifestation'], $data['CommuneId'], $data['DateManifestation'], $data['CommentairesManifestation']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Manifestations created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Manifestations_list'));

        }
    }

    return $app['twig']->render('Manifestations/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('Manifestations_create');



$app->match('/Manifestations/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Manifestations` WHERE `ManifestationId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('Manifestations_list'));
    }

    
    $initial_data = array(
		'NomManifestation' => $row_sql['NomManifestation'], 
		'TypeManifestation' => $row_sql['TypeManifestation'], 
		'VilleManifestation' => $row_sql['VilleManifestation'], 
		'CommuneId' => $row_sql['CommuneId'], 
		'DateManifestation' => $row_sql['DateManifestation'], 
		'CommentairesManifestation' => $row_sql['CommentairesManifestation'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('NomManifestation', 'text', array('required' => true));
	$form = $form->add('TypeManifestation', 'text', array('required' => true));
	$form = $form->add('VilleManifestation', 'text', array('required' => true));
	$form = $form->add('CommuneId', 'text', array('required' => false));
	$form = $form->add('DateManifestation', 'text', array('required' => true));
	$form = $form->add('CommentairesManifestation', 'textarea', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `Manifestations` SET `NomManifestation` = ?, `TypeManifestation` = ?, `VilleManifestation` = ?, `CommuneId` = ?, `DateManifestation` = ?, `CommentairesManifestation` = ? WHERE `ManifestationId` = ?";
            $app['db']->executeUpdate($update_query, array($data['NomManifestation'], $data['TypeManifestation'], $data['VilleManifestation'], $data['CommuneId'], $data['DateManifestation'], $data['CommentairesManifestation'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Manifestations edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Manifestations_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('Manifestations/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('Manifestations_edit');



$app->match('/Manifestations/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Manifestations` WHERE `ManifestationId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `Manifestations` WHERE `ManifestationId` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'Manifestations deleted!',
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

    return $app->redirect($app['url_generator']->generate('Manifestations_list'));

})
->bind('Manifestations_delete');






