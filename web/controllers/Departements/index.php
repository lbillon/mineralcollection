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

$app->match('/Departements/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'DepartementREGION', 
		'DepartementDEP', 
		'DepartementCHEFLIEU', 
		'DepartementTNCC', 
		'DepartementNCC', 
		'DepartementNCCENR', 
		'ISO2', 
		'DepartementId', 
		'RegionId', 

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
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `Departements`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `Departements`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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

$app->match('/Departements', function () use ($app) {
    
	$table_columns = array(
		'DepartementREGION', 
		'DepartementDEP', 
		'DepartementCHEFLIEU', 
		'DepartementTNCC', 
		'DepartementNCC', 
		'DepartementNCCENR', 
		'ISO2', 
		'DepartementId', 
		'RegionId', 

    );

    $primary_key = "DepartementId";	

    return $app['twig']->render('Departements/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('Departements_list');



$app->match('/Departements/create', function () use ($app) {
    
    $initial_data = array(
		'DepartementREGION' => '', 
		'DepartementDEP' => '', 
		'DepartementCHEFLIEU' => '', 
		'DepartementTNCC' => '', 
		'DepartementNCC' => '', 
		'DepartementNCCENR' => '', 
		'ISO2' => '', 
		'RegionId' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('DepartementREGION', 'text', array('required' => true));
	$form = $form->add('DepartementDEP', 'text', array('required' => false));
	$form = $form->add('DepartementCHEFLIEU', 'text', array('required' => false));
	$form = $form->add('DepartementTNCC', 'text', array('required' => false));
	$form = $form->add('DepartementNCC', 'text', array('required' => false));
	$form = $form->add('DepartementNCCENR', 'text', array('required' => false));
	$form = $form->add('ISO2', 'text', array('required' => false));
	$form = $form->add('RegionId', 'text', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `Departements` (`DepartementREGION`, `DepartementDEP`, `DepartementCHEFLIEU`, `DepartementTNCC`, `DepartementNCC`, `DepartementNCCENR`, `ISO2`, `RegionId`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['DepartementREGION'], $data['DepartementDEP'], $data['DepartementCHEFLIEU'], $data['DepartementTNCC'], $data['DepartementNCC'], $data['DepartementNCCENR'], $data['ISO2'], $data['RegionId']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Departements created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Departements_list'));

        }
    }

    return $app['twig']->render('Departements/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('Departements_create');



$app->match('/Departements/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Departements` WHERE `DepartementId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('Departements_list'));
    }

    
    $initial_data = array(
		'DepartementREGION' => $row_sql['DepartementREGION'], 
		'DepartementDEP' => $row_sql['DepartementDEP'], 
		'DepartementCHEFLIEU' => $row_sql['DepartementCHEFLIEU'], 
		'DepartementTNCC' => $row_sql['DepartementTNCC'], 
		'DepartementNCC' => $row_sql['DepartementNCC'], 
		'DepartementNCCENR' => $row_sql['DepartementNCCENR'], 
		'ISO2' => $row_sql['ISO2'], 
		'RegionId' => $row_sql['RegionId'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('DepartementREGION', 'text', array('required' => true));
	$form = $form->add('DepartementDEP', 'text', array('required' => false));
	$form = $form->add('DepartementCHEFLIEU', 'text', array('required' => false));
	$form = $form->add('DepartementTNCC', 'text', array('required' => false));
	$form = $form->add('DepartementNCC', 'text', array('required' => false));
	$form = $form->add('DepartementNCCENR', 'text', array('required' => false));
	$form = $form->add('ISO2', 'text', array('required' => false));
	$form = $form->add('RegionId', 'text', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `Departements` SET `DepartementREGION` = ?, `DepartementDEP` = ?, `DepartementCHEFLIEU` = ?, `DepartementTNCC` = ?, `DepartementNCC` = ?, `DepartementNCCENR` = ?, `ISO2` = ?, `RegionId` = ? WHERE `DepartementId` = ?";
            $app['db']->executeUpdate($update_query, array($data['DepartementREGION'], $data['DepartementDEP'], $data['DepartementCHEFLIEU'], $data['DepartementTNCC'], $data['DepartementNCC'], $data['DepartementNCCENR'], $data['ISO2'], $data['RegionId'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Departements edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Departements_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('Departements/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('Departements_edit');



$app->match('/Departements/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Departements` WHERE `DepartementId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `Departements` WHERE `DepartementId` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'Departements deleted!',
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

    return $app->redirect($app['url_generator']->generate('Departements_list'));

})
->bind('Departements_delete');






