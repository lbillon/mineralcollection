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
		if($this->input->get('add', TRUE)){
			$this->load->view('minerals_add.php',$output);
		}else{
			$this->load->view('minerals.php',$output);			
		}
	}

	private function _set_unset_back_to_list($crud){
		if($this->input->get('add', TRUE)){
			$crud->unset_back_to_list();
		}
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
        	$crud->set_table('Acquisitions');
        	$crud->set_relation('EchantillonId','Echantillons','Titre');
			$crud->set_relation('PersonneId','Personnes','PersonneMoraleNom');
			
			$crud->set_parent_add_form('EchantillonId','/index.php/minerals/Echantillons/add?add=true');
			$crud->set_parent_add_form('PersonneId','/index.php/minerals/Personnes/add?add=true');
		
			$this->_set_unset_back_to_list($crud);
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
    }
	
	public function Adresses(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('Adresses');
        	
			$this->_set_unset_back_to_list($crud);
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
	
	public function Communes(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('Communes');
        	$crud->set_relation('DepartementId','Departements','DepartementNCCENR');
		
			$crud->set_parent_add_form('DepartementId','/index.php/minerals/Departements/add?add=true');
			$crud->set_parent_add_form_label_field("CommuneNCCENR");
    	    $this->_set_unset_back_to_list($crud);
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
	
	public function Departements(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('Departements');
        	$crud->set_relation('RegionId','Regions','RegionNCCENR');
			
			$crud->set_parent_add_form('RegionId','/index.php/minerals/Regions/add?add=true');
		
			$crud->set_parent_add_form_label_field("DepartementNCCENR");
		
		$this->_set_unset_back_to_list($crud);
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
	
	public function Echanges(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('Echanges');
        	$crud->set_relation('PersonneId','Personnes','PersonneMoraleNom');
			
			$crud->set_parent_add_form('PersonneId','/index.php/minerals/Personnes/add?add=true');
		
		$this->_set_unset_back_to_list($crud);
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
	
	public function Echantillons(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('Echantillons')
        	->set_relation('PersonneId','Personnes','PersonneMoraleNom')
			->set_relation('RegionId','Regions','RegionNCCENR')
			->set_relation('DepartementId','Departements','DepartementNCCENR')
			->set_relation('SortieId','SortiesCollection','SortiePrecision')
			->set_relation('SiteId','Sites','SiteNom')
			->set_relation('CommuneId', 'Communes', 'CommuneNCCENR')
			->set_relation('EtatId','Etats','EtatNom');
			
			$crud->set_parent_add_form_label_field("Titre");
			
			$crud->set_parent_add_form('PersonneId','/index.php/minerals/Personnes/add?add=true');
			$crud->set_parent_add_form('RegionId','/index.php/minerals/Regions/add?add=true');
			$crud->set_parent_add_form('DepartementId','/index.php/minerals/Departements/add?add=true');
			$crud->set_parent_add_form('SortieId','/index.php/minerals/SortiesCollection/add?add=true');
			$crud->set_parent_add_form('SiteId','/index.php/minerals/Sites/add?add=true');
			$crud->set_parent_add_form('CommuneId','/index.php/minerals/Communes/add?add=true');
			$crud->set_parent_add_form('EtatId','/index.php/minerals/Etats/add?add=true');
		
		$this->_set_unset_back_to_list($crud);
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}

public function Etats(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('Etats');
			$crud->set_parent_add_form_label_field("EtatNom");
    	    
    	    $this->_set_unset_back_to_list($crud);
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
	
		public function Manifestations(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('Manifestations')
        		 ->set_relation('CommuneId', 'Communes', 'CommuneNCCENR');
			
			$crud->set_parent_add_form('CommuneId','/index.php/minerals/Communes/add?add=true');
    	    $this->_set_unset_back_to_list($crud);
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
		
		public function Mineraux(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('Mineraux');
		
    	    $this->_set_unset_back_to_list($crud);
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}

		public function Pays(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('Pays');
		
    	    $this->_set_unset_back_to_list($crud);
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
		
		public function Personnes(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('Personnes');
		
		$crud->set_parent_add_form_label_field("PersonneMoraleNom");
    	    $this->_set_unset_back_to_list($crud);
    	    $output = $crud->render();
 
        	$this->_do_output($output);
			      
		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}  
		
	}
		
		public function Regions(){
		try{
			$crud = new grocery_CRUD();
        	$crud->set_table('Regions')
        		 ->set_relation('EtatId', 'Etats', 'EtatNom');
		
			$crud->set_parent_add_form('EtatId','/index.php/minerals/Etats/add?add=true');	
			$crud->set_parent_add_form_label_field("RegionNCCENR");
    	    $this->_set_unset_back_to_list($crud);
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

			$crud
			->set_table('Sites')
			->set_subject('Site')
			->display_as('CommuneId','Commune')
			->set_relation('CommuneId','Communes','CommuneNCCENR');

			$crud->set_parent_add_form('CommuneId','/index.php/minerals/Communes/add?add=true');	
			$crud->set_parent_add_form_label_field("SiteNom");

            $state = $crud->getState();

            if($state=='edit'|| $state=='add') {
                $crud->add_fields('pos');
                $crud->callback_edit_field('pos', function () {
                    return $this->load->view('location_picker.html', '', true);
                });
            }
            $this->_set_unset_back_to_list($crud);
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

			$crud->set_table('SitesGeoLocalisation')
			->set_relation('SiteId','Sites','SiteNom');

			$crud->set_parent_add_form('SiteId','/index.php/minerals/Sites/add?add=true');	

			$this->_set_unset_back_to_list($crud);
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

			$crud->set_table('SortiesCollection')
			->set_relation('EchantillonId','Echantillons','Titre')
			->set_relation('PersonneId','Personnes','PersonneMoraleNom');

			$crud->set_parent_add_form('EchantillonId','/index.php/minerals/Echantillons/add?add=true');
			$crud->set_parent_add_form('PersonneId','/index.php/minerals/Personnes/add?add=true');
			
			$crud->set_parent_add_form_label_field("SortiePrecision");
			$this->_set_unset_back_to_list($crud);
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

			$crud->set_table('SortiesSurTerrain')
			->set_relation('SiteId','Sites','SiteNom')
			->set_relation('PersonneId','Personnes','PersonneMoraleNom');

			$crud->set_parent_add_form('SiteId','/index.php/minerals/Sites/add?add=true');
			$crud->set_parent_add_form('PersonneId','/index.php/minerals/Personnes/add?add=true');

			$this->_set_unset_back_to_list($crud);
			$output = $crud->render();

			$this->_do_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

}