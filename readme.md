# Fruitigniter <small> V0.5 BETA </small>
<h3> Power full code igniter CRUD library use ajax and datatable</h3>
<p> you can easy install and use this library for your project </p>
<h3> Why use Fruitigniter ? </h3>
<ul>
<li> Very easy install [just 3 step] </li>
<li> Very easy use of library [just define in controller] </li>
<li> Very power full [have very methods] </li>
<li> Full ajax [dont need refresh page]  </li>
<li> Make for very big data  [ limit and controll all data ] </li>
<li> Very power full support by <a href='http://www.piero.ir'> piero group </a> [pirooz jenabi administrator] </li>
</ul>

<h3> Fast Example </h3>
<pre>
        $this->load->library("Fruit_igniter");// load library
        $this->fruit_igniter->table="fruit_group";
        $this->fruit_igniter->title="Manage fruit group";
        $this->fruit_igniter->column_order=array("id","name","des");
        $this->fruit_igniter->column_title=array("Id","Name","Description");
        $this->fruit_igniter->column_require=array(0,1,0);
        $this->fruit_igniter->column_type=array("input","input","input");
        $this->fruit_igniter->column_search=array("name","des");
        $this->fruit_igniter->render();
</pre>
