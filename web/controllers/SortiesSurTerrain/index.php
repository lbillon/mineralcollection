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

$app->match('/SortiesSurTerrain/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'SortieSurTerrainId', 
		'SiteId', 
		'Type', 
		'Date', 
		'PersonneId', 
		'SortieSurTerrainDescription', 

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
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `SortiesSurTerrain`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `SortiesSurTerrain`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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

$app->match('/SortiesSurTerrain', function () use ($app) {
    
	$table_columns = array(
		'SortieSurTerrainId', 
		'SiteId', 
		'Type', 
		'Date', 
		'PersonneId', 
		'SortieSurTerrainDescription', 

    );

    $primary_key = "SortieSurTerrainId";	

    return $app['twig']->render('SortiesSurTerrain/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('SortiesSurTerrain_list');



$app->match('/SortiesSurTerrain/create', function () use ($app) {
    
    $initial_data = array(
		'SiteId' => '', 
		'Type' => '', 
		'Date' => '', 
		'PersonneId' => '', 
		'SortieSurTerrainDescription' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('SiteId', 'text', array('required' => true));
	$form = $form->add('Type', 'text', array('required' => true));
	$form = $form->add('Date', 'text', array('required' => true));
	$form = $form->add('PersonneId', 'text', array('required' => false));
	$form = $form->add('SortieSurTerrainDescription', 'textarea', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `SortiesSurTerrain` (`SiteId`, `Type`, `Date`, `PersonneId`, `SortieSurTerrainDescription`) VALUES (?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['SiteId'], $data['Type'], $data['Date'], $data['PersonneId'], $data['SortieSurTerrainDescription']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'SortiesSurTerrain created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('SortiesSurTerrain_list'));

        }
    }

    return $app['twig']->render('SortiesSurTerrain/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('SortiesSurTerrain_create');



$app->match('/SortiesSurTerrain/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `SortiesSurTerrain` WHERE `SortieSurTerrainId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('SortiesSurTerrain_list'));
    }

    
    $initial_data = array(
		'SiteId' => $row_sql['SiteId'], 
		'Type' => $row_sql['Type'], 
		'Date' => $row_sql['Date'], 
		'PersonneId' => $row_sql['PersonneId'], 
		'SortieSurTerrainDescription' => $row_sql['SortieSurTerrainDescription'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('SiteId', 'text', array('required' => true));
	$form = $form->add('Type', 'text', array('required' => true));
	$form = $form->add('Date', 'text', array('required' => true));
	$form = $form->add('PersonneId', 'text', array('required' => false));
	$form = $form->add('SortieSurTerrainDescription', 'textarea', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `SortiesSurTerrain` SET `SiteId` = ?, `Type` = ?, `Date` = ?, `PersonneId` = ?, `SortieSurTerrainDescription` = ? WHERE `SortieSurTerrainId` = ?";
            $app['db']->executeUpdate($update_query, array($data['SiteId'], $data['Type'], $data['Date'], $data['PersonneId'], $data['SortieSurTerrainDescription'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'SortiesSurTerrain edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('SortiesSurTerrain_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('SortiesSurTerrain/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('SortiesSurTerrain_edit');



$app->match('/SortiesSurTerrain/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `SortiesSurTerrain` WHERE `SortieSurTerrainId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `SortiesSurTerrain` WHERE `SortieSurTerrainId` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'SortiesSurTerrain deleted!',
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

    return $app->redirect($app['url_generator']->generate('SortiesSurTerrain_list'));

})
->bind('SortiesSurTerrain_delete');






