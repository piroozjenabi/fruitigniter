<?php
/**
 * Created by pirooz jenabi.
 * company www.piero.ir
 * Date: 7/30/17
 * Time: 6:45 PM
 */
class Fruit_igniter_core{

    //variable for out put baja cript and css in header
    public  $javascriptlib='';
    public  $css_lib='';


    public function load($path,$datam=null,$datah=null,$dataf=null)
    {

        $CI =& get_instance();
        //load css and js
        $this->load_css("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css");
        $this->load_css("https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css");


        $this->load_js("https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js");
        $this->load_js("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js");
        $this->load_js("https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js");

        $bsjas=$this->javascriptlib;
        $bscss=$this->css_lib;
        $data=array('bsjas' => $bsjas ,'bscss' => $bscss,'output'=>$datam);
        $CI->load->view($path,$data);
    }

    //standardad load js  automatic load javascript in header tag
    public function load_js($path)
    {
        $this->javascriptlib .= "<script type='text/javascript' src='$path'> </script> \n";
    }

    //automatic load cc in header tag
    public function load_css($path)
    {
        $this->css_lib .= "<link rel='stylesheet' type='text/css' href='$path'" ."/> \n";
    }

}