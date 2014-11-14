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


}