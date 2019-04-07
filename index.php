<?php
Include "conf/conn.php";
spl_autoload_register(function($nome) {
    include "classes/$nome.class.php"; //inclui a class
});
session_start();
if(!isset($_SESSION['uId']) and !isset($_SESSION['password'])){
	header("location:login.php");
}

if(isset($_GET['page'])){
	$page = $_GET['page'];
} else {
	$page = null;
}

$queryCargos = new cargos();
$cargos = $queryCargos->select('','','',array('id'=>$_SESSION['cargo']),'MYSQLI_ASSOC');
// $queryCargos = "SELECT * FROM cargos WHERE id = '".$_SESSION['cargo']."'";
// $cargosesultCargos = $conn->query($queryCargos);
// $cargos = mysqli_fetch_array($cargosesultCargos);

$queryUsers = new utilizadores();
$users = $queryUsers->select(array('imagem','data'),'','',array('id'=>$_SESSION['uId']),'MYSQLI_ASSOC');
// $queryUser = "SELECT imagem, data FROM utilizadores WHERE id = '".$_SESSION['uId']."'";
// $resultUser = $conn->query($queryUser);
// $users = mysqli_fetch_array($resultUser);

$queryPedidos = new pedidos();
$resultMeusPedidos = $queryPedidos->select('','','',array('userId'=>$_SESSION['uId'],'terminado'=>'1'));
// $queryMeusPedidos = "SELECT * FROM pedidos WHERE userId = '".$_SESSION['uId']."' and terminado = 1";
// $resultMeusPedidos = $conn->query($queryMeusPedidos);

$resultPedidos = $queryPedidos->select('','','','');
// $queryPedidos = "SELECT * FROM pedidos ";
// $resultPedidos = $conn->query($queryPedidos);

$resultUsers = $queryUsers->select('','','','');
// $queryUsers = "SELECT * FROM utilizadores ";
// $resultUsers = $conn->query($queryUsers);

$resultEquipamentosLevantados = $queryPedidos->select(array('modelo','qnt'),array('pedidos_linha','equipamentos'),array('pedidos_linha.idPedido'=>'pedidos.id','pedidos_linha.idEquipamentos'=>'equipamentos.id'),array('userId'=>$_SESSION['uId'],'levantado'=>'1'));
// $queryEquipamentosLevantados = "SELECT modelo, qnt  FROM pedidos
// INNER JOIN pedidos_linha ON pedidos_linha.idPedido = pedidos.id
// INNER JOIN equipamentos ON pedidos_linha.idEquipamentos = equipamentos.id
// WHERE userId=".$_SESSION['uId']." AND levantado = 1";
// $resultEquipamentosLevantados = $conn->query($queryEquipamentosLevantados);

$resultEntregarHoje = $queryPedidos->select('','','',array('userId'=>$_SESSION['uId'],'terminado'=>'1','entregue'=>'0','DATE(dataEntrega)'=>'DATE(NOW())'));
// $queryEntregarHoje = "SELECT * FROM pedidos WHERE userId = '".$_SESSION['uId']."' and terminado = 1 and entregue = 0 and DATE(dataEntrega) = DATE(NOW())";
// $resultEntregarHoje = $conn->query($queryEntregarHoje);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $siteInfo['nomeDoSistema']; ?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="ionicons-2.0.1/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="css/AdminLTE.min.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="plugins/select2/select2.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
	<link rel="stylesheet" href="plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css" />
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="css/skins/skin-blue.min.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body class="hold-transition skin-blue sidebar-mini ">
	<div class="wrapper">

		<!-- Main Header -->
		<header class="main-header">

			<!-- Logo -->
			<a href="index.php" class="logo">
				<!-- mini logo for sidebar mini 50x50 pixels -->
				<span class="logo-mini"><?php echo $siteInfo['siglaDoSistema']; ?></span>
				<!-- logo for regular state and mobile devices -->
				<span class="logo-lg"><?php echo $siteInfo['siglaDoSistema']; ?></span>
			</a>

			<!-- Header Navbar -->
			<nav class="navbar navbar-static-top" role="navigation">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>
				<!-- Navbar Right Menu -->
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<!-- Pedidos Menu -->
						<li class="dropdown notifications-menu">
							<!-- Menu toggle button -->
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-calendar-check-o"></i>
								<span id="spanPedidosLinha" class="label label-warning"></span>
							</a>
							<ul class="dropdown-menu">
								<li class="header">Equipamentos deste pedido</li>
								<li>	
									<!-- Inner Menu: contains the notifications -->
									<ul id="pedidosEmFila" class="menu" style="margin-left: 3%;">
										
									</ul>
								</li>
								<li class="footer"><a id="pedidosEmFilaFinalizar" href="?page=finalizarPedido">Finalizar pedido</a></li>
							</ul>
						</li>
						
						
						<!-- Notifications Menu -->
						<li class="dropdown notifications-menu">
							<!-- Menu toggle button -->
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-bell-o"></i>
								<span class="label label-warning">10</span>
							</a>
							<ul class="dropdown-menu">
								<li class="header">You have 10 notifications</li>
								<li>
									<!-- Inner Menu: contains the notifications -->
									<ul class="menu">
										<li><!-- start notification -->
											<a href="#">
												<i class="fa fa-users text-aqua"></i> 5 new members joined today
											</a>
										</li>
										<!-- end notification -->
									</ul>
								</li>
								<li class="footer"><a href="#">View all</a></li>
							</ul>
						</li>
						
						<!-- User Account Menu -->
						<li class="dropdown user user-menu">
							<!-- Menu Toggle Button -->
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<!-- The user image in the navbar-->
								<img src="data:image/png;base64,<?php echo base64_encode($users['imagem']); ?>" class="user-image" alt="User Image">
								<!-- hidden-xs hides the username on small devices so only the image appears. -->
								<span class="hidden-xs"><?php echo $_SESSION['nome']; ?></span>
							</a>
							<ul class="dropdown-menu">
								<!-- The user image in the menu -->
								<li class="user-header">
									<img onclick="$('#pictureUploadModal').modal('show');" src="data:image/png;base64,<?php echo base64_encode($users['imagem']); ?>"  class="img-circle" alt="User Image">
									<p>
										<?php echo $_SESSION['nome']; ?> - <?php echo $cargos['nome'] ?>
										<small>Membro desde <?php $userData = new DateTime($users['data']); echo $userData->format('d-m-Y');; ?></small>
									</p>
								</li>
							</li>
							<!-- Menu Footer-->
							<li class="user-footer">
								<div class="pull-left">
									<a href="#" class="btn btn-default btn-flat">Profile</a>
								</div>
								<div class="pull-right">
									<a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
								</div>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
	</header>
	<!-- Left side column. contains the logo and sidebar -->
	<aside class="main-sidebar">

		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">

			<!-- Sidebar user panel (optional) -->
			<div class="user-panel">
				<div class="pull-left image">
					<img src="data:image/png;base64,<?php echo base64_encode($users['imagem']); ?>" class="img-circle"  alt="User Image">
				</div>
				<div class="pull-left info">
					<p><?php echo $_SESSION['nome']; ?></p>
					<!-- Status -->
					<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
				</div>
			</div>


			<!-- Sidebar Menu -->
			<ul class="sidebar-menu">
				<li class="header">Menu</li>
				<!-- Optionally, you can add icons to the links -->
				<?php if(!isset($_GET['page'])){?>
					<li class="active"><a href="index.php"><i class="fa fa-home"></i> <span>Home</span></a></li>
				<?php } else { ?>
					<li><a href="index.php"><i class="fa fa-home"></i> <span>Home</span></a></li>
				<?php } ?>
				<?php if(isset($_GET['page']) && $_GET['page'] == "pedidos"){ ?>
					<?php if($_SESSION['block'] == 0){?>
						<li class="active"><a href="?page=pedidos"><i class="fa fa-tasks"></i> <span>Pedidos</span></a></li>
					<?php } ?>
				<?php } else { ?>
					<?php if($_SESSION['block'] == 0){?>
						<li><a href="?page=pedidos"><i class="fa fa-tasks"></i> <span>Pedidos</span></a></li>
					<?php } ?>
				<?php } ?>
				<?php if(isset($_GET['page']) && $_GET['page'] == "mostrarPedidos"){ ?>
					<?php if($_SESSION['cargo'] == 1 ||  $_SESSION['cargo'] == 4 ||  $_SESSION['cargo'] == 5){?>
						<li class="active"><a href="?page=mostrarPedidos"><i class="fa fa-eye"></i> <span>Mostrar Pedidos</span></a></li>
					<?php } ?>
				<?php } else { ?>
					<?php if($_SESSION['cargo'] == 1 ||  $_SESSION['cargo'] == 4 ||  $_SESSION['cargo'] == 5){?>
						<li><a href="?page=mostrarPedidos"><i class="fa fa-eye"></i> <span>Mostrar Pedidos</span></a></li>
					<?php } ?>
				<?php } ?>
				<?php if(isset($_GET['page']) && $_GET['page'] == "gerirEquipamentos"){ ?>
					<?php if($_SESSION['cargo'] == 1 ||  $_SESSION['cargo'] == 4 ||  $_SESSION['cargo'] == 5){?>
						<li class="active"><a href="?page=gerirEquipamentos"><i class="fa fa-cog"></i> <span>Gerir Equipamentos</span></a></li>
					<?php } ?>
				<?php } else { ?>
					<?php if($_SESSION['cargo'] == 1 ||  $_SESSION['cargo'] == 4 ||  $_SESSION['cargo'] == 5){?>
						<li><a href="?page=gerirEquipamentos"><i class="fa fa-cog"></i> <span>Gerir Equipamentos</span></a></li>
					<?php } ?>
				<?php } ?>
				<?php if(isset($_GET['page']) && $_GET['page'] == "gerirUtilizadores"){ ?>
					<?php if($_SESSION['cargo'] == 1 ||  $_SESSION['cargo'] == 4 ||  $_SESSION['cargo'] == 5){?>
						<li class="active"><a href="?page=gerirUtilizadores"><i class="fa fa-users"></i> <span>Gerir Utilizadores</span></a></li>
					<?php } ?>
				<?php } else { ?>
					<?php if($_SESSION['cargo'] == 1 ||  $_SESSION['cargo'] == 4 ||  $_SESSION['cargo'] == 5){?>
						<li><a href="?page=gerirUtilizadores"><i class="fa fa-users"></i> <span>Gerir Utilizadores</span></a></li>
					<?php } ?>
				<?php } ?>
			</ul>
			<!-- /.sidebar-menu -->
		</section>
		<!-- /.sidebar -->
	</aside>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1 id="title">
			<?php if(!isset($_GET['page'])){ ?>
				Home - User Area
			<?php } ?>
			</h1>
			<ol id="breadcrumb" class="breadcrumb">
			<?php if(!isset($_GET['page'])){ ?>
				<li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
			<?php } ?>
			</ol>
		</section>

		<!-- Main content -->
		<section id="content" class="content">
		<?php if(!isset($_GET['page'])){ ?>
			<?php if($_SESSION['block'] == 1){?>
				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
					<h4><i class="icon fa fa-ban"></i> Alerta!</h4>
					<p>Estas bloqueado de fazer pedidos por não ter entregue um pedido!</p>
				</div>
			<?php } ?>
			<!-- Your Page Content Here -->
			<div class="row">
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div  class="small-box bg-aqua">
						<div onclick="meusPedidos();" class="inner">
							<h3> <?php  $numPedidos = 0;
								if(mysqli_num_rows ($resultMeusPedidos) > 0 ){
									while($pedidos = mysqli_fetch_array($resultMeusPedidos)){
										$numPedidos += 1;
									}
									echo $numPedidos;
								}
								else
								{
									echo $numPedidos;            
								}
								?>
							</h3>

							<p><?php if($numPedidos == 1){echo "Pedido";}else{echo "Pedidos";} ?></p>
						</div>
						<div class="icon">
							<i class="ion ion-information-circled"></i>
						</div>
						<a href="?page=meusPedidos"  class="small-box-footer">Ver Mais <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-green">
						<div onclick="equipLevantados();" class="inner">
							<h3> <?php  $numEquipLevant = 0;
								if(mysqli_num_rows ($resultEquipamentosLevantados) > 0 ){
									while($pedidos = mysqli_fetch_array($resultEquipamentosLevantados)){
										$numEquipLevant += 1;
									}
									echo $numEquipLevant;
								}
								else
								{
									echo $numEquipLevant;            
								}
								?>
							</h3>

							<p>Equipamentos levantados</p>
						</div>
						<div class="icon">
							<i class="ion ion-ios-download"></i>
						</div>
						<a href="?page=equipLevantados" class="small-box-footer">Ver Mais <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-yellow">
						<div class="inner">
							<h3> <?php  $numEntregarHoje = 0;
								if(mysqli_num_rows ($resultEntregarHoje) > 0 ){
									while($pedidos = mysqli_fetch_array($resultEntregarHoje)){
										$numEntregarHoje += 1;
									}
									echo $numEntregarHoje;
								}
								else
								{
									echo $numEntregarHoje;            
								}
								?>
							</h3>

							<p>Hoje para entregar</p>
						</div>
						<div class="icon">
							<i class="ion ion-ios-upload"></i>
						</div>
						<a class="small-box-footer">Atenção!!!</a>
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-orange">
						<div class="inner">
							<h3>asd</h3>

							<p>Alertas</p>
						</div>
						<div class="icon">
							<i class="ion ion-alert"></i>
						</div>
						<a href="#" class="small-box-footer">Ver Mais <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<!-- ./col -->
			</div>
			<?php if($_SESSION['cargo'] == 1 ||  $_SESSION['cargo'] == 4 ||  $_SESSION['cargo'] == 5){?>
			<h3 id="title">
				Home - Admin Area <a onclick="adminArea();" href="#">+</a>
			</h3>
			<!-- Your Page Content Here -->
			<div id="adminArea" class="row" style="display:none">
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-aqua">
						<div onclick="mostraPedidos();" class="inner">
							<h3> <?php  $numPedidos = 0;
								if(mysqli_num_rows ($resultPedidos) > 0 ){
									while($pedidos = mysqli_fetch_array($resultPedidos)){
										$numPedidos += 1;
									}
									echo $numPedidos;
								}
								else
								{
									echo $numPedidos;            
								}
								?>
							</h3>

							<p><?php if($numPedidos == 1){echo "Pedido";}else{echo "Pedidos";} ?></p>
						</div>
						<div class="icon">
							<i class="ion ion-information-circled"></i>
						</div>
						<a href="?page=mostrarPedidos" class="small-box-footer">Ver Mais <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-green">
						<div class="inner">
							<h3>53<sup style="font-size: 20px">%</sup></h3>

							<p>Bounce Rate</p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-yellow">
						<div onclick="gerirUtilizadores();" class="inner">
							<h3> <?php  $numUsers = 0;
								if(mysqli_num_rows ($resultUsers) > 0 ){
									while($temp = mysqli_fetch_array($resultUsers)){
										$numUsers += 1;
									}
									echo $numUsers;
								}
								else
								{
									echo $numUsers;            
								}
								?>
							</h3>

							<p>Utilizadores Registados</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<a href="?page=gerirUtilizadores" class="small-box-footer">Ver Mais <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<!-- ./col -->
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-red">
						<div class="inner">
							<h3> 44 </h3>

							<p>Unique Visitors</p>
						</div>
						<div class="icon">
							<i class="ion ion-pie-graph"></i>
						</div>
						<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<!-- ./col -->
			</div>
			<?php } ?>
		<?php } else {
				echo '<img src="img/ajaxSpinner.gif" style="display:block;
    margin:auto;" width="200" height="200" alt="">';
			} ?>
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
	<div class="modal fade" id="pictureUploadModal" role="dialog">
		<div id="pictureModalContent" class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Profile Image Upload</h4>
				</div>
			<div class="modal-body">
				<div class="box">
					<div class="box-body">
						<form id="uploadProfileImage" method="post" enctype="multipart/form-data">
							<label>Upload Image File:</label><br/>
							<input name="userImage" type="file" class="inputFile" />
							<input type="submit" value="Submit" class="btnSubmit" />
						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			</div>	 
		</div>
	</div>
	<!-- Main Footer -->
	<footer class="main-footer">
		<!-- To the right -->
		<div class="pull-right hidden-xs">
			<?php echo $siteInfo['nomeDoSistema']; ?>
		</div>
		<!-- Default to the left -->
		<strong>Copyright &copy; 2017 <a href="https://www.facebook.com/Nunokisc">Nuno Cardoso</a>.</strong> Todos os direitos reservados.
	</footer>

<!-- REQUIRED JS SCRIPTS -->
<?php if(isset($_GET['page']) == "pedidos" || isset($_GET['page']) == "finalizarPedido"){ ?>
<script src="http://185.13.148.4:4002/socket.io/socket.io.js"></script>
<?php } ?>
<!-- jQuery 2.2.3 -->
<script src="plugins/jQuery/jquery-3.2.1.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="plugins/moment/moment.js"></script>
<script src="plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js"></script>
<!-- AdminLTE App -->
<script src="js/app.min.js"></script>
<!-- Select2 -->
<script src="plugins/select2/select2.full.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
<script>
	var page="<?php echo $page ?>";
	$(document).ready(function(){
		pedidosEmFila();
	});

	function pedidosEmFila(){
		$.ajax( {
			type: "GET",
			url: "pedidosemfila_proc.php",
			dataType: 'json',
			success: function( data ) {
				$('#pedidosEmFila').html(data[0]);
				console.log(data);
				$('#spanPedidosLinha').html(data[1]);
				if(data[1] == 0){
					$('#pedidosEmFilaFinalizar').hide();
				} else {
					$('#pedidosEmFilaFinalizar').show();
				}
			}
		} );
	}

	function pedidoInterno(){
		$.ajax( {
			type: "GET",
			url: "pedidos_form.php",
			success: function( data ) {
				$('#content').html(data);
				$('#title').html("Pedidos");
				$('#breadcrumb').html('<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li><li class="active">Pedidos</li>');	
			}
		} );
	}
	function mostraPedidos(){
		$.ajax( {
			type: "GET",
			url: "mostraPedidos.php",
			data: {} ,
			success: function( data ) {
				$('#content').html(data);
				$('#title').html("Mostrar Pedidos");
				$('#breadcrumb').html('<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li><li class="active">Mostrar Pedidos</li>'); 
			}
		} );
	}
	function finalizarPedido(){
		$.ajax( {
			type: "GET",
			url: "finalizarPedido.php",
			success: function( data ) {
				$('#content').html(data);
				$('#title').html("Finalizar Pedido");
				$('#breadcrumb').html('<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li><li class="active">Finalizar Pedido</li>'); 
			}
		} );
	}
	function deletePedidosFila(msg){
		
		$.ajax( {
			type: "POST",
			url: "deletePedidoFila_proc.php",
			data: {id : msg.id} ,
			success: function( data ) {
				pedidosEmFila();
				var obj = {
					value: $("#material").val()
				};
				showMaterial(obj);
			}
		} );  
	}
	function meusPedidos(){
		$.ajax( {
			type: "GET",
			url: "meusPedidos.php",
			data: {} ,
			success: function( data ) {
				$('#content').html(data);
				$('#title').html("Meus Pedidos");
				$('#breadcrumb').html('<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li><li class="active">Meus Pedidos</li>'); 
			}
		} );
	}	
	function equipLevantados(){
		$.ajax( {
			type: "GET",
			url: "equipLevantados.php",
			data: {} ,
			success: function( data ) {
				$('#content').html(data);
				$('#title').html("Equipamentos levantados");
				$('#breadcrumb').html('<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li><li class="active">Equipamentos levantados</li>'); 
			}
		} );
	}	
	function adminArea(){
		if($('#adminArea').is(':visible')){
			$("#adminArea").hide();
		} else {
			$("#adminArea").show();
		}
	}
	function gerirEquipamentos(){
		$.ajax( {
			type: "GET",
			url: "gerirEquipamentos.php",
			data: {} ,
			success: function( data ) {
				$('#content').html(data);
				$('#title').html("Gerir Equipamentos");
				$('#breadcrumb').html('<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li><li class="active">Gerir Equipamentos</li>'); 
			}
		} );
	}
	//aqui
	function gerirUtilizadores(){
		$.ajax( {
			type: "GET",
			url: "gerirUtilizadores.php",
			data: {} ,
			success: function( data ) {
				$('#content').html(data);
				$('#title').html("Gerir Utilizadores");
				$('#breadcrumb').html('<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li><li class="active">Gerir Utilizadores</li>'); 
			}
		} );
	}
	$( "#uploadProfileImage" ).on( "submit", function( e ) {
		var formData = new FormData(this);
	       

	    $.ajax({
	        url: "proc_uploadProfileImage.php",
	        type: 'POST',
	        data: formData,
	        processData: false,
    		contentType: false,
    		cache: false,
	        success: function (data) {
	            alert(data);
	            location.reload();
	        }
	    });

	    e.preventDefault(); 
	});
	if(page == "pedidos")
	{
		pedidoInterno();
	} 
	else if(page == "mostrarPedidos")
	{
		mostraPedidos();
	}
	else if(page == "finalizarPedido")
	{
		finalizarPedido();
	}
	else if(page == "meusPedidos")
	{
		meusPedidos();
	}
	else if(page == "equipLevantados")
	{
		equipLevantados();
	}
	else if(page == "gerirEquipamentos")
	{
		gerirEquipamentos();
	}
	else if(page == "gerirUtilizadores")
	{
		gerirUtilizadores();
	}

</script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->
 </body>
 </html>
