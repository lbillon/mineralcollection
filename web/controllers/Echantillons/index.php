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

$app->match('/Echantillons/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'EchantillonID', 
		'ESC', 
		'EPE', 
		'Titre', 
		'Titre2', 
		'Description', 
		'SiteId', 
		'PrecisionLocalisation', 
		'PrecisionLocalisationSurEtiquette', 
		'CommuneId', 
		'DepartementId', 
		'RegionId', 
		'EtatId', 
		'ISO2', 
		'PersonneId', 
		'SortieId', 
		'Rangement', 
		'DateDecouverte', 
		'PrecisionDateDecouverte', 
		'PrecisionDecouverte', 
		'EchantillonCommentaires', 
		'EmptyTextUtility', 

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
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `Echantillons`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `Echantillons`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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

$app->match('/Echantillons', function () use ($app) {
    
	$table_columns = array(
		'EchantillonID', 
		'ESC', 
		'EPE', 
		'Titre', 
		'Titre2', 
		'Description', 
		'SiteId', 
		'PrecisionLocalisation', 
		'PrecisionLocalisationSurEtiquette', 
		'CommuneId', 
		'DepartementId', 
		'RegionId', 
		'EtatId', 
		'ISO2', 
		'PersonneId', 
		'SortieId', 
		'Rangement', 
		'DateDecouverte', 
		'PrecisionDateDecouverte', 
		'PrecisionDecouverte', 
		'EchantillonCommentaires', 
		'EmptyTextUtility', 

    );

    $primary_key = "EchantillonID";	

    return $app['twig']->render('Echantillons/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('Echantillons_list');



$app->match('/Echantillons/create', function () use ($app) {
    
    $initial_data = array(
		'ESC' => '', 
		'EPE' => '', 
		'Titre' => '', 
		'Titre2' => '', 
		'Description' => '', 
		'SiteId' => '', 
		'PrecisionLocalisation' => '', 
		'PrecisionLocalisationSurEtiquette' => '', 
		'CommuneId' => '', 
		'DepartementId' => '', 
		'RegionId' => '', 
		'EtatId' => '', 
		'ISO2' => '', 
		'PersonneId' => '', 
		'SortieId' => '', 
		'Rangement' => '', 
		'DateDecouverte' => '', 
		'PrecisionDateDecouverte' => '', 
		'PrecisionDecouverte' => '', 
		'EchantillonCommentaires' => '', 
		'EmptyTextUtility' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('ESC', 'text', array('required' => true));
	$form = $form->add('EPE', 'text', array('required' => true));
	$form = $form->add('Titre', 'text', array('required' => true));
	$form = $form->add('Titre2', 'text', array('required' => false));
	$form = $form->add('Description', 'textarea', array('required' => true));
	$form = $form->add('SiteId', 'text', array('required' => false));
	$form = $form->add('PrecisionLocalisation', 'textarea', array('required' => false));
	$form = $form->add('PrecisionLocalisationSurEtiquette', 'text', array('required' => false));
	$form = $form->add('CommuneId', 'text', array('required' => false));
	$form = $form->add('DepartementId', 'text', array('required' => false));
	$form = $form->add('RegionId', 'text', array('required' => false));
	$form = $form->add('EtatId', 'text', array('required' => false));
	$form = $form->add('ISO2', 'text', array('required' => false));
	$form = $form->add('PersonneId', 'text', array('required' => false));
	$form = $form->add('SortieId', 'text', array('required' => false));
	$form = $form->add('Rangement', 'text', array('required' => false));
	$form = $form->add('DateDecouverte', 'text', array('required' => false));
	$form = $form->add('PrecisionDateDecouverte', 'text', array('required' => false));
	$form = $form->add('PrecisionDecouverte', 'textarea', array('required' => true));
	$form = $form->add('EchantillonCommentaires', 'textarea', array('required' => true));
	$form = $form->add('EmptyTextUtility', 'textarea', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `Echantillons` (`ESC`, `EPE`, `Titre`, `Titre2`, `Description`, `SiteId`, `PrecisionLocalisation`, `PrecisionLocalisationSurEtiquette`, `CommuneId`, `DepartementId`, `RegionId`, `EtatId`, `ISO2`, `PersonneId`, `SortieId`, `Rangement`, `DateDecouverte`, `PrecisionDateDecouverte`, `PrecisionDecouverte`, `EchantillonCommentaires`, `EmptyTextUtility`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['ESC'], $data['EPE'], $data['Titre'], $data['Titre2'], $data['Description'], $data['SiteId'], $data['PrecisionLocalisation'], $data['PrecisionLocalisationSurEtiquette'], $data['CommuneId'], $data['DepartementId'], $data['RegionId'], $data['EtatId'], $data['ISO2'], $data['PersonneId'], $data['SortieId'], $data['Rangement'], $data['DateDecouverte'], $data['PrecisionDateDecouverte'], $data['PrecisionDecouverte'], $data['EchantillonCommentaires'], $data['EmptyTextUtility']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Echantillons created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Echantillons_list'));

        }
    }

    return $app['twig']->render('Echantillons/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('Echantillons_create');



$app->match('/Echantillons/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Echantillons` WHERE `EchantillonID` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('Echantillons_list'));
    }

    
    $initial_data = array(
		'ESC' => $row_sql['ESC'], 
		'EPE' => $row_sql['EPE'], 
		'Titre' => $row_sql['Titre'], 
		'Titre2' => $row_sql['Titre2'], 
		'Description' => $row_sql['Description'], 
		'SiteId' => $row_sql['SiteId'], 
		'PrecisionLocalisation' => $row_sql['PrecisionLocalisation'], 
		'PrecisionLocalisationSurEtiquette' => $row_sql['PrecisionLocalisationSurEtiquette'], 
		'CommuneId' => $row_sql['CommuneId'], 
		'DepartementId' => $row_sql['DepartementId'], 
		'RegionId' => $row_sql['RegionId'], 
		'EtatId' => $row_sql['EtatId'], 
		'ISO2' => $row_sql['ISO2'], 
		'PersonneId' => $row_sql['PersonneId'], 
		'SortieId' => $row_sql['SortieId'], 
		'Rangement' => $row_sql['Rangement'], 
		'DateDecouverte' => $row_sql['DateDecouverte'], 
		'PrecisionDateDecouverte' => $row_sql['PrecisionDateDecouverte'], 
		'PrecisionDecouverte' => $row_sql['PrecisionDecouverte'], 
		'EchantillonCommentaires' => $row_sql['EchantillonCommentaires'], 
		'EmptyTextUtility' => $row_sql['EmptyTextUtility'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('ESC', 'text', array('required' => true));
	$form = $form->add('EPE', 'text', array('required' => true));
	$form = $form->add('Titre', 'text', array('required' => true));
	$form = $form->add('Titre2', 'text', array('required' => false));
	$form = $form->add('Description', 'textarea', array('required' => true));
	$form = $form->add('SiteId', 'text', array('required' => false));
	$form = $form->add('PrecisionLocalisation', 'textarea', array('required' => false));
	$form = $form->add('PrecisionLocalisationSurEtiquette', 'text', array('required' => false));
	$form = $form->add('CommuneId', 'text', array('required' => false));
	$form = $form->add('DepartementId', 'text', array('required' => false));
	$form = $form->add('RegionId', 'text', array('required' => false));
	$form = $form->add('EtatId', 'text', array('required' => false));
	$form = $form->add('ISO2', 'text', array('required' => false));
	$form = $form->add('PersonneId', 'text', array('required' => false));
	$form = $form->add('SortieId', 'text', array('required' => false));
	$form = $form->add('Rangement', 'text', array('required' => false));
	$form = $form->add('DateDecouverte', 'text', array('required' => false));
	$form = $form->add('PrecisionDateDecouverte', 'text', array('required' => false));
	$form = $form->add('PrecisionDecouverte', 'textarea', array('required' => true));
	$form = $form->add('EchantillonCommentaires', 'textarea', array('required' => true));
	$form = $form->add('EmptyTextUtility', 'textarea', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `Echantillons` SET `ESC` = ?, `EPE` = ?, `Titre` = ?, `Titre2` = ?, `Description` = ?, `SiteId` = ?, `PrecisionLocalisation` = ?, `PrecisionLocalisationSurEtiquette` = ?, `CommuneId` = ?, `DepartementId` = ?, `RegionId` = ?, `EtatId` = ?, `ISO2` = ?, `PersonneId` = ?, `SortieId` = ?, `Rangement` = ?, `DateDecouverte` = ?, `PrecisionDateDecouverte` = ?, `PrecisionDecouverte` = ?, `EchantillonCommentaires` = ?, `EmptyTextUtility` = ? WHERE `EchantillonID` = ?";
            $app['db']->executeUpdate($update_query, array($data['ESC'], $data['EPE'], $data['Titre'], $data['Titre2'], $data['Description'], $data['SiteId'], $data['PrecisionLocalisation'], $data['PrecisionLocalisationSurEtiquette'], $data['CommuneId'], $data['DepartementId'], $data['RegionId'], $data['EtatId'], $data['ISO2'], $data['PersonneId'], $data['SortieId'], $data['Rangement'], $data['DateDecouverte'], $data['PrecisionDateDecouverte'], $data['PrecisionDecouverte'], $data['EchantillonCommentaires'], $data['EmptyTextUtility'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Echantillons edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Echantillons_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('Echantillons/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('Echantillons_edit');



$app->match('/Echantillons/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Echantillons` WHERE `EchantillonID` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `Echantillons` WHERE `EchantillonID` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'Echantillons deleted!',
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

    return $app->redirect($app['url_generator']->generate('Echantillons_list'));

})
->bind('Echantillons_delete');






