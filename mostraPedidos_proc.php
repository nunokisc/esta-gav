<?php 
Include "conf/conn.php";
spl_autoload_register(function($nome) {
    include "classes/$nome.class.php"; //inclui a class
});
session_start();
if(!isset($_SESSION['uId']) and !isset($_SESSION['password'])){
	header("location:login.php");
}
$estPedidos = $_GET['estPedidos'];
$queryPedidos = new pedidos();
$queryUtilizadores = new utilizadores();
if ($estPedidos == "all"){
	$resultPedidos = $queryPedidos->select('','','','');
	//$queryPedidos = "SELECT * FROM pedidos";
} 
else if ($estPedidos == "none") 
{
	die;
} 
else 
{
	$resultPedidos = $queryPedidos->select('','','',array('terminado'=>'1','entregue'=>'0','aprovado'=>$estPedidos));
	//$queryPedidos = "SELECT * FROM pedidos WHERE terminado=1 and entregue = 0 and aprovado=".$estPedidos;
}
//$resultPedidos = $conn->query($queryPedidos);
?>

<div class="box-body">
	<table id="mostraPedidosTable" class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>ID</th>
				<th>Utilizador</th>
				<th>Data de Criação</th>
				<th>Data do Levantamento</th>
				<th>Data de Entrega</th>
				<th>Tipo</th>
				<th>Acção</th>
			</tr>
		</thead>
		<tbody>
			<?php while($pedido = mysqli_fetch_array($resultPedidos)){ ?>
			<tr id="">
				<td><?php echo '<a href="#" id="'.$pedido['id'].'" onclick="mostrarPedido(this);" >Pedido - '.$pedido['id'].'</a>'; ?></td>
				<td><?php
					$resultUtilizador = $queryUtilizadores->select('','','',array('id'=>$pedido['userId']));  	
					//$queryUtilizador = "SELECT * FROM utilizadores WHERE id=".$pedido['userId'];
					//$resultUtilizador = $conn->query($queryUtilizador);
					$utilizador = mysqli_fetch_array($resultUtilizador);
					echo $utilizador['nome']." - ".$utilizador['numeroAluno'];
					?></td>
					<td><?php echo $pedido['data']; ?></td>
					<td><?php echo $pedido['dataLevantamento']; ?></td>
					<td><?php echo $pedido['dataEntrega']; ?></td>
					<td><?php echo $pedido['tipo']; ?></td>
					<td><?php 	if($pedido['aprovado'] == 0 && ($_SESSION['cargo'] == 1 || $_SESSION['cargo'] == 4 ))
						{
							echo '<a href="#" id="'.$pedido['id'].'" onclick="aprovPedido(this);" title="Aprovar" class="fa fa-check text-success"></a>';
						}
						else if ($pedido['aprovado'] == 1)
						{
							if($pedido['levantado'] == 0 && $pedido['finalizado'] == 0 && ($_SESSION['cargo'] == 1 || $_SESSION['cargo'] == 4 || $_SESSION['cargo'] == 5))
							{
								echo '<a href="#" id="'.$pedido['id'].'" onclick="levantarPedido(this);" title="Levantar" class="fa fa-upload"></a>';
							}
							else if ($pedido['entregue'] == 0 && ($_SESSION['cargo'] == 1 || $_SESSION['cargo'] == 4 || $_SESSION['cargo'] == 5))
							{
								echo '<a href="#" id="'.$pedido['id'].'" onclick="entregarPedido(this);" title="Entregar" class="fa fa-download"></a>';
							}
						}
						if ($pedido['levantado'] == 0 && $pedido['finalizado'] == 0 && ($_SESSION['cargo'] == 1 || $_SESSION['cargo'] == 4 ))
						{
							echo '<a href="#" id="'.$pedido['id'].'" onclick="deletePedido(this);" title="Eliminar" class="fa fa-times text-danger"></a>';
						}
						?>	 
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		<div class="modal fade" id="pedidoModal" role="dialog">
			<div id="pedidoModalContent" class="modal-dialog">
			</div>
		</div>


		<script type="text/javascript">
			$(function () {
				$('#mostraPedidosTable').DataTable();
			});

			function aprovPedido(msg)
			{
				$.ajax( {
					type: "POST",
					url: "aprovPedidos_proc.php",
					data: {id: msg.id} ,
					success: function( data ) {
						alert(data);
						var obj = {
							value: $("#pedidos").val()
						};
						showPedidos(obj);
					}
				} );	
			}
			function deletePedido(msg)
			{
				$.ajax( {
					type: "POST",
					url: "deletePedidos_proc.php",
					data: {id: msg.id} ,
					success: function( data ) {
						alert(data);
						var obj = {
							value: $("#pedidos").val()
						};
						showPedidos(obj);
					}
				} );	
			}
			function levantarPedido(msg)
			{
				$.ajax( {
					type: "POST",
					url: "levantarPedidos_proc.php",
					data: {id: msg.id} ,
					success: function( data ) {
						alert(data);
						var obj = {
							value: $("#pedidos").val()
						};
						showPedidos(obj);
					}
				} );	
			}
			function entregarPedido(msg)
			{
				$.ajax( {
					type: "POST",
					url: "entregarPedidos_proc.php",
					data: {id: msg.id} ,
					success: function( data ) {
						alert(data);
						var obj = {
							value: $("#pedidos").val()
						};
						showPedidos(obj);
					}
				} );	
			}
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