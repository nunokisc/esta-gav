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
	$resultPedidosFila = $queryPedidos->select(array('modelo','qnt','pedidos_linha.id'),array('pedidos_linha','equipamentos'),array('pedidos_linha.idPedido'=>'pedidos.id','pedidos_linha.idEquipamentos'=>'equipamentos.id'),array('userId'=>$_SESSION['uId'],'terminado'=>'0'));
	// $queryPedidosFila = "SELECT modelo, qnt, pedidos_linha.id  FROM pedidos
	// 						INNER JOIN pedidos_linha ON pedidos_linha.idPedido = pedidos.id
	// 							INNER JOIN equipamentos ON pedidos_linha.idEquipamentos = equipamentos.id
	// 								WHERE userId = '".$_SESSION['uId']."' and terminado = 0";
	// $resultPedidosFila = $conn->query($queryPedidosFila);
	$id = $queryPedidos->select(array('id'),'','',array('userId'=>$_SESSION['uId'],'terminado'=>'0'),'MYSQLI_ASSOC');
	// $queryPedidosFilaId = "SELECT id FROM pedidos WHERE userId = '".$_SESSION['uId']."' and terminado = 0";
	// $resultPedidosFilaId = $conn->query($queryPedidosFilaId);
	// $id = mysqli_fetch_array($resultPedidosFilaId);
?>
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">Finalizar pedido nº <?php  echo $id['id']; ?></h3>
			</div>
			<div class="box-body">
				<table id="finalizarPedidoTable" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Equipamento</th>
							<th>Quantidade</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php while($pedidosFila = mysqli_fetch_array($resultPedidosFila)){ ?>
						<tr>
							<td><?php echo $pedidosFila['modelo']; ?></td>
							<td><?php echo $pedidosFila['qnt']; ?></td>
							<td><a id="<?php echo $pedidosFila['id']; ?>" href="#" onclick="deletePedidosFila(this);return false;" class="btn btn-mini pull-right"><i class="fa fa-times"></i> Delete</a></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				
				<button type="button" onclick="finalizarPedidoProc();" class="btn btn-block btn-default">Finalizar Pedido</button>
			</div>
		</div>
<script type="text/javascript">
var socketIoAddress = "91.121.84.50:4002";
	if(typeof socket == 'undefined'){
		socket = io.connect("http://"+socketIoAddress);
	}
	
	socket.on('update', function(msg){
    //checkPageUpdates();
    console.log("loool");
    if(page == "pedidosInternos"){
    	var obj = {
    		value: $("#material").val()
    	};
    	showMaterial(obj);
    }
}); 
	var pedidoId = "<?php  echo $id['id']; ?>";
	function deletePedidosFila(msg){
		
		$.ajax( {
				type: "POST",
				url: "deletePedidoFila_proc.php",
				data: {id : msg.id} ,
				success: function( data ) {
					finalizarPedido();
					pedidosEmFila();
				}
			} );	
	}

	function finalizarPedidoProc(){
		
		$.ajax( {
				type: "POST",
				url: "finalizarPedido_proc.php",
				success: function( data ) {
					alert(data);
					socket.emit("update", 2);
					window.location.replace("index.php");
				}
			} );	
	}

$(function () {
	$('#finalizarPedidoTable').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": false,
      "autoWidth": false
    });
});
</script>
