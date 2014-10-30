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

$app->match('/Mineraux/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'MineralId', 
		'MineralSynonymeBool', 
		'MineralSynonyme', 
		'MineralNom', 
		'MineralStatut', 
		'MineralClasseChimique', 
		'MineralFormuleChimique', 

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
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `Mineraux`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `Mineraux`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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

$app->match('/Mineraux', function () use ($app) {
    
	$table_columns = array(
		'MineralId', 
		'MineralSynonymeBool', 
		'MineralSynonyme', 
		'MineralNom', 
		'MineralStatut', 
		'MineralClasseChimique', 
		'MineralFormuleChimique', 

    );

    $primary_key = "MineralId";	

    return $app['twig']->render('Mineraux/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('Mineraux_list');



$app->match('/Mineraux/create', function () use ($app) {
    
    $initial_data = array(
		'MineralSynonymeBool' => '', 
		'MineralSynonyme' => '', 
		'MineralNom' => '', 
		'MineralStatut' => '', 
		'MineralClasseChimique' => '', 
		'MineralFormuleChimique' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('MineralSynonymeBool', 'text', array('required' => true));
	$form = $form->add('MineralSynonyme', 'text', array('required' => true));
	$form = $form->add('MineralNom', 'text', array('required' => true));
	$form = $form->add('MineralStatut', 'text', array('required' => true));
	$form = $form->add('MineralClasseChimique', 'text', array('required' => true));
	$form = $form->add('MineralFormuleChimique', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `Mineraux` (`MineralSynonymeBool`, `MineralSynonyme`, `MineralNom`, `MineralStatut`, `MineralClasseChimique`, `MineralFormuleChimique`) VALUES (?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['MineralSynonymeBool'], $data['MineralSynonyme'], $data['MineralNom'], $data['MineralStatut'], $data['MineralClasseChimique'], $data['MineralFormuleChimique']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Mineraux created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Mineraux_list'));

        }
    }

    return $app['twig']->render('Mineraux/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('Mineraux_create');



$app->match('/Mineraux/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Mineraux` WHERE `MineralId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('Mineraux_list'));
    }

    
    $initial_data = array(
		'MineralSynonymeBool' => $row_sql['MineralSynonymeBool'], 
		'MineralSynonyme' => $row_sql['MineralSynonyme'], 
		'MineralNom' => $row_sql['MineralNom'], 
		'MineralStatut' => $row_sql['MineralStatut'], 
		'MineralClasseChimique' => $row_sql['MineralClasseChimique'], 
		'MineralFormuleChimique' => $row_sql['MineralFormuleChimique'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('MineralSynonymeBool', 'text', array('required' => true));
	$form = $form->add('MineralSynonyme', 'text', array('required' => true));
	$form = $form->add('MineralNom', 'text', array('required' => true));
	$form = $form->add('MineralStatut', 'text', array('required' => true));
	$form = $form->add('MineralClasseChimique', 'text', array('required' => true));
	$form = $form->add('MineralFormuleChimique', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `Mineraux` SET `MineralSynonymeBool` = ?, `MineralSynonyme` = ?, `MineralNom` = ?, `MineralStatut` = ?, `MineralClasseChimique` = ?, `MineralFormuleChimique` = ? WHERE `MineralId` = ?";
            $app['db']->executeUpdate($update_query, array($data['MineralSynonymeBool'], $data['MineralSynonyme'], $data['MineralNom'], $data['MineralStatut'], $data['MineralClasseChimique'], $data['MineralFormuleChimique'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Mineraux edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Mineraux_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('Mineraux/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('Mineraux_edit');



$app->match('/Mineraux/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Mineraux` WHERE `MineralId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `Mineraux` WHERE `MineralId` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'Mineraux deleted!',
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

    return $app->redirect($app['url_generator']->generate('Mineraux_list'));

})
->bind('Mineraux_delete');






