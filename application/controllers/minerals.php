<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Minerals extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
	}

	public function _do_output($output = null)
	{
		$this->load->view('minerals.php',$output);
	}


    public function index()
    {
        $this->_do_output((object)array('output' => $this->load->view('home.html','',true) , 'js_files' => array() , 'css_files' => array()));
    }

    public function map()
    {
        $this->_do_output((object)array('output' => $this->load->view('map.html','',true) , 'js_files' => array() , 'css_files' => array()));
    }

	public function sites()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('Sites');
			$crud->set_subject('Site');
			$crud->display_as('CommuneId','Commune');
			$crud->set_relation('CommuneId','Communes','CommuneNCCENR');


			$output = $crud->render();

			$this->_do_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}


}