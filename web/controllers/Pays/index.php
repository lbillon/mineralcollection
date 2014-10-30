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

$app->match('/Pays/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
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
		'COG', 
		'Actual', 
		'CAPAY', 
		'CRPAY', 
		'ANI', 
		'LIBCOG', 
		'LIBENR', 
		'ANCNOM', 
		'ISO2', 
		'DrapeauPays', 

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
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `Pays`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `Pays`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
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

$app->match('/Pays', function () use ($app) {
    
	$table_columns = array(
		'COG', 
		'Actual', 
		'CAPAY', 
		'CRPAY', 
		'ANI', 
		'LIBCOG', 
		'LIBENR', 
		'ANCNOM', 
		'ISO2', 
		'DrapeauPays', 

    );

    $primary_key = "ISO2";	

    return $app['twig']->render('Pays/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('Pays_list');



$app->match('/Pays/create', function () use ($app) {
    
    $initial_data = array(
		'COG' => '', 
		'Actual' => '', 
		'CAPAY' => '', 
		'CRPAY' => '', 
		'ANI' => '', 
		'LIBCOG' => '', 
		'LIBENR' => '', 
		'ANCNOM' => '', 
		'ISO2' => '', 
		'DrapeauPays' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('COG', 'text', array('required' => false));
	$form = $form->add('Actual', 'text', array('required' => false));
	$form = $form->add('CAPAY', 'text', array('required' => false));
	$form = $form->add('CRPAY', 'text', array('required' => false));
	$form = $form->add('ANI', 'text', array('required' => false));
	$form = $form->add('LIBCOG', 'text', array('required' => false));
	$form = $form->add('LIBENR', 'text', array('required' => false));
	$form = $form->add('ANCNOM', 'text', array('required' => false));
	$form = $form->add('ISO2', 'text', array('required' => true));
	$form = $form->add('DrapeauPays', 'text', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `Pays` (`COG`, `Actual`, `CAPAY`, `CRPAY`, `ANI`, `LIBCOG`, `LIBENR`, `ANCNOM`, `ISO2`, `DrapeauPays`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['COG'], $data['Actual'], $data['CAPAY'], $data['CRPAY'], $data['ANI'], $data['LIBCOG'], $data['LIBENR'], $data['ANCNOM'], $data['ISO2'], $data['DrapeauPays']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Pays created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Pays_list'));

        }
    }

    return $app['twig']->render('Pays/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('Pays_create');



$app->match('/Pays/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Pays` WHERE `ISO2` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('Pays_list'));
    }

    
    $initial_data = array(
		'COG' => $row_sql['COG'], 
		'Actual' => $row_sql['Actual'], 
		'CAPAY' => $row_sql['CAPAY'], 
		'CRPAY' => $row_sql['CRPAY'], 
		'ANI' => $row_sql['ANI'], 
		'LIBCOG' => $row_sql['LIBCOG'], 
		'LIBENR' => $row_sql['LIBENR'], 
		'ANCNOM' => $row_sql['ANCNOM'], 
		'ISO2' => $row_sql['ISO2'], 
		'DrapeauPays' => $row_sql['DrapeauPays'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('COG', 'text', array('required' => false));
	$form = $form->add('Actual', 'text', array('required' => false));
	$form = $form->add('CAPAY', 'text', array('required' => false));
	$form = $form->add('CRPAY', 'text', array('required' => false));
	$form = $form->add('ANI', 'text', array('required' => false));
	$form = $form->add('LIBCOG', 'text', array('required' => false));
	$form = $form->add('LIBENR', 'text', array('required' => false));
	$form = $form->add('ANCNOM', 'text', array('required' => false));
	$form = $form->add('ISO2', 'text', array('required' => true));
	$form = $form->add('DrapeauPays', 'text', array('required' => false));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `Pays` SET `COG` = ?, `Actual` = ?, `CAPAY` = ?, `CRPAY` = ?, `ANI` = ?, `LIBCOG` = ?, `LIBENR` = ?, `ANCNOM` = ?, `ISO2` = ?, `DrapeauPays` = ? WHERE `ISO2` = ?";
            $app['db']->executeUpdate($update_query, array($data['COG'], $data['Actual'], $data['CAPAY'], $data['CRPAY'], $data['ANI'], $data['LIBCOG'], $data['LIBENR'], $data['ANCNOM'], $data['ISO2'], $data['DrapeauPays'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'Pays edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('Pays_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('Pays/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('Pays_edit');



$app->match('/Pays/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `Pays` WHERE `ISO2` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `Pays` WHERE `ISO2` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'Pays deleted!',
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

    return $app->redirect($app['url_generator']->generate('Pays_list'));

})
->bind('Pays_delete');






