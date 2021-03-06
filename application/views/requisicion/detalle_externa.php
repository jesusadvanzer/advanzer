<!-- Main jumbotron for a primary marketing message or call to action -->
<div class="jumbotron">
	<div class="container">
		<h2>Detalle de Requisición de Colocación Externa - Folio #<?= $requisicion->id;?></h2>
	</div>
</div>
<div class="container">
	<div align="center"><a style="cursor:pointer;" onclick="window.history.back();">&laquo;Regresar</a></div>
	<div align="center" id="alert" style="display:none">
		<div class="alert alert-danger" role="alert" style="max-width:400px;">
			<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
			<span class="sr-only">Error:</span>
			<label id="msg"></label>
		</div>
	</div>
	<hr>
	<div class="row" align="center">
		<div class="col-md-12"><div id="cargando" style="display:none; color: green;">
			<img src="<?= base_url('assets/images/loading.gif');?>"></div></div>
	</div>
	<div id="detalle">
		<input id="id" type="hidden" value="<?= $requisicion->id;?>">
		<input id="accion" type="hidden">
		<form id="razon" role="form" method="post" action="javascript:" class="form-signin" style="display:none">
			<div class="row" align="center">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<br>
					<div class="input-group">
						<span class="input-group-addon">Razón</span>
						<textarea class="form-control" required id="razon_txt" rows="4" placeholder="Razón por la que se descarta"></textarea>
					</div>
					<br>
				</div>
			</div>
			<div class="row" align="right">
				<div class="col-md-1"></div>
				<div class="col-md-10">
					<div class="btn-group btn-group-lg" role="group" aria-label="...">
						<button id="confirma" type="submit" class="btn btn-primary" style="min-width:200px;text-align:center;display:inline;">Confirmar</button>
						<button onclick="$('#razon').hide('slow');$('#update').show('slow');" type="reset" class="btn" style="min-width:200px;text-align:center;display:inline;">Regresar</button>
					</div>
				</div>
			</div>
		</form>
		<form id="update" role="form" method="post" action="javascript:" class="form-signin">
			<div class="row" align="center">
				<div class="col-md-1"></div>
				<div class="col-md-10">
					<br>
					<div class="input-group">
						<span class="input-group-addon">Autorizador</span>
						<input readonly value="JULIO VALENTE LUNA ALATORRE" class="form-control" style="max-width:40%;background-color:white;">
					</div>
					<br>
					<div class="input-group">
						<span class="input-group-addon">Fecha de Solicitud</span>
						<input data-provide="datepicker" data-date-format="yyyy-mm-dd" class="form-control" type="text" id="solicitud" 
							style="text-align:center;background-color:white" value="<?= $requisicion->fecha_solicitud;?>" required <?php if($this->session->userdata('area')==4 || !in_array($requisicion->estatus,array(2,4))) echo "disabled";?>>
						<span class="input-group-addon">Fecha Estimada de Ingreso</span>
						<input data-provide="datepicker" data-date-format="yyyy-mm-dd" class="form-control" type="text" id="fecha_estimada" 
							style="text-align:center;background-color:white" value="<?= $requisicion->fecha_estimada;?>" required <?php if($this->session->userdata('area')==4 || !in_array($requisicion->estatus,array(2,4))) echo "disabled";?>>
					</div>
					<br>
					<label>Información del Cliente</label>
					<div class="input-group">
						<span class="input-group-addon">Empresa</span>
						<input type="text" id="empresa" class="form-control" style="text-align:center;background-color:white" value="<?= $requisicion->empresa;?>" <?php if($this->session->userdata('area')==4 || !in_array($requisicion->estatus,array(2,4))) echo "disabled";?>>
						<span class="input-group-addon">Dirección</span>
						<input type="text" id="domicilio_cte" class="form-control" style="text-align:center;background-color:white" value="<?= $requisicion->domicilio_cte;?>" <?php if($this->session->userdata('area')==4 || !in_array($requisicion->estatus,array(2,4))) echo "disabled";?>>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Contacto</span>
						<input type="text" id="contacto" class="form-control" style="text-align:center;background-color:white" value="<?= $requisicion->contacto;?>" <?php if($this->session->userdata('area')==4 || !in_array($requisicion->estatus,array(2,4))) echo "disabled";?>>
						<span class="input-group-addon">Teléfono</span>
						<input type="text" id="telefono_contacto" class="form-control" style="text-align:center;background-color:white" value="<?= $requisicion->telefono_contacto;?>" <?php if($this->session->userdata('area')==4 || !in_array($requisicion->estatus,array(2,4))) echo "disabled";?>>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Celular</span>
						<input type="text" id="celular_contacto" class="form-control" style="text-align:center;background-color:white" value="<?= $requisicion->celular_contacto;?>" <?php if($this->session->userdata('area')==4 || !in_array($requisicion->estatus,array(2,4))) echo "disabled";?>>
						<span class="input-group-addon">Email</span>
						<input type="email" class="form-control" id="email_contacto" style="text-align:center;background-color:white" value="<?= $requisicion->email_contacto;?>" <?php if($this->session->userdata('area')==4 || !in_array($requisicion->estatus,array(2,4))) echo "disabled";?>>
					</div><br>
					<label>Datos de la Posición</label>
					<div class="input-group">
						<span class="input-group-addon">Posición Solicitada</span>
						<input class="form-control" id="posicion" style="text-align:center;background-color:white" value="<?= $requisicion->posicion;?>" <?php if($this->session->userdata('area')==4 || !in_array($requisicion->estatus,array(2,4))) echo "disabled";?>>
						<span class="input-group-addon">Nivel de Expertise</span>
						<select id="expertise" class="form-control" style="background-color:white;" <?php if($this->session->userdata('area')==4 || !in_array($requisicion->estatus,array(2,4))) echo "disabled";?>>
							<option <?php if($requisicion->expertise=="JUNIOR") echo"selected";?>>JUNIOR</option>
							<option <?php if($requisicion->expertise=="SENIOR") echo"selected";?>>SENIOR</option>
							<option <?php if($requisicion->expertise=="GERENTE") echo"selected";?>>GERENTE</option>
						</select>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Duración de la Asignación</span>
						<select id="contratacion" class="form-control" style="background-color:white;" <?php if($this->session->userdata('area')==4 || !in_array($requisicion->estatus,array(2,4))) echo "disabled";?>>
							<option <?php if($requisicion->contratacion == 'INDETERMINADO') echo "selected";?>>INDETERMINADO</option>
							<option <?php if($requisicion->contratacion == '3 MESES') echo "selected";?>>3 MESES</option>
							<option <?php if($requisicion->contratacion == '6 MESES') echo "selected";?>>6 MESES</option>
							<option <?php if($requisicion->contratacion == '9 MESES') echo "selected";?>>9 MESES</option>
							<option <?php if($requisicion->contratacion == '12 MESES') echo "selected";?>>12 MESES</option>
							<option <?php if($requisicion->contratacion == 'DURACIÓN DEL PROYECTO') echo "selected";?>>DURACIÓN DEL PROYECTO</option>
						</select>
						<span class="input-group-addon">Tarifa Mensual del Cliente</span>
						<input class="form-control" value="<?= $requisicion->costo_cliente;?>" id="costo_maximo_cliente" style="background-color:white;" <?php if($this->session->userdata('area')==4 || !in_array($requisicion->estatus,array(2,4))) echo "disabled";?>>
						<span class="input-group-addon">más IVA</span>
					</div><br>
					<?php if($this->session->userdata('id')==2 || !in_array($requisicion->estatus,array(1,4))): ?>
						<h2>Propuesta Advanzer/Entuizer</h2>
						<div class="input-group">
							<span class="input-group-addon">Tarifa Mensual Advanzer/Entuizer</span>
							<input class="form-control" value="<?php if($requisicion->costo) echo$requisicion->costo;?>" id="costo_maximo" pattern="[0-9]+" placeholder="Número entero" title="Introduce un número entero" required <?php if($this->session->userdata('id')!=2)echo"readonly";?>>
							<span class="input-group-addon">más IVA</span>
						</div><br>
					<?php endif;
					if(in_array($requisicion->estatus,array(2,3)) && ($requisicion->solicita == $this->session->userdata('id') || $this->session->userdata('area')==4) && !in_array($requisicion->estatus,array(0,1))): ?>
						<h2>Detalle de la Requisición</h2>
						<div class="input-group">
							<span class="input-group-addon">Residencia</span>
							<select id="residencia" class="form-control" <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>>
								<option <?php if($requisicion->residencia=='MTY') echo "selected";?>>MTY</option>
								<option <?php if($requisicion->residencia=='CDMX') echo "selected";?>>CDMX</option>
								<option <?php if($requisicion->residencia=='INDISTINTO') echo "selected";?>>INDISTINTO</option>
								<option value="" <?php if(!in_array($requisicion->residencia,array('INDISTINTO','MTY','CDMX'))) echo "selected";?>>OTRO...</option>
							</select>
							<span class="input-group-addon">Especifique</span>
							<input class="form-control" style="background-color:white" disabled value="<?php if($requisicion->residencia!="MTY" && $requisicion->residencia!="CDMX" && $requisicion->residencia!="INDISTINTO") echo $requisicion->residencia;?>" id="residencia_otro"placeholder="Especfique la ciudad">
							<span class="input-group-addon">Lugar de Trabajo</span>
							<input class="form-control" style="background-color:white" required value="<?= $requisicion->lugar_trabajo;?>" id="lugar_trabajo" <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>>
						</div>
						<br>
						<div class="input-group">
							<span class="input-group-addon">Evaluador Técnico</span>
							<input type="text" class="form-control" id="entrevista" required value="<?= $requisicion->entrevista;?>" <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>>
							<span class="input-group-addon">Disponibilidad p/Viajar</span>
							<select id="disp_viajar" class="form-control" <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>>
								<option <?php if($requisicion->disp_viajar == 'INDISTINTO') echo "selected";?>>INDISTINTO</option>
								<option <?php if($requisicion->disp_viajar == 'SI') echo "selected";?>>SI</option>
								<option <?php if($requisicion->disp_viajar == 'NO') echo "selected";?>>NO</option>
							</select>
						</div>
						<br>
						<div class="input-group">
							<span class="input-group-addon">Edad de</span>
							<select id="edad_uno" class="form-control" onchange="$('#edad_dos').val($(this).val());" <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>>
								<?php for($i=20;$i<=50;$i++): ?>
									<option <?php if($requisicion->edad_uno==$i) echo"selected";?>><?= $i;?></option>
								<?php endfor;?>
							</select>
							<span class="input-group-addon">a</span>
							<select id="edad_dos" class="form-control" <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>>
								<?php for($i=20;$i<=50;$i++): ?>
									<option <?php if($requisicion->edad_dos==$i) echo"selected";?>><?= $i;?></option>
								<?php endfor;?>
							</select>
							<span class="input-group-addon">Sexo</span>
							<select id="sexo" class="form-control" <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>>
								<option <?php if($requisicion->sexo == 'INDISTINTO') echo "selected";?>>INDISTINTO</option>
								<option <?php if($requisicion->sexo == 'HOMBRE') echo "selected";?>>HOMBRE</option>
								<option <?php if($requisicion->sexo == 'MUJER') echo "selected";?>>MUJER</option>
							</select>
							<span class="input-group-addon">Nivel de estudios</span>
							<select id="nivel" class="form-control" <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>>
								<option <?php if($requisicion->nivel == 'PRACTICANTE') echo "selected";?>>PRACTICANTE</option>
								<option <?php if($requisicion->nivel == 'PASANTE') echo "selected";?>>PASANTE</option>
								<option <?php if($requisicion->nivel == 'TÉCNICO') echo "selected";?>>TÉCNICO</option>
								<option <?php if($requisicion->nivel == 'LICENCIATURA/INGENIERÍA') echo "selected";?>>LICENCIATURA/INGENIERÍA</option>
								<option <?php if($requisicion->nivel == 'MAESTRÍA') echo "selected";?>>MAESTRÍA</option>
								<option <?php if($requisicion->nivel == 'DOCTORADO') echo "selected";?>>DOCTORADO</option>
							</select>
						</div>
						<br>
						<div class="input-group">
							<span class="input-group-addon">Carrera</span>
							<input class="form-control" required value="<?= $requisicion->carrera;?>" id="carrera" <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>>
							<span class="input-group-addon">Inglés Oral</span>
							<select id="ingles_hablado" class="form-control" required <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>>
								<option <?php if($requisicion->ingles_hablado=="Excelente") echo"selected";?>>Excelente</option>
								<option <?php if($requisicion->ingles_hablado=="Bueno") echo"selected";?>>Bueno</option>
								<option <?php if($requisicion->ingles_hablado=="Básico") echo"selected";?>>Básico</option>
							</select>
							<span class="input-group-addon">Inglés Lectura</span>
							<select id="ingles_lectura" class="form-control" required <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>>
								<option <?php if($requisicion->ingles_lectura=="Excelente") echo"selected";?>>Excelente</option>
								<option <?php if($requisicion->ingles_lectura=="Bueno") echo"selected";?>>Bueno</option>
								<option <?php if($requisicion->ingles_lectura=="Básico") echo"selected";?>>Básico</option>
							</select>
							<span class="input-group-addon">Inglés Escritura</span>
							<select id="ingles_escritura" class="form-control" required <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>>
								<option <?php if($requisicion->ingles_escritura=="Excelente") echo"selected";?>>Excelente</option>
								<option <?php if($requisicion->ingles_escritura=="Bueno") echo"selected";?>>Bueno</option>
								<option <?php if($requisicion->ingles_escritura=="Básico") echo"selected";?>>Básico</option>
							</select>
						</div>
						<br>
						<div class="input-group">
							<span class="input-group-addon" style="min-width:260px">Experiencia / Conocimientos en</span>
							<textarea class="form-control" required id="experiencia" rows="4" <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>><?= $requisicion->experiencia;?></textarea>
						</div>
						<br>
						<div class="input-group">
							<span class="input-group-addon" style="min-width:260px">Características / Habilidades Deseadas</span>
							<textarea class="form-control" required id="habilidades" rows="4" <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>><?= $requisicion->habilidades;?></textarea>
						</div>
						<br>
						<div class="input-group">
							<span class="input-group-addon" style="min-width:260px">Funciones a Desempeñar</span>
							<textarea class="form-control" required id="funciones" rows="4" <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>><?= $requisicion->funciones;?></textarea>
						</div>
						<br>
						<div class="input-group">
							<span class="input-group-addon" style="min-width:260px">Observaciones</span>
							<textarea class="form-control" id="observaciones" rows="4" <?php if($this->session->userdata('area')==4 || $requisicion->estatus != 2) echo "disabled";?>><?= $requisicion->observaciones;?></textarea>
						</div>
						<br>
						<?php if($requisicion->estatus == 0): ?>
							<div class="input-group">
								<span class="input-group-addon" style="min-width:260px">Motivos de Rechazo/Cancelación</span>
								<textarea class="form-control" disabled rows="4"><?= $requisicion->razon;?></textarea>
							</div>
						<?php endif; ?>
						<?php if(in_array($requisicion->estatus,array(4,5,6,7))): ?>
							<div class="input-group">
								<span class="input-group-addon" style="min-width:260px">Comentarios</span>
								<textarea class="form-control" disabled rows="4"><?= $requisicion->razon;?></textarea>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div><hr>
			<div class="row" align="right">
				<div class="col-md-1"></div>
				<?php if(!in_array($requisicion->estatus,array(0,5,6))): ?>
					<div class="col-md-10">
						<div class="btn-group btn-group-lg" role="group" aria-label="...">
							<?php if($this->session->userdata('tipo') >= 3 && $this->session->userdata('area')==4): ?>
								<button id="exportar" type="button" class="btn btn-primary" style="min-width:180px;text-align:center;display:inline;">Exportar XLS</button>
								<?php if($requisicion->estatus == 3 || $requisicion->estatus==7): ?>
									<button id="realizada" type="button" class="btn btn-primary" style="min-width:180px;text-align:center;display:inline;">Completada</button>
									<button id="sin_completar" type="button" class="btn" style="min-width:180px;text-align:center;display:inline;">Cerrar sin completar</button>
								<?php endif;
							endif;
							if($requisicion->autorizador == $this->session->userdata('id') && $requisicion->autorizador != $requisicion->solicita): ?>
								<button id="autorizar" type="button" class="btn btn-primary" style="min-width:180px;text-align:center;display:inline;">Autorizar</button>
							<?php elseif($requisicion->director == $this->session->userdata('id') && $requisicion->director != $requisicion->solicita): ?>
								<button id="aceptar" type="button" class="btn btn-primary" style="min-width:180px;text-align:center;display:inline;">Aceptar</button>
							<?php elseif($requisicion->estatus==3): ?>
								<button id="stand_by" type="button" class="btn btn-primary" style="min-width:180px;text-align:center;display:inline;">Stand By</button>
							<?php elseif($requisicion->estatus==7): ?>
								<button id="reactivar" type="button" class="btn btn-primary" style="min-width:180px;text-align:center;display:inline;">Reactivar</button>
							<?php endif;
							if($requisicion->solicita == $this->session->userdata('id')):
								if(in_array($requisicion->estatus,array(2,4))): ?>
									<button type="submit" class="btn btn-primary" style="min-width:180px;text-align:center;display:inline;">Actualizar</button>
								<?php endif; ?>
							<?php elseif(in_array($requisicion->estatus,array(1,2)) && in_array($this->session->userdata('id'), array($requisicion->director,$requisicion->autorizador))): ?>
								<button id="rechazar" type="button" class="btn" style="min-width:180px;text-align:center;display:inline;">Rechazar</button>
							<?php endif; ?>
							<button id="cancelar" type="button" class="btn" style="min-width:180px;text-align:center;display:inline;">Cancelar</button>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</form>
	</div>
	<script>
		$(document).ready(function() {
			check_edit_mode();
			$('#solicitud').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('#fecha_estimada').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$("#residencia").change(function() {
				$("#residencia option:selected").each(function() {
					residencia = $('#residencia').val();
				});
				if(residencia!="")
					$('#residencia_otro').prop({'required':false,'disabled':true}).val('');
				else
					$('#residencia_otro').prop({'required':true,'disabled':false}).val('');
			});
			$("#update").submit(function(event){
				if(!confirm('¿Seguro que desea reenviar la requisición?'))
					return false;
				//get form values
					data={};
					data['id']=$('#id').val();
					data['costo'] = $('#costo_maximo').val();
					data['costo_maximo_cliente']=$('#costo_maximo_cliente').val();
					data['solicitud'] = $('#solicitud').val();
					data['fecha_estimada'] = $('#fecha_estimada').val();
					data['empresa'] = $('#empresa').val();
					data['domicilio_cte'] = $('#domicilio_cte').val();
					data['contacto'] = $('#contacto').val();
					data['telefono_contacto'] = $('#telefono_contacto').val();
					data['celular_contacto'] = $('#celular_contacto').val();
					data['email_contacto'] = $('#email_contacto').val();
					data['posicion'] = $('#posicion').val();
					$("#residencia option:selected").each(function() {
						data['residencia'] = $('#residencia').val();
					});
					if(data['residencia']=="")
						data['residencia']=$('#residencia_otro').val();
					data['lugar_trabajo'] = $('#lugar_trabajo').val();
					data['entrevista'] = $('#entrevista').val();
					$("#disp_viajar option:selected").each(function() {
						data['disp_viajar'] = $('#disp_viajar').val();
					});
					$("#edad_uno option:selected").each(function() {
						data['edad_uno'] = $('#edad_uno').val();
					});
					$("#edad_dos option:selected").each(function() {
						data['edad_dos'] = $('#edad_dos').val();
					});
					$("#sexo option:selected").each(function() {
						data['sexo'] = $('#sexo').val();
					});
					$("#nivel option:selected").each(function() {
						data['nivel'] = $('#nivel').val();
					});
					data['carrera'] = $('#carrera').val();
					$("#ingles_hablado option:selected").each(function() {
						data['ingles_hablado'] = $('#ingles_hablado').val();
					});
					$("#ingles_lectura option:selected").each(function() {
						data['ingles_lectura'] = $('#ingles_lectura').val();
					});
					$("#ingles_escritura option:selected").each(function() {
						data['ingles_escritura'] = $('#ingles_escritura').val();
					});
					data['experiencia'] = $('#experiencia').val();
					data['habilidades'] = $('#habilidades').val();
					data['funciones'] = $('#funciones').val();
					data['observaciones'] = $('#observaciones').val();
					$("#expertise option:selected").each(function() {
						data['expertise'] = $('#expertise').val();
					});
					$("#contratacion option:selected").each(function() {
						data['contratacion'] = $('#contratacion').val();
					});
				$.ajax({
					url: '<?= base_url("requisicion/update");?>',
					type: 'post',
					data: data,
					beforeSend: function() {
						$('#update').hide('slow');
						$('#cargando').show('slow');
					},
					success: function(data){
						var returnedData = JSON.parse(data);
						console.log(returnedData['msg']);
						if(returnedData['msg']=="ok"){
							alert('Se han enviado los datos de la requisición');
							window.document.location='<?= base_url("requisicion");?>';
						}else{
							$('#cargando').hide('slow');
							$('#update').show('slow');
							$('#alert').prop('display',true).show('slow');
							$('#msg').html(returnedData['msg']);
							setTimeout(function() {
								$("#alert").fadeOut(1500);
							},3000);
						}
					},
					error: function(xhr) {
						console.log(xhr.statusText);
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

			$("#autorizar").click(function() {
				id = $('#id').val();
				costo = $('#costo_maximo').val();
				if(parseInt(costo)<0 || costo==''){
					alert('Ingresa la Tarifa Mensual');
					$('#costo_maximo').focus();
					return false;
				}
				if(!confirm('¿Seguro que desea autorizar la requisición?'))
					return false;
				$.ajax({
					url: '<?= base_url("requisicion/set_costo");?>',
					type: 'post',
					data: {'id':id,'estatus':2,'costo':costo},
					beforeSend: function() {
						$('#update').hide('slow');
						$('#cargando').show('slow');
					},
					success: function(data){
						var returnedData = JSON.parse(data);
						console.log(returnedData['msg']);
						if(returnedData['msg']=="ok"){
							alert('Se ha autorizado la requisición');
							window.document.location='<?= base_url("requisicion");?>';
						}else{
							$('#cargando').hide('slow');
							$('#update').show('slow');
							$('#alert').prop('display',true).show('slow');
							$('#msg').html(returnedData['msg']);
							setTimeout(function() {
								$("#alert").fadeOut(1500);
							},3000);
						}
					},
					error: function(xhr) {
						console.log(xhr.statusText);
						$('#cargando').hide('slow');
						$('#update').show('slow');
						$('#alert').prop('display',true).show('slow');
						$('#msg').html('Error, intenta de nuevo o contacta al Administrador de la Aplicación');
						setTimeout(function() {
							$("#alert").fadeOut(1500);
						},3000);
					}
				});
			});

			$("#aceptar").click(function() {
				if(!confirm('¿Aceptar y Enviar al autorizador final?'))
					return false;
				id = $('#id').val();
				$.ajax({
					url: '<?= base_url("requisicion/ch_estatus");?>',
					type: 'post',
					data: {'id':id,'estatus':2},
					beforeSend: function() {
						$('#update').hide('slow');
						$('#cargando').show('slow');
					},
					success: function(data){
						var returnedData = JSON.parse(data);
						console.log(returnedData['msg']);
						if(returnedData['msg']=="ok"){
							alert('Se ha aceptado la requisición y se ha notificado al autorizador');
							window.document.location='<?= base_url("requisicion");?>';
						}else{
							$('#cargando').hide('slow');
							$('#update').show('slow');
							$('#alert').prop('display',true).show('slow');
							$('#msg').html(returnedData['msg']);
							setTimeout(function() {
								$("#alert").fadeOut(1500);
							},3000);
						}
					},
					error: function(xhr) {
						console.log(xhr.statusText);
						$('#cargando').hide('slow');
						$('#update').show('slow');
						$('#alert').prop('display',true).show('slow');
						$('#msg').html('Error, intenta de nuevo o contacta al Administrador de la Aplicación');
						setTimeout(function() {
							$("#alert").fadeOut(1500);
						},3000);
					}
				});
			});

			$("#rechazar").click(function() {
				$('#update').hide('slow');
				$('#razon').show('slow');
				$('#accion').val("rechazar");
			});
			$("#cancelar").click(function() {
				$('#update').hide('slow');
				$('#razon').show('slow');
				$('#accion').val("cancelar");
			});
			$("#sin_completar").click(function() {
				$('#update').hide('slow');
				$('#razon').show('slow');
				$('#accion').val("sin_completar");
			});

			$("#razon").submit(function(event) {
				razon = $("#razon_txt").val();
				accion = $("#accion").val();
				id = $("#id").val();
				autorizador=<?= $requisicion->autorizador;?>;
				if(accion == "rechazar"){
					if(!confirm("¿Seguro que desea enviar los comentarios de la requisición?"))
						return false;
					estatus=4;
					alerta="Se ha enviado notificación al solicitante";
					$.ajax({
						url: '<?= base_url("requisicion/rechazar");?>',
						type: 'post',
						data: {'id':id,'estatus':estatus,'razon':razon},
						beforeSend: function() {
							$('#razon').hide('slow');
							$('#cargando').show('slow');
						},
						success: function(data){
							var returnedData = JSON.parse(data);
							if(returnedData['msg']=="ok"){
								alert(alerta);
								window.document.location='<?= base_url("requisicion");?>';
							}else{
								$('#razon_txt').val("");
								$('#cargando').hide('slow');
								$('#update').show('slow');
								$('#alert').prop('display',true).show('slow');
								$('#msg').html(returnedData['msg']);
								setTimeout(function() {
									$("#alert").fadeOut(1500);
								},3000);
							}
						},
						error: function(xhr) {
							console.log(xhr.statusText);
							$('#cargando').hide('slow');
							$('#razon').show('slow');
							$('#alert').prop('display',true).show('slow');
							$('#msg').html('Error, intenta de nuevo o contacta al Administrador de la Aplicación');
							setTimeout(function() {
								$("#alert").fadeOut(1500);
							},3000);
						}
					});
				}else if(accion == "cancelar"){
					if(!confirm("¿Seguro que desea cancelar la requisición?"))
						return false;
					$.ajax({
						url: '<?= base_url("requisicion/rechazar");?>',
						type: 'post',
						data: {'id':id,'estatus':0,'razon':razon},
						beforeSend: function() {
							$('#razon').hide('slow');
							$('#cargando').show('slow');
						},
						success: function(data){
							var returnedData = JSON.parse(data);
							if(returnedData['msg']=="ok"){
								alert("Se ha cancelado la requisición");
								window.document.location='<?= base_url("requisicion");?>';
							}else{
								$('#razon_txt').val("");
								$('#cargando').hide('slow');
								$('#update').show('slow');
								$('#alert').prop('display',true).show('slow');
								$('#msg').html(returnedData['msg']);
								setTimeout(function() {
									$("#alert").fadeOut(1500);
								},3000);
							}
						},
						error: function(xhr) {
							console.log(xhr.statusText);
							$('#cargando').hide('slow');
							$('#razon').show('slow');
							$('#alert').prop('display',true).show('slow');
							$('#msg').html('Error, intenta de nuevo o contacta al Administrador de la Aplicación');
							setTimeout(function() {
								$("#alert").fadeOut(1500);
							},3000);
						}
					});
				}else if(accion == "sin_completar"){
					if(!confirm("¿Seguro que desea cerrar la requisición?"))
						return false;
					$.ajax({
						url: '<?= base_url("requisicion/cerrar");?>',
						type: 'post',
						data: {'id':id,'estatus':6,'razon':razon},
						beforeSend: function() {
							$('#razon').hide('slow');
							$('#cargando').show('slow');
						},
						success: function(data){
							var returnedData = JSON.parse(data);
							if(returnedData['msg']=="ok"){
								alert("Se ha cerrado la requisición");
								window.document.location='<?= base_url("requisicion");?>';
							}else{
								$('#razon_txt').val("");
								$('#cargando').hide('slow');
								$('#update').show('slow');
								$('#alert').prop('display',true).show('slow');
								$('#msg').html(returnedData['msg']);
								setTimeout(function() {
									$("#alert").fadeOut(1500);
								},3000);
							}
						},
						error: function(xhr) {
							console.log(xhr.statusText);
							$('#cargando').hide('slow');
							$('#razon').show('slow');
							$('#alert').prop('display',true).show('slow');
							$('#msg').html('Error, intenta de nuevo o contacta al Administrador de la Aplicación');
							setTimeout(function() {
								$("#alert").fadeOut(1500);
							},3000);
						}
					});
				}

				event.preventDefault();
			});

			$('#exportar').click(function() {
				id = $("#id").val();
				location.href='<?= base_url("requisicion/exportar");?>/'+id;
			});

			$("#realizada").click(function() {
				if(!confirm('¿Seguro(a) que desea cerrar la requisición?'))
					return false;
				id = $("#id").val();

				$.ajax({
					url: '<?= base_url("requisicion/cerrar");?>',
					type: 'post',
					data: {'id':id,'estatus':6},
					beforeSend: function() {
						$('#update').hide('slow');
						$('#cargando').show('slow');
					},
					success: function(data){
						var returnedData = JSON.parse(data);
						console.log(returnedData);
						if(returnedData['msg']=="ok"){
							alert("Se ha cerrado la requisición");
							window.document.location='<?= base_url("requisicion");?>';
						}else{
							$('#cargando').hide('slow');
							$('#update').show('slow');
							$('#alert').prop('display',true).show('slow');
							$('#msg').html(returnedData['msg']);
							setTimeout(function() {
								$("#alert").fadeOut(1500);
							},3000);
						}
					},
					error: function(xhr) {
						console.log(xhr.statusText);
						$('#cargando').hide('slow');
						$('#update').show('slow');
						$('#alert').prop('display',true).show('slow');
						$('#msg').html('Error, intenta de nuevo o contacta al Administrador de la Aplicación');
						setTimeout(function() {
							$("#alert").fadeOut(1500);
						},3000);
					}
				});
			});

			$("#stand_by").click(function() {
				if(!confirm('¿Seguro(a) que desea turnar la requisición a Stand By?'))
					return false;
				id = $("#id").val();
				$.ajax({
					url: '<?= base_url("requisicion/ch_estatus");?>',
					type: 'post',
					data: {'id':id,'estatus':7},
					beforeSend: function() {
						$('#update').hide('slow');
						$('#cargando').show('slow');
					},
					success: function(data){
						var returnedData = JSON.parse(data);
						console.log(returnedData);
						if(returnedData['msg']=="ok"){
							alert("Se ha turnado la requisición a Stand By");
							window.document.location='<?= base_url("requisicion");?>';
						}else{
							$('#cargando').hide('slow');
							$('#update').show('slow');
							$('#alert').prop('display',true).show('slow');
							$('#msg').html(returnedData['msg']);
							setTimeout(function() {
								$("#alert").fadeOut(1500);
							},3000);
						}
					},
					error: function(xhr) {
						console.log(xhr.statusText);
						$('#cargando').hide('slow');
						$('#update').show('slow');
						$('#alert').prop('display',true).show('slow');
						$('#msg').html('Error, intenta de nuevo o contacta al Administrador de la Aplicación');
						setTimeout(function() {
							$("#alert").fadeOut(1500);
						},3000);
					}
				});
			});
			
			$("#reactivar").click(function() {
				if(!confirm('¿Seguro(a) que desea reactivar la requisición?'))
					return false;
				id = $("#id").val();
				$.ajax({
					url: '<?= base_url("requisicion/react");?>',
					type: 'post',
					data: {'id':id,'estatus':3},
					beforeSend: function() {
						$('#update').hide('slow');
						$('#cargando').show('slow');
					},
					success: function(data){
						var returnedData = JSON.parse(data);
						console.log(returnedData);
						if(returnedData['msg']=="ok"){
							alert("Se ha reactivado la requisición");
							window.document.location='<?= base_url("requisicion");?>';
						}else{
							$('#cargando').hide('slow');
							$('#update').show('slow');
							$('#alert').prop('display',true).show('slow');
							$('#msg').html(returnedData['msg']);
							setTimeout(function() {
								$("#alert").fadeOut(1500);
							},3000);
						}
					},
					error: function(xhr) {
						console.log(xhr.statusText);
						$('#cargando').hide('slow');
						$('#update').show('slow');
						$('#alert').prop('display',true).show('slow');
						$('#msg').html('Error, intenta de nuevo o contacta al Administrador de la Aplicación');
						setTimeout(function() {
							$("#alert").fadeOut(1500);
						},3000);
					}
				});
			});
			
			function check_edit_mode(){
				usuario=<?= $this->session->userdata('id');?>;
				solicita=<?= $requisicion->solicita;?>;
				estatus=<?= $requisicion->estatus;?>;
				if(estatus != 4 && usuario != solicita){
					$("#update :input:not(button)").attr("disabled", true).css({'background-color':'white','cursor':'default'});
					$("#update button").attr("disabled", false).css('cursor','pointer');
				}
				if(usuario=='2')
					$('#costo_maximo').attr("disabled", false).css({'background-color':'white','cursor':'text'});
			}
		});
	</script>