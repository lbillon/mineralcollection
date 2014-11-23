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

	public function Acquisitions()
    {
    	try{
			$crud = new grocery_CRUD();
        	$crud->set_table('acquisitions');
        	$crud->set_relation('EchantillonId','Echantillons','Titre');
			$crud->set_relation('PersonneId','Personnes','PersonneMoraleNom');
			
			$crud->set_parent_add_form('EchantillonId','/index.php/minerals/Echantillons/add');
			$crud->set_parent_add_form('PersonneId','/index.php/minerals/Personnes/add');
		
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
    }
	
	public function Adresses(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('adresses');
        	
		
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
	
	public function Communes(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('communes');
        	$crud->set_relation('DepartementId','Departements','DepartementNCCENR');
			
		
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
	
	public function Departements(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('departements');
        	$crud->set_relation('RegionId','Regions','RegionNCCENR');
			
		
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
	
	public function Echanges(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('echanges');
        	$crud->set_relation('PersonneId','Personnes','PersonneMoraleNom');
			
		
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
	
	public function Echantillons(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('echantillons')
        	->set_relation('PersonneId','Personnes','PersonneMoraleNom')
			->set_relation('RegionId','Regions','RegionNCCENR')
			->set_relation('DepartementId','Departements','DepartementNCCENR')
			->set_relation('SortieId','SortiesCollection','SortiePrecision')
			->set_relation('SiteId','Sites','SiteNom')
			->set_relation('CommuneId', 'Communes', 'CommuneNCCENR')
			->set_relation('EtatId','Etats','EtatNom');
			
		
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}

public function Etats(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('etats');
		
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
	
		public function Manifestations(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('manifestations')
        		 ->set_relation('CommuneId', 'Communes', 'CommuneNCCENR');
			
		
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
		
		public function Mineraux(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('mineraux');
		
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}

		public function Pays(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('pays');
		
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
		
		public function Personnes(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('personnes');
		
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
		
		public function Regions(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('regions')
        		 ->set_relation('EtatId', 'Etats', 'EtatNom');
			
		
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
		
	public function sites()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_theme('datatables')
			->set_table('Sites')
			->set_subject('Site')
			->display_as('CommuneId','Commune')
			->set_relation('CommuneId','Communes','CommuneNCCENR');


			$output = $crud->render();

			$this->_do_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function SitesGeoLocalisation()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_table('sitesGeoLocalisation')
			->set_relation('SiteId','Sites','SiteNom');


			$output = $crud->render();

			$this->_do_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

	public function SortiesCollection()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_table('sortiesCollection')
			->set_relation('EchantillonId','Echantillons','Titre')
			->set_relation('PersonneId','Personnes','PersonneMoraleNom');


			$output = $crud->render();

			$this->_do_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

public function SortiesSurTerrain()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_table('sortiesSurTerrain')
			->set_relation('SiteId','Sites','SiteNom')
			->set_relation('PersonneId','Personnes','PersonneMoraleNom');


			$output = $crud->render();

			$this->_do_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

}