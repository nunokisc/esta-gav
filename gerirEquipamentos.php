<?php 
Include "conf/conn.php";
spl_autoload_register(function($nome) {
    include "classes/$nome.class.php"; //inclui a class
});
session_start();
if(!isset($_SESSION['uId']) and !isset($_SESSION['password'])){
	header("location:login.php");
}
$queryEquip = new equipamentos();
$resultEquip = $queryEquip->select('','','','');
// $queryEquip = "SELECT * FROM equipamentos";
// $resultEquip = $conn->query($queryEquip);
$queryTipoE = new tipo_e();
$resultTipoEquip = $queryTipoE->select('','','','');
// $queryTipoEquip = "SELECT * FROM tipo_e";
// $resultTipoEquip = $conn->query($queryTipoEquip);

?>
<div id="alertaSucesso" class="alert alert-success alert-dismissible" style="display:none">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-check"></i> Alerta!</h4>
			<p id="textAlertSuccess">Equipamento adicionado com sucesso!</p>
		</div>
		<div id="alertaErro" class="alert alert-danger alert-dismissible" style="display:none">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
			<h4><i class="icon fa fa-ban"></i> Alerta!</h4>
			<p id="textAlertError">Equipamento não foi adicionado com sucesso!</p>
		</div>
<div  class="box">
	<div class="box-body">
		<table id="gerirEquipamentosTable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Tipo</th>
					<th>Em uso</th>
					<th>Quantidade</th>
					<th>Modelo</th>
					<th>Obs</th>
				</tr>
			</thead>
			<tbody>
				<?php while($equip = mysqli_fetch_array($resultEquip)){ ?>
				<tr id="">
					<td><?php echo $equip['id']; ?></td>
					<td><?php
						$resultTipo = $queryTipoE->select(array('tipo'),'','',array('id'=>$equip['tipo']));	
						// $queryTipo = "SELECT tipo FROM tipo_e WHERE id=".$equip['tipo'];
						// $resultTipo= $conn->query($queryTipo);
						$tipo = mysqli_fetch_array($resultTipo);
						echo $tipo['tipo'];
						?>
					</td>
					<td><?php 	
					$queryEquipInUse = "SELECT * FROM equipamentos_em_uso WHERE (idEquip =".$equip['id']." and DATE(data) <= DATE(NOW()))";
					$resultEquipInUse = $conn->query($queryEquipInUse);
					$inUse = 0;
					while($equipInUse = mysqli_fetch_array($resultEquipInUse)){
						$inUse += $equipInUse['qnt'];
					}
					echo $inUse;

					?> </td>
					<td id="qtd<?php echo $equip['id']; ?>">
						<?php echo $equip['qtd']; ?>
						<a onclick="editarQtd(<?php echo $equip['id'].",".$equip['qtd']; ?>);" href="#" title="Editar" class="pull-right fa fa-pencil"></a>
					</td>
					<td id="model<?php echo $equip['id']; ?>">
						<?php echo $equip['modelo']; ?>
						<a onclick="editarModel(<?php echo $equip['id'].",'".$equip['modelo']."'"; ?>);" href="#" title="Editar" class="pull-right fa fa-pencil"></a>
					</td>
					<td id="obs<?php echo $equip['id']; ?>">
						<?php echo $equip['obs']; ?>
						<a onclick="editarObs(<?php echo $equip['id'].",'".$equip['obs']."'"; ?>);" href="#" title="Editar" class="pull-right fa fa-pencil"></a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<div  class="box">
	<div class="box-body">
		<div class="box-header with-border">
			<h3 class="box-title">Adicionar Equipamento</h3>
		</div>
		<div class="box-body">
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="equiTipo" class="col-form-label">Tipo</label>
					<select id="equiTipo" class="form-control">
			            <option value="0">Selecionar</option>
			        	<?php 	while($tipoEquip = mysqli_fetch_array($resultTipoEquip)){ 
									echo '<option value="'.$tipoEquip['id'].'">'.$tipoEquip['tipo'].'</option>';
			         			} 
			        	?>
		            </select>
		        </div>
		        <div class="form-group col-md-2">
					<label for="equiQtd" class="control-label">Quantidade</label>
					<input id="equiQtd" type="text" class="form-control" placeholder="">
		        </div>
		        <div class="form-group col-md-4">
			        <label for="equiModelo" class="control-label">Modelo</label>
					<input id="equiModelo" type="text" class="form-control" placeholder="">
		        </div>
		        <div class="form-group col-md-4">
			        <label for="equiObs" class="control-label">Obs</label>
					<input id="equiObs" type="text" class="form-control" placeholder="">
		        </div>
	        </div>
		</div>
		<div class="box-footer">
			<button type="button" onclick="addEquip();" class="btn btn-primary">Submit</button>
		</div>
	</div>
</div>


<script type="text/javascript">
	$(function () {
		$('#gerirEquipamentosTable').DataTable();
	});

	function addEquip(){
		if($('#equiTipo').val() != 0 && $.isNumeric($('#equiQtd').val()) && $('#equiModelo').val() != ""){
			console.log("entrou");
			$.ajax( {
				type: "POST",
				url: "gerirEquipamentos_addEquip_proc.php",
				data: {tipo: $('#equiTipo').val(),qtd: $('#equiQtd').val(), model: $('#equiModelo').val(), obs: $('#equiObs').val() } ,
				success: function( data ) {
					alert(data);
					$("#alertaSucesso").show();
					setTimeout(
								  function() 
								  {
								    gerirEquipamentos();
								  }, 500);
				}
			} );
		} else {
			$("#textAlertError").html("Equipamento não adicionado com sucesso. </br> -> Tem de selecionar um tipo, a quantidade não pode ser zero e tem de inserir um modelo.");
			$("#alertaErro").show();
		}	
	}

	function editarQtd(id,qtd){
		$('#qtd'+id).html("<input id='eQtd"+id+"' style='width:50%' type='number' class='form-control' value='"+qtd+"' /><a onclick='subQtd("+id+")'  href='#'' title='Ok' class='pull-right fa fa-check text-success'></a>");
	}

	function editarModel(id,model){
		$('#model'+id).html("<input id='eModel"+id+"' style='width:50%' type='text' class='form-control' value='"+model+"' /><a onclick='subModel("+id+")'  href='#'' title='Ok' class='pull-right fa fa-check text-success'></a>");	
	}

	function editarObs(id,obs){
		$('#obs'+id).html("<input id='eObs"+id+"' style='width:50%' type='text' class='form-control' value='"+obs+"' /><a onclick='subObs("+id+")'  href='#'' title='Ok' class='pull-right fa fa-check text-success'></a>");
	}

	function subQtd(id){
		$.ajax( {
			type: "POST",
			url: "gerirEquipamentos_proc.php",
			data: {id: id,val: $('#eQtd'+id).val(), field:"qtd"} ,
			success: function( data ) {
				$('#qtd'+id).html($('#eQtd'+id).val()+'<a onclick="editarQtd('+id+','+$('#eQtd'+id).val()+');" href="#" title="Editar" class="pull-right fa fa-pencil"></a>');
			}
		} );		
	}

	function subModel(id){
		$.ajax( {
			type: "POST",
			url: "gerirEquipamentos_proc.php",
			data: {id: id,val: $('#eModel'+id).val(), field:"model"} ,
			success: function( data ) {
				var teste = "'"+$('#eModel'+id).val()+"'";
				$('#model'+id).html($('#eModel'+id).val()+'<a onclick="editarModel('+id+','+teste+');" href="#" title="Editar" class="pull-right fa fa-pencil"></a>');
			}
		} );		
	}

	function subObs(id){
		$.ajax( {
			type: "POST",
			url: "gerirEquipamentos_proc.php",
			data: {id: id,val: $('#eObs'+id).val(), field:"obs"} ,
			success: function( data ) {
				var teste = "'"+$('#eObs'+id).val()+"'";
				$('#obs'+id).html($('#eObs'+id).val()+'<a onclick="editarObs('+id+','+teste+');" href="#" title="Editar" class="pull-right fa fa-pencil"></a>');
			}
		} );		
	}
</script>