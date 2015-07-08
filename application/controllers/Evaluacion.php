<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Evaluacion extends CI_Controller {
    // Layout used in this controller
    public $layout_view = 'layout/default';

    function __construct(){
    	parent::__construct();
    	$this->load->model('evaluacion_model');
        $this->load->model('user_model');
    	$this->load->library('pagination');
    }

    public function evaluadores($msg=null) {
    	if($msg!='(:num)')
    		$data['msg'] = $msg;

    	//pagination settings
        $config['base_url'] = base_url('evaluadores');
        $config['total_rows'] = $this->evaluacion_model->getEvaluadores()->num_rows();
        $config['per_page'] = "25";
        $config["uri_segment"] = 2;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);

        //config for bootstrap pagination class integration
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $data['page'] = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        //call the model function to get the data
        $data['evaluadores'] = $this->evaluacion_model->getPagination($config["per_page"], $data['page']);
        //$data['evaluadores'] = $this->evaluacion_model->getEvaluadores()->result();

        $data['pagination'] = $this->pagination->create_links();
    	
        $this->layout->title('Capital Humano - Evaluadores');
    	$this->layout->view('evaluacion/evaluadores',$data);
    }

    public function searchByText($tipo=0) {
    	if($this->input->post('valor')) :
            switch ($tipo) {
                case 0:
                    $url = 'evaluacion/asignar_evaluadores360';
                    break;
                
                case 1:
                    $url = 'evaluacion/asignar_evaluadores';
                    break;
            }
    		$valor = $this->input->post('valor');
    		$resultados = $this->evaluacion_model->getByText($valor);
    		foreach ($resultados as $ev) : ?>
    			<tr>
		          <td><img height="25px" src="<?= base_url('assets/images/fotos')."/".$ev->foto;?>"></td>
		          <td><span class="glyphicon glyphicon-eye-open" style="cursor:pointer" onclick="
		            location.href='<?= base_url($url);?>/'+<?= $ev->id;?>"></span> 
		            <span style="cursor:pointer" onclick="location.href='<?= base_url($url);?>/'+
		            <?= $ev->id;?>"><?= $ev->nombre;?></span></td>
		          <td><span style="cursor:pointer" onclick="location.href='<?= base_url($url);?>/'+
		            <?= $ev->id;?>"><?= $ev->cantidad;?></span></td>
		          <!--<td align="right"><span style="cursor:pointer;" onclick="
		            if(confirm('Seguro que desea eliminar el evaluador: \n <?= $ev->nombre;?>'))location.href=
		            '<?= base_url('evaluacion/del/');?>/'+<?= $ev->id;?>;" class="glyphicon 
		            <?php if($ev->estatus ==1 ) echo "glyphicon-ok"; else echo "glyphicon-ban-circle"; ?>"></span></td>-->
		        </tr>
		        <script type="text/javascript">$("#pagination").hide('slow');</script>
    		<?php endforeach;
    	else:
            if($tipo==1){ ?>
                <script type="text/javascript">document.location.href="<?= base_url('evaluadores360');?>";</script>
            <?php }else { ?>
                <script type="text/javascript">document.location.href="<?= base_url('evaluadores');?>";</script>
        	<?php }
        endif;
    }

    public function nuevo_evaluador($err_msg=null,$msg=null) {
        $data=array();
        if($err_msg!=null)
            $data['err_msg'] = $err_msg;
        if($msg!=null)
            $data['msg'] = $msg;
        $data['evaluadores'] = $this->user_model->getAll();
        $this->layout->title('Capital Humano - Nuevo Evaluador');
        $this->layout->view('evaluacion/nuevo_evaluador',$data);
    }

    public function add_colaboradores() {
        $evaluador = $this->uri->segment(3,null);
        $tipo = $this->uri->segment(4,0);
        if ($evaluador) {
            $opciones=$this->input->post('selected');
            foreach ($opciones as $colaborador) {
                if(!$this->evaluacion_model->addColaboradorToEvaluador($evaluador,$colaborador,$tipo))
                    break;
            }
        }
    }

    public function del_colaboradores() {
        $evaluador = $this->uri->segment(3,null);
        $tipo = $this->uri->segment(4,0);
        if ($evaluador) {
            $opciones=$this->input->post('selected');
            foreach ($opciones as $colaborador) {
                if(!$this->evaluacion_model->delColaboradorFromEvaluador($evaluador,$colaborador,$tipo))
                    break;
            }
        }
    }

    public function load_asignados($tipo=0) {
        if($this->input->post('evaluador')) {
            $evaluador = $this->input->post('evaluador');
            $asignados = $this->evaluacion_model->getByEvaluador($evaluador,$tipo);
            foreach($asignados as $colaborador) : ?>
            <option value="<?= $colaborador->id;?>"><?= $colaborador->nombre ." - ". $colaborador->posicion;?></option>
          <?php endforeach;
        }else
            echo "";
    }

    public function load_no_asignados($tipo=0) {
        if($this->input->post('evaluador')) {
            $evaluador = $this->input->post('evaluador');
            $asignados = $this->evaluacion_model->getByEvaluador($evaluador,$tipo);
            $colaboradores = $this->evaluacion_model->getNotByEvaluador($evaluador,$asignados);
            foreach($colaboradores as $colaborador) : ?>
                <option value="<?= $colaborador->id;?>"><?= $colaborador->nombre ." - ". $colaborador->posicion;?></option>
          <?php endforeach;
        }else
            echo "";
    }

    public function asignar_colaborador($evaluador,$msg=null,$err_msg=null) {
        $data=array();
        if($msg!=null)
            $data['msg']=$msg;
        if($err_msg!=null)
            $data['err_msg']=$err_msg;
        $data['evaluador']=$this->user_model->searchById($evaluador);
        $data['asignados']=$this->evaluacion_model->getByEvaluador($evaluador);
        $data['no_asignados'] = $this->evaluacion_model->getNotByEvaluador($evaluador,$data['asignados']);
        $this->layout->title('Capital Humano - Asigna Colaboradores');
        $this->layout->view('evaluacion/asignar_colaborador',$data);
    }

    public function asignar_colaborador360($evaluador,$msg=null,$err_msg=null) {
        $data=array();
        if($msg!=null)
            $data['msg']=$msg;
        if($err_msg!=null)
            $data['err_msg']=$err_msg;
        $data['evaluador']=$this->user_model->searchById($evaluador);
        $data['asignados']=$this->evaluacion_model->getByEvaluador($evaluador,1);
        $data['no_asignados'] = $this->evaluacion_model->getNotByEvaluador($evaluador,$data['asignados']);
        $this->layout->title('Capital Humano - Asigna Colaboradores');
        $this->layout->view('evaluacion/asignar_colaborador360',$data);
    }

    public function evaluadores360($msg=null) {
        if($msg!='(:num)')
            $data['msg'] = $msg;

        //pagination settings
        $config['base_url'] = base_url('evaluadores360');
        $config['total_rows'] = $this->evaluacion_model->getEvaluadores(1)->num_rows();
        $config['per_page'] = "25";
        $config["uri_segment"] = 2;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = floor($choice);

        //config for bootstrap pagination class integration
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $data['page'] = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        //call the model function to get the data
        $data['evaluadores'] = $this->evaluacion_model->getPagination($config["per_page"], $data['page'],1);
        //$data['evaluadores'] = $this->evaluacion_model->getEvaluadores()->result();

        $data['pagination'] = $this->pagination->create_links();
        
        $this->layout->title('Capital Humano - Evaluadores 360');
        $this->layout->view('evaluacion/evaluadores360',$data);
    }

    public function nuevo_evaluador360($err_msg=null,$msg=null) {
        $data=array();
        if($err_msg!=null)
            $data['err_msg'] = $err_msg;
        if($msg!=null)
            $data['msg'] = $msg;
        $data['evaluadores'] = $this->user_model->getAll();
        $this->layout->title('Capital Humano - Nuevo Evaluador 360');
        $this->layout->view('evaluacion/nuevo_evaluador360',$data);
    }
}