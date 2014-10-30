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

$app->match('/Personnes/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'PersonneId', 
		'Professionnel', 
		'PersonneMoraleNom', 
		'PersonneNom', 
		'PersonnePrenom', 
		'PersonneTelPortable', 
		'PersonneEmail', 
		'PersonneSiteWeb', 
		'PersonneIdEbay', 
		'Commentaire', 
		'PCPS', 

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
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `Personnes`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `Personnes`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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

$app->match('/Personnes', function () use ($app) {
    
	$table_columns = array(
		'PersonneId', 
		'Professionnel', 
		'PersonneMoraleNom', 
		'PersonneNom', 
		'PersonnePrenom', 
		'PersonneTelPortable', 
		'PersonneEmail', 
		'PersonneSiteWeb', 
		'PersonneIdEbay', 
		'Commentaire', 
		'PCPS', 

    );

    $primary_key = "PersonneId";	

    return $app['twig']->render('Personnes/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('Personnes_list');



$app->match('/Personnes/create', function () use ($app) {
    
    $initial_data = array(
		'Professionnel' => '', 
		'PersonneMoraleNom' => '', 
		'PersonneNom' => '', 
		'PersonnePrenom' => '', 
		'PersonneTelPortable' => '', 
		'PersonneEmail' => '', 
		'PersonneSiteWeb' => '', 
		'PersonneIdEbay' => '', 
		'Commentaire' => '', 
		'PCPS' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('Professionnel', 'text', array('required' => true));
	$form = $form->add('PersonneMoraleNom', 'text', array('required' => true));
	$form = $form->add('PersonneNom', 'text', array('required' => true));
	$form = $form->add('PersonnePrenom', 'text', array('required' => true));
	$form = $form->add('PersonneTelPortable', 'text', array('required' => true));
	$form = $form->add('PersonneEmail', 'text', array('required' => true));
	$form = $form->add('PersonneSiteWeb', 'text', array('required' => true));
	$form = $form->add('PersonneIdEbay', 'text', array('required' => true));
	$form = $form->add('Commentaire', 'textarea', array('required' => true));
	$form = $form->add('PCPS', 'text', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `Personnes` (`Professionnel`, `PersonneMoraleNom`, `PersonneNom`, `PersonnePrenom`, `PersonneTelPortable`, `PersonneEmail`, `PersonneSiteWeb`, `PersonneIdEbay`, `Commentaire`, `PCPS`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['Professionnel'], $data['PersonneMoraleNom'], $data['PersonneNom'], $data['PersonnePrenom'], $data['PersonneTelPortable'], $data['PersonneEmail'], $data['PersonneSiteWeb'], $data['PersonneIdEbay'], $data['Commentaire'], $data['PCPS']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Personnes created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Personnes_list'));

        }
    }

    return $app['twig']->render('Personnes/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('Personnes_create');



$app->match('/Personnes/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Personnes` WHERE `PersonneId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('Personnes_list'));
    }

    
    $initial_data = array(
		'Professionnel' => $row_sql['Professionnel'], 
		'PersonneMoraleNom' => $row_sql['PersonneMoraleNom'], 
		'PersonneNom' => $row_sql['PersonneNom'], 
		'PersonnePrenom' => $row_sql['PersonnePrenom'], 
		'PersonneTelPortable' => $row_sql['PersonneTelPortable'], 
		'PersonneEmail' => $row_sql['PersonneEmail'], 
		'PersonneSiteWeb' => $row_sql['PersonneSiteWeb'], 
		'PersonneIdEbay' => $row_sql['PersonneIdEbay'], 
		'Commentaire' => $row_sql['Commentaire'], 
		'PCPS' => $row_sql['PCPS'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('Professionnel', 'text', array('required' => true));
	$form = $form->add('PersonneMoraleNom', 'text', array('required' => true));
	$form = $form->add('PersonneNom', 'text', array('required' => true));
	$form = $form->add('PersonnePrenom', 'text', array('required' => true));
	$form = $form->add('PersonneTelPortable', 'text', array('required' => true));
	$form = $form->add('PersonneEmail', 'text', array('required' => true));
	$form = $form->add('PersonneSiteWeb', 'text', array('required' => true));
	$form = $form->add('PersonneIdEbay', 'text', array('required' => true));
	$form = $form->add('Commentaire', 'textarea', array('required' => true));
	$form = $form->add('PCPS', 'text', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `Personnes` SET `Professionnel` = ?, `PersonneMoraleNom` = ?, `PersonneNom` = ?, `PersonnePrenom` = ?, `PersonneTelPortable` = ?, `PersonneEmail` = ?, `PersonneSiteWeb` = ?, `PersonneIdEbay` = ?, `Commentaire` = ?, `PCPS` = ? WHERE `PersonneId` = ?";
            $app['db']->executeUpdate($update_query, array($data['Professionnel'], $data['PersonneMoraleNom'], $data['PersonneNom'], $data['PersonnePrenom'], $data['PersonneTelPortable'], $data['PersonneEmail'], $data['PersonneSiteWeb'], $data['PersonneIdEbay'], $data['Commentaire'], $data['PCPS'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Personnes edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Personnes_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('Personnes/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('Personnes_edit');



$app->match('/Personnes/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Personnes` WHERE `PersonneId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `Personnes` WHERE `PersonneId` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'Personnes deleted!',
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

    return $app->redirect($app['url_generator']->generate('Personnes_list'));

})
->bind('Personnes_delete');






