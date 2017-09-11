# Fruitigniter 

<h3> Make awesome crud (Create , Read , Update , Delete ) for codeigniter like eat fruit<small> V0.5 BETA </small></h3>
<p> you can easy install and use this library for your project </p>

<h4> Screen shot  </h4>
<img src="http://www.piero.ir/fruitigniter/screenshot/fruitigniter-sc1.jpg" alt="list" >
<br/>
<img src="http://www.piero.ir/fruitigniter/screenshot/fruitigniter-sc2.jpg" alt="add" >


<h3> Why use Fruitigniter for make crud in codeigniter ? </h3>
<ul>
<li> Very easy install [just 3 step] </li>
<li> Very easy use of library [just define in controller] </li>
<li> Very power full [have very methods] </li>
<li> Full ajax [dont need refresh page]  </li>
<li> Make for very big data  [ limit and controll all data ] </li>
<li> Very power full support by <a href='http://www.piero.ir'> piero group </a> [pirooz jenabi administrator] </li>
<li> very language support [english,persian,other coming soon] </li>
<li> use font awesome [for icons ] </li>
</ul>

<h3> Fast user guide  </h3>
<pre>
       //Simple guide use fruit igniter
        
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

<h3> database sql   </h3>
<pre>
create table fruit_group
(
	id int auto_increment
		primary key,
	name varchar(255) null,
	des text null
);
create table fruit
(
	id int auto_increment
		primary key,
	group_id int null,
	state int null,
	name varchar(100) null,
	date_create varchar(100) null,
	date_eat varchar(100) null,
	tase varchar(100) null,
	price int null,
	des text null,
	constraint fruit_fruit_group_id_fk
		foreign key (group_id) references fruit_group (id)
			on delete cascade);
create index fruit_fruit_group_id_fk
	on fruit (group_id)
;
</pre>
<h3> row types </h3>
<ul>
<li> "input"     : simple text box </li> 
<li> "email"     : text box with email format </li>
<li> "number"    : text box with number format  </li>
<li> "bool"      : drop down with true or false value </li>
<li> "date"      : for input date </li>
<li> "array"     : define and array for drop down </li>
<li> "select_db" : select one row from database <small>
 <pre>
 json_encode(array("select_db","fruit_group","name")
 </pre>
 </small> </li>
</ul>
<h3> Latest Changes </h3>
<ul>
<li> add select_db type for select from other database </li> 
<li> in language unique define for avoid conflicts   </li>
</ul>
<b> Copy right by  <a href="http://www.piero.ir" >piero.ir </a> </b>


