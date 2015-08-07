<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
  <div class="container">
    <h2>Asignar Evaluaciones 360 a <?= $evaluador->nombre;?></h2>
  </div>
</div>
<div class="container">
  <div align="center">
  <?php if(isset($msg)): ?>
		<div id="alert" class="alert alert-success" role="alert" style="max-width:400px;">
			<span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			<?= $msg;?>
		</div>
		<script>
			$(document).ready(function() {
				setTimeout(function() {
					window.location="<?= base_url('evaluacion/asignar_colaborador360/'.$evaluador->id);?>"
				},3000);
			});
		</script>
	<?php endif; ?>
	<?php if(isset($err_msg)): ?>
		<div id="alert" class="alert alert-danger" role="alert" style="max-width:400px;">
			<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			<?= $err_msg;?>
		</div>
	<?php endif; ?>
  </div>
  <div class="row" align="center">
  	<div class="col-md-12">
	  <a href="<?= base_url('evaluadores360');?>">&laquo;Regresar</a>
  	</div>
  </div>
  <hr>
  <div class="row" align="center" id>
  	<div class="col-md-5">
  	  <div class="panel panel-primary">
  	  <div class="panel-heading">Colaboradores Asignados</div>
		<select id="quitar" name="quitar" multiple class="form-control" style="overflow-y:auto;overflow-x:auto;min-height:300px;max-height:700px">
		  <?php foreach($asignados as $colaborador) : ?>
			<option value="<?= $colaborador->id;?>"><?= $colaborador->nombre ." - ". $colaborador->posicion;?></option>
		  <?php endforeach; ?>
		</select>
  	  </div>
  	</div>
  	<div class="col-md-2">
	  <div class="form-group">&nbsp;</div>
	  <div class="form-group">
		<button id="btnQuitar" class="form-control" style="max-width:100px;">Quitar&raquo;</button>
	  </div>
	  <div class="form-group">
		<button id="btnAgregar" class="form-control" style="max-width:100px;">&laquo;Agregar</button>
	  </div>
	  <div class="form-group">&nbsp;</div>
  	</div>
  	<div class="col-md-5">
  	  <div class="panel panel-primary">
  	  <div class="panel-heading">Colaboradores Sin Asignar</div>
		<select id="agregar" name="agregar" multiple class="form-control" style="overflow-y:auto;overflow-x:auto;min-height:300px;max-height:700px">
  	  	  <?php foreach($no_asignados as $colaborador) : ?>
            <option value="<?= $colaborador->id;?>"><?= $colaborador->nombre ." - ". $colaborador->posicion;?></option>
          <?php endforeach; ?>
        </select>
  	  </div>
  	</div>
  </div>

	<script type="text/javascript">
		$(document).ready(function() {
			$('#btnAgregar').click(function() {
				if($('#agregar :selected').length > 0){
					var selected = [];
					$('#agregar :selected').each(function(i,select) {
						selected[i] = $(select).val();
					});
					var evaluador = <?= $evaluador->id;?>;
					$.ajax({
						url:'<?= base_url("evaluacion/add_colaboradores");?>',
						data:{'selected':selected,'evaluador':evaluador,'tipo':3},
						type:'POST',
						success:function(data) {
							$('body').html(data);
							alert('Se han agregado!');
							window.location.reload();
						}
					});
				}
			});
			$('#btnQuitar').click(function() {
				if($('#quitar :selected').length > 0){
					var selected = [];
					$('#quitar :selected').each(function(i,select) {
						selected[i] = $(select).val();
					});
					var evaluador = <?= $evaluador->id;?>;
					$.ajax({
						url:'<?= base_url("evaluacion/del_colaboradores");?>',
						data:{'selected':selected,'evaluador':evaluador,'tipo':3},
						type:'POST',
						success:function(data) {
							$('body').html(data);
							alert('Se han eliminado!');
							window.location.reload();
						}
					});
				}
			});
		});
	</script>