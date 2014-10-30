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

$app->match('/Echanges/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'EchangeId', 
		'PersonneId', 
		'EchangeDate', 
		'EchangeDatePrecision', 
		'Occasion', 
		'ManifestationId', 
		'Commentaire', 

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
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `Echanges`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `Echanges`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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

$app->match('/Echanges', function () use ($app) {
    
	$table_columns = array(
		'EchangeId', 
		'PersonneId', 
		'EchangeDate', 
		'EchangeDatePrecision', 
		'Occasion', 
		'ManifestationId', 
		'Commentaire', 

    );

    $primary_key = "EchangeId";	

    return $app['twig']->render('Echanges/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('Echanges_list');



$app->match('/Echanges/create', function () use ($app) {
    
    $initial_data = array(
		'PersonneId' => '', 
		'EchangeDate' => '', 
		'EchangeDatePrecision' => '', 
		'Occasion' => '', 
		'ManifestationId' => '', 
		'Commentaire' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('PersonneId', 'text', array('required' => false));
	$form = $form->add('EchangeDate', 'text', array('required' => true));
	$form = $form->add('EchangeDatePrecision', 'text', array('required' => true));
	$form = $form->add('Occasion', 'textarea', array('required' => true));
	$form = $form->add('ManifestationId', 'text', array('required' => false));
	$form = $form->add('Commentaire', 'textarea', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `Echanges` (`PersonneId`, `EchangeDate`, `EchangeDatePrecision`, `Occasion`, `ManifestationId`, `Commentaire`) VALUES (?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['PersonneId'], $data['EchangeDate'], $data['EchangeDatePrecision'], $data['Occasion'], $data['ManifestationId'], $data['Commentaire']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Echanges created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Echanges_list'));

        }
    }

    return $app['twig']->render('Echanges/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('Echanges_create');



$app->match('/Echanges/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Echanges` WHERE `EchangeId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('Echanges_list'));
    }

    
    $initial_data = array(
		'PersonneId' => $row_sql['PersonneId'], 
		'EchangeDate' => $row_sql['EchangeDate'], 
		'EchangeDatePrecision' => $row_sql['EchangeDatePrecision'], 
		'Occasion' => $row_sql['Occasion'], 
		'ManifestationId' => $row_sql['ManifestationId'], 
		'Commentaire' => $row_sql['Commentaire'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('PersonneId', 'text', array('required' => false));
	$form = $form->add('EchangeDate', 'text', array('required' => true));
	$form = $form->add('EchangeDatePrecision', 'text', array('required' => true));
	$form = $form->add('Occasion', 'textarea', array('required' => true));
	$form = $form->add('ManifestationId', 'text', array('required' => false));
	$form = $form->add('Commentaire', 'textarea', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `Echanges` SET `PersonneId` = ?, `EchangeDate` = ?, `EchangeDatePrecision` = ?, `Occasion` = ?, `ManifestationId` = ?, `Commentaire` = ? WHERE `EchangeId` = ?";
            $app['db']->executeUpdate($update_query, array($data['PersonneId'], $data['EchangeDate'], $data['EchangeDatePrecision'], $data['Occasion'], $data['ManifestationId'], $data['Commentaire'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Echanges edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Echanges_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('Echanges/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('Echanges_edit');



$app->match('/Echanges/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Echanges` WHERE `EchangeId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `Echanges` WHERE `EchangeId` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'Echanges deleted!',
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

    return $app->redirect($app['url_generator']->generate('Echanges_list'));

})
->bind('Echanges_delete');






