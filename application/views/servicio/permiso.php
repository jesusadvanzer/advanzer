<div class="jumbotron">
	<div class="container">
		<h2 align="left"><b>Permiso de Ausencia</b></h2>
	</div>
</div>
<div class="container">
	<div class="row" align="center">
		<div class="col-md-12"><div id="cargando" style="display:none; color: green;">
			<img src="<?= base_url('assets/images/loading.gif');?>"></div></div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<p>La ausencia a las labores por parte del colaborador de la cual haya conocimiento con antelación podrá ser autorizada como permiso (con goce o sin goce de sueldo) de acuerdo a los lineamientos que continuación se describen:
			<ol type="1">
				<p><li>Permiso con Goce de Sueldo</li>
				<ol type="a">
					<p><li>Las situaciones previstas por Capital Humano dentro de las cuales se podrá autorizar un permiso con Goce de Sueldo serán las siguientes:
						<p><table width="50%" align="center" class="tbl table-condensed table-bordered">
							<thead>
								<tr>
									<th style="cursor:default">Ocasión</th>
									<th style="cursor:default">Días Autorizados</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="cursor:default">Matrimonio</td>
									<td style="cursor:default">2 Días hábiles</td></tr>
								<tr>
									<td style="cursor:default">Nacimiento de Hijos</td>
									<td style="cursor:default">5 Días hábiles</td></tr>
								<tr>
									<td style="cursor:default">Fallecimiento de Cónyuge</td>
									<td style="cursor:default">2 Días hábiles</td></tr>
								<tr>
									<td style="cursor:default">Fallecimiento de Hermanos</td>
									<td style="cursor:default">2 Días hábiles</td></tr>
								<tr>
									<td style="cursor:default">Fallecimiento de Hijos</td>
									<td style="cursor:default">3 Días hábiles</td></tr>
								<tr>
									<td style="cursor:default">Fallecimiento de Padres</td>
									<td style="cursor:default">3 Días hábiles</td></tr>
								<tr>
									<td style="cursor:default">Fallecimiento Padres Políticos</td>
									<td style="cursor:default">2 Días hábiles</td></tr>
							</tbody>
						</table></p>
					</li></p>
					<p><li> Procedimiento</li></p>
					<ol type="i">
						<p><li>En los casos de las situaciones mencionadas en el cuadro anterior, el colaborador deberá enviar la <a class="link" style="text-decoration:none" href="<?= base_url('solicitar_permiso');?>">Solicitud de Ausencia</a> a su Jefe Directo y anexar el comprobante de la ocasión por la cual se vuelve relevante el permiso.</li></p>
						<p><li>Deberá estar al pendiente de la solicitud para asegurarse de que llegue a Capital Humano.</li></p>
					</ol>
				</ol></p>
				<p><li>Permiso Sin Goce Sueldo</li>
				<ol type="a">
					<p><li>En el caso que algún colaborador sepa con antelación que requerirá ausentarse de sus labores por una situación específica y justificada y el motivo no sea contemplado dentro del punto anterior, podrá solicitar una autorización de Permiso Sin Goce de Sueldo para evitar que le afecte su historial de asistencia.</li></p>
					<p><li>Solamente podrán ser solicitadas Autorizaciones Sin Goce de Sueldo por personal con antigüedad mayor a un año.</li></p>
					<p><li>Se podrá solicitar autorización para ausentarse un máximo 5 días hábiles seguidos (de acuerdo a la fecha de ingreso del colaborador).</li></p>
					<p><li>Procedimiento</li></p>
					<ol type="i">
						<p><li>El colaborador deberá solicitar la autorización de parte de su superior inmediato de acuerdo a la estructura, tan pronto tenga conocimiento del motivo del permiso.</li></p>
						<p><li>El colaborador deberá llenar enviar la <a class="link" style="text-decoration:none" href="<?= base_url('solicitar_permiso');?>">Solicitud de Ausencia</a>.</li></p>
						<p><li>Una vez que se haya autorizado y llegue a Capital Humano se le notificará por correo la fecha de descuento en nómina.</li></p>
					</ol>
				</ol></p>
			</ol>
			Podrán existir situaciones dentro de las cuales se podrá autorizar un Permiso con Goce de Sueldo que se encuentren fuera de las nombradas anteriormente. En estos casos la solicitud llegará a Capital Humano quien se encargará de revisar la situación particular y definirá qué aplica.</p>
		</div>
	</div>
	<div class="row" align="center">
		<button class="btn btn-primary" onclick="location.href='<?= base_url("solicitar_permiso");?>';"><big>Solicitar Permiso de Ausencia</big></button>
	</div>
	<hr>
	<script>
		document.write('\
			<style>\
				.tbl > thead > tr > th {\
					background: '+color+'\
				}\
			</style>\
		');
	</script>