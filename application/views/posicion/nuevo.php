<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
  <div class="container">
    <h2>Nueva Posición</h2>
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
  <div align="center"><a href="<?= base_url('track');?>">&laquo;Regresar</a></div>
  <form id="create" role="form" method="post" action="javascript:" class="form-signin">
  	<div class="row" align="center">
	  <div class="col-md-2"></div>
	  <div class="col-md-4">
		<div class="form-group">
		  <label for="nombre">Nombre:</label>
		  <input name="nombre" type="text" class="form-control" style="max-width:300px; text-align:center;" 
			id="nombre" required value="" placeholder="Nombre">
		</div>
		<div class="form-group">
		  <label for="nivel">Nivel en Organigrama:</label>
		  <select id="nivel" style="max-width:300px" class="form-control" required>
		  	<option disabled value="">--Selecciona un nivel de escalabilidad --</option>
			<option value="8" selected>Analista</option>
			<option value="7">Consultor</option>
			<option value="6">Consultor Sr</option>
			<option value="5">Gerente</option>
			<option value="4">Gerente Sr</option>
			<option value="3">Director</option>
		  </select>
		</div>
	  </div>
	  <div class="col-md-4">
		<div class="form-group">
		  <label for="track">Track(s):<p style="font-size:smaller;">Utiliza la tecla Ctrl(Windows) / Command (Mac) para 1+</p></label>
		  <select multiple id="track" style="max-width:300px;min-height:100px;max-height:300px" 
		  	class="form-control" required>
		  	<option disabled>--Selecciona al menos un track --</option>
		  	<?php foreach ($tracks as $track) : ?>
		  		<option selected value="<?= $track->id;?>"><?= $track->nombre;?></option>
		  	<?php endforeach; ?>
		  </select>
		</div>
	  </div>
	</div>
	<div style="height:60px" class="row" align="center">
	  <div class="col-md-12">
		  <button type="submit" class="btn btn-lg btn-primary btn-block" style="max-width:200px; text-align:center;">
		  	Registrar Datos</button>
	  </div>
	</div>
  </form>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#create').submit(function(event){
				$('#alert').prop('display',false);
				if($('#track :selected').length > 0){
					var selected = [];
					$('#track :selected').each(function(i,select) {
						selected[i] = $(select).val();
					});
					$('#nivel :selected').each(function() {
						nivel = $('#nivel').val();
					});
					nombre = $('#nombre').val();
					$.ajax({
						url: '<?= base_url("posicion/create");?>',
						type: 'post',
						data: {'nombre':nombre,'tracks':selected,'nivel':nivel},
						success: function(data){
							var returnedData = JSON.parse(data);
							console.log(returnedData['msg']);
							if(returnedData['msg']=="ok")
								window.document.location='<?= base_url("track");?>';
							else{
								$('#alert').prop('display',true).show();
								$('#msg').html(returnedData['msg']);
							}
						}
					});
					event.preventDefault();
				}
				else
					alert('Selecciona al menos un track');
			});
		});
	</script>