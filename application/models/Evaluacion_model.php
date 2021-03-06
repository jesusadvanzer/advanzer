<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Evaluacion_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}

	function getInfoCaptura($idEvaluacion) {

	    //$dato = $this->getEvaluacionAnualVigente();
        //print_r($dato);exit;
        //echo empty($dato);exit;

        //print_r($this->getEvaluacionAnualVigente());exit;
	    //print_r($this->getEvaluacionAnualVigente()->inicio);exit;
        //print_r($this->getEvaluacionAnual());exit;
	    //print_r(array('accion'=>3,'descripcion'=>$this->session->userdata('tipo')));
        //print_r($this->db->where(array('accion'=>3,'descripcion'=>$this->session->userdata('tipo')))->get('Bitacora')->result());exit;
        //return $this->db->where(array('accion'=>3,'descripcion'=>$this->session->userdata('tipo')))->get('Bitacora')->result();

        $result = $this->evaluacion_model->getEvaluacionById($idEvaluacion);
        //print_r($result);exit;

        return $this->db->where(array('accion'=>3,'descripcion'=>$this->session->userdata('tipo'),'fecha>='=>$result->inicio,'fecha<='=>$result->fin))->get('Bitacora')->result();

	}

	function getComportamientoByCompetencia($competencia,$posicion,$asignacion=null) {
		$this->db->distinct();
		$this->db->select('C.id,C.descripcion,C.competencia')->from('Comportamientos C');
		$this->db->join('Comportamiento_Posicion CP','CP.comportamiento = C.id','LEFT OUTER');
		$this->db->where(array('C.competencia'=>$competencia,'CP.nivel_posicion'=>$posicion));
		$this->db->order_by('C.descripcion');
		$result = $this->db->get()->result();
		foreach ($result as $comportamiento) :
			$res = $this->db->from('Detalle_ev_Competencia')->
				where(array('asignacion'=>$asignacion,'comportamiento'=>$comportamiento->id))->get();
			if($res->num_rows() == 1){
				$comportamiento->respuesta = $res->first_row()->respuesta;
				$comportamiento->justificacion = $res->first_row()->justificacion;
			}else{
				$comportamiento->respuesta = null;
				$comportamiento->justificacion = null;
			}
		endforeach;
		return $result;
	}

	function getCompetenciasByIndicador($indicador,$posicion) {
		$this->db->distinct();
		$this->db->select('C.id,C.nombre,C.descripcion,C.resumen');
		$this->db->from('Competencias C');
		$this->db->join('Comportamientos Co','Co.competencia = C.id');
		$this->db->join('Comportamiento_Posicion CP','CP.comportamiento = Co.id');
		$this->db->where(array('C.estatus'=>1,'C.indicador'=>$indicador,'CP.nivel_posicion'=>$posicion));
		$this->db->order_by('C.nombre','asc');
		return $this->db->get()->result();
	}

	function getIndicadoresByPosicion($posicion) {
		$this->db->distinct();
		$this->db->select('I.id,I.nombre');
		$this->db->from('Indicadores I');
		$this->db->join('Competencias C','C.indicador = I.id');
		$this->db->join('Comportamientos Co','Co.competencia = C.id');
		$this->db->join('Comportamiento_Posicion CP','Co.id = CP.comportamiento');
		$this->db->where(array('I.estatus'=>1,'C.estatus'=>1,'CP.nivel_posicion'=>$posicion));
		return $this->db->get()->result();
	}

	function getObjetivosByDominio($dominio,$area,$posicion) {
		$this->db->select('O.id,O.nombre,PO.valor,O.descripcion,O.tipo');
		$this->db->from('Objetivos O');
		$this->db->join('Objetivos_Areas OA','OA.objetivo = O.id','LEFT OUTER');
		$this->db->join('Porcentajes_Objetivos PO','PO.objetivo_area = OA.id','LEFT OUTER');
		$this->db->where(array('O.dominio'=>$dominio,'OA.area'=>$area,'PO.nivel_posicion'=>$posicion,'O.estatus'=>1,'PO.valor !='=>0));
		$this->db->order_by('O.tipo,O.nombre');
		return $this->db->get()->result();
	}

	function getMetricaByObjetivo($obj) {
		$this->db->from('Metricas');
		$this->db->where('objetivo',$obj);
		$this->db->order_by('valor','desc');
		return $this->db->get()->result();
	}

	function getResponsabilidadByArea($area,$posicion) {
		$this->db->distinct();
		$this->db->select('D.id, D.nombre,D.descripcion');
		$this->db->from('Areas A');
		$this->db->join('Objetivos_Areas OA','OA.area = A.id');
		$this->db->join('Porcentajes_Objetivos PO','PO.objetivo_area = OA.id');
		$this->db->join('Objetivos O','O.id = OA.objetivo');
		$this->db->join('Dominios D','O.dominio = D.id');
		$this->db->order_by('D.nombre');
		$this->db->where(array('A.id'=>$area,'D.estatus'=>1,'A.estatus'=>1,'O.estatus'=>1,'PO.nivel_posicion'=>$posicion));
		return $this->db->get()->result();
	}

	function getPerfil($area,$posicion) {
		$this->db->select('D.nombre dominio,O.descripcion,A.nombre area,O.nombre objetivo,O.id id_objetivo,PO.valor');
		$this->db->from('Areas A');
		$this->db->join('Objetivos_Areas OA','OA.area = A.id');
		$this->db->join('Objetivos O','OA.objetivo = O.id');
		$this->db->join('Dominios D','O.dominio = D.id');
		$this->db->join('Porcentajes_Objetivos PO','PO.objetivo = O.id');
		$this->db->where('O.estatus',1);
		if($posicion != null)
			$this->db->where('PO.posicion',$posicion);
		if($area != null)
			$this->db->where('A.id',$area);
		$this->db->order_by('D.nombre,O.nombre','asc');
		return $this->db->get()->result();

	}

	function getEvaluadores() {
		$evaluacion = $this->getEvaluacionAnual();
		($evaluacion) ? $anio=$this->getEvaluacionById($evaluacion)->anio : $anio=null;
		$this->db->distinct();
		$this->db->select('Us.id,Us.foto,Us.nombre')->from('Users Us');
		$this->db->join('Evaluadores Ev','Us.id = Ev.evaluador','LEFT OUTER');
		$this->db->join('Evaluaciones E','E.id = Ev.evaluacion');
		$this->db->where(array('Us.estatus'=>1,'E.anio'=>$anio));
		$this->db->where('Ev.evaluado != Ev.evaluador');
		$this->db->order_by('Us.nombre');
		$result = $this->db->get()->result();
		foreach ($result as $evaluador) :
			$evaluador->asignaciones = null;
			$evaluador->asignaciones360 = null;
			$evaluador->asignacionesProyecto = null;
			//evaluaciones
			$this->db->select('Ev.id,Ev.evaluado,Us.nombre,Us.foto')->from('Evaluadores Ev');
			$this->db->join('Evaluaciones E','E.id = Ev.evaluacion');
			$this->db->join('Users Us','Us.id = Ev.evaluado');
			$this->db->where(array('E.id'=>$evaluacion,'Ev.evaluado !='=>$evaluador->id,'Ev.evaluador'=>$evaluador->id,'Ev.anual'=>1,'E.tipo'=>1));
			$asignaciones = $this->db->get();
			if($asignaciones->num_rows() > 0):
				$asignaciones=$asignaciones->result();
				foreach ($asignaciones as $asignacion) :
					$res = $this->db->select('total')->where('asignacion',$asignacion->id)->get('Resultados_ev_Competencia');
					($res->num_rows() == 1) ? $asignacion->competencia = $res->first_row()->total : $asignacion->competencia=null;
					$res = $this->db->select('total')->where('asignacion',$asignacion->id)->get('Resultados_ev_Responsabilidad');
					($res->num_rows() == 1) ? $asignacion->responsabilidad = $res->first_row()->total : $asignacion->responsabilidad=null;
					$asignacion->total = ($asignacion->competencia*0.3)+($asignacion->responsabilidad*0.7);
				endforeach;
				$evaluador->asignaciones = $asignaciones;
			endif;

			//evaluaciones 360
			$this->db->select('Ev.id,Ev.evaluado,Us.nombre,Us.foto')->from('Evaluadores Ev');
			$this->db->join('Evaluaciones E','E.id = Ev.evaluacion');
			$this->db->join('Users Us','Us.id = Ev.evaluado');
			$this->db->join('Posicion_Track PT','PT.id = Us.posicion_track');
			$this->db->join('Posiciones P','P.id = PT.posicion');
			$this->db->where(array('E.id'=>$evaluacion,'Ev.evaluado !='=>$evaluador->id));
			$this->db->where(array('Ev.anual'=>0,'Ev.evaluador'=>$evaluador->id,'E.tipo'=>1,'Ev.evaluado !='=>$evaluador->id));
			$asignaciones = $this->db->get()->result();
			foreach ($asignaciones as $asignacion) :
				$res = $this->db->select('total')->where('asignacion',$asignacion->id)->get('Resultados_ev_Competencia');
				($res->num_rows() == 1) ? $asignacion->total = $res->first_row()->total : $asignacion->total=null;
			endforeach;
			$evaluador->asignaciones360 = $asignaciones;

			//evaluaciones de proyecto
			if($proyectos=$this->getEvaluacionesProyecto($anio)):
				$ids = array();
				foreach ($proyectos as $row)
					array_push($ids, $row->id);
				$this->db->select('Ev.id,Ev.evaluado,Us.nombre,Us.foto')->from('Evaluadores Ev');
				$this->db->join('Evaluaciones E','E.id = Ev.evaluacion');
				$this->db->join('Users Us','Us.id = Ev.evaluado');
				$this->db->where('Ev.evaluador',$evaluador->id);
				$this->db->where_in('E.id',$ids);
				$asignaciones = $this->db->get()->result();
				foreach ($asignaciones as $asignacion) :
					$res=$this->db->select('total')->from('Resultados_ev_Responsabilidad')->where('asignacion',$asignacion->id)->get();
					($res->num_rows() == 1) ? $asignacion->total = $res->first_row()->total : $asignacion->total = null;
					//$asignacion = $evaluador->asignacion;
				endforeach;
				$evaluador->asignacionesProyecto = $asignaciones;
			endif;
		endforeach;
		return $result;
	}

	function getPendientes($estatus="",$tipo="") {
		$evaluacion=$this->getEvaluacionAnualVigente();
		$this->db->distinct()->select('U.id,U.foto,U.nombre,U.email')->from('Evaluadores Ev')->join('Evaluaciones E','E.id = Ev.evaluacion')
			->join('Users U','Ev.evaluador = U.id')
			->where(array('E.anio'=>$evaluacion['anio'],'U.estatus'=>1))->order_by('U.nombre');
		$result = $this->db->get()->result();
		foreach ($result as $evaluador) :
			if($estatus == "" && $tipo == ""):
				$this->db->select('count(id) total')->from('Evaluadores')
					->where(array('evaluador'=>$evaluador->id,'evaluacion'=>$evaluacion['id'],'estatus !='=>2,'anual'=>1));
				$evaluador->anuales=$this->db->get()->first_row()->total;
				$this->db->select('count(id) total')->from('Evaluadores')
					->where(array('evaluador'=>$evaluador->id,'evaluado !='=>$evaluador->id,'evaluacion'=>$evaluacion['id'],'estatus !='=>2,'anual'=>0));
				$evaluador->tres60=$this->db->get()->first_row()->total;
				$this->db->select('count(id) total')->from('Evaluadores')
					->where(array('evaluador'=>$evaluador->id,'evaluado'=>$evaluador->id,'evaluacion'=>$evaluacion['id'],'estatus !='=>2,));
				$res=$this->db->get();
				($res->num_rows() > 0) ? $evaluador->auto=$res->first_row()->total : $evaluador->auto=0;
				$this->db->select('count(E.id) total')->from('Evaluadores E')->join('Evaluaciones Ev','Ev.id = E.evaluacion')
					->where(array('Ev.anio'=>$evaluacion['anio'],'E.evaluador'=>$evaluador->id,'E.evaluado !='=>$evaluador->id,'E.estatus !='=>2,'Ev.tipo'=>0));
				$res=$this->db->get();
				($res->num_rows() > 0) ? $evaluador->proyectos=$res->first_row()->total : $evaluador->proyectos=0;
			else:
				if($estatus != "")
					$this->db->where_in('E.estatus',$estatus);
				else
					$this->db->where_in('E.estatus',array(0,1));
				switch ($tipo) {
					case '360':
						$this->db->select('count(E.id) total')->from('Evaluadores E')
							->where(array('E.evaluador'=>$evaluador->id,'E.evaluado !='=>$evaluador->id,'E.evaluacion'=>$evaluacion['id'],'E.anual'=>0));
						break;
					case 'anual':
						$this->db->select('count(E.id) total')->from('Evaluadores E')
							->where(array('E.evaluador'=>$evaluador->id,'E.evaluacion'=>$evaluacion['id'],'E.anual'=>1));
						break;
					case 'auto':
						$this->db->select('count(E.id) total')->from('Evaluadores E')
							->where(array('E.evaluador'=>$evaluador->id,'E.evaluado'=>$evaluador->id,'E.evaluacion'=>$evaluacion['id']));
						break;
					case 'proyecto':
						$this->db->select('count(E.id) total')->from('Evaluadores E')->join('Evaluaciones Ev','Ev.id = E.evaluacion')
							->where(array('E.evaluador'=>$evaluador->id,'E.evaluado !='=>$evaluador->id,'Ev.tipo'=>0,'Ev.anio'=>$evaluacion['id']));
						break;
					default:
						$this->db->select('count(E.id) total')->from('Evaluadores E')
							->where(array('E.evaluador'=>$evaluador->id,'E.evaluacion'=>$evaluacion['id']));
						break;
				}
				$evaluador->resultado=$this->db->get()->first_row()->total;
			endif;
		endforeach;
		return $result;
	}

	function getByText($valor,$tipo) {
		$this->db->select('Us.foto,Ev.id id,Us.nombre nombre,count(Ev.evaluado) as cantidad');
		$this->db->join('Users as Us','Us.id = Ev.evaluador');
		$this->db->where('Ev.estatus',0);
		if($tipo==1)//1=evaluacion 360
			$this->db->where('Ev.tipo',3);
		else
			$this->db->where('Ev.tipo !=',3);

		$this->db->select('Us.foto,Ev.id id,Us.nombre nombre,count(Ev.evaluado) as cantidad');
		$this->db->join('Users as Us','Us.id = Ev.evaluador');
		$this->db->where('Ev.estatus',0);
		$this->db->where('Ev.360',0);
		$this->db->like('Us.nombre',$valor);
		$this->db->group_by('Ev.evaluador');
		$this->db->order_by('Us.nombre','asc');
		return $this->db->get('Evaluadores as Ev')->result();
	}

	function getCountUsers() {
		return $this->db->where('estatus',1)->count_all_results('Users');
	}

	function getInfoFeedback($id) {
		$this->db->select('F.id,F.compromisos,F.oportunidad,F.fortalezas,F.estatus,U.nombre,U.foto,RE.rating,RE.colaborador,RE.comentarios,RE.cumple_gastos,RE.cumple_harvest,RE.cumple_cv')
			->from('Feedbacks F')
			->join('Resultados_Evaluacion RE','RE.id = F.resultado')
			->join('Users U','U.id = RE.colaborador')
			->where('F.id',$id);
		return $this->db->get()->first_row();
	}

	function getFeedbacksByColaborador($evaluacion,$colaborador) {
		$this->db->select('U.id,U.foto,U.nombre,F.id feedback,F.estatus estatus_f,F.compromisos,F.oportunidad,F.fortalezas')
			->from('Resultados_Evaluacion RE')
			->join('Feedbacks F','RE.id = F.resultado')
			->join('Users U','U.id = F.feedbacker')
			->join('Areas A','A.id = U.area','LEFT OUTER')
			->join('Posicion_Track PT','PT.id = U.posicion_track','LEFT OUTER')
			->join('Posiciones P','P.id = PT.posicion','LEFT OUTER')
			->join('Tracks T','T.id = PT.track','LEFT OUTER')
			->where(array('RE.colaborador'=>$colaborador,'RE.evaluacion'=>$evaluacion,'U.estatus'=>1))
			->order_by('F.estatus,U.nombre');
		return $this->db->get()->result();
	}

	function getFeedbacksByEvaluador($evaluacion,$evaluador) {
		$this->db->select('U.id,U.foto,U.nombre,A.nombre area,P.nombre posicion,RE.rating,T.nombre track,F.id feedback,F.estatus estatus_f')
			->from('Resultados_Evaluacion RE')
			->join('Feedbacks F','RE.id = F.resultado')
			->join('Users U','U.id = RE.colaborador')
			->join('Areas A','A.id = U.area','LEFT OUTER')
			->join('Posicion_Track PT','PT.id = U.posicion_track','LEFT OUTER')
			->join('Posiciones P','P.id = PT.posicion','LEFT OUTER')
			->join('Tracks T','T.id = PT.track','LEFT OUTER')
			->where(array('F.feedbacker'=>$evaluador,'RE.evaluacion'=>$evaluacion,'U.estatus'=>1))
			->order_by('F.estatus,U.nombre');
		return $this->db->get()->result();
	}

	function getResumen($anio,$id) {
		$posicion = $this->getPosicionByColaborador($id);
		$result=new stdClass();
		if ($posicion <= 5) :
			$asignaciones = $this->db->select('Ev.id')->from('Evaluadores Ev')->join('Evaluaciones E','E.id = Ev.evaluacion')
				->where(array('Ev.evaluado'=>$id,'E.anio'=>$anio))->get()->result();
			$ids=array();
			foreach ($asignaciones as $as) {
				array_push($ids, $as->id);
			}
			if(!empty($ids)){
				$categories=array();
				$data=array();
				$items="";
				foreach ($this->getIndicadoresByPosicion($posicion) as $indicador) :
					$string="";
					foreach ($this->getCompetenciasByIndicador($indicador->id,$posicion) as $competencia) :
						$justificacion=array();
						array_push($categories,$competencia->nombre);
						$temp=$this->db->select('AVG(DE.respuesta) promedio')->from('Detalle_ev_360 DE')
							->join('Competencias C','C.id = DE.competencia')
							->where_in('asignacion',$ids)
							->where('C.id',$competencia->id)->get()->first_row();
						array_push($data, $temp->promedio);

						$temp2=$this->db->select('justificacion')->from('Detalle_ev_360 DE')
							->join('Competencias C','C.id = DE.competencia')
							->where_in('asignacion',$ids)
							->where('C.id',$competencia->id)->get()->result();
						foreach ($temp2 as $t) :
							if($t->justificacion != "")
								$string .= "$t->justificacion<br>";
						endforeach;
					endforeach;
					$items .="<tr><td style='cursor:default'>$indicador->nombre</td><td style='cursor:default'>$string</td></tr>";
				endforeach;
				$result->data = $data;
				$result->justificacion = $items;
				$result->categories = $categories;
				$result->name = $this->db->where('id',$id)->get('Users')->first_row()->nombre;
			}
		endif;
		return $result;
	}

	function getResumenByEvaluacion($evaluacion,$id) {
		$posicion = $this->getPosicionByColaborador($id);
		$result=new stdClass();
		if ($posicion <= 5) :
			$asignaciones = $this->db->select('Ev.id')->from('Evaluadores Ev')->join('Evaluaciones E','E.id = Ev.evaluacion')
				->where(array('Ev.evaluado'=>$id,'E.id'=>$evaluacion,'Ev.estatus'=>2))->get()->result();
			$ids=array();
			foreach ($asignaciones as $as) {
				array_push($ids, $as->id);
			}
			if(!empty($ids)){
				$categories=array();
				$data=array();
				$items="";
				foreach ($this->getIndicadoresByPosicion($posicion) as $indicador) :
					$string="";
					foreach ($this->getCompetenciasByIndicador($indicador->id,$posicion) as $competencia) :
						$justificacion=array();
						array_push($categories,$competencia->nombre);
						$temp=$this->db->select('AVG(DE.respuesta) promedio')->from('Detalle_ev_360 DE')
							->join('Competencias C','C.id = DE.competencia')
							->where_in('asignacion',$ids)
							->where('C.id',$competencia->id)->get()->first_row();
						array_push($data, $temp->promedio);

						$temp2=$this->db->select('justificacion')->from('Detalle_ev_360 DE')
							->join('Competencias C','C.id = DE.competencia')
							->where_in('asignacion',$ids)
							->where('C.id',$competencia->id)->get()->result();
						foreach ($temp2 as $t) :
							if($t->justificacion != "")
								$string .= "$t->justificacion<br>";
						endforeach;
					endforeach;
					$items .="<tr><td style='cursor:default'><small>$indicador->nombre</small></td><td style='cursor:default'><small>$string</small></td></tr>";
				endforeach;
				$result->data = $data;
				$result->justificacion = $items;
				$result->categories = $categories;
				$result->name = $this->db->where('id',$id)->get('Users')->first_row()->nombre;
			}
		endif;
		return $result;
	}

	function getSubordinados($evaluacion,$jefe) {
		$this->db->select('U.id,U.foto,U.nombre,A.nombre area,P.nombre posicion,T.nombre track')->from('Resultados_Evaluacion RE')
			->join('Users U','U.id = RE.colaborador')
			->join('Areas A','A.id = U.area','LEFT OUTER')
			->join('Posicion_Track PT','PT.id = U.posicion_track','LEFT OUTER')
			->join('Posiciones P','P.id = PT.posicion','LEFT OUTER')
			->join('Tracks T','T.id = PT.track','LEFT OUTER')
			->where(array('RE.evaluacion'=>$evaluacion,'U.estatus'=>1,'P.nivel <='=>5))
			->order_by('U.nombre');
			if(!in_array($this->session->userdata('tipo'),array(4,5,6)))
				$this->db->where('U.jefe',$jefe);
		return $this->db->get()->result();
	}

	function hasFeedback($anio,$id) {
		$result = $this->db->from('Resultados_Evaluacion RE')->join('Feedbacks F','F.resultado = RE.id')
			->join('Evaluaciones E','E.id = RE.evaluacion')
			->where(array('RE.colaborador'=>$id,'E.anio'=>$anio,'F.estatus >'=>0))->get();
		if($result->num_rows() == 1)
			return true;
		return false;
	}

	// Allows get feedback by user
    function getFeedback($id,$anio) {
        $result = $this->db
            //->select('RE.rating,F.id feedback,F.estatus estatus_f')
            ->from('Resultados_Evaluacion RE')->join('Feedbacks F','F.resultado = RE.id')
            ->join('Evaluaciones E','E.id = RE.evaluacion')
            ->where(array('RE.colaborador'=>$id,'E.anio'=>$anio,'F.estatus >'=>0))->get();
        if($result->num_rows() == 1)
            return $result->result()[0];
        return false;
    }

	function getEvaluacionesByEvaluador($evaluador) {
		if($ev = $this->getActiveEvaluation())
			if($evaluacion=$this->getEvaluacionById($ev->id)):
				if($evaluacion->estatus == 2 && $evaluacion->tipo==1){
					$evaluacion = $evaluacion->id;
					redirect("evaluacion/defineFeedback/$evaluacion");
				}
				$this->db->select('U.id,U.foto,U.nombre,U.nomina,A.nombre area,P.nombre posicion,Ev.nombre evaluacion,E.estatus,E.id asignacion,
					Ev.inicio,Ev.fin,Ev.tipo,Ev.estatus estatus_ev,T.nombre track,E.anual')->from('Users U')
					->join('Areas A','A.id = U.area','LEFT OUTER')
					->join('Posicion_Track PT','PT.id = U.posicion_track','LEFT OUTER')
					->join('Posiciones P','P.id = PT.posicion','LEFT OUTER')
					->join('Tracks T','T.id = PT.track','LEFT OUTER')
					->join('Evaluadores E','E.evaluado = U.id','LEFT OUTER')
					->join('Evaluaciones Ev','Ev.id = E.evaluacion','LEFT OUTER')
					->where(array('E.evaluador'=>$evaluador,'U.estatus'=>1,'Ev.estatus'=>1,'Ev.inicio <='=>date('Y-m-d'),'Ev.fin >='=>date('Y-m-d')))
					->order_by('E.estatus,Ev.nombre,U.nombre');
				return $this->db->get()->result();
			else:
				return null;
			endif;
	}

	function getEvaluados() {
		($this->getEvaluacionAnual()) ? $anio=$this->getEvaluacionById($this->getEvaluacionAnual())->anio : $anio=null;
		$this->db->select('U.id,U.email,U.foto,U.nombre,U.nomina,P.nombre posicion,T.nombre track,U.fecha_ingreso,P.nivel nivel_posicion')
			->from('Users U')->join('Posicion_Track PT','PT.id = U.posicion_track','LEFT OUTER')
			->join('Posiciones P','P.id = PT.posicion','LEFT OUTER')
			->join('Tracks T','T.id = PT.track','LEFT OUTER')
			->where(array('U.estatus'=>1,'U.fecha_ingreso <='=>$anio.'-09-30'))
			->order_by('U.nombre');
		$result = $this->db->get()->result();
		foreach ($result as $colaborador) : // getEvaluadores
			$colaborador = $this->getResultadosByColaborador($colaborador);
		endforeach;
		return $result;
	}

	function updateRating($where,$datos,$feedbacker) {
		$this->db->trans_begin();
		$this->db->where($where)->update('Resultados_Evaluacion',$datos);
		$res = $this->db->where($where)->get('Resultados_Evaluacion')->first_row();
		$this->db->where('resultado',$res->id)->update('Feedbacks',$feedbacker);

		$anio=$this->getEvaluacionById($where['evaluacion'])->anio;
		$where_historial=array('anio'=>$anio,'colaborador'=>$where['colaborador']);
		$res = $this->db->where($where_historial)->get('Historial');
		if ($res->num_rows() == 1)
			$this->db->where($where_historial)->update('Historial',array('rating'=>$datos['rating']));
		else{
			$where_historial['rating'] = $datos['rating'];
			$this->db->insert('Historial',$where_historial);
		}
		if($this->db->trans_status() === FALSE):
			$this->db->trans_rollback();
			return false;
		else:
			$this->db->trans_commit();
			return true;
		endif;
	}

	function guardaHistorial($datos) {
		$this->db->insert('Historial',$datos);
		if($this->db->affected_rows() == 1)
			return true;
		return false;
	}

	function updateFeedbacker($where,$datos) {
		$res = $this->db->where($where)->get('Resultados_Evaluacion')->first_row();
		$this->db->where('resultado',$res->id)->update('Feedbacks',$datos);
		if($this->db->affected_rows() == 0)
			$this->db->insert('Feedbacks',array('resultado'=>$res->id,'feedbacker'=>$datos['feedbacker']));
		if($this->db->affected_rows() == 1)
			return true;
		return false;
	}

	function updateFeedback($id,$datos) {
		$this->db->where('id',$id)->update('Feedbacks',$datos);
		if($this->db->affected_rows() == 1)
			return true;
		return false;
	}

	function asigna_ci($colaborador,$valor,$field) {
		$evaluacion=$this->getEvaluacionAnual();
		if($valor == "true")
			$valor="SI";
		else
			$valor="NO";
		$res =$this->db->where(array('colaborador'=>$colaborador,'evaluacion'=>$evaluacion))->get('Resultados_Evaluacion');
		if($res->num_rows() == 1){
			$resultado = $res->first_row()->id;
			$this->db->where('id',$resultado)->update('Resultados_Evaluacion',array($field=>$valor));
		}else
			$this->db->insert('Resultados_Evaluacion',array('evaluacion'=>$evaluacion,'colaborador'=>$colaborador,"$field"=>$valor));
		if($this->db->affected_rows() == 1)
			return true;
		return false;
	}

	function getResultadosByColaborador($colaborador, $evaluacion = null) {

	    if(empty($evaluacion)){
            $evaluacion=$this->getEvaluacionAnual();
        }

        ($evaluacion) ? $anio=$this->getEvaluacionById($evaluacion)->anio : $anio=null;
		$res=$this->db->from('Resultados_Evaluacion')->where(array('colaborador'=>$colaborador->id,'evaluacion'=>$evaluacion))->get();
		if($res->num_rows() == 1){
			$colaborador->rating = $res->first_row()->rating;
			$colaborador->comentarios = $res->first_row()->comentarios;
			$colaborador->total = $res->first_row()->total;
			$colaborador->cumple_gastos = $res->first_row()->cumple_gastos;
			$colaborador->cumple_harvest = $res->first_row()->cumple_harvest;
			$colaborador->cumple_cv = $res->first_row()->cumple_cv;
			$res = $this->db->select('F.id,F.estatus,F.feedbacker,F.fortalezas,F.oportunidad,F.compromisos,U.nombre')->from('Feedbacks F')
				->join('Users U','U.id = F.feedbacker')
				->where(array('F.resultado'=>$res->first_row()->id,'U.estatus'=>1))->get();
			if($res->num_rows() == 0){
				$jefe=$this->db->select('jefe')->from('Users')->where('id',$colaborador->id)->get()->first_row()->jefe;
				$res = $this->db->select('id feedbacker,nombre')->where('id',$jefe)->get('Users');
			}
			$colaborador->feedback = $res->first_row();
		}else{
			$colaborador->feedback = $this->db->select('id feedbacker,nombre')->where('id',1)->get('Users')->first_row();
			$colaborador->rating = null;
			$colaborador->total = 0;
		}
		$colaborador->proyecto = null;
		$colaborador->evaluadores360 = null;
		$colaborador->evaluadoresProyecto = null;
		//obtener evaluacion360 si es de nivel 3-4-5
		$posicion=$this->getPosicionByColaborador($colaborador->id);
		//totalizado
			$res=$this->db->select('RC.total')->from('Resultados_ev_Competencia RC')->join('Evaluadores E','E.id = RC.asignacion')
				->where(array('E.evaluado'=>$colaborador->id,'E.anual'=>1,'E.evaluacion'=>$evaluacion))->get();
			($res->num_rows() == 1) ? $colaborador->competencias=$res->first_row()->total : $colaborador->competencias = 0;

			$res=$this->db->select('RR.total')->from('Resultados_ev_Responsabilidad RR')->join('Evaluadores E','E.id = RR.asignacion')
				->where(array('E.evaluado'=>$colaborador->id,'E.anual'=>1,'E.evaluacion'=>$evaluacion))->get();
			($res->num_rows() == 1) ? $colaborador->responsabilidades=$res->first_row()->total : $colaborador->responsabilidades = 0;


		if($posicion >=3 && $posicion <= 5){
			$res=$this->db->select('AVG(RC.total) total')->from('Resultados_ev_Competencia RC')->join('Evaluadores E','E.id = RC.asignacion')
				->where(array('E.evaluado'=>$colaborador->id,'E.evaluador !='=>$colaborador->id,'E.anual'=>0,'E.evaluacion'=>$evaluacion))->get();
			($res->num_rows() == 1) ? $colaborador->tres60=$res->first_row()->total : $colaborador->tres60 = 0;

			//listado de evaluadores
			$jefe=$this->db->select('evaluador')->where(array('evaluado'=>$colaborador->id,'anual'=>1,'evaluacion'=>$evaluacion))
				->get('Evaluadores');
			if($jefe->num_rows() ==1)
				$jefe=$jefe->first_row()->evaluador;
			$this->db->select('U.id,U.foto,U.nombre,E.id asignacion,E.comentarios,Ev.nombre evaluacion')->from('Users U')
				->join('Evaluadores E','E.evaluador = U.id')
				->join('Evaluaciones Ev','Ev.id = E.evaluacion','LEFT OUTER')
				->where(array('Ev.estatus !='=>0,'Ev.id'=>$evaluacion,'E.evaluado'=>$colaborador->id,'E.evaluador !='=>$jefe,
					'E.evaluador !='=>$colaborador->id,'E.anual'=>0));
			$colaborador->evaluadores360 = $this->db->get()->result();
			$ignore=array();
			foreach ($colaborador->evaluadores360 as $evaluador) :
				array_push($ignore, $evaluador->id);
				$res=$this->db->from('Resultados_ev_Competencia')->where('asignacion',$evaluador->asignacion)->get();
				($res->num_rows() == 1) ? $evaluador->competencia = $res->first_row()->total : $evaluador->competencia = null;
			endforeach;
			if (!empty($ignore))
				$this->db->where_not_in('U.id',$ignore);
		}
		//evaluadores
		$this->db->select('U.id,U.foto,U.nombre,E.id asignacion,E.comentarios,Ev.nombre evaluacion,E.anual,Ev.tipo')->from('Users U')
			->join('Evaluadores E','E.evaluador = U.id')
			->join('Evaluaciones Ev','Ev.id = E.evaluacion','LEFT OUTER')
			->where(array('Ev.estatus !='=>0,'Ev.id'=>$evaluacion,'E.evaluado'=>$colaborador->id,'E.evaluador !='=>$colaborador->id,'E.anual'=>1));
		$colaborador->evaluadores = $this->db->get()->result();
		foreach ($colaborador->evaluadores as $evaluador) :
			$res=$this->db->from('Resultados_ev_Competencia')->where('asignacion',$evaluador->asignacion)->get();
			($res->num_rows() == 1) ? $evaluador->competencia = $res->first_row()->total : $evaluador->competencia = null;
			$res=$this->db->from('Resultados_ev_Responsabilidad')->where('asignacion',$evaluador->asignacion)->get();
			($res->num_rows() == 1) ? $evaluador->responsabilidad = $res->first_row()->total : $evaluador->responsabilidad = null;
			$evaluador->total = ($evaluador->competencia*.3)+($evaluador->responsabilidad*.7);
			$asignacion = $evaluador->asignacion;
		endforeach;

		//autoevaluacion
		$res=$this->db->select('RC.total, comentarios,E.id asignacion')->from('Resultados_ev_Competencia RC')->join('Evaluadores E','E.id = RC.asignacion')
		->where(array('E.evaluado'=>$colaborador->id,'E.evaluador'=>$colaborador->id,'E.evaluacion'=>$evaluacion))->get();
		if($res->num_rows() == 1):
			$colaborador->autoevaluacion = $res->first_row()->total;
			$colaborador->auto=$res->first_row();
		else:
			$colaborador->autoevaluacion = 0;
			$colaborador->auto=null;
		endif;

		//evaluaciones por proyecto
		$colaborador->proyectos=null;
		if($proyectos=$this->getEvaluacionesProyecto($anio)):
			$ids = array();
			foreach ($proyectos as $row)
				array_push($ids, $row->id);
			//totales para index
				$this->db->select('RR.total,E.fin_periodo,E.inicio_periodo')->from('Resultados_ev_Responsabilidad RR')
					->join('Evaluadores Ev','Ev.id = RR.asignacion')
					->join('Evaluaciones E','E.id = Ev.evaluacion')
					->where('Ev.evaluado',$colaborador->id)->where_in('E.id',$ids);
				$res=$this->db->get();
				if($res->num_rows() > 0):
					$colaborador->proyectos=0;
					$dias_total=0;
					foreach ($res->result() as $info) :
						$diferencia=date_diff(date_create($info->inicio_periodo),date_create($info->fin_periodo));
						$dias=(int)$diferencia->format("%a");
						$dias_total+=$dias;
					endforeach;
					foreach ($res->result() as $info) :
						$diferencia=date_diff(date_create($info->inicio_periodo),date_create($info->fin_periodo));
						$dias=(int)$diferencia->format("%a");
						($dias == 0) ? $dias=1:$dias=$dias;
						$colaborador->proyectos += $info->total*($dias/$dias_total);
					endforeach;
				endif;
			$this->db->select('U.id,U.foto,U.nombre,E.id asignacion,Ev.nombre evaluacion,E.comentarios,Ev.fin_periodo,Ev.inicio_periodo')
				->from('Users U')->join('Evaluadores E','E.evaluador = U.id')
				->join('Evaluaciones Ev','Ev.id = E.evaluacion','LEFT OUTER')
				->where_in('Ev.id',$ids)->where(array('Ev.estatus !='=>0,'E.evaluado'=>$colaborador->id,'E.evaluador !='=>$colaborador->id))
				->order_by('Ev.nombre,U.nombre');
			$colaborador->evaluadoresProyecto = $this->db->get()->result();
			foreach ($colaborador->evaluadoresProyecto as $evaluador) :
				$res=$this->db->from('Resultados_ev_Responsabilidad')->where('asignacion',$evaluador->asignacion)->get();
				($res->num_rows() == 1) ? $evaluador->responsabilidad = $res->first_row()->total : $evaluador->responsabilidad = 0;
				//$asignacion = $evaluador->asignacion;
			endforeach;
		endif;

		return $colaborador;
	}

	function calculaResultado($asignacion) {
		$ev = $this->getEvaluacionAnualVigente();
		$r_anual=0;
		if($evaluacion=$this->getEvaluacionById($ev['id'])):
			$posicion=$this->getPosicionByColaborador($asignacion->evaluado);
			$jefe=$this->db->select('evaluador')->where(array('evaluado'=>$asignacion->evaluado,'anual'=>1,'evaluacion'=>$evaluacion->id))
				->get('Evaluadores');
			($jefe->num_rows() > 0) ? $jefe=$jefe->first_row()->evaluador : $jefe=1;
			$competencia=0;
			$responsabilidad=0;
			//si es de gerente a director se evalúa 360
			if($posicion <= 5):
				$this->db->select('AVG(RC.total) total360')->from('Resultados_ev_Competencia RC')
					->join('Evaluadores Ev','Ev.id = RC.asignacion')
					->where(array('Ev.evaluado'=>$asignacion->evaluado,'Ev.evaluacion'=>$evaluacion->id,'Ev.evaluador !='=>$jefe,
					'Ev.evaluador !='=>$asignacion->evaluado,'Ev.anual'=>0));
				$res = $this->db->get();
				if($res->num_rows() == 1)
					(double)$competencia += (double)($res->first_row()->total360)*0.1;
			endif;
			//evaluacion del encargado de evaluar anualmente
			$this->db->select('RC.total')->from('Resultados_ev_Competencia RC')
				->join('Evaluadores Ev','Ev.id = RC.asignacion')
				->where(array('Ev.evaluado'=>$asignacion->evaluado,'Ev.evaluacion'=>$evaluacion->id,'Ev.anual'=>1));
			$res=$this->db->get();
			if($res->num_rows() == 1):
				if($posicion <= 5)
					(double)$competencia += (double)($res->first_row()->total)*0.1;
				else
					(double)$competencia += (double)($res->first_row()->total)*0.15;
			endif;
			//autoevaluación
			$this->db->select('RC.total')->from('Resultados_ev_Competencia RC')
				->join('Evaluadores Ev','Ev.id = RC.asignacion')
				->where(array('Ev.evaluado'=>$asignacion->evaluado,'Ev.evaluacion'=>$evaluacion->id,'Ev.evaluador'=>$asignacion->evaluado));
			$res=$this->db->get();
			if($res->num_rows() == 1):
				if($posicion >= 3 && $posicion <= 5)
					(double)$competencia += ($res->first_row()->total)*0.1;
				else
					(double)$competencia += ($res->first_row()->total)*0.15;
			endif;
			//evaluacion jefe
			$this->db->select('AVG(RR.total) total')->from('Resultados_ev_Responsabilidad RR');
			$this->db->join('Evaluadores Ev','Ev.id = RR.asignacion');
			$this->db->join('Evaluaciones E','E.id = Ev.evaluacion');
			$this->db->where(array('Ev.evaluado'=>$asignacion->evaluado,'E.id'=>$evaluacion->id,'Ev.anual'=>1));
			$res=$this->db->get();
			if($res->num_rows() == 1)
				(double)$r_anual = ($res->first_row()->total);
			//evaluaciones de proyectos
			if($proyectos=$this->getEvaluacionesProyecto($evaluacion->anio)):
				$ids = array();
				foreach ($proyectos as $row)
					array_push($ids, $row->id);
				$this->db->select('RR.total,E.fin_periodo,E.inicio_periodo')->from('Resultados_ev_Responsabilidad RR')
					->join('Evaluadores Ev','Ev.id = RR.asignacion')
					->join('Evaluaciones E','E.id = Ev.evaluacion')
					->where('Ev.evaluado',$asignacion->evaluado)->where_in('E.id',$ids);
				$res=$this->db->get();
				if($res->num_rows() > 0):
					$r_proyectos=0;
					$dias_total=0;
					foreach ($res->result() as $info) :
						$diferencia=date_diff(date_create($info->inicio_periodo),date_create($info->fin_periodo));
						$dias=(int)$diferencia->format("%a");
						$dias_total+=$dias;
					endforeach;
					foreach ($res->result() as $info) :
						$diferencia=date_diff(date_create($info->inicio_periodo),date_create($info->fin_periodo));
						$dias=(int)$diferencia->format("%a");
						($dias == 0) ? $dias=1:$dias=$dias;
						$r_proyectos += $info->total*($dias/$dias_total);
					endforeach;
				endif;
			endif;
			(isset($r_proyectos)) ? (double)$responsabilidad = (($r_anual + $r_proyectos)/2)*.7 : (double)$responsabilidad = $r_anual*.7;
			(double)$total = $responsabilidad+$competencia;
			$res = $this->db->where(array('evaluacion'=>$evaluacion->id,'colaborador'=>$asignacion->evaluado))->get('Resultados_Evaluacion');
			if($res->num_rows() == 1){
				$id_r = $res->first_row()->id;
				$this->db->where('id',$id_r)->update('Resultados_Evaluacion',array('total'=>$total));
			}
			else{
				$this->db->insert('Resultados_Evaluacion',array('evaluacion'=>$evaluacion->id,'colaborador'=>$asignacion->evaluado,'total'=>$total));
				$id_r = $this->db->insert_id();
			}
			// verificar que haya registrado un feedbacker
			$res = $this->db->where('resultado',$id_r)->get('Feedbacks');
			if($res->num_rows() == 0)
				$this->db->insert('Feedbacks',array('resultado'=>$id_r,'feedbacker'=>$jefe));
		endif;
	}

	function getPagination($anio) {
		$this->db->select('U.id,U.foto,U.nombre,A.nombre area,P.nombre posicion')
			->join('Areas A','A.id = U.area','LEFT OUTER')
			->join('Posicion_Track PT','PT.id = U.posicion_track')
			->join('Posiciones P','P.id = PT.posicion')
			->where(array('U.fecha_ingreso <='=>$anio.'-09-30','U.estatus'=>1,'P.nivel <='=>8))
			->group_by('U.id')
			->order_by('U.nombre');
		$result = $this->db->get('Users U')->result();
		$evaluacion=$this->getEvaluacionAnual();
		foreach ($result as $user) :
			$user->total_evaluadores = $this->db->select('count(evaluador) total')
			->where(array('evaluador !='=>$user->id,'evaluado'=>$user->id,'evaluacion'=>$evaluacion))->get('Evaluadores')->first_row()->total;
		endforeach;
		return $result;
	}

	function getInfoLider($lider) {
		$this->db->select('Us.id,Us.nombre,P.nombre posicion,T.nombre track')->from('Users Us')
			->join('Posicion_Track PT','PT.id = Us.posicion_track','LEFT OUTER')
			->join('Posiciones P','P.id = PT.posicion','LEFT OUTER')
			->join('Tracks T','T.id = PT.track','LEFT OUTER')
			->where('Us.id',$lider);
		$result = $this->db->get()->first_row();
		$res = $this->db->where(array('evaluador'=>$lider,'estatus !='=>0))->get('Evaluadores');
		($res->num_rows() != 0) ? $result->estatus = $res->first_row()->estatus : $result->estatus=null;
		return $result;
	}

	function getParticipantesByEvaluacion($evaluacion) {
		$this->db->select('Us.id,Us.nombre,P.nombre posicion,T.nombre track,E.estatus')->from('Users Us');
		$this->db->join('Posicion_Track PT','PT.id = Us.posicion_track','LEFT OUTER');
		$this->db->join('Posiciones P','P.id = PT.posicion','LEFT OUTER');
		$this->db->join('Tracks T','T.id = PT.track','LEFT OUTER');
		$this->db->join('Evaluadores E','E.evaluado = Us.id');
		$this->db->join('Evaluaciones Ev','Ev.id = E.evaluacion');
		$this->db->where('Ev.id',$evaluacion);
		return $this->db->get()->result();
	}

	function getEvaluadoresByColaborador($colaborador) {
		$evaluacion=$this->getEvaluacionAnual();
		$this->db->select('Ev.evaluador id,Us.nombre,P.nombre posicion,T.nombre track,Ev.estatus,Ev.anual');
		$this->db->where(array('evaluado'=>$colaborador,'evaluacion'=>$evaluacion,'evaluador !='=>$colaborador));
		$this->db->from('Evaluadores Ev');
		$this->db->join('Users Us','Ev.evaluador = Us.id');
		$this->db->join('Posicion_Track PT','PT.id = Us.posicion_track','LEFT OUTER');
		$this->db->join('Posiciones P','P.id = PT.posicion','LEFT OUTER');
		$this->db->join('Tracks T','T.id = PT.track','LEFT OUTER');
		return $this->db->get()->result();
	}

	function getNotEvaluadoresByColaborador($colaborador,$ignore) {
		$empresa=$this->db->select('empresa')->from('Users')->where('id',$colaborador)->get()->first_row()->empresa;
		if(count($ignore)>0){
			$array_temp=array();
			foreach ($ignore as $temp) 
				array_push($array_temp, $temp->id);
			$this->db->where_not_in('Us.id',$array_temp);
		}

		$this->db->select('Us.id,Us.nombre,P.nombre posicion,T.nombre track,Us.empresa')->from('Users Us')
			->where(array('Us.id !='=>$colaborador,'Us.estatus'=>1))
			->join('Posicion_Track PT','PT.id = Us.posicion_track','LEFT OUTER')
			->join('Posiciones P','P.id = PT.posicion','LEFT OUTER')
			->join('Tracks T','T.id = PT.track','LEFT OUTER');
		return $this->db->order_by('Us.nombre')->get()->result();
	}

	function addEvaluadorToColaborador($datos) {
		if($this->db->where($datos)->get('Evaluadores')->num_rows() == 0){
			$this->db->insert('Evaluadores',$datos);
			return $this->db->insert_id();
		}
	}

	function delEvaluadorFromColaborador($datos) {
		if($this->db->where($datos)->where('estatus !=',2)->get('Evaluadores')->num_rows() != 0)
			$this->db->where($datos)->where('estatus !=',2)->delete('Evaluadores');
	}

	function getEvaluacionesSinAplicar() {
		$this->db->where('estatus <',2);
		$this->db->order_by('nombre','desc');
		return $this->db->get('Evaluaciones')->result();
	}

	function gestionar($id,$datos) {
		$this->db->where('id',$id);
		$this->db->update('Evaluaciones',$datos);
		if($this->db->affected_rows() == 1)
			return true;
		else
			return false;
	}

	function getEvaluacionById($id) {
		return $this->db->where('id',$id)->get('Evaluaciones')->first_row();
	}

	function eraseByEvaluador($evaluacion,$evaluador) {
		$this->db->where(array('evaluacion'=>$evaluacion,'evaluador'=>$evaluador))->delete('Evaluadores');
		echo $this->db->affected_rows()." borradas";
	}

	function updateLider($evaluacion,$lider) {
		$this->db->where('id',$evaluacion)->update('Evaluaciones',array('lider'=>$lider));
	}

	function getRespuestaByAsignacionResponsabilidad($asignacion,$objetivo) {
		$result = $this->db->from('Detalle_Evaluacion')->where(array('asignacion'=>$asignacion,'objetivo'=>$objetivo->id))->get();
		if($result->num_rows() == 1){
			$id_m = $result->first_row()->metrica;
			$objetivo->respuesta = $this->db->from('Metricas')->where('id',$id_m)->get()->first_row()->valor;
			$objetivo->justificacion = $result->first_row()->justificacion;
		}else{
			$objetivo->respuesta = null;
			$objetivo->justificacion="";
		}
		return $objetivo;
	}

	function getEvaluacionByAsignacion($asignacion) {
		$result = $this->searchAsignacionById($asignacion);
		$posicion=$this->getPosicionByColaborador($result->evaluado);
		$result->indicadores = 0;
		$result->total_responsabilidades=0;
		if($result->tipo == 1){
			$result->indicadores = $this->getIndicadoresByPosicion($posicion);
			$result->total_competencias=count($result->indicadores);
			foreach ($result->indicadores as $indicador) :
				foreach ($indicador->competencias = $this->getCompetenciasByIndicador($indicador->id,$posicion) as $competencia ) :
					$res=$this->db->where(array('asignacion'=>$asignacion,'competencia'=>$competencia->id))->get('Detalle_ev_360');
					if($res->num_rows() == 1): 
						$competencia->respuesta=$res->first_row()->respuesta;
						$competencia->justificacion=$res->first_row()->justificacion;
					else:
						$competencia->respuesta=null;
						$competencia->justificacion=null;
					endif;
					$competencia->comportamientos = $this->getComportamientoByCompetencia($competencia->id,$posicion,$asignacion);
				endforeach;
			endforeach;
		}
		if($result->anual == 1 || $result->tipo==0){
			$area = $this->getAreaByColaborador($result->evaluado);
			$result->dominios=$this->getResponsabilidadByArea($area,$posicion);
			foreach ($result->dominios as $dominio) :
				$res = $this->db->from('Detalle_ev_Proyecto')->where(array('asignacion'=>$asignacion,'dominio'=>$dominio->id))->get();
				if($res->num_rows() == 1):
					$res = $res->first_row();
					$dominio->respuesta = $res->respuesta;
					$dominio->justificacion = $res->justificacion;
				else:
					$dominio->respuesta = null;
					$dominio->justificacion = null;
				endif;
				foreach ($dominio->responsabilidades=$this->getObjetivosByDominio($dominio->id,$area,$posicion) as $responsabilidad) :
					$responsabilidad = $this->getRespuestaByAsignacionResponsabilidad($asignacion,$responsabilidad);
					$responsabilidad->metricas = $this->getMetricaByObjetivo($responsabilidad->id);
				endforeach;
				$result->total_responsabilidades += count($result->indicadores);
			endforeach;
		}
		return $result;
	}

	function getProyectoByAsignacion($asignacion) {
		$result = $this->searchAsignacionById($asignacion);
		$posicion=$this->getPosicionByColaborador($result->evaluado);
		$area = $this->getAreaByColaborador($result->evaluado);
		foreach ($result->dominios=$this->getResponsabilidadByArea($area,$posicion) as $dominio) :
			$res = $this->db->from('Detalle_ev_Proyecto')->where(array('asignacion'=>$asignacion,'dominio'=>$dominio->id))->get();
			if($res->num_rows() == 1):
				$res = $res->first_row();
				$dominio->respuesta = $res->respuesta;
				$dominio->justificacion = $res->justificacion;
			else:
				$dominio->respuesta = null;
				$dominio->justificacion = null;
			endif;
		endforeach;
		return $result;
	}

	function guardaMetrica($asignacion,$metrica,$objetivo,$justificacion) {
		$id_m=$this->db->select('id')->where(array('objetivo'=>$objetivo,'valor'=>$metrica))->get('Metricas')->first_row()->id;
		$result = $this->db->from('Detalle_Evaluacion')->where(array('objetivo'=>$objetivo,'asignacion'=>$asignacion))->get();
		if($result->num_rows() == 1){
			$id_detalle=$result->first_row()->id;
			$this->db->where('id',$id_detalle)->update('Detalle_Evaluacion',array('metrica'=>$id_m,'justificacion'=>$justificacion));
		}else
			$this->db->insert('Detalle_Evaluacion',array('asignacion'=>$asignacion,'objetivo'=>$objetivo,'metrica'=>$id_m,
				'justificacion'=>$justificacion));
		if($this->db->affected_rows() == 1)
			return true;
		return false;
	}

	function guardaDominio($asignacion,$respuesta,$dominio,$justificacion) {
		$result = $this->db->from('Detalle_ev_Proyecto')->where(array('dominio'=>$dominio,'asignacion'=>$asignacion))->get();
		if($result->num_rows() == 1){
			$id_detalle=$result->first_row()->id;
			$this->db->where('id',$id_detalle)->update('Detalle_ev_Proyecto',array('respuesta'=>$respuesta,'justificacion'=>$justificacion));
		}else
			$this->db->insert('Detalle_ev_Proyecto',array('asignacion'=>$asignacion,'respuesta'=>$respuesta,'dominio'=>$dominio,
				'justificacion'=>$justificacion));
		if($this->db->affected_rows() == 1)
			return true;
		return false;
	}

	function guardaComportamiento($asignacion,$respuesta,$comportamiento,$justificacion) {
		$result = $this->db->from('Detalle_ev_Competencia')->where(array('asignacion'=>$asignacion,'comportamiento'=>$comportamiento))->get();
		if($result->num_rows() == 1){
			$id_detalle=$result->first_row()->id;
			$this->db->where('id',$id_detalle)->update('Detalle_ev_Competencia',array('respuesta'=>$respuesta,'justificacion'=>$justificacion));
		}else
			$this->db->insert('Detalle_ev_Competencia',array('respuesta'=>$respuesta,'asignacion'=>$asignacion,'comportamiento'=>$comportamiento,
				'justificacion'=>$justificacion));
		if($this->db->affected_rows() == 1){
			$this->ch_estatus($asignacion);
			return true;
		}
		return false;
	}

	function guardaCompetencia($asignacion,$respuesta,$competencia,$justificacion) {
		$result = $this->db->from('Detalle_ev_360')->where(array('asignacion'=>$asignacion,'competencia'=>$competencia))->get();
		if($result->num_rows() == 1){
			$id_detalle=$result->first_row()->id;
			$this->db->where('id',$id_detalle)->update('Detalle_ev_360',array('respuesta'=>$respuesta,'justificacion'=>$justificacion));
		}else
			$this->db->insert('Detalle_ev_360',array('respuesta'=>$respuesta,'asignacion'=>$asignacion,'competencia'=>$competencia,
				'justificacion'=>$justificacion));
		if($this->db->affected_rows() == 1){
			$this->ch_estatus($asignacion);
			return true;
		}
		return false;
	}

	function ch_estatus($asignacion,$estatus=1) {
		$this->db->where('id',$asignacion)->update('Evaluadores',array('estatus'=>$estatus));
		if($this->db->affected_rows() == 1)
			return true;
		return false;
	}

	function getAreaByColaborador($evaluado) {
		return $this->db->where('id',$evaluado)->select('area')->from('Users')->get()->first_row()->area;
	}

	function getPosicionByColaborador($evaluado) {
		$this->db->select('P.nivel')->from('Users U')->join('Posicion_Track PT','PT.id = U.posicion_track');
		$this->db->join('Posiciones P','P.id = PT.posicion');
		$res = $this->db->where('U.id',$evaluado)->get();
		if($res->num_rows() > 0) 
			($res->first_row()->nivel < 3) ? $res=3 : $res=$res->first_row()->nivel;
		else
			$res = false;
		return $res;
	}

	function searchAsignacionById($id) {
		return $this->db->select('E.comentarios,E.id,Ev.tipo,E.evaluador,E.evaluado,E.estatus,Ev.id evaluacion,Ev.nombre,E.anual')
			->from('Evaluadores E')->join('Evaluaciones Ev','Ev.id=E.evaluacion')->where('E.id',$id)->get()->first_row();
	}

	function isAsignacionEmpty($asignacion) {
		if ($this->searchAsignacionById($asignacion)->tipo == 0) {
			if($this->db->where('asignacion',$asignacion)->get('Detalle_Evaluacion')->num_rows() > 0)
				return false;
		}else
			if($this->db->where('asignacion',$asignacion)->get('Detalle_ev_Competencia')->num_rows() > 0)
				return false;
		return true;
	}

	function genera_autoevaluacion($evaluacion,$colaborador) {
		$posicion=$this->getPosicionByColaborador($colaborador);
		$result = $this->db->from('Evaluadores')->where(array('evaluador'=>$colaborador,'evaluado'=>$colaborador,'evaluacion'=>$evaluacion))->get();
		if($result->num_rows() == 0)
			if($colaborador > 2 && $colaborador != 51)
				if($posicion < 9)
					$this->db->insert('Evaluadores',array('evaluador'=>$colaborador,'evaluado'=>$colaborador,'evaluacion'=>$evaluacion));
	}

	function finalizar_evaluacion($asignacion,$tipo,$comentarios) {
		$info = $this->searchAsignacionById($asignacion);
		$posicion = $this->getPosicionByColaborador($info->evaluado);
		$area = $this->getAreaByColaborador($info->evaluado);
		switch ($tipo) {
			case 1: // evaluador anual - responsabilidades y competencias
				//total responsabilidades
				$total = $this->db->select('SUM(PO.valor/100*M.valor) total')->from('Detalle_Evaluacion DE')
					->join('Objetivos O','O.id = DE.objetivo')->join('Objetivos_Areas OA','OA.objetivo = O.id')
					->join('Porcentajes_Objetivos PO','PO.objetivo_area = OA.id')
					->join('Metricas M','M.id = DE.metrica')
					->where(array('DE.asignacion'=>$asignacion,'PO.nivel_posicion'=>$posicion,'OA.area'=>$area))->get()->first_row()->total;
				if(!$total)
					$total=0;
				$this->db->insert('Resultados_ev_Responsabilidad',array('asignacion'=>$asignacion,'total'=>$total));
				// total de competencias
				$total = $this->db->select('AVG(DC.respuesta) total')->from('Detalle_ev_Competencia DC')
					->join('Comportamientos C','C.id = DC.comportamiento')
					->join('Comportamiento_Posicion CP','CP.comportamiento = C.id')
					->where(array('DC.asignacion'=>$asignacion,'CP.nivel_posicion'=>$posicion))->get()->first_row()->total;
				$this->db->insert('Resultados_ev_Competencia',array('asignacion'=>$asignacion,'total'=>$total));
				break;
			case 0: // por proyecto - responsabilidades
				$total = $this->db->select('AVG(respuesta) total')->where('asignacion',$asignacion)->get('Detalle_ev_Proyecto')
					->first_row()->total;
					if(!$total)
						$total=0;
				$this->db->insert('Resultados_ev_Responsabilidad',array('asignacion'=>$asignacion,'total'=>$total));
				break;
			case 360: // por 360 - competencias
				$total=$this->db->select('AVG(respuesta) total')->where('asignacion',$asignacion)->get('Detalle_ev_360')->first_row()->total;
				$this->db->insert('Resultados_ev_Competencia',array('asignacion'=>$asignacion,'total'=>$total));
				break;
		}
		if($this->getEvaluacionAnualVigente())
			$this->calculaResultado($info);
		$this->db->where('id',$info->id)->update('Evaluadores',array('comentarios'=>$comentarios));
		if($this->ch_estatus($asignacion,2))
			return true;
		return false;
	}

	function autogenera($colaboradores,$evaluacion,$anio) {
		foreach ($colaboradores as $colaborador) :
			if($colaborador->fecha_ingreso <= $anio.'-09-30'):
				$this->genera_autoevaluacion($evaluacion,$colaborador->id);
				if($colaborador->nivel_posicion <= 8 && $colaborador->nivel_posicion > 3):
					$jefe=$this->db->select('jefe')->from('Users')->where('id',$colaborador->id)->get()->first_row()->jefe;
					if($colaborador->id != $jefe):
						$asignacion = $this->addEvaluadorToColaborador(array('evaluador'=>$jefe,
	                    	'evaluado'=>$colaborador->id,'evaluacion'=>$evaluacion));
						$this->db->where('id',$asignacion)->update('Evaluadores',array('anual'=>1));
					endif;
				endif;
			endif;
		endforeach;
	}

	function create($datos) {
		$this->db->insert('Evaluaciones',$datos);
		if($this->db->affected_rows() == 1)
			return $this->db->insert_id();
		else
			return false;
	}

	// Check if there are current Evaluation
	function getEvaluacionAnualVigente() {
		$result = $this->db->select('MAX(id) id, anio, inicio,fin')->where_in('estatus',array(1))
			->where(array('tipo'=>1,'inicio <='=>date('Y-m-d'),'fin >='=>date('Y-m-d')))->get('Evaluaciones');



        $rows = $result->first_row();
        $row = json_decode(json_encode($rows), true);
        //print_r($row);
        //echo empty($row); exit;

        // clean out empty values before checking (generally done to prevent exploding weird strings)
        foreach ($row as $key => $value) {
            if (empty($value)) {
                unset($row[$key]);
            }
        }

        /*if (empty($row)) {
            echo "empty array";
        }*/

        //print_r($row);
        //echo empty($row); exit;


		//if($result->num_rows() != 0)
        if (!empty($row)){
			//return $result->first_row();
            return $row;
        }else{
            return false;
        }

	}
	
	function getEvaluacionAnual() {
		$result = $this->db->select('MAX(id) id')->where_in('estatus',array(1,2))->where(array('tipo'=>1,'inicio <='=>date('Y-m-d')))->get('Evaluaciones');
		if($result->num_rows() != 0)
			return $result->first_row()->id;
		return false;
	}

	function getEvaluacionesProyecto($anio=null) {
		if($anio==null)
			$anio=date('Y')-1;
		$result = $this->db->where_in('estatus',array(1,2))->where(array('tipo'=>0,'anio'=>$anio))
			->get('Evaluaciones');
		if($result->num_rows() != 0):
			return $result->result();
		endif;
		return false;
	}

	function getEvaluadorAnual($colaborador) {
		$evaluacion=$this->getEvaluacionAnual();
		$res = $this->db->select('evaluador')->from('Evaluadores')->where(array('evaluado'=>$colaborador,'anual'=>1))->get();
		if($res->num_rows() == 1)
			return $res->first_row()->evaluador;
		return null;
	}

	function updateEvaluadorAnual($colaborador,$anual) {
		$evaluacion=$this->getEvaluacionAnual();
		$this->db->where(array('evaluado'=>$colaborador,'evaluacion'=>$evaluacion))->update('Evaluadores',array('anual'=>0));
		$this->db->where(array('evaluado'=>$colaborador,'evaluador'=>$anual,'evaluacion'=>$evaluacion))->update('Evaluadores',array('anual'=>1));
	}

	function finaliza_periodo($evaluacion){
		$this->db->where('id',$evaluacion)->update('Evaluaciones',array('estatus'=>2));
	}

	function check_for_evaluations() {
		$new=strtotime('-1 day',strtotime(date('Y-m-d')));
		$fin=date('Y-m-d',$new);
		$result = $this->db->from('Evaluaciones')->where(array('estatus'=>1,'fin<='=>$fin))->get();
		if($result->num_rows() != 0)
			return $result->result();
		return false;
	}

	function setPeriodoEdicion() {
		$fecha=date('Y-m-d');
		$fin=strtotime('+1 month',strtotime($fecha));
		$fin=date('Y-m-d',$fin);
		$this->db->insert('Bitacora',array('accion'=>4,'descripcion'=>$fin,'valor'=>1));
	}

	function check_PeriodoEdicion() {
		$result = $this->db->where(array('descripcion'=>date('Y-m-d'),'accion'=>4,'valor'=>1))->update('Bitacora',array('valor'=>0));
	}

	function getPeriodoEdicion() {
		$result=$this->db->select('MAX(id), valor')->where('accion',4)->get('Bitacora');
		if($result->num_rows() == 1)
			return $result->first_row()->valor;
		return 0;
	}

	function cierra_captura($tipo,$archivo) {
		$this->db->insert('Bitacora',array('accion'=>3,'descripcion'=>$tipo,'valor'=>$archivo));
		switch ($tipo) {
			case 1:
				$cumple="cumple_gastos";
				break;
			case 2:
				$cumple="cumple_harvest";
				break;
			default:
				$cumple="cumple_cv";
				break;
		}
		foreach ($this->getEvaluados() as $colaborador) :
			$evaluacion=$this->getEvaluacionAnual();
			if(!isset($colaborador->$cumple) || $colaborador->$cumple == null){
				$res=$this->db->where(array('evaluacion'=>$evaluacion,'colaborador'=>$colaborador->id))->get('Resultados_Evaluacion');
				if($res->num_rows() == 1)
					$this->db->where('id',$res->first_row()->id)->update('Resultados_Evaluacion',array("$cumple"=>"NO"));
				else
					$this->db->insert('Resultados_Evaluacion',array("$cumple"=>"NO",'colaborador'=>$colaborador->id,'evaluacion'=>$evaluacion));
			}
		endforeach;
	}

	function getActiveEvaluation() {
		$result = $this->db->where(array('fin >='=>date('Y-m-d'),'estatus'=>1))->get('Evaluaciones');
		if($result->num_rows() > 0)
			return $result->first_row();
		return false;
	}

	function getEvaluadoresPendientesByEvaluacion($evaluacion) {
		return $this->db->distinct('U.id')->select('U.id,U.nombre,U.email,Ev.evaluacion')->join('Evaluadores Ev','Ev.evaluador = U.id')
			->where('Ev.evaluacion',$evaluacion)->where_in('Ev.estatus',array(0,1))->get('Users U')->result();
	}
}