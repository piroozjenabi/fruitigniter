# Fruitigniter <small> V0.5 BETA </small>
<h3> Power full code igniter CRUD library use ajax and datatable</h3>
<p> you can easy install and use this library for your project </p>
<img src="http://www.piero.ir/fruitigniter/screenshot/fruitigniter-sc1.jpg" alt="list" >
<img src="http://www.piero.ir/fruitigniter/screenshot/fruitigniter-sc2.jpg" alt="add" >


<h3> Why use Fruitigniter ? </h3>
<ul>
<li> Very easy install [just 3 step] </li>
<li> Very easy use of library [just define in controller] </li>
<li> Very power full [have very methods] </li>
<li> Full ajax [dont need refresh page]  </li>
<li> Make for very big data  [ limit and controll all data ] </li>
<li> Very power full support by <a href='http://www.piero.ir'> piero group </a> [pirooz jenabi administrator] </li>
</ul>

<h3> Fast user guide  </h3>
<pre>
        $this->load->library("Fruit_igniter");// load library
      
        $this->fruit_igniter->table="fruit_group"; // define table for database
       
        $this->fruit_igniter->title="Manage fruit group"; // set title for view in page of crud
       
        $this->fruit_igniter->column_order=array("id","name","des");//set columns of table by order
      
        $this->fruit_igniter->column_title=array("Id","Name","Description");/set title to show in header
       
        $this->fruit_igniter->column_require=array(0,1,0); // set requirement fileds for add in database 
      
        $this->fruit_igniter->column_type=array("input","input","input");//select type of columns
       
        $this->fruit_igniter->column_search=array("name","des");// set search where to find 
       
        $this->fruit_igniter->render();//last step render fruit igniter
</pre>


<h3> easy install </h3>
<ol>
<li> Copy Fruit_igniter.php and Fruit_igniter_core.php library in your project </li>
<li> Copy fruit_view.php in root view folder in your project  </li>
<li> Copy Fruit_igniter_lang.php in you language folder  </li>
</ol>
<p> Your installing is finish . for use fruit igniter just define controller very easy like fast example .</p>

<b> Copy right by  <a href="http://www.piero.ir" >piero.ir </a> </b>
