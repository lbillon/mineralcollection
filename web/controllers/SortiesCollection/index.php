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

$app->match('/SortiesCollection/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'SortieId', 
		'EchantillonId', 
		'PersonneId', 
		'SortieDate', 
		'SortieDatePrecis', 
		'SortieType', 
		'SortiePrecision', 
		'Prix', 

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
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `SortiesCollection`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `SortiesCollection`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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

$app->match('/SortiesCollection', function () use ($app) {
    
	$table_columns = array(
		'SortieId', 
		'EchantillonId', 
		'PersonneId', 
		'SortieDate', 
		'SortieDatePrecis', 
		'SortieType', 
		'SortiePrecision', 
		'Prix', 

    );

    $primary_key = "SortieId";	

    return $app['twig']->render('SortiesCollection/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('SortiesCollection_list');



$app->match('/SortiesCollection/create', function () use ($app) {
    
    $initial_data = array(
		'EchantillonId' => '', 
		'PersonneId' => '', 
		'SortieDate' => '', 
		'SortieDatePrecis' => '', 
		'SortieType' => '', 
		'SortiePrecision' => '', 
		'Prix' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('EchantillonId', 'text', array('required' => true));
	$form = $form->add('PersonneId', 'text', array('required' => false));
	$form = $form->add('SortieDate', 'text', array('required' => false));
	$form = $form->add('SortieDatePrecis', 'text', array('required' => true));
	$form = $form->add('SortieType', 'text', array('required' => true));
	$form = $form->add('SortiePrecision', 'textarea', array('required' => true));
	$form = $form->add('Prix', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `SortiesCollection` (`EchantillonId`, `PersonneId`, `SortieDate`, `SortieDatePrecis`, `SortieType`, `SortiePrecision`, `Prix`) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['EchantillonId'], $data['PersonneId'], $data['SortieDate'], $data['SortieDatePrecis'], $data['SortieType'], $data['SortiePrecision'], $data['Prix']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'SortiesCollection created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('SortiesCollection_list'));

        }
    }

    return $app['twig']->render('SortiesCollection/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('SortiesCollection_create');



$app->match('/SortiesCollection/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `SortiesCollection` WHERE `SortieId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('SortiesCollection_list'));
    }

    
    $initial_data = array(
		'EchantillonId' => $row_sql['EchantillonId'], 
		'PersonneId' => $row_sql['PersonneId'], 
		'SortieDate' => $row_sql['SortieDate'], 
		'SortieDatePrecis' => $row_sql['SortieDatePrecis'], 
		'SortieType' => $row_sql['SortieType'], 
		'SortiePrecision' => $row_sql['SortiePrecision'], 
		'Prix' => $row_sql['Prix'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('EchantillonId', 'text', array('required' => true));
	$form = $form->add('PersonneId', 'text', array('required' => false));
	$form = $form->add('SortieDate', 'text', array('required' => false));
	$form = $form->add('SortieDatePrecis', 'text', array('required' => true));
	$form = $form->add('SortieType', 'text', array('required' => true));
	$form = $form->add('SortiePrecision', 'textarea', array('required' => true));
	$form = $form->add('Prix', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `SortiesCollection` SET `EchantillonId` = ?, `PersonneId` = ?, `SortieDate` = ?, `SortieDatePrecis` = ?, `SortieType` = ?, `SortiePrecision` = ?, `Prix` = ? WHERE `SortieId` = ?";
            $app['db']->executeUpdate($update_query, array($data['EchantillonId'], $data['PersonneId'], $data['SortieDate'], $data['SortieDatePrecis'], $data['SortieType'], $data['SortiePrecision'], $data['Prix'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'SortiesCollection edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('SortiesCollection_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('SortiesCollection/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('SortiesCollection_edit');



$app->match('/SortiesCollection/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `SortiesCollection` WHERE `SortieId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `SortiesCollection` WHERE `SortieId` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'SortiesCollection deleted!',
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

    return $app->redirect($app['url_generator']->generate('SortiesCollection_list'));

})
->bind('SortiesCollection_delete');






