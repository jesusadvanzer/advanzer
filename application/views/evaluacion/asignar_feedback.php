<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
	<div class="container">
		<h2>Feedbacks</h2>
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
	<?php if(count($asignaciones) > 0): ?>
		<div class="row">
			<div class="col-md-12">
				<h3><b>Asignaciones:</b></h3>
				<table id="tbl" align="center"class="table table-hover table-condensed" data-hover="true">
					<thead>
						<tr>
							<th data-halign="center" data-align="center" ></th>
							<th data-halign="center">Nombre</th>
							<th data-halign="center">Area</th>
							<th data-halign="center">Posición</th>
							<th data-halign="center">Rating</th>
							<th data-halign="center">Estatus</th>
						</tr>
					</thead>
					<tbody data-link="row">
						<?php foreach ($asignaciones as $colab):?>
							<tr>
								<td><a href="<?= base_url("evaluacion/update_feedback/$colab->feedback");?>">
									<img class="img-circle avatar avatar-original" height="40px" src="<?= base_url('assets/images/fotos')."/".$colab->foto;?>"></a></td>
								<td><small><?= $colab->nombre;?></small></td>
								<td><small><?= $colab->area;?></small></td>
								<td><small><?= $colab->posicion;?></small></td>
								<td><small><?= $colab->rating;?></small></td>
								<td><small><?php if($colab->estatus_f==0)echo"Pendiente";elseif($colab->estatus_f==1)echo"Enviado";else echo"Enterado";?></small></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endif;
	if(count($propias) > 0): ?>
		<div class="row">
			<div class="col-md-12">
				<h3><b>Tu Feedback:</b></h3>
				<table id="tbl" align="center"class="table-hover table-striped table-condensed" data-hover="true">
					<thead>
						<tr>
							<th class="col-md-1" data-halign="center" data-align="center" data-defaultsort="disabled"></th>
							<th class="col-md-5" data-halign="center" data-field="nombre">Nombre</th>
							<th class="col-md-1" data-halign="center" data-field="estatus">Estatus</th>
						</tr>
					</thead>
					<tbody data-link="row">
						<?php foreach ($propias as $colab): ?>
							<tr>
								<td><?php if($colab->estatus_f!=0): ?>
									<a href="<?= base_url("evaluacion/revision_final/$colab->feedback");?>">
									<img class="img-circle avatar avatar-original" height="40px" src="<?= base_url('assets/images/fotos')."/".$colab->foto;?>"></a>
								<?php else: ?>
									<img class="img-circle avatar avatar-original" height="40px" src="<?= base_url('assets/images/fotos')."/".$colab->foto;?>">
								<?php endif; ?></td>
								<td><small><?= $colab->nombre;?></small></td>
								<td><small><?php if($colab->estatus_f==0) echo "Pendiente"; elseif($colab->estatus_f==1)echo"Enviado";else echo"Enterado";?></small></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endif; ?>

  <script>
    $.bootstrapSortable(true);

    $(function() {
      $('[id^=tbl]').DataTable({responsive: true});
    });
  </script>