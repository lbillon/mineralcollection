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

$app->match('/Adresses/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'AdresseId', 
		'NumeroEtRueAdresse', 
		'PrecisionAdresse', 
		'CodePostal', 
		'Ville', 
		'Pays', 
		'NumTelFixe1', 
		'NumTelFixe2', 
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
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `Adresses`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `Adresses`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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

$app->match('/Adresses', function () use ($app) {
    
	$table_columns = array(
		'AdresseId', 
		'NumeroEtRueAdresse', 
		'PrecisionAdresse', 
		'CodePostal', 
		'Ville', 
		'Pays', 
		'NumTelFixe1', 
		'NumTelFixe2', 
		'Commentaire', 

    );

    $primary_key = "AdresseId";	

    return $app['twig']->render('Adresses/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('Adresses_list');



$app->match('/Adresses/create', function () use ($app) {
    
    $initial_data = array(
		'NumeroEtRueAdresse' => '', 
		'PrecisionAdresse' => '', 
		'CodePostal' => '', 
		'Ville' => '', 
		'Pays' => '', 
		'NumTelFixe1' => '', 
		'NumTelFixe2' => '', 
		'Commentaire' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('NumeroEtRueAdresse', 'text', array('required' => true));
	$form = $form->add('PrecisionAdresse', 'text', array('required' => true));
	$form = $form->add('CodePostal', 'text', array('required' => true));
	$form = $form->add('Ville', 'text', array('required' => true));
	$form = $form->add('Pays', 'text', array('required' => true));
	$form = $form->add('NumTelFixe1', 'text', array('required' => false));
	$form = $form->add('NumTelFixe2', 'text', array('required' => false));
	$form = $form->add('Commentaire', 'textarea', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `Adresses` (`NumeroEtRueAdresse`, `PrecisionAdresse`, `CodePostal`, `Ville`, `Pays`, `NumTelFixe1`, `NumTelFixe2`, `Commentaire`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['NumeroEtRueAdresse'], $data['PrecisionAdresse'], $data['CodePostal'], $data['Ville'], $data['Pays'], $data['NumTelFixe1'], $data['NumTelFixe2'], $data['Commentaire']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Adresses created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Adresses_list'));

        }
    }

    return $app['twig']->render('Adresses/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('Adresses_create');



$app->match('/Adresses/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Adresses` WHERE `AdresseId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('Adresses_list'));
    }

    
    $initial_data = array(
		'NumeroEtRueAdresse' => $row_sql['NumeroEtRueAdresse'], 
		'PrecisionAdresse' => $row_sql['PrecisionAdresse'], 
		'CodePostal' => $row_sql['CodePostal'], 
		'Ville' => $row_sql['Ville'], 
		'Pays' => $row_sql['Pays'], 
		'NumTelFixe1' => $row_sql['NumTelFixe1'], 
		'NumTelFixe2' => $row_sql['NumTelFixe2'], 
		'Commentaire' => $row_sql['Commentaire'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('NumeroEtRueAdresse', 'text', array('required' => true));
	$form = $form->add('PrecisionAdresse', 'text', array('required' => true));
	$form = $form->add('CodePostal', 'text', array('required' => true));
	$form = $form->add('Ville', 'text', array('required' => true));
	$form = $form->add('Pays', 'text', array('required' => true));
	$form = $form->add('NumTelFixe1', 'text', array('required' => false));
	$form = $form->add('NumTelFixe2', 'text', array('required' => false));
	$form = $form->add('Commentaire', 'textarea', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `Adresses` SET `NumeroEtRueAdresse` = ?, `PrecisionAdresse` = ?, `CodePostal` = ?, `Ville` = ?, `Pays` = ?, `NumTelFixe1` = ?, `NumTelFixe2` = ?, `Commentaire` = ? WHERE `AdresseId` = ?";
            $app['db']->executeUpdate($update_query, array($data['NumeroEtRueAdresse'], $data['PrecisionAdresse'], $data['CodePostal'], $data['Ville'], $data['Pays'], $data['NumTelFixe1'], $data['NumTelFixe2'], $data['Commentaire'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Adresses edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Adresses_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('Adresses/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('Adresses_edit');



$app->match('/Adresses/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Adresses` WHERE `AdresseId` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `Adresses` WHERE `AdresseId` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'Adresses deleted!',
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

    return $app->redirect($app['url_generator']->generate('Adresses_list'));

})
->bind('Adresses_delete');






