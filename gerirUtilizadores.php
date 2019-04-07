<?php 
Include "conf/conn.php";
spl_autoload_register(function($nome) {
    include "classes/$nome.class.php"; //inclui a class
});
session_start();
if(!isset($_SESSION['uId']) and !isset($_SESSION['password'])){
	header("location:login.php");
}
$queryUtilizadores = new utilizadores();
$resultUsers = $queryUtilizadores->select('','','','');
$queryCargos = new cargos(); 
// $queryUsers = "SELECT * FROM utilizadores";
// $resultUsers = $conn->query($queryUsers);
?>
<div  class="box">
	<div class="box-body">
		<table id="gerirUsersTable" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Username</th>
					<th>Nome</th>
					<th>Numero</th>
					<th>Cargo</th>
					<th>Estado</th>
					<th>Acção</th>
				</tr>
			</thead>
			<tbody>
				<?php while($users = mysqli_fetch_array($resultUsers)){ ?>
				<tr id="">
					<td><?php echo $users['id']; ?></td>
					<td><?php echo $users['username']; ?></td>
					<td><?php echo $users['nome']; ?></td>
					<td><?php echo $users['numeroAluno']; ?></td>
					<td>
						<?php 
						$resultCargo = $queryCargos->select(array('nome'),'','',array('id'=>$users['idCargo']));
						// $queryCargo = "SELECT nome FROM cargos WHERE id=".$users['idCargo'];
						// $resultCargo= $conn->query($queryCargo);
						$cargo = mysqli_fetch_array($resultCargo);
						echo $cargo['nome'];
						?>
					</td>
					<td>
						<?php 	if($users['block'] == 0){
									echo '<a class="text-success">Normal</a>';
								}else{
									echo '<a class="text-danger">Bloqueado</a>';
								}
					 	?>
					</td>
					<td><a href="#" id="<?php echo $users['id']; ?>" onclick="deleteUser(this);" title="Eliminar" class="fa fa-times text-danger"></a></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	$(function () {
		$('#gerirUsersTable').DataTable();
	});
	function deleteUser(msg)
	{
		$.ajax( {
			type: "POST",
			url: "deleteUsers_proc.php",
			data: {id: msg.id} ,
			success: function( data ) {
				alert(data);
				gerirUtilizadores();
			}
		} );	
	}
</script>