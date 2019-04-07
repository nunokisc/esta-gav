<?php 
Include "conf/conn.php";
spl_autoload_register(function($nome) {
    include "classes/$nome.class.php"; //inclui a class
});
session_start();
if(!isset($_SESSION['uId']) and !isset($_SESSION['password'])){
	header("location:login.php");
}

$id = $_GET['idTipo'];
$tipoPedido = $_GET['tipoPedido'];
$uid = $_SESSION['uId'];

$queryEquipamentos = new equipamentos();
$queryPedidos = new pedidos();
$queryTipo = new tipo_e();
$queryEquipamentosEmUso = new equipamentos_em_uso();

$equip = $queryTipo->select('','','',array('id'=>$id),'MYSQLI_ASSOC');
// $queryEquip = "SELECT * FROM tipo_e WHERE id = '$id'";
// $resultEquip = $conn->query($queryEquip);
// $equip = mysqli_fetch_array($resultEquip);
$nameTipo = $equip['tipo'];

$resultEquip = $queryEquipamentos->select('','','',array('tipo'=>$id));
// $queryEquip = "SELECT * FROM equipamentos WHERE tipo = '$id'";
// $resultEquip = $conn->query($queryEquip);

$pedidos = $queryPedidos->select(array('dataLevantamento'),'','',array('userId'=>$uid,'terminado'=>'0'),'MYSQLI_ROW');
// $queryPedidos = "SELECT dataLevantamento FROM pedidos WHERE userId = '$uid' and terminado = 0";
// $resultPedidos = $conn->query($queryPedidos);
// $pedidos = mysqli_fetch_row($resultPedidos);
?>
<div class="box-header">
	<h3 class="box-title">Lista de <?php echo $nameTipo; ?></h3>
</div>
<div class="box-body">
	<table id="equipamentosTable" class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>Quantidade</th>
				<th>Em uso</th>
				<th>Modelo</th>
				<th>Observa&ccedil;&otilde;es</th>
				<th>Requesitar</th>
			</tr>
		</thead>
		<tbody>
			<?php while($equip = mysqli_fetch_array($resultEquip)){ ?>
			<tr id="tr<?php echo $equip['id']; ?>">
				<td id="qtd"> <?php echo $equip['qtd']; ?> </td>
				<td id="inUse"> 
					<?php
					$resultEquipInUse = $queryEquipamentosEmUso->select('','','',array('idEquip'=>$equip['id'],'data'=>'"'.$pedidos[0].'"'));
					// $queryEquipInUse = "SELECT * FROM equipamentos_em_uso WHERE (idEquip =".$equip['id']." and data ='".$pedidos[0]."')";
					// $resultEquipInUse = $conn->query($queryEquipInUse);
					$inUse = 0;
					while($equipInUse = mysqli_fetch_array($resultEquipInUse)){
						$inUse += $equipInUse['qnt'];
					}
					echo $inUse;

					?> 
				</td>
				<td> <?php echo $equip['modelo']; ?> </td>
				<td> <?php echo $equip['obs']; ?> </td>
				<td> <?php 
					$resultPedidosFila = $queryPedidos->select(array('pedidos_linha.id'),array('pedidos_linha','equipamentos'),array('pedidos_linha.idPedido'=>'pedidos.id','pedidos_linha.idEquipamentos'=>'equipamentos.id'),array('userId'=>$_SESSION['uId'],'terminado'=>'0','idEquipamentos'=>$equip['id']));

					// $queryPedidosFila = "SELECT pedidos_linha.id  FROM pedidos
					// INNER JOIN pedidos_linha ON pedidos_linha.idPedido = pedidos.id
					// INNER JOIN equipamentos ON pedidos_linha.idEquipamentos = equipamentos.id
					// WHERE userId = '".$_SESSION['uId']."' and terminado = 0 and idEquipamentos= ".$equip['id']."";
					// $resultPedidosFila = $conn->query($queryPedidosFila);

					if($inUse == $equip['qtd']){ 
						echo "Equipamentos todos em uso nesta data.";
					}else if ( mysqli_num_rows ($resultPedidosFila) > 0 ){
						echo "Equipamento já está adicionado no pedido.";
					}else{ ?>
					<div class="input-group margin"> 
					<input id="<?php echo $equip['id']; ?>" type="number" class="form-control">
						<span class="input-group-btn">
							<button type="button" onclick="submitEquip(	<?php echo $equip['id']; ?>	);" class="btn btn-block btn-default">Adicionar</button>
						</span>
					</div>
					<?php } ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
	function submitEquip(msg){
		$("#alertaSucesso").hide();
		$("#alertaErro").hide();
		var qtdReq = $("#"+msg).val();
		var emStock = $("#tr"+msg+" #qtd").text().replace(/[^a-zA-Z0-9]/g, '')-$("#tr"+msg+" #inUse").text().replace(/[^a-zA-Z0-9]/g, '');
		if( qtdReq <= 0){
			$("#textAlertError").html("A quantidade a Requesitar não pode ser igual ou menor a zero.");
			$("#alertaErro").show();
		} else if( qtdReq > emStock) {
			$("#textAlertError").html("A quantidade a Requesitar não pode ser superior á existente.");
			$("#alertaErro").show();
		}else{
			$.ajax( {
				type: "POST",
				url: "add_equip_proc.php",
				data: {qtd: qtdReq, idEqui: msg, tipoPedido: tipoPedido} ,
				success: function( data ) {
					if(data == 1){
						$("#textAlertSuccess").html("Equipamento adicionado com sucesso.");
						$("#alertaSucesso").show();
						pedidosEmFila();
						var obj = {
							value: $("#material").val()
						};
						showMaterial(obj);
					}
					else
					{
						$("#textAlertError").html("Equipamento não adicionado com sucesso.");
						$("#alertaErro").show();
					}
					console.log(data);
				}
			} );	
		}
	}

	$(function () {
		$('#equipamentosTable').DataTable();
	});
</script>
<?php ?>