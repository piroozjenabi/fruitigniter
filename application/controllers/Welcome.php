<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
        $this->load->library("Fruit_igniter");// load library
        $this->fruit_igniter->table="fruit_group";
        $this->fruit_igniter->title="Manage fruit group";
        $this->fruit_igniter->column_order=array("id","name","des");
        $this->fruit_igniter->column_title=array("Id","Name","Description");
        $this->fruit_igniter->column_require=array(0,1,0);
        $this->fruit_igniter->column_type=array("input","input","input");
        $this->fruit_igniter->column_search=array("name","des");
        $this->fruit_igniter->render();
	}
	//manage fruit
    public function fruit()
    {
        $this->load->library("Fruit_igniter");// load library
        $this->fruit_igniter->table="fruit";
        $this->fruit_igniter->title="Manage fruits";
        $this->fruit_igniter->column_order=array("state","group_id","name","date_eat","price","des");
        $this->fruit_igniter->column_title=array("State","Group id","Name","Date to eat","price","Description");
        $this->fruit_igniter->column_require=array(0,1,1,1,0,0,0);
        $this->fruit_igniter->column_type=array("bool","input","input","input","input","input");
        $this->fruit_igniter->column_search=array("name","des");
        $this->fruit_igniter->render();
    }


}
