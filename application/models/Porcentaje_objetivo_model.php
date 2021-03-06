<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Porcentaje_objetivo_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}

	function getPorcentajes($direccion) {
		if($direccion != null)
			$this->db->where('A.direccion',$direccion);
		$result = $this->db->select('A.nombre,A.id,A.estatus,D.id direccion,D.nombre nombre_direccion')
			->join('Direcciones D','D.id = A.direccion')->where('A.estatus',1)->order_by('D.nombre,A.nombre')
			->get('Areas A')->result();
		foreach ($result as $area) :
			$area->objetivos = $this->db->select('O.id,O.nombre,O.tipo,D.nombre dominio')->from('Objetivos O')
				->join('Objetivos_Areas OA','OA.objetivo = O.id')
				->join('Dominios D','D.id = O.dominio')
				->where(array('O.estatus'=>1,'OA.area'=>$area->id))->order_by('D.nombre,O.tipo,O.nombre')->get()->result();
			foreach ($area->objetivos as $objetivo) :
				$objetivo->analista = $this->db->select('PO.valor')->from('Porcentajes_Objetivos PO')
					->join('Objetivos_Areas OA','OA.id = PO.objetivo_area')
					->where(array('objetivo'=>$objetivo->id,'area'=>$area->id,'PO.nivel_posicion'=>8))->get()->first_row();
				$objetivo->consultor = $this->db->select('PO.valor')->from('Porcentajes_Objetivos PO')
					->join('Objetivos_Areas OA','OA.id = PO.objetivo_area')
					->where(array('objetivo'=>$objetivo->id,'area'=>$area->id,'PO.nivel_posicion'=>7))->get()->first_row();
				$objetivo->sr = $this->db->select('PO.valor')->from('Porcentajes_Objetivos PO')
					->join('Objetivos_Areas OA','OA.id = PO.objetivo_area')
					->where(array('objetivo'=>$objetivo->id,'area'=>$area->id,'PO.nivel_posicion'=>6))->get()->first_row();
				$objetivo->gerente = $this->db->select('PO.valor')->from('Porcentajes_Objetivos PO')
					->join('Objetivos_Areas OA','OA.id = PO.objetivo_area')
					->where(array('objetivo'=>$objetivo->id,'area'=>$area->id,'PO.nivel_posicion'=>5))->get()->first_row();
				$objetivo->experto = $this->db->select('PO.valor')->from('Porcentajes_Objetivos PO')
					->join('Objetivos_Areas OA','OA.id = PO.objetivo_area')
					->where(array('objetivo'=>$objetivo->id,'area'=>$area->id,'PO.nivel_posicion'=>4))->get()->first_row();
				$objetivo->director = $this->db->select('PO.valor')->from('Porcentajes_Objetivos PO')
					->join('Objetivos_Areas OA','OA.id = PO.objetivo_area')
					->where(array('objetivo'=>$objetivo->id,'area'=>$area->id,'PO.nivel_posicion'=>3))->get()->first_row();
			endforeach;
		endforeach;
		return $result;
	}

	function getByObjetivoArea($objetivo,$area) {
		$result = $this->db->where(array('objetivo'=>$objetivo,'area'=>$area))->get('Objetivos_Areas')->first_row();
		$result->analista = $this->db->select('PO.valor')->from('Porcentajes_Objetivos PO')
			->join('Objetivos_Areas OA','OA.id = PO.objetivo_area')
			->where(array('objetivo'=>$objetivo,'area'=>$area,'PO.nivel_posicion'=>8))->get()->first_row();
		$result->consultor = $this->db->select('PO.valor')->from('Porcentajes_Objetivos PO')
			->join('Objetivos_Areas OA','OA.id = PO.objetivo_area')
			->where(array('objetivo'=>$objetivo,'area'=>$area,'PO.nivel_posicion'=>7))->get()->first_row();
		$result->sr = $this->db->select('PO.valor')->from('Porcentajes_Objetivos PO')
			->join('Objetivos_Areas OA','OA.id = PO.objetivo_area')
			->where(array('objetivo'=>$objetivo,'area'=>$area,'PO.nivel_posicion'=>6))->get()->first_row();
		$result->gerente = $this->db->select('PO.valor')->from('Porcentajes_Objetivos PO')
			->join('Objetivos_Areas OA','OA.id = PO.objetivo_area')
			->where(array('objetivo'=>$objetivo,'area'=>$area,'PO.nivel_posicion'=>5))->get()->first_row();
		$result->experto = $this->db->select('PO.valor')->from('Porcentajes_Objetivos PO')
			->join('Objetivos_Areas OA','OA.id = PO.objetivo_area')
			->where(array('objetivo'=>$objetivo,'area'=>$area,'PO.nivel_posicion'=>4))->get()->first_row();
		$result->director = $this->db->select('PO.valor')->from('Porcentajes_Objetivos PO')
			->join('Objetivos_Areas OA','OA.id = PO.objetivo_area')
			->where(array('objetivo'=>$objetivo,'area'=>$area,'PO.nivel_posicion'=>3))->get()->first_row();
		return $result;
	}

	function asigna_peso($datos,$valor) {
		if($this->db->where($datos)->get('Porcentajes_Objetivos')->num_rows() == 1){
			$this->db->where($datos)->update('Porcentajes_Objetivos',array('valor'=>$valor));
		}else{
			$datos['valor']=$valor;
			$this->db->insert('Porcentajes_Objetivos',$datos);
		}
		if($this->db->affected_rows() == 1)
			return true;
		return false;
	}

	function getObjetivoArea($datos) {
		return $this->db->where($datos)->get('Objetivos_Areas')->first_row()->id;
	}
}