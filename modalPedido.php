<?php
Include "conf/conn.php";
spl_autoload_register(function($nome) {
    include "classes/$nome.class.php"; //inclui a class
});
session_start();
if(!isset($_SESSION['uId']) and !isset($_SESSION['password'])){
	header("location:login.php");
}
$id= $_GET['id'];
$queryPedidos = new pedidos();
$resultPedido = $queryPedidos->select(array('modelo','qnt'),array('pedidos_linha','equipamentos'),array('pedidos_linha.idPedido'=>'pedidos.id','pedidos_linha.idEquipamentos'=>'equipamentos.id'),array('pedidos.id'=>$id));
// $queryPedido = "SELECT modelo, qnt  FROM pedidos
// INNER JOIN pedidos_linha ON pedidos_linha.idPedido = pedidos.id
// INNER JOIN equipamentos ON pedidos_linha.idEquipamentos = equipamentos.id
// WHERE pedidos.id=".$id;
// $resultPedido = $conn->query($queryPedido);
?>
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?php echo "Pedido - ".$id; ?></h4>
	</div>
	<div class="modal-body">
		<div class="box">
			<div class="box-body">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Equipamento</th>
							<th>Quantidade</th>
						</tr>
					</thead>
					<tbody>
						<?php while($pedido = mysqli_fetch_array($resultPedido)){ ?>
						<tr>
							<td><?php echo $pedido['modelo']; ?></td>
							<td><?php echo $pedido['qnt']; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
</div>	 
