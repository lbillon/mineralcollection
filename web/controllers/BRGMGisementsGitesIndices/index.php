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

$app->match('/BRGMGisementsGitesIndices/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'NumFiche', 
		'NomSite', 
		'Production', 
		'Importance', 
		'Substance', 
		'Commune', 
		'XL2etenduMetrique', 
		'YL2etenduMetrique', 

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
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `BRGMGisementsGitesIndices`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `BRGMGisementsGitesIndices`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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

$app->match('/BRGMGisementsGitesIndices', function () use ($app) {
    
	$table_columns = array(
		'NumFiche', 
		'NomSite', 
		'Production', 
		'Importance', 
		'Substance', 
		'Commune', 
		'XL2etenduMetrique', 
		'YL2etenduMetrique', 

    );

    $primary_key = "NumFiche";	

    return $app['twig']->render('BRGMGisementsGitesIndices/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('BRGMGisementsGitesIndices_list');



$app->match('/BRGMGisementsGitesIndices/create', function () use ($app) {
    
    $initial_data = array(
		'NumFiche' => '', 
		'NomSite' => '', 
		'Production' => '', 
		'Importance' => '', 
		'Substance' => '', 
		'Commune' => '', 
		'XL2etenduMetrique' => '', 
		'YL2etenduMetrique' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('NumFiche', 'text', array('required' => true));
	$form = $form->add('NomSite', 'text', array('required' => true));
	$form = $form->add('Production', 'text', array('required' => true));
	$form = $form->add('Importance', 'text', array('required' => true));
	$form = $form->add('Substance', 'text', array('required' => true));
	$form = $form->add('Commune', 'text', array('required' => false));
	$form = $form->add('XL2etenduMetrique', 'text', array('required' => false));
	$form = $form->add('YL2etenduMetrique', 'text', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `BRGMGisementsGitesIndices` (`NumFiche`, `NomSite`, `Production`, `Importance`, `Substance`, `Commune`, `XL2etenduMetrique`, `YL2etenduMetrique`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['NumFiche'], $data['NomSite'], $data['Production'], $data['Importance'], $data['Substance'], $data['Commune'], $data['XL2etenduMetrique'], $data['YL2etenduMetrique']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'BRGMGisementsGitesIndices created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('BRGMGisementsGitesIndices_list'));

        }
    }

    return $app['twig']->render('BRGMGisementsGitesIndices/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('BRGMGisementsGitesIndices_create');



$app->match('/BRGMGisementsGitesIndices/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `BRGMGisementsGitesIndices` WHERE `NumFiche` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('BRGMGisementsGitesIndices_list'));
    }

    
    $initial_data = array(
		'NumFiche' => $row_sql['NumFiche'], 
		'NomSite' => $row_sql['NomSite'], 
		'Production' => $row_sql['Production'], 
		'Importance' => $row_sql['Importance'], 
		'Substance' => $row_sql['Substance'], 
		'Commune' => $row_sql['Commune'], 
		'XL2etenduMetrique' => $row_sql['XL2etenduMetrique'], 
		'YL2etenduMetrique' => $row_sql['YL2etenduMetrique'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('NumFiche', 'text', array('required' => true));
	$form = $form->add('NomSite', 'text', array('required' => true));
	$form = $form->add('Production', 'text', array('required' => true));
	$form = $form->add('Importance', 'text', array('required' => true));
	$form = $form->add('Substance', 'text', array('required' => true));
	$form = $form->add('Commune', 'text', array('required' => false));
	$form = $form->add('XL2etenduMetrique', 'text', array('required' => false));
	$form = $form->add('YL2etenduMetrique', 'text', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `BRGMGisementsGitesIndices` SET `NumFiche` = ?, `NomSite` = ?, `Production` = ?, `Importance` = ?, `Substance` = ?, `Commune` = ?, `XL2etenduMetrique` = ?, `YL2etenduMetrique` = ? WHERE `NumFiche` = ?";
            $app['db']->executeUpdate($update_query, array($data['NumFiche'], $data['NomSite'], $data['Production'], $data['Importance'], $data['Substance'], $data['Commune'], $data['XL2etenduMetrique'], $data['YL2etenduMetrique'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'BRGMGisementsGitesIndices edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('BRGMGisementsGitesIndices_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('BRGMGisementsGitesIndices/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('BRGMGisementsGitesIndices_edit');



$app->match('/BRGMGisementsGitesIndices/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `BRGMGisementsGitesIndices` WHERE `NumFiche` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `BRGMGisementsGitesIndices` WHERE `NumFiche` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'BRGMGisementsGitesIndices deleted!',
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

    return $app->redirect($app['url_generator']->generate('BRGMGisementsGitesIndices_list'));

})
->bind('BRGMGisementsGitesIndices_delete');






