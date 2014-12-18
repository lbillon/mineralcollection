<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This controller only used to show reverse relations in detailed view
 */
class Minerals_relation_details extends CI_Controller {

	var $basePath = "/mineralcollection/index.php/minerals/";
	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
	}

	public function _do_output($output = null)
	{
			$this->load->view('minerals_add.php',$output);
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
        	
        	
			
			
			$fieldName = $this->input->get('field');
			$fieldValue = $this->input->get('value');
			$subject = $this->input->get('subject');
        	$crud->set_table('Acquisitions')
			->set_subject($subject);
			$crud->set_relation('EchantillonId','Echantillons','{EchantillonId} - {Titre}');
			$crud->set_relation('PersonneId','Personnes','PersonneMoraleNom');
  		    if($fieldName!=null){
  		    	$crud->where('Acquisitions.'.$fieldName,$fieldValue);	
				$crud->set_detailed_relationship_table("Acquisitions",'?subject=Echanges&field=EchantillonId&value=');
  		    }			
		
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
		
			$crud->set_parent_add_form('DepartementId',$this->basePath.'Departements/add?add=true');
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
			
			$crud->set_parent_add_form('RegionId',$this->basePath.'Regions/add?add=true');
		
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
        	$crud->set_relation('PersonneId','Personnes','{PersonneMoraleNom} {PersonneNom} {PersonnePrenom}')
                ->set_relation_n_n('Sorties', 'JointureEchangesSorties', 'SortiesCollection', 'EchangeId', 'SortieId', '{SortieDate} {SortiePrecision}')
            ->set_relation_n_n('Acquisitions', 'jointureEchangesAcquisitions', 'Acquisitions', 'EchangeId', 'AcquisitionId', '{AcquisitionDate} - {Acquisitions.AcquisitionId}');

            $crud->set_parent_add_form('PersonneId',$this->basePath.'Personnes/add?add=true');

		
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
			$fieldName = $this->input->get('field');
			$fieldValue = $this->input->get('value');
			$subject = $this->input->get('subject');
        	$crud->set_table('Echantillons')
			->set_subject($subject)
			->set_relation('PersonneId','Personnes','{PersonneMoraleNom} {PersonneNom} {PersonnePrenom}')
			->set_relation('RegionId','Regions','RegionNCCENR')
			->set_relation('DepartementId','Departements','DepartementNCCENR')
			->set_relation('SortieId','SortiesCollection','{SortieDate} {SortiePrecision}',null,'SortieDate DESC')
			->set_relation('SiteId','Sites','SiteNom')
			->set_relation('CommuneId', 'Communes', 'CommuneNCCENR')
			->set_relation('EtatId','Etats','EtatNom')
            ->set_relation_n_n('Mineraux', 'JointureEchantillonsMineraux', 'Mineraux', 'EchantillonId', 'MineralId', 'MineralNom');
  		    if($fieldName!=null){
  		    	$crud->where('Echantillons.'.$fieldName,$fieldValue);	
				$crud->set_detailed_relationship_table("Echantillons",'?subject=Sites&field=SiteId&value='.$fieldValue);
  		    }			
		
			$crud->unset_add();
			$crud->unset_delete();
			$crud->unset_edit();
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
			
			$crud->set_parent_add_form('CommuneId',$this->basePath.'Communes/add?add=true');
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

            $crud->set_relation_n_n('Adresses', 'JointurePersonnesAdresses', 'Adresses', 'PersonneId', 'AdresseId', '{Ville}, {NumeroEtRueAdresse}');
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
		
			$crud->set_parent_add_form('EtatId',$this->basePath.'Etats/add?add=true');	
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

			$crud->set_parent_add_form('CommuneId',$this->basePath.'Communes/add?add=true');	
			$crud->set_parent_add_form_label_field("SiteNom");
            $crud->set_relation_n_n('Mineraux', 'jointureSitesMineraux', 'Mineraux', 'SiteId', 'MineralId', 'MineralNom')
                ->set_relation_n_n('Fiches_BRGM', 'SitesJoinBRGMExploitations', 'BRGMExploitations', 'SiteId', 'NumFiche', 'NomExploitation');

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
	
//	public function SitesGeoLocalisation()
//	{
//		try{
//			$crud = new grocery_CRUD();
//
//			$crud->set_table('SitesGeoLocalisation')
//			->set_relation('SiteId','Sites','SiteNom');
//
//			$crud->set_parent_add_form('SiteId',$this->basePath.'Sites/add?add=true');
//
//			$this->_set_unset_back_to_list($crud);
//			$output = $crud->render();
//
//			$this->_do_output($output);
//
//		}catch(Exception $e){
//			show_error($e->getMessage().' --- '.$e->getTraceAsString());
//		}
//	}

	public function SortiesCollection()
	{
		try{
			$crud = new grocery_CRUD();

			$crud->set_table('SortiesCollection')
			->set_relation('EchantillonId','Echantillons','{EchantillonId} - {Titre}')
			->set_relation('PersonneId','Personnes','{PersonneMoraleNom} {PersonneNom} {PersonnePrenom}');

			$crud->set_parent_add_form('EchantillonId',$this->basePath.'Echantillons/add?add=true');
			$crud->set_parent_add_form('PersonneId',$this->basePath.'Personnes/add?add=true');
			
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
			->set_relation('PersonneId','Personnes','{PersonneMoraleNom} {PersonnePrenom} {PersonneNom}');

			$crud->set_parent_add_form('SiteId',$this->basePath.'Sites/add?add=true');
			$crud->set_parent_add_form('PersonneId',$this->basePath.'Personnes/add?add=true');

			$this->_set_unset_back_to_list($crud);
			$output = $crud->render();

			$this->_do_output($output);

		}catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}

}
