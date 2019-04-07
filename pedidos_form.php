<?php 
Include "conf/conn.php";
spl_autoload_register(function($nome) {
    include "classes/$nome.class.php"; //inclui a class
});
session_start();
if(!isset($_SESSION['uId']) and !isset($_SESSION['password'])){
	header("location:login.php");
}

$uid = $_SESSION['uId'];

$queryTipo = new tipo_e();
$queryPedidos = new pedidos();
$resultMaterial = $queryTipo->select('','','','');
$resultPedidos = $queryPedidos->select('','','',array('userId'=>$uid,'terminado'=>'0'));
// $queryMaterial = "SELECT * FROM tipo_e";
// $resultMaterial = $conn->query($queryMaterial);

// $queryPedidos = "SELECT * FROM pedidos WHERE userId = '$uid' and terminado = 0";
// $resultPedidos = $conn->query($queryPedidos);
$pedidos[0] = "";


?>
<html>
<head>
	<meta charset="utf-8"><!-- Your HTML file can still use UTF-8-->
</head>
<body>
	<form >
		<?php 	if(mysqli_num_rows ($resultPedidos) == 0 ){ ?>
		<div class="form-group">
			<label>Data pedido:</label>

			<div style="width: 100%;" class="input-group">
				<div class='input-group date' id='datetimepicker5'>
					<span class="input-group-addon">
						<span class="glyphicon glyphicon-calendar"></span>
					</span>
					<input id="datemask" type='text' class="form-control" />
				</div>
			</div>
		</div>
		<div class="form-group">
			<label>Tipo pedido:</label>
			<div style="width: 100%;" class="input-group">
				<select class="form-control" name="tipoPedidoSelect" id="tipoPedidoSelect">
					<option selected="selected" value="selected">Selecionar</option>
					<option value="Interno">Interno</option>
					<option value="Externo">Externo</option>
				</select>
			</div>
		</div>
		<button type="button" onclick="criarPedido();" class="btn btn-block btn-default">Iniciar Pedido</button>
		<HR style="border-color: grey">
		<script type="text/javascript">
			$("#material").attr('disabled', 'disabled');
		</script>
		<?php }else { 
			$pedidos = mysqli_fetch_row($resultPedidos);?>
			<div class="form-group">
				<label>Data pedido:</label>

				<div style="width: 100%;" class="input-group">
					<div class='input-group date'>
						<span class="input-group-addon">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
						<input  type='text' class="form-control" disabled value="<?php echo date("Y-m-d", strtotime($pedidos[6])); ?>" />
					</div>
					<button type="button" onclick="cancelarPedido();" class="btn btn-block btn-default">Cancelar Pedido</button>
				</div>
			</div>
			<script type="text/javascript">
				tipoPedido = "<?php echo $pedidos[8] ?>";
			</script>
			<?php } ?>
			<div class="form-group">
				<label>Material:</label>
				<select id="material" onchange="showMaterial(this);" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true">
					<option selected="selected">Selecionar</option>
					<?php 	while($material = mysqli_fetch_array($resultMaterial))
					echo '<option value="'.$material["id"].'">'.$material["tipo"].'</option>';
					?>
				</select>
			</div>
		</form>
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
		<div id="material_show" class="box">
		</div>
	</body>
	</html>
	<script type="text/javascript">
	var socketIoAddress = "185.13.148.4:4002";
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
	
		var idPedido = "<?php echo $pedidos[0]; ?>";
		$(function () {
			$(".select2").select2();
			$('#datetimepicker5').datetimepicker({
				daysOfWeekDisabled: [0, 1, 3,4,6],
				format: 'YYYY-MM-DD'
			});
		});
		$( "select#material option:checked" ).val();

		function criarPedido(){
			if($( "select#tipoPedidoSelect option:checked" ).val() == "selected"){
				alert("Inserir tipo de pedido");
			}else{
				$.ajax( {
					type: "GET",
					url: "criarPedido_proc.php",
					data: {data : $("#datemask").val(), tipoPedido: $( "select#tipoPedidoSelect option:checked" ).val()} ,
					success: function( data ) {
						pedidoInterno();
				//alert(data);
			}
		} );	
		//console.log($("#datemask").val());
	}
}

function cancelarPedido(){
	$.ajax( {
		type: "POST",
		url: "deletePedido_proc.php",
		data: {id: idPedido} ,
		success: function( data ) {
			pedidoInterno();
			pedidosEmFila();
			console.log(data);
		}
	} );	
}

function showMaterial(msg){
	
	$.ajax( {
		type: "GET",
		url: "pedidos_proc.php",
		data: {idTipo : msg.value, tipoPedido: tipoPedido} ,
		success: function( data ) {
			$('#material_show').html(data);
		}
	} );	
}

</script>