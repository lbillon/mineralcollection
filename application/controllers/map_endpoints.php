<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Map_endpoints extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');

        $this->load->library('grocery_CRUD');
    }

    //Function used in order to allow only SELECT queries
    private function QueryValidation($querySource)
    {
        $result = true;

        $query = strtolower(trim($querySource));
        if(substr($query,0,6) != "select")
            $result = false;

        return $result;
    }

    public function querySearch()
    {
        $arr = array();
        $arr["error"] = false;
        $arr["msg"] = "";
        $arr["result"] = array();

        if(!isset($_POST["query"]))
        {
            $arr["msg"] = "No query sent";
        }
        else
        {
            $queryString = $_POST["query"];
            $isValid = $this->QueryValidation($queryString);
            
            if($isValid)
            {
                //Run Query
                $query = $this->db->query($queryString);

                //Catch errors
                $error = $this->db->_error_message();

                if($error!="") //An error occured
                {
                    $arr["error"]=true;
                    $arr["msg"]=$error;
                }
                else //no error
                {
                    if($query->num_rows()>0)
                    {
                        $fieldsArray = $query->list_fields();
                        foreach ($query->result() as $row) {
                            $currentObject = array();

                            foreach ($fieldsArray as $field) {
                                $currentObject[$field] = $row->$field;
                            }

                            $arr["result"][]=$currentObject;
                        }

                    }
                }         
            }
            else
            {
                $arr["error"]=true;
                $arr["msg"]="Query not allowed"; 
            }

        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($arr));
    }

}