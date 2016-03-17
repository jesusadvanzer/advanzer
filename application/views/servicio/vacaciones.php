<?php
	$ingreso=new DateTime($yo->fecha_ingreso);
	$hoy=new DateTime(date('Y-m-d'));
	$date_dif = $ingreso->diff($hoy);
?>
<div class="jumbotron">
	<div class="container">
		<h2 align="left"><b>Vacaciones</b></h2>
	</div>
</div>
<div class="container">
	<div class="row" align="center">
		<div class="col-md-12"><div id="cargando" style="display:none; color: green;">
			<img src="<?= base_url('assets/images/loading.gif');?>"></div></div>
	</div>
	<div class="row">
		<div align="center"><button class="btn btn-primary" onclick="location.href='<?= base_url("solicitar_vacaciones");?>';"><big>Solicitud de Vacaciones</big></button></div>
		<hr>
	</div>
	<div class="row">
		<div class="col-md-12">
			Las vacaciones que generas como empleado de Advanzer/Entuizer son de acuerdo al artículo 76 de la Ley Federal del Trabajo (en rojo los días que te corresponden de acuerdo a tu antigüedad):
			<p><table width="50%" align="center" class="tbl table-condensed table-bordered">
				<tbody>
					<tr>
						<th style="cursor:default">Años</th>
						<td style="cursor:default" <?php if($date_dif->y+1 == 1) echo 'bgcolor="red"' ?>>1</td>
						<td style="cursor:default" <?php if($date_dif->y+1 == 2) echo 'bgcolor="red"' ?>>2</td>
						<td style="cursor:default" <?php if($date_dif->y+1 == 3) echo 'bgcolor="red"' ?>>3</td>
						<td style="cursor:default" <?php if($date_dif->y+1 == 4) echo 'bgcolor="red"' ?>>4</td>
						<td style="cursor:default" <?php if($date_dif->y+1 >= 5 && $date_dif->y+1 <= 9) echo 'bgcolor="red"' ?>>5-9</td>
						<td style="cursor:default" <?php if($date_dif->y+1 >= 10 && $date_dif->y+1 <= 14) echo 'bgcolor="red"' ?>>10-14</td>
						<td style="cursor:default" <?php if($date_dif->y+1 >= 15 && $date_dif->y+1 <= 19) echo 'bgcolor="red"' ?>>15-19</td>
						<td style="cursor:default" <?php if($date_dif->y+1 >= 20 && $date_dif->y+1 <= 24) echo 'bgcolor="red"' ?>>20-24</td>
						<td style="cursor:default" <?php if($date_dif->y+1 >= 25 && $date_dif->y+1 <= 29) echo 'bgcolor="red"' ?>>25-29</td>
					</tr>
					<tr><th style="cursor:default">Días</th>
						<td style="cursor:default" <?php if($date_dif->y+1 == 1) echo 'bgcolor="red"' ?>>6</td>
						<td style="cursor:default" <?php if($date_dif->y+1 == 2) echo 'bgcolor="red"' ?>>8</td>
						<td style="cursor:default" <?php if($date_dif->y+1 == 3) echo 'bgcolor="red"' ?>>10</td>
						<td style="cursor:default" <?php if($date_dif->y+1 == 4) echo 'bgcolor="red"' ?>>12</td>
						<td style="cursor:default" <?php if($date_dif->y+1 >= 5 && $date_dif->y+1 <= 9) echo 'bgcolor="red"' ?>>14</td>
						<td style="cursor:default" <?php if($date_dif->y+1 >= 10 && $date_dif->y+1 <= 14) echo 'bgcolor="red"' ?>>16</td>
						<td style="cursor:default" <?php if($date_dif->y+1 >= 15 && $date_dif->y+1 <= 19) echo 'bgcolor="red"' ?>>18</td>
						<td style="cursor:default" <?php if($date_dif->y+1 >= 20 && $date_dif->y+1 <= 24) echo 'bgcolor="red"' ?>>20</td>
						<td style="cursor:default" <?php if($date_dif->y+1 >= 25 && $date_dif->y+1 <= 29) echo 'bgcolor="red"' ?>>22</td></tr>
				</tbody>
			</table></p>
		</div>
	</div><br>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default" style="background-color:color;">
				<div class="panel-heading">
						<h3 class="panel-title">Premisas Básicas</h3>
				</div>
				<div class="panel-body" id="premisas">
					<p><ol type="a">
						<li>Los días que se consideran como hábiles, son los días que laboras de acuerdo a tus condiciones contractuales. Por ejemplo: Si laboras de Lunes a Viernes, los días hábiles son esos 5 y los días Sábado y Domingo no se contabilizan como vacaciones sino como días de descanso.</li>
						<li>Podrás disfrutar de los días proporcionales de vacaciones que te corresponden a partir de los 9 meses de ingreso a la empresa.</li>
						<li>A partir de tu fecha de aniversario cuentas con un período de 18 meses para disfrutar tus días de vacaciones. Los días de vacaciones que no se disfruten dentro del período de 18  meses establecido no se acumularán para el siguiente período y caducarán. Por ningún motivo se pagarán los días no disfrutados.</li>
						<li>La Prima Vacacional será pagada en la segunda quincena del mes que corresponda a tu aniversario de la empresa y corresponde al 25% de los días de vacaciones generados en el período que acabe de cerrar.</li>
						<li>Las vacaciones deberán solicitarse con un período mínimo de 30 días y las autorizaciones que deberás gestionar son las siguientes (según aplique):
							<ul type="square">
								<li>Si estás en proyecto: El líder de proyecto te autorizará.</li>
								<li>Si no está en proyecto o eres área administrativa: El superior inmediato de acuerdo a la estructura será quien autorice.</li>
							</ul>
						</li>
						<li>Una vez autorizadas por tu líder/superior, tu solicitud pasará a validación por parte de Capital Humano. Una vez que Capital Humano valide, se te notificará vía correo electrónico.</li>
					</ol></p>
				</div>
			</div>
		</div>
	</div>
	<script>
		document.write('\
			<style>\
				.tbl > tbody > tr > th {\
					background: '+color+'\
				}\
			</style>\
		');
	</script>