<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
	<div class="container">
		<h2>Detalle Feedback</h2>
	</div>
</div>
<div class="container">
	<div align="center" id="alert" style="display:none">
		<div class="alert alert-danger" role="alert" style="max-width:400px;">
			<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			<label id="msg"></label>
		</div>
	</div>
	<div align="center" id="alert_success" style="display:none">
		<div class="alert alert-success" role="alert" style="max-width:400px;">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			<label id="msg_success"></label>
		</div>
	</div>
	<div class="row" align="center">
		<div class="col-md-12">
			<a href="<?= base_url('evaluacion/evaluar');?>">&laquo;Regresar</a>
		</div>
	</div>
	<hr>
	<div class="row" align="center">
		<div class="col-md-12">
			<div id="cargando" style="display:none; color: green;">
				<img src="<?= base_url('assets/images/loading.gif');?>"></div></div>
	</div>
	<div class="row" align="center">
		<h4>
			<div class="col-md-3">Rating: <span><?= $feedback->rating;?></span></div>
			<div class="col-md-3">Gastos de Viaje: <span class="<?php if(isset($feedback->cumple_gastos) && $feedback->cumple_gastos == "SI") echo"glyphicon glyphicon-ok"; else echo"glyphicon glyphicon-remove";?>"></span></div>
			<div class="col-md-3">Asignaciones en Harvest: <span class="<?php if(isset($feedback->cumple_harvest) && $feedback->cumple_harvest == "SI") echo"glyphicon glyphicon-ok"; else echo"glyphicon glyphicon-remove";?>"></span></div>
			<div class="col-md-3">Actualización de CV: <span class="<?php if(isset($feedback->cumple_cv) && $feedback->cumple_cv == "SI") echo"glyphicon glyphicon-ok"; else echo"glyphicon glyphicon-remove";?>"></span></div></h4>
	</div>
	<hr>
	<form id="update" role="form" method="post" action="javascript:" class="form-signin">
		<input type="hidden" id="id" value="<?= $feedback->id;?>">
		<div class="row" align="center">
			<div class="col-md-2"></div>
			<div class="col-md-10">
				<label>Comentarios de la Junta:&nbsp;</label><p style="font-size: 13pt;"><?= $feedback->comentarios;?></p>
			</div><br>
			<div class="col-md-2">
				<img class="img-circle avatar avatar-original" src="<?= base_url("assets/images/fotos/$feedback->foto");?>" height="120px">
				<label><?= $feedback->nombre;?></label>
			</div>
			<div class="col-md-5">
				<div class="form-group">
					<label for="fortalezas">Fortalezas/Logros:</label>
					<?php if($feedback->estatus ==0):?>
						<textarea onkeyup="$('#guardar').show('slow');$('#enviar').hide('slow');" class="form-control" 
							style="text-align:center;" rows="6" id="fortalezas" required><?= $feedback->fortalezas;?></textarea>
					<?php else: ?>
						<p style="text-align:center;"><?= $feedback->fortalezas;?></p>
					<?php endif;?>
				</div>
			</div>
			<div class="col-md-5">
				<div class="form-group">
					<label for="oportunidad">Área(s) de Oportunidad:</label>
					<?php if($feedback->estatus ==0):?>
						<textarea onkeyup="$('#guardar').show('slow');$('#enviar').hide('slow');" class="form-control" 
							style="text-align:center;" rows="6" id="oportunidad" required><?= $feedback->oportunidad;?></textarea>
					<?php else: ?>
						<p style="text-align:center;"><?= $feedback->oportunidad;?></p>
					<?php endif;?>
				</div>
			</div>
		</div>
		<?php if($feedback->estatus !=0):?>
			<div class="row" align="center">
				<div class="col-md-12">
					<div class="form-group">
						<label for="compromisos">Compromisos:</label>
						<p style="text-align:center;"><?= $feedback->compromisos;?></p>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="row" align="center">
			<div class="col-md-12">
				<div class="form-group">
					<label for="boton">&nbsp;</label>
					<?php if($feedback->estatus ==0): ?>
						<button id="guardar" type="submit" class="btn btn-lg btn-primary btn-block" style="max-width:200px;text-align:center;
							display:none">Guardar</button>
						<button id="enviar" type="button" class="btn btn-lg btn-primary btn-block" style="max-width:200px;text-align:center;
							<?php if($feedback->fortalezas == "" || $feedback->oportunidad == "")
							echo 'display:none' ?>" onclick="location.href='<?= base_url("evaluacion/revision_final/$feedback->id");?>';" >Continuar</button>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</form>
	<div class="row">
		<div class="col-md-12">
			<?php if(isset($evaluaciones->evaluadores) && count($evaluaciones->evaluadores) > 0): ?>
				<h3><b>Evaluaciones:</b></h3>
				<table id="tbl" align="center" class="table table-hover table-condensed table-striped">
					<thead>
						<tr>
							<!--<th data-halign="center"></th>
							<th data-halign="center">Evaluador</th>-->
							<th data-halign="center">Evaluación</th>
							<th data-halign="center">Calificación</th>
							<th data-halign="center">Comentarios</th>
						</tr>
					</thead>
					<tbody data-link="row">
						<?php foreach ($evaluaciones->evaluadores as $evaluador):?>
							<tr>
								<!--<td align="center">
									<img class="img-circle avatar avatar-original" height="40px" 
										src="<?= base_url('assets/images/fotos')."/".$evaluador->foto;?>"></td>
								<td><?= $evaluador->nombre;?></td>-->
								<td><small><a target="_blank" href="<?= base_url("evaluacion/detalle_asignacion/$evaluador->asignacion");?>">Anual</a></small></td>
								<td><small><?= number_format(($evaluador->competencia*.3)+($evaluador->responsabilidad*.7),2);?></small></td>
								<td><small><?php if($evaluador->comentarios) echo $evaluador->comentarios;?></small></td>
							</tr>
						<?php endforeach;
						if(isset($evaluaciones->evaluadores360) && count($evaluaciones->evaluadores360) > 0)
							foreach ($evaluaciones->evaluadores360 as $evaluador):?>
								<tr>
									<!--<td align="center">
										<img class="img-circle avatar avatar-original" height="40px" 
											src="<?= base_url('assets/images/fotos')."/".$evaluador->foto;?>"></td>
									<td><?= $evaluador->nombre;?></td>-->
									<td><small><a target="_blank" href="<?= base_url("evaluacion/detalle_asignacion/$evaluador->asignacion");?>">360</a></small></td>
									<td><small><?= number_format($evaluador->competencia,2);?></small></td>
									<td><small><?= $evaluador->comentarios;?></small></td>
								</tr>
							<?php endforeach;
						if(isset($evaluaciones->evaluadoresProyecto) && count($evaluaciones->evaluadoresProyecto) > 0)
							foreach ($evaluaciones->evaluadoresProyecto as $evaluador):?>
								<tr>
									<!--<td align="center">
										<img class="img-circle avatar avatar-original" height="40px" 
											src="<?= base_url('assets/images/fotos')."/".$evaluador->foto;?>"></td>
									<td><?= $evaluador->nombre;?></td>-->
									<td><small><a target="_blank" href="<?= base_url("evaluacion/detalle_asignacion/$evaluador->asignacion/1");?>">
										Proyecto - <?= $evaluador->evaluacion;?></a></small></td>
									<td><small><?= number_format($evaluador->responsabilidad,2);?></small></td>
									<td><small><?php if($evaluador->comentarios) echo $evaluador->comentarios;?></small></td>
								</tr>
							<?php endforeach; ?>
						<tr>
							<!--<td align="center">
								<img class="img-circle avatar avatar-original" height="40px" 
									src="<?= base_url('assets/images/fotos')."/".$evaluaciones->foto;?>">
							</td>
							<td><?= $evaluaciones->nombre;?></td>-->
							<td><small><?php if($evaluaciones->auto):?>
									<a target="_blank" href="<?= base_url("evaluacion/detalle_asignacion/".$evaluaciones->auto->asignacion);?>">
									AUTOEVALUACIÓN</a>
								<?php else: ?>AUTOEVALUACIÓN
								<?php endif;?>
							</small></td>
							<td><small><?php if($evaluaciones->auto) echo number_format($evaluaciones->autoevaluacion,2);?></small></td>
							<td><small><?php if($evaluaciones->auto) echo $evaluaciones->auto->comentarios;?></small></td>
						</tr>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	</div>
	<script>
	$(document).ready(function() {
		$('#update').submit(function(event){
			fortalezas=$('#fortalezas').val();
			oportunidad=$('#oportunidad').val();
			id=$('#id').val();
			$.ajax({
				url: '<?= base_url("evaluacion/updateFeedback");?>',
				type: 'POST',
				data: {'id':id,'fortalezas':fortalezas,'oportunidad':oportunidad},
				beforeSend: function() {
					$('#update').hide('slow');
					$('#cargando').show('slow');
				},
				success: function(data) {
					console.log(data);
					var returnData = JSON.parse(data);
					$('#cargando').hide('slow');
					$('#update').show('slow');
					if(returnData['msg'] == "ok"){
						$('#guardar').hide('slow');
						$('#enviar').show('slow');
						$('#alert_success').prop('display',true).show();
						$('#msg_success').html('Se ha guardado el feedback. Presiona "Continuar" para capturar los compromisos con el colaborador');
						setTimeout(function() {
							$("#alert_success").fadeOut(1500);
						},3000);
					}else{
						$('#alert').prop('display',true).show();
						$('#msg').html(returnData['msg']);
						setTimeout(function() {
							$("#alert").fadeOut(1500);
						},3000);
					}
				},
				error: function(xhr) {
					console.log(xhr.responseText);
					$('#cargando').hide('slow');
					$('#update').show('slow');
					$('#alert').prop('display',true).show('slow');
					$('#msg').html('Error, intenta de nuevo o contacta al Administrador de la Aplicación');
					setTimeout(function() {
						$("#alert").fadeOut(1500);
					},3000);
				}
			});
			event.preventDefault();
		});
	});
	</script>