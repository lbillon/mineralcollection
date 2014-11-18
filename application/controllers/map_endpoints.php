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


    public function test()
    {
        $arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);

            //add the header here
            header('Content-Type: application/json');
            echo json_encode($arr);

    }

    public function querySearch()
    {
        $arr = array();
        $arr["error"] = false;
        $arr["msg"] = "";
        $arr["result"] = array();

        //Concatenate
        $fullQueryString = $_GET["selectPart"].' '.$_GET["fromPart"].' '.$_GET["wherePart"];

        //Run Query
        $query = $this->db->query($fullQueryString);

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
                foreach ($query->result() as $row) {
                    $currentObject = array();
                    $currentObject["id"] = $row->SiteId;
                    $currentObject["name"] = $row->SiteNom;
                    $currentObject["description"] = $row->SiteDescrGen;
                    $currentObject["type"] = $row->SiteType;
                    // $currentObject["lon"] = $row->SiteLon;
                    // $currentObject["lat"] = $row->SiteLat;

                    $arr["result"][]=$currentObject;
                }

            }
        }


        return json_encode($arr);
    }


}