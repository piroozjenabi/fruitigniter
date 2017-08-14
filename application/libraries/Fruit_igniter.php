<?php
/**
 * Created by pirooz jenabi.
 * User: piero.ir
 * Date: 4/30/17
 * Time: 6:56 PM
 */

//crud library

// info for select_db ----------------------- start
//param 1 : define select_db type and not user
//param 2 : define database
//param 3 : name of col
//param 4 : where
//param 5 : order
//exam  $tmp_selectdb=array("select_db","tbl_usergroup_eemploy","name");
//$this->crud->column_type=array("input","bool",json_encode($tmp_selectdb));
// info for select_db ----------------------- end


class Fruit_igniter{

    //database opttions
    var $table=null;//name of table--b1
    var $column_order = array();//columns to load--b2
    var $column_search = array();//for search--b3
    var $order = array('id' => 'DESC');//for sorting--b4
    var $where ;//for where--b5
    var $join =null;//--b6
    var $limit=null;//--b7
    //general options
    var $title=null;//name of table--b8
    var $column_title = array();//columns to load in header and footer--b9
    var $column_type = array();//columns type for add and edit and view--b10
    var $column_require = array();//for search--b11
    var $permision=array("add"=>true,"edit"=>true,"delete" =>true);//permisions--b12
    var $tableId=null;//table id for jquery--b13
    var $actions=null;//other html for header--b14
    var $actions_row=null;//other buttons for rows replace [[id]]--b15
    var $form_add=null;//add this codes to add form--b16
//------------------------------------------database start

    function __construct()
    {
        $CI=&get_instance();
        $CI->load->library("Fruit_igniter_core");
        $CI->load->database();
        $CI->load->language("Fruit_igniter");
        $CI->load->helper("url");
    }

//    render query



    //render query--b17
    private function _get_datatables_query()
    {
        $CI=&get_instance();
        if($this->table==null) die("first select your db");
        $CI->db->from($this->table);
        $i = 0;
        foreach ($this->column_search as $item) // loop column
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {

                if($i===0) // first loop
                {
                    $CI->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $CI->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $CI->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $CI->db->group_end(); //close bracket
            }
            $i++;
        }

        if($this->limit)
        {
            $CI->db->limit($this->limit);
        }

        if($this->where)
        {
            $CI->db->where($this->where);
        }
                if($this->join)
        {
            $CI->db->join($this->join[0],$this->join[1]);
        }

        if(isset($_POST['order'])) // here order processing
        {
            $CI->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $CI->db->order_by(key($order), $order[key($order)]);
        }
    }
    //for filterted value --b18
    function count_filtered()
    {
        $CI=&get_instance();
        $this->_get_datatables_query();
        $query = $CI->db->get();
        return $query->num_rows();
    }
    //GET COOUNT OF AL --b19
    public function count_all()
    {
        $CI=&get_instance();
        $CI->db->from($this->table);
        return $CI->db->count_all_results();
    }

    //render data FROM DATABASE --b20
    function list_ajax()
    {
        $CI=&get_instance();
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
            $CI->db->limit($_POST['length'], $_POST['start']);
        return $CI->db->get()->result();

    }

    //return values of table --b21
    public function get_by_id($id)
    {
        $CI=&get_instance();
        $CI->db->from($this->table);
        $CI->db->where('id',$id);
        $query = $CI->db->get();
        return $query->row();
    }

    //save in databse --b22
    public function save($data)
    {
        if($this->permision["add"]) {
            $data["id"]=null;
            $CI =& get_instance();
            $CI->db->insert($this->table, $data);
            return $CI->db->insert_id();
        }
    }
    //update to database --b23
    public function update($where, $data)
    {
        if($this->permision["edit"]) {
            $CI =& get_instance();
            $CI->db->update($this->table, $data, $where);
            return $CI->db->affected_rows();


        }
    }
    // delete row from database by id --b24
    public function delete_by_id($id)
    {
        if($this->permision["delete"])
        {
        $CI=&get_instance();
        $CI->db->where('id', $id);
        $CI->db->delete($this->table);
        }
    }
    
    
    
//------------------------------database end

//------------------------------control start

    // validate input params from db --b25
    public function _validate()
    {
        $CI=&get_instance();
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
        $c=0;
        foreach ($this->column_order as $k){

        if($CI->input->post($k) == '' && $this->column_require[$c])
        {
            $data['inputerror'][] = $k;
            $data['error_string'][] = $this->column_title[$c].__. _IS_REQUIRED.'<br>' ;
            $data['status'] = FALSE;
        }

            $c++;
        }
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

    // edit row from db from recive ajax post method --b26
    public function ajax_edit()
    {
        $CI=&get_instance();
        $id=$CI->input->post("id",true);
        $data =  $this->get_by_id($id);
        echo json_encode($data);
    }
    // add row from db from recive ajax post method --b27
    public function ajax_add()
    {
        $CI=&get_instance();
        $this->_validate();
        $data = array();
	    foreach ($CI->input->post() as $k => $v)
		   if ($k!='typeIn') $data[$k]=$v;
        $insert =  $this->save($data);
        echo json_encode(array("status" =>$insert));
    }
    // update row from db from recive ajax post method --b26
    public function ajax_update()
    {
        $CI=&get_instance();
        $this->_validate();
        $data = array();
        foreach ($CI->input->post() as $k => $v)
            if ($k!='typeIn') $data[$k]=$v;
        $this->update(array('id' => $CI->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }
    // delete  row from db from recive ajax post method --b27
    public function ajax_delete()
    {
        $CI=&get_instance();
        $id=$CI->input->post("id",true);
        $this->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }


//------------------------------controller end
//------------------------------view start

    private function load_header()
    {
        echo '<h2 class="page-header">'. $this->title .' </h2>';
    }
    private function load_action()
    {
        ?>
        <div class="btn-group" style="padding: 5px;">
        <?php if($this->permision["add"]):?>
        <button class="btn btn-success" onclick="_add_()"><i class="fa fa-plus"></i> <?= _ADD ?> </button>
        <?php endif; ?>
        <button class="btn btn-default" onclick="reload_table()"><i class="fa fa-refresh"></i> <?= _REFRESH ?> </button>
        <?=$this->actions?>
        </div>
        <?php
    }


    private function load_table()
    {
     ?>
        <table id="<?=$this->tableId ?>" class="display" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th><i class="fa fa-sort-numeric-asc "></i> </th>
                <?php foreach ($this->column_title as $k): ?>
                <th><?=$k?></th>
                <?php endforeach; ?>
                <?php if ($this->permision["edit"] || $this->permision["delete"]): ?>
                    <th><?=_OP ?></th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            <tr>
                <th><i class="fa fa-sort-numeric-asc "></i> </th>
                <?php foreach ($this->column_title as $k): ?>
                    <th><?=$k?></th>
                <?php endforeach; ?>
                <?php if ($this->permision["edit"] || $this->permision["delete"]): ?>
                <th><?=_OP ?>
                </th>
                 <?php endif; ?>
            </tr>
            </tfoot>
        </table>
    <?php
    }

    private function load_modal()
    {
        ?>

        <!-- Bootstrap modal -->
        <div class="modal fade" id="modal_form" role="dialog" data-backdrop="static" >

            <div class="modal-dialog" >
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h3 class="modal-title"><?=$this->title ?></h3>
                    </div>
                    <div class="modal-body form">
                        <form action="#" id="form" class="form-horizontal">
                            <input type="hidden" value="" name="id"/>
                            <div class="form-body">
                            <?php for ( $i=0;$i<count($this->column_order );$i++): ?>
                                <div class="form-group">
                                    <label class="control-label col-md-3"><?= $this->column_title[$i] ?></label>
                                    <div class=" col-md-9">
                                        <?= $this->render_element($i) ?>
                                    </div>
                                </div>
                            <?php endfor; ?>
                             <?=($this->form_add)?$this->form_add:null ?>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnSave" onclick="save()" class="btn btn-primary"><?= _SAVE?></button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><?= _CANCEL ?></button>

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- End Bootstrap modal -->
        <?php
    }

    private function load_datatable($url)
    {
    ?>

        <script type="text/javascript">
            var table;
            $(document).ready(function() {
                //datatables
                table = $('#<?=$this->tableId ?>').DataTable({

                    "processing": true, //Feature control the processing indicator.
                    "serverSide": true, //Feature control DataTables' server-side processing mode.
                    "order": [], //Initial no order.
                    // Load data for the table's content from an Ajax source
                    "ajax": {
                        "url": "<?= $url?>",
                        "type": "POST",
                        "data": {'typeIn':'json'<?=($this->where)?",'where':'$this->where'":null;?>}
                    },

                    //Set column definition initialisation properties.
                    "columnDefs": [
                        {
                            "targets": [ 0 ], //first column / numbering column
                            "orderable": false, //set not orderable
                        },
                    ], stateSave: true,
                    scrollY:'50vh'  ,

                    "lengthMenu": [[10, 25, 50,100,200,500, -1], [10, 25, 50, 100 , 200 , 500 ,"<?=_ALL ?>"]],
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'colvis',
                            text: '<i class="fa fa-table" ></i>  <?=_VIEW.__._COLUMNS ?> '
                        },
                        {
                            extend: 'copy',
                            text: '<i class="fa fa-copy" > </i> <?=_COPY ?> '
                        },
                        {
                            extend: 'csv',
                            "filename":"<?=$this->title.rand(0,100) ?>",
                            text: '<i class="fa fa-file-excel-o" ></i> EXCEL/CSV',
                            charset: 'UTF-16LE',

                            bom: true
                        },
                    ],
                    "language": {
                        "lengthMenu": "<?=_VIEW?> _MENU_ <?= _PER_PAGE ?>",
                        "zeroRecords": "<?=_NOT_FOUND?>",
                        "info": "<?= _VIEW_PAGE?> _PAGE_ <?=_OF?> _PAGES_",
                        "infoEmpty": "<?=_NOT_FOUND?>",
                        "infoFiltered": "(<?= _FILTERED_FROM ?> _MAX_ <?=_RECORD?>)",
                        "search": "<i class='fa fa-search '>",
                        "pagingType": "full_numbers",
                        "processing": "<div class='loading' > </div>",
                        "oPaginate": {
                            "sFirst":    	"<?= _FIRST?> <i class='fa fa-fast-fa-backward '> </i>",
                            "sPrevious": 	"<?= _PREVIOUS?> <i class='fa fa-chevron-left  '> </i>",
                            "sNext":     	"<i class='fa fa-chevron-right '> </i> <?= _NEXT?> ",
                            "sLast":     	"<i class='fa fa-fast-forward '> </i> <?= _LAST?> "
                        }
                    }
                });

                //set input/textarea/select event when change value, remove class error and remove text help block
                $("input").change(function(){
                    $(this).parent().parent().removeClass('has-error');
                    $(this).next().empty();
                });
                $("textarea").change(function(){
                    $(this).parent().parent().removeClass('has-error');
                    $(this).next().empty();
                });
                $("select").change(function(){
                    $(this).parent().parent().removeClass('has-error');
//                    $(this).next().empty();
                });
            });
            function _add_()
            {
                save_method = 'add';
                $('#form')[0].reset(); // reset form on modals
                $('.form-group').removeClass('has-error'); // clear error class
                $('.help-block').empty(); // clear error string
                $('#modal_form').modal('show'); // show bootstrap modal
                $('.modal-title').text('<?= _ADD ?>'); // Set Title to Bootstrap modal title
            }

            function _edit_(id)
            {
                save_method = 'update';
                $('#form')[0].reset(); // reset form on modals
                $('.form-group').removeClass('has-error'); // clear error class
                $('.help-block').empty(); // clear error string

                //Ajax Load data from ajax
                $.ajax({
                    url : "<?= $url ?>",
                    type: "POST",
                    dataType: "JSON",
                    "data": {'typeIn':'edit','id':id},
                    success: function(data)
                    {
                        $('[name="id"]').val(data.id);
                        <?php foreach ($this->column_order as $k): ?>
                        $('[name="<?=$k ?>"]').val(data.<?=$k ?>);
                        <?php endforeach; ?>
                        $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                        $('.modal-title').text('<?=_EDIT ?>'); // Set title to Bootstrap modal title

                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        piero_alert("<?=_ERROR ?>","<?=_ERROR_AJAX ?>");
                    }
                });
            }

            function reload_table()
            {
                table.ajax.reload(null,false); //reload datatable ajax
            }

            function save()
            {

                $('#btnSave').text('<?= _SAVE2 ?>...'); //change button text
                $('#btnSave').attr('disabled',true); //set button disable
                var _dt;
                var _tmp_serialize;
                if(save_method == 'add') {
                    _dt = "add";
                } else {
                    _dt = "update";
                }
                _tmp_serialize= $('#form').serialize()+"&typeIn="+_dt;
                // ajax adding data to database
                $.ajax({
                    url : "<?=$url ?>",
                    type: "POST",
                    data: _tmp_serialize,
                    dataType: "JSON",
                    success: function(data)
                    {
                        if(data.status) //if success close modal and reload ajax table
                        {
                            $('#modal_form').modal('hide');
                            reload_table();
                        }
                        else
                        {
                            for (var i = 0; i < data.inputerror.length; i++)
                            {
                                $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
//                                $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string

                            }
                            piero_alert('<?=_ERROR ?>',data.error_string)
                        }
                        $('#btnSave').text("<?= _SAVE2 ?>"); //change button text
                        $('#btnSave').attr('disabled',false); //set button enable


                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        piero_alert("<?=_ERROR ?>","<?=_ERROR_UPDATE ?>");
                        $('#btnSave').text('<?= _SAVE2 ?>...'); //change button text
                        $('#btnSave').attr('disabled',false); //set button enable

                    }
                });
            }

            function _delete_(id)
            {
                _res=confirm('<?=_ASK_DELETE ?>');
                if(_res){
                    // ajax delete data to database
                    $.ajax({
                        url : "<?= $url?>",
                        type: "POST",
                        dataType: "JSON",
                        data:{"typeIn":"delete","id":id},
                        success: function(data)
                        {
                            reload_table();
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                           alert("<?=_ERROR_DELETE ?>");
                        }
                    });

                }

            }


            function piero_alert(_head,_mes) {
              alert(_mes);
            }
        </script>
    <?php
    }

//------------------------------view end
//------------------------------render start
    function render_url($def=null)
    {
        $CI=&get_instance();
        $def=($def)?$def:$CI->router->method;
        return site_url($CI->router->directory.$CI->router->class.'/'.$def) ;
    }
    //out for table
    private function render_view()
    {
        ob_start();
        $this->load_header();
        $this->load_action();
        $this->load_table();
        $this->load_modal();
        $this->load_datatable($this->render_url());
        $output = ob_get_contents();
        ob_end_clean();
        return$output;
    }

    //out for ajax out in datatable
    function render_ajax_list()
    {
        $CI=&get_instance();
        $list = $this->list_ajax();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $k) {
            $no++;
            $row = array();
            $row[] = $no;
            $c=0;
            foreach ($this->column_order as $key)
            {
            switch ($this->column_type[$c]){
                case ("number"):
                    $row[] = number_format($k->$key) ;
                    break;
                case ("bool"):
//                    $row[] = ($k->$key)?_TRUE:_FALSE;
                    $row[] = ($k->$key)?_TRUE:_FALSE;
                    break;
                    case ("input"):
                    $row[] = $k->$key;
                    break;
                case ("client"):
                    $row[] = $CI->system->get_user_from_id($k->$key) ;
                    break;
                case ("date"):
                    $CI->load->library('Piero_jdate');
                    $row[] = ($k->$key)?$CI->piero_jdate->jdate("Y/m/d",  rtrim($k->$key,'0000') ):null ;
//	                $row[] =$k->$key;

                    break;
                case ("json"):
                        $CI->load->library('Piero_jdate');
                    $row[] =$CI->piero_jdate->jdate("Y/m/d",json_decode($k->$key)->datecheck) ;
                break;
                default:
                    //mavared pichide keniaz ba tarkib json darad
                    if (strpos($this->column_type[$c],"array")){
                        $tmp_array =(array) json_decode( $this->column_type[$c] );
                        $tmp=(array) $tmp_array[1];
                        $row[]=$tmp[$k->$key];
                    }
                    else if (strpos($this->column_type[$c],"select_db"))
                    {
                        if($k->$key){
                        $tmp_array=json_decode($this->column_type[$c]);
	                    $row[]=@$CI->db->select($tmp_array[2])->from($tmp_array[1])->where("id",$k->$key)->get()->result_array()[0][$tmp_array[2]];
                        }
                        else
	                    $row[] = $k->$key;

                    }
                    else
                    $row[] = $k->$key;
            }
            $c++;
            }
            $tmp='<div class="btn-group">';
            $tmp.=($this->permision["edit"])?'<a class="btn btn-primary" href="javascript:void(0)" title="Edit" onclick="_edit_('."'".$k->id."'".')"><i class="fa fa-edit"></i>'._EDIT.'</a>':null;
            $tmp.=($this->permision["delete"])?'<a class="btn  btn-danger" href="javascript:void(0)" title="Hapus" onclick="_delete_('."'".$k->id."'".')"><i class="fa fa-close"></i>'._DELETE.'</a>':null;
            $tmp.=(str_replace("[[id]]",$k->id,$this->actions_row) );
	        $tmp.="</div>";
            $row[] =$tmp;
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->count_all(),
            "recordsFiltered" => $this->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    //total render
    function render($view="fruit_view") {
	    $CI            =& get_instance();
	    $this->tableId = "taBle" . rand( 0, 1000 );
	    if ( $CI->input->post( "where", true ) ) {
		    $this->where = $CI->input->post( "where", true );
	    }
	    switch ( $CI->input->post( "typeIn", true ) ) {
		    case "json":
			    $this->render_ajax_list();
			    break;
		    case "edit":
			    $this->ajax_edit();
			    break;
		    case "update":
			    $this->ajax_update();
			    break;
		    case "add":
			    $this->ajax_add();
			    break;
		    case "delete":
			    $this->ajax_delete();
			    break;
		    default:
			    $CI->fruit_igniter_core->load( $view, array( "out" => $this->render_view() ) );
	    }
    }

    //render element for edit adn add
    private function render_element($i)
    {
        $CI=&get_instance();
	    $CI->load->helper('form');
        switch ($this->column_type[$i])
        {
            case "input":?>
                <input name="<?= $this->column_order[$i] ?>" placeholder="<?= $this->column_title[$i] ?>" class="form-control" type="text">
            <?php break;
            case "email":?>
                <input name="<?= $this->column_order[$i] ?>" placeholder="<?= $this->column_title[$i] ?>" class="form-control" type="email">
            <?php break;
            case "number":?>
                <input name="<?= $this->column_order[$i] ?>" placeholder="<?= $this->column_title[$i] ?>" class="form-control" type="number">
            <?php break;
            case "bool":
                echo form_dropdown($this->column_order[$i] ,array(0=>_FALSE,1=>_TRUE));
             break;
             case "date":
              echo  $CI->element->date_input( $this->column_order[$i]);
             break;
            default:
                if (strpos($this->column_type[$i],"array")){
                    $tmp_array =  json_decode( $this->column_type[$i] );
                    echo form_dropdown($this->column_order[$i] ,(array) $tmp_array[1]);
                }
                else if (strpos($this->column_type[$i],"select_db")) {
		             $tmp_array = json_decode( $this->column_type[ $i ] );
                    echo @form_dropdown($this->column_order[$i] ,$CI->element->pselect($tmp_array[1],($tmp_array[2])?$tmp_array[2]:null,($tmp_array[3])?$tmp_array[3]:null,_DEF_ELEMENT,($tmp_array[4])?$tmp_array[4]:null,($tmp_array[5])?$tmp_array[5]:null,null,0));
	             }

                else {
		            ?>
                    <input name="<?= $this->column_order[$i] ?>" placeholder="<?= $this->column_title[$i] ?>" class="form-control" type="text">
		            <?php
                }



        }

    }


//---------------------------------------------------------------------oth statr
//generate array for crud permison
function render_permsion_crud($per)
{
    $CI=get_instance();
    return array("add"=>($CI->permision->check($per."_add"))?1:0,"edit"=>($CI->permision->check($per."_edit"))?1:0,"delete"=>($CI->permision->check($per."_delete"))?1:0);
}

//---------------------------------------------------------------------oth end




}