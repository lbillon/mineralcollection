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

$app->match('/Regions/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'RegionId', 
		'EtatId', 
		'RegionREGION', 
		'RegionCHEFLIEU', 
		'RegionTNCC', 
		'RegionNCC', 
		'RegionNCCENR', 
		'ISO2', 
		'RegionDrapeau', 

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
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `Regions`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `Regions`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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

$app->match('/Regions', function () use ($app) {
    
	$table_columns = array(
		'RegionId', 
		'EtatId', 
		'RegionREGION', 
		'RegionCHEFLIEU', 
		'RegionTNCC', 
		'RegionNCC', 
		'RegionNCCENR', 
		'ISO2', 
		'RegionDrapeau', 

    );

    $primary_key = "RegionId";	

    return $app['twig']->render('Regions/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('Regions_list');



$app->match('/Regions/create', function () use ($app) {
    
    $initial_data = array(
		'EtatId' => '', 
		'RegionREGION' => '', 
		'RegionCHEFLIEU' => '', 
		'RegionTNCC' => '', 
		'RegionNCC' => '', 
		'RegionNCCENR' => '', 
		'ISO2' => '', 
		'RegionDrapeau' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('EtatId', 'text', array('required' => false));
	$form = $form->add('RegionREGION', 'text', array('required' => false));
	$form = $form->add('RegionCHEFLIEU', 'text', array('required' => false));
	$form = $form->add('RegionTNCC', 'text', array('required' => false));
	$form = $form->add('RegionNCC', 'text', array('required' => false));
	$form = $form->add('RegionNCCENR', 'text', array('required' => false));
	$form = $form->add('ISO2', 'text', array('required' => false));
	$form = $form->add('RegionDrapeau', 'text', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `Regions` (`EtatId`, `RegionREGION`, `RegionCHEFLIEU`, `RegionTNCC`, `RegionNCC`, `RegionNCCENR`, `ISO2`, `RegionDrapeau`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['EtatId'], $data['RegionREGION'], $data['RegionCHEFLIEU'], $data['RegionTNCC'], $data['RegionNCC'], $data['RegionNCCENR'], $data['ISO2'], $data['RegionDrapeau']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Regions created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Regions_list'));

        }
    }

    return $app['twig']->render('Regions/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('Regions_create');



$app->match('/Regions/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Regions` WHERE `RegionId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('Regions_list'));
    }

    
    $initial_data = array(
		'EtatId' => $row_sql['EtatId'], 
		'RegionREGION' => $row_sql['RegionREGION'], 
		'RegionCHEFLIEU' => $row_sql['RegionCHEFLIEU'], 
		'RegionTNCC' => $row_sql['RegionTNCC'], 
		'RegionNCC' => $row_sql['RegionNCC'], 
		'RegionNCCENR' => $row_sql['RegionNCCENR'], 
		'ISO2' => $row_sql['ISO2'], 
		'RegionDrapeau' => $row_sql['RegionDrapeau'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('EtatId', 'text', array('required' => false));
	$form = $form->add('RegionREGION', 'text', array('required' => false));
	$form = $form->add('RegionCHEFLIEU', 'text', array('required' => false));
	$form = $form->add('RegionTNCC', 'text', array('required' => false));
	$form = $form->add('RegionNCC', 'text', array('required' => false));
	$form = $form->add('RegionNCCENR', 'text', array('required' => false));
	$form = $form->add('ISO2', 'text', array('required' => false));
	$form = $form->add('RegionDrapeau', 'text', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `Regions` SET `EtatId` = ?, `RegionREGION` = ?, `RegionCHEFLIEU` = ?, `RegionTNCC` = ?, `RegionNCC` = ?, `RegionNCCENR` = ?, `ISO2` = ?, `RegionDrapeau` = ? WHERE `RegionId` = ?";
            $app['db']->executeUpdate($update_query, array($data['EtatId'], $data['RegionREGION'], $data['RegionCHEFLIEU'], $data['RegionTNCC'], $data['RegionNCC'], $data['RegionNCCENR'], $data['ISO2'], $data['RegionDrapeau'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Regions edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Regions_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('Regions/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('Regions_edit');



$app->match('/Regions/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Regions` WHERE `RegionId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `Regions` WHERE `RegionId` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'Regions deleted!',
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

    return $app->redirect($app['url_generator']->generate('Regions_list'));

})
->bind('Regions_delete');






