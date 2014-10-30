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

$app->match('/Etats/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'EtatId', 
		'ISO2', 
		'EtatReel', 
		'EtatNom', 
		'EtatDrapeau', 

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
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `Etats`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `Etats`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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

$app->match('/Etats', function () use ($app) {
    
	$table_columns = array(
		'EtatId', 
		'ISO2', 
		'EtatReel', 
		'EtatNom', 
		'EtatDrapeau', 

    );

    $primary_key = "EtatId";	

    return $app['twig']->render('Etats/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('Etats_list');



$app->match('/Etats/create', function () use ($app) {
    
    $initial_data = array(
		'ISO2' => '', 
		'EtatReel' => '', 
		'EtatNom' => '', 
		'EtatDrapeau' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('ISO2', 'text', array('required' => true));
	$form = $form->add('EtatReel', 'text', array('required' => true));
	$form = $form->add('EtatNom', 'text', array('required' => true));
	$form = $form->add('EtatDrapeau', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `Etats` (`ISO2`, `EtatReel`, `EtatNom`, `EtatDrapeau`) VALUES (?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['ISO2'], $data['EtatReel'], $data['EtatNom'], $data['EtatDrapeau']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Etats created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Etats_list'));

        }
    }

    return $app['twig']->render('Etats/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('Etats_create');



$app->match('/Etats/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Etats` WHERE `EtatId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('Etats_list'));
    }

    
    $initial_data = array(
		'ISO2' => $row_sql['ISO2'], 
		'EtatReel' => $row_sql['EtatReel'], 
		'EtatNom' => $row_sql['EtatNom'], 
		'EtatDrapeau' => $row_sql['EtatDrapeau'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('ISO2', 'text', array('required' => true));
	$form = $form->add('EtatReel', 'text', array('required' => true));
	$form = $form->add('EtatNom', 'text', array('required' => true));
	$form = $form->add('EtatDrapeau', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `Etats` SET `ISO2` = ?, `EtatReel` = ?, `EtatNom` = ?, `EtatDrapeau` = ? WHERE `EtatId` = ?";
            $app['db']->executeUpdate($update_query, array($data['ISO2'], $data['EtatReel'], $data['EtatNom'], $data['EtatDrapeau'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Etats edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Etats_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('Etats/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('Etats_edit');



$app->match('/Etats/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Etats` WHERE `EtatId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `Etats` WHERE `EtatId` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'Etats deleted!',
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

    return $app->redirect($app['url_generator']->generate('Etats_list'));

})
->bind('Etats_delete');






