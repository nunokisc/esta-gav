<form >
	<div class="form-group">
		<label>Estado dos pedidos:</label>
		<select id="pedidos" onchange="showPedidos(this);" class="form-control">
			<option value="none" selected="selected">Selecionar</option>
			<option value="1">Aprovados</option>
			<option value="0">Por Aprovar</option>
			<option value="all">Todos</option>
		</select>
	</div>
</form>
<div id="showPedidos" class="box">
</div>
<script type="text/javascript">
	function showPedidos(msg){
		
		$.ajax( {
			type: "GET",
			url: "mostraPedidos_proc.php",
			data: {estPedidos: msg.value} ,
			success: function( data ) {
				$('#showPedidos').html(data);
			}
		} );	
	}
</script>