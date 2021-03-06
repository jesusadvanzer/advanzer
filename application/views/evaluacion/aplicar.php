<style type="text/css">
	#carousel {
		-moz-box-shadow: 0px 0px 20px #000; 
		-webkit-box-shadow: 0px 0px 20px #000; 
		box-shadow: 0px 0px 20px #000;
	}
	.accordion {
	  margin: 40px auto;
	  text-align: center;
	}
	.accordion h1, h2, h3, h4 {
	  cursor: default;
	  -webkit-margin-before: 0em;
	  -webkit-margin-after: 0em;
	}
	.accordion h1 {
	  padding: 15px 20px;
	  background-color: #444;
	  font-size: 1.4rem;
	  font-weight: normal;
	  color: #FFF;
	  text-transform: uppercase;
	  border-radius: 10px 10px 10px 10px;
	}
	.accordion h1:hover {
	  color: #999;
	}
	.accordion h2 {
	  padding: 5px 25px;
	  font-size: 14px;
	  color: #666666;
	  text-transform: uppercase;
	  border-radius: 10px 10px 10px 10px;
	}
	.accordion h2:hover {
	  color: #000;
	}
	.accordion label {
	  width: 100%;
	  padding: 5px 30px;
	  font-size: 1rem;
	  color: #000;
	}
	.accordion h3:hover {
	  background-color: #000;
	  color: #FFF;
	}
	.accordion h4 {
	  padding: 5px 35px;
	  background-color: #ffc25a;
	  color: #af720a; 
	}
	.accordion h4:hover {
	  background-color: #e0b040;
	}
	.accordion p {
	  padding: 0px 35px;
	  background-color: #ddd;
	  font-size: 1rem;
	  color: #333;
	  line-height: 1.3rem;
	}
</style>
<script>
	document.write('\
		<style>\
		.accordion h2 {\
				background: -webkit-linear-gradient(#fff,'+color+'); /* For Safari 5.1 to 6.0 */\
				background: -o-linear-gradient(#fff,'+color+'); /* For Opera 11.1 to 12.0 */\
				background: -moz-linear-gradient(#fff,'+color+'); /* For Firefox 3.6 to 15 */\
				background: linear-gradient(#fff,'+color+'); /* Standard syntax (must be last) */\
			}\
		</style>\
	');
</script>
<div class="jumbotron">
	<div class="container">
		<h2 style="cursor:default;"><?= $evaluacion->nombre;?></h2>
		<p><small>Evaluando a: <i><?= $colaborador->nombre;?></i></small></p>
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
	<div id="carousel" class="carousel slide" data-wrap="false" data-ride="carousel" data-interval="false">
		<div class="carousel-inner" style="background-color:#dedede;" role="listbox">
			<div class="item active" align="center" style="min-height:550px;">
				<img height="100%" style="opacity:0.1;position:absolute" src="<?= base_url('assets/images/evaluacion.jpg');?>">
				<div style="display:block;width:60%;position:absolute;top:20%;z-index:20;left: 50%;width: 60%;margin-left: -30%;text-align: center;">
					<h2><b>Indicaciones</b></h2><hr>
					<h4 align="left"><li>Recuerda evaluar objetivamente tomando en cuenta el desempeño de todo el año, no solo el de los últimos meses</li>
						<li>Si tu respuesta es diferente a "<i>3</i>" es indispensable justificar tu respuesta y darle clic a "<i>guardar</i>".</li>
						<li>Son indispensables al menos 3 palabras para que se active la opción de "<i>guardar</i>"</li>
						<li>Si deseas suspender la evaluación y continuar más tarde, asegúrate de darle "<i>guardar</i>" a todas tus respuestas 
							diferentes a "<i>3</i>" para conservar tu avance.</li>
						<li>Al retomar la evaluación, se resaltarán los reactivos pendientes de respuesta</li>
						<li>Antes de terminar y para poder enviar y cerrar cada evaluación se te solicitarán comentarios generales. Esto es, 
							un resumen breve de lo más relevante del desempeño del evaluado</li>
					</h4>
				</div>
			</div>
			<?php if(isset($evaluacion->dominios)): ?>
				<div class="item" align="center" style="min-height:550px;">
					<img width="100%" style="opacity:0.3;position:absolute" src="<?= base_url('assets/images/responsabilidades.jpg');?>">
					<div style="width:60%;position:absolute;top:15%;z-index:20;left: 50%;width: 60%;margin-left: -30%;text-align: center;">
						<h3>En el recuadro "<i>Respuesta</i>", selecciona la métrica que consideres correcta de acuerdo a su descripción y 
							justifica todas las respuestas diferentes a 3, dando clic a "<i>guardar</i>" para poder continuar y no perder tu avance.</h3>
					</div>
					<div class="carousel-caption"><h3 style="cursor:default;">Responsabilidades</h3></div>
				</div>
				<?php foreach ($evaluacion->dominios as $dominio) : if(count($dominio->responsabilidades) > 0): ?>
					<div class="item" style="min-height:470px;">
						<aside class="accordion" style="max-width:70%;">
							<h1><?= $dominio->nombre;?></h1>
							<?php foreach ($dominio->responsabilidades as $resp) : ?>
								<label>
								<form id="mark" onsubmit="return verify(this);" action="javascript:" class="form-signin" role="form">
									<input type="hidden" value="<?= $resp->id;?>" id="elemento">
									<input type="hidden" value="<?= $evaluacion->id;?>" id="asignacion">
									<input type="hidden" id="tipo" value="responsabilidad">
									<div class="form-group" align="center">
										<h2><?= $resp->nombre;?><span style="min-width:100px;float:right;">
												<i>Respuesta</i>: 
												<select onchange="this.form.boton.style.display='';if(this.options[this.selectedIndex].value == 3){
														this.form.justificacion.value='';
														this.form.justificacion.removeAttribute('required');}
													else{
														this.form.justificacion.setAttribute('required','required');
														if(this.form.justificacion.value.trim().split(' ').length >= 3)
														this.form.boton.removeAttribute('disabled');
													}
													verify(this.form);" class="form-control" 
													id="respuesta" style="height:15px;padding: 0px 10px;font-size:10px;max-width:60px;display:inline">
													<option disabled selected value="">-- Selecciona tu respuesta --</option>
													<?php for ($i=5; $i >= 1; $i--) : ?>
														<option <?php if($resp->respuesta == $i) echo "selected";?>><?= $i;?></option>
													<?php endfor; ?>
												</select>
											</span>
											<label><?= $resp->descripcion;?></label>
										</h2>
										<div align="left">
											<p><?php foreach ($this->evaluacion_model->getMetricaByObjetivo($resp->id) as $metrica) :?>
												<label><?= $metrica->valor.'. '.$metrica->descripcion;?></label>
											<?php endforeach; ?></p>
										</div>
									</div>
									<div class="row" align="center" id="naranja">
										<div class="col-md-8">
											<div class="form-group" align="center">
												<textarea id="justificacion" class="form-control" rows="2" style="max-width:300px;text-align:center;" 
													onkeydown="if(this.value.trim().split(' ').length >= 3){ this.form.boton.removeAttribute('disabled');
														}else{ this.form.boton.setAttribute('disabled','disabled');}" placeholder="Justifique su respuesta"
													required><?= $resp->justificacion;?></textarea>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group" align="center">
												<input id="boton" class="btn btn-lg btn-primary btn-block" style="max-width:200px;
												<?php if(!$resp->respuesta) echo "display:none;"?>
													text-align:center;" type="submit" value="Guardar" disabled>
											</div>
										</div>
									</div>
								</form>
								</label>
							<?php endforeach; ?>
						</aside>
					</div>
				<?php endif; endforeach; 
			endif; ?>
			<div class="item" align="center" style="min-height:550px;">
				<img width="100%" style="opacity:0.3;position:absolute" src="<?= base_url('assets/images/competencias.jpg');?>">
				<div style="width:60%;position:absolute;top:15%;z-index:20;left: 50%;width: 60%;margin-left: -30%;text-align: center;">
					<h2>Cada competencia se compone de varios comportamientos.<br>
						Selecciona la métrica para cada comportamiento de acuerdo a lo siguiente:</h2><br>
					<h4 align="left">
						5. Es un modelo a seguir para toda la organización<br>
						4. Excede las expectativas<br>
						3. Cumple las expectativas al 100%<br>
						2. Cumple parcialmente las expectativas<br>
						1. No cumple las expectativas<br>
					</h4>
					<h4 align="left">Recuerda que debes justificar cada respuesta diferente a "<i>3</i>" y darle clic a "<i>guardar</i>" 
						para no perder tu avance.</h4>
				</div>
				<div class="carousel-caption"><h3 style="cursor:default;">Competencias</h3></div>
			</div>
			<?php foreach ($evaluacion->indicadores as $indicador) : ?>
				<div class="item" style="min-height:470px;">
					<aside class="accordion" style="max-width:70%;">
						<h1><?= $indicador->nombre;?></h1>
						<div class="col-md-12">
						<?php foreach ($indicador->competencias as $comp) : ?>
							<h2><?= $comp->nombre;?>
								<label><?= $comp->descripcion;?></label>
							</h2>
							<div align="left">
								<ul type="square">
								<?php foreach ($comp->comportamientos as $comportamiento) : ?>
									<form id="mark" onsubmit="return verify(this);" action="javascript:" class="form-signin" role="form">
										<input type="hidden" value="<?= $comportamiento->id;?>" id="elemento">
										<input type="hidden" value="<?= $evaluacion->id;?>" id="asignacion">
										<input type="hidden" value="" id="tipo">
										<span><?= $comportamiento->descripcion;?></span>
										<div class="row" id="naranja">
											<div class="col-md-2"><i>Respuesta</i>: 
												<select onchange="this.form.boton.style.display='';if(this.options[this.selectedIndex].value == 3){
														this.form.justificacion.value='';
														this.form.justificacion.removeAttribute('required');}
													else{
														this.form.justificacion.setAttribute('required','required');
														this.form.justificacion.focus();
														if(this.form.justificacion.value.trim().split(' ').length >= 3)
															this.form.boton.removeAttribute('disabled');
													}
													verify(this.form);" class="form-control" id="respuesta" 
													style="max-width:60px;text-align:center">
													<option disabled selected value="">-- Selecciona tu respuesta --</option>
													<?php for ($i=5; $i >= 1; $i--) : ?>
														<option <?php if(isset($comportamiento->respuesta) && $comportamiento->respuesta == $i) echo "selected";?>><?= $i;?></option>
													<?php endfor; ?>
												</select>
											</div>
											<div class="col-md-5">
												<div class="form-group" align="center">
													<textarea id="justificacion" class="form-control" rows="2" style="max-width:300px;text-align:center;" 
														onkeydown="if(this.value.trim().split(' ').length >= 3){ this.form.boton.removeAttribute('disabled');
															}else{ this.form.boton.setAttribute('disabled','disabled');}" placeholder="Justifique su respuesta"
														required><?= $comportamiento->justificacion;?></textarea>
												</div>
											</div>
											<div class="col-md-5">
												<div class="form-group" align="center">
													<input id="boton" class="btn btn-lg btn-primary btn-block" style="max-width:200px;
														<?php if(!$comportamiento->respuesta) echo "display:none;"?>
														text-align:center;" type="submit" value="Guardar" disabled>
												</div>
											</div>
										</div>
									</form>
								<?php endforeach; ?></ul></p>
							</div>
						<?php endforeach; ?>
						</div>
					</aside>
				</div>
			<?php endforeach; ?>
			<div class="item" align="center" style="min-height:480px;">
				<img id="finalizar" width="100%" style="opacity:0.3;position:absolute" src="<?= base_url('assets/images/gracias.jpg');?>">
				<div style="width:60%;position:absolute;top:15%;z-index:20;left: 50%;width: 60%;margin-left: -30%;text-align: center;"
					id="mensaje">
					<div class="col-md-12">
						<div class="form-group" align="center">
							<h2><b>Tienes reactivos pendientes por responder.</b></h2><hr>
							<h3>Revisa detenidamente las Responsabilidades Funcionales y/o Comportamientos para que puedas finalizar esta evaluación</h3>
						</div>
					</div>
				</div>
				<form class="form-signin" action="javascript:" id="finalizar">
					<input id="finalizar_asignacion" type="hidden" value="<?= $evaluacion->id;?>">
					<input id="finalizar_tipo" type="hidden" value="<?= $evaluacion->tipo;?>">
					<div style="width:60%;position:absolute;top:15%;z-index:20;left: 50%;width: 60%;margin-left: -30%;text-align: center;">
						<div class="col-md-12">
							<div class="form-group" align="center">
								<label>Comentarios generales de la evaluación</label>
								<textarea id="comentarios" class="form-control" rows="5" style="text-align:center"required
									><?= $evaluacion->comentarios;?></textarea>
							</div>
						</div>
					</div>
					<div class="carousel-caption" align="center">
						<button id="enviar" type="submit" class="btn btn-lg btn-primary" style="max-width:200px; text-align:center;">
							Enviar Evaluación</button>
						<h3 style="cursor:default;">Gracias por tu tiempo...!</h3>
					</div>
				</form>
			</div>
		</div>
		<!-- Controls -->
		<a class="left carousel-control" href="#carousel" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left"></span></a>
		<a class="right carousel-control" href="#carousel" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right"></span></a>
	</div>
	<div class="row" align="center">
		<div class="col-md-12"><div id="cargando" style="display:none;">
			<img src="<?= base_url('assets/images/loading.gif');?>"></div></div>
	</div>
	<script>
		$(document).ready(function() {
			$('[id^=finalizar]').hide();
			estatus = '<?= $evaluacion->estatus;?>';
			if(estatus==1){
				mark();
				revisar();
			}
			$('[id^=finalizar]').submit(function(event){
				asignacion=$('#finalizar_asignacion').val();
				tipo=$('#finalizar_tipo').val();
				console.log(asignacion,tipo);
				if(confirm('Una vez que haya enviado ésta evaluación no será posible editarla. ¿Seguro(a) que desea enviarla?'))
					$.ajax({
						url: '<?= base_url("evaluacion/finalizar_evaluacion");?>',
						type: 'post',
						data: {'asignacion':asignacion,'tipo':tipo,'comentarios':$('#comentarios').val()},
						beforeSend: function (xhr) {
							$('#carousel').hide('slow');
							$('#cargando').show('slow');
						},
						success: function(data){
							console.log(data);
							var returnData = JSON.parse(data);
							$('#cargando').hide('slow');
							if(returnData['redirecciona'] == "si"){
								alert(returnData['msg'])
								location.href="<?= base_url('evaluar');?>";
							}else{
								alert(returnData['msg']);
								$('#carousel').show('slow');
								mark();
								return false;
							}
						},
						error: function(data){
							console.log(data.responseText);
							$('#carousel').show('slow');
							$('#cargando').hide('slow');
							alert('Error al finalizar la evaluación. Intenta de nuevo');
						}
					});
			});
		});
		var checkitem = function() {
			var $this;
			$this = $("#carousel");
			if ($("#carousel .carousel-inner .item:first").hasClass("active")) {
				$this.children(".left").hide();
				$this.children(".right").show();
			} else if ($("#carousel .carousel-inner .item:last").hasClass("active")) {
				mark();
				$this.children(".right").hide();
				$this.children(".left").show();
			} else {
				$this.children(".carousel-control").show();
			}
		};

		checkitem();

		$("#carousel").on("slid.bs.carousel", "", checkitem);
		
		function mark(formulario) {
			if(formulario == undefined)
				$('[id^=mark]').each(function(i,form) {
					$(form).each(function() {
						//console.log('respuesta: '+$(this[3]).val()+ 'justificación: '+$(this[4]).val());
						if($(this[3]).val() != 3 && $(this[4]).val() == "") {
							if($(this[4]).focus()){
								$(form['children'][4]).css({'background-color':'#fc8111','border-radius':'10px 10px 10px 10px'});
								//console.log($(form['children'][4]));
								$('#mensaje').show();
								$('[id^=finalizar]').hide();
							}
						}else
							$(form['children'][4]).css({'background-color':'','border-radius':''});
					});
				});
			else
				$(formulario).each(function() {
					//console.log('respuesta: '+$(this[3]).val()+ 'justificación: '+$(this[4]).val());
					if($(this[3]).val() != 3 && $(this[4]).val() == "") {
						if($(this[4]).focus()){
							$(formulario['children'][4]).css({'background-color':'#fc8111','border-radius':'10px 10px 10px 10px'});
							//console.log($(form['children'][4]));
							$('#mensaje').show();
							$('[id^=finalizar]').hide();
						}
					}else
						$(formulario['children'][4]).css({'background-color':'','border-radius':''});
				});
		}

		function revisar() {
			flag=true;
			$('[id^=respuesta] option:selected').each(function(i,select) {
				if($(select).val() == ""){
					flag = false;
				}
			});
			if(flag){
				$('#mensaje').hide();
				$('[id^=finalizar]').show();
			}
			else{
				$('#mensaje').show();
				$('[id^=finalizar]').hide();
			}
		}

		function verify(form) {
			var asignacion = form.asignacion.value;
			var elemento = form.elemento.value;
			var tipo = form.tipo.value;
			var justificacion = form.justificacion.value;
			var respuesta = form.respuesta.options[form.respuesta.selectedIndex].value;
			console.log(asignacion,elemento,tipo,respuesta,justificacion);
			form.boton.setAttribute('disabled','disabled');
			$.ajax({
				url: '<?= base_url("evaluacion/guardar_avance");?>',
				type: 'post',
				data: {'asignacion':asignacion,'tipo':tipo,'valor':respuesta,'elemento':elemento,'justificacion':justificacion},
				beforeSend: function (xhr) {
					//$('#carousel').hide('slow');
					$('#cargando').show('slow');
				},
				success: function(data){
					var returnedData = JSON.parse(data);
					revisar();
					console.log(returnedData);
					$('#cargando').hide('slow');
					mark(form);
					//$('#carousel').show('slow');
				},
				error: function(data){
					$('#cargando').hide('slow');
					form.respuesta.selectedIndex=0;
					console.log(data.status,data.responseText);
				}
			});
			return false;
		}
	</script>