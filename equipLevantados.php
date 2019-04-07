<?php 
Include "conf/conn.php";
spl_autoload_register(function($nome) {
    include "classes/$nome.class.php"; //inclui a class
});
session_start();
if(!isset($_SESSION['uId']) and !isset($_SESSION['password'])){
	header("location:login.php");
}
$queryPedidos = new pedidos();
$resultPedidos = $queryPedidos->select('','','',array('terminado'=>'1','levantado'=>'1','userId'=>$_SESSION['uId']));
// $queryPedidos = "SELECT * FROM pedidos WHERE terminado=1 AND levantado = 1 AND userId =".$_SESSION['uId'];
// $resultPedidos = $conn->query($queryPedidos);
?>
<div  class="box">
	<div class="box-body">
		<table id="equipLevantadosTable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Data de Criação</th>
					<th>Data do Levantamento</th>
					<th>Data de Entrega</th>
					<th>Tipo</th>
				</tr>
			</thead>
			<tbody>
				<?php while($pedido = mysqli_fetch_array($resultPedidos)){ ?>
				<tr id="">
					<td><?php echo '<a href="#" id="'.$pedido['id'].'" onclick="mostrarPedido(this);" >Pedido - '.$pedido['id'].'</a>'; ?></td>
					<td><?php echo $pedido['data']; ?></td>
					<td><?php echo $pedido['dataLevantamento']; ?></td>
					<td><?php $timestamp = date('w', strtotime($pedido['dataLevantamento']));
						if ($timestamp >= 2 AND $timestamp < 5)
						{
							echo date('Y-m-d',strtotime('next Friday',strtotime($pedido['dataLevantamento'])));
						}
						else
						{
							echo date('Y-m-d',strtotime('next Tuesday',strtotime($pedido['dataLevantamento']))); 
						}

						?>
					</td>
					<td><?php echo $pedido['tipo']; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<div class="modal fade" id="pedidoModal" role="dialog">
			<div id="pedidoModalContent" class="modal-dialog">
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	$(function () {
		$('#equipLevantadosTable').DataTable();
	});
	function mostrarPedido(msg)
	{
		$.ajax( {
			type: "GET",
			url: "modalPedido.php",
			data: {id: msg.id} ,
			success: function( data ) {
				$('#pedidoModalContent').html(data);
				$('#pedidoModal').modal('show');
			}
		} );	
	}
</script>