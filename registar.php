<?php
Include "conf/conn.php";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $siteInfo['nomeDoSistema']; ?> | Pagina de Registo</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition register-page">
  <div class="register-box">
    <div class="register-logo">
      <a href="../../index2.html"><?php echo $siteInfo['nomeDoSistema']; ?></a>
    </div>

    <div class="register-box-body">
      <p class="login-box-msg">Registar novo utilizador</p>

      <form id="formRegistar">
        <div  class="form-group has-feedback">
          <input type="text" name="Utilizador" class="form-control" placeholder="Utilizador">
        </div>
        <div class="form-group has-feedback">
          <input type="text" name="Nome" class="form-control" placeholder="Nome Completo">
        </div>
        <div class="form-group has-feedback">
          <input type="number" name="NumeroAluno" class="form-control" placeholder="Numero de Aluno">
        </div>
        <div class="form-group has-feedback">
          <input type="number" name="Numero" class="form-control" placeholder="Contacto">
        </div>
        <div class="form-group has-feedback">
          <input type="email" name="Email" class="form-control" placeholder="E-mail">
        </div>
        <div class="form-group has-feedback">
          <input type="password" name="Password" id="Password" class="form-control" placeholder="Password">
        </div>
        <div class="form-group has-feedback">
          <input type="password" name="PasswordConfirmar" class="form-control" placeholder="Repetir password">
        </div>
        <div class="row">
          <div class="col-xs-8">
            <div class="checkbox icheck">
              <label>
                <input type="checkbox"> Aceitar os <a href="#">termos</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Registar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <a href="login.php" class="text-center">Já tenho conta.</a>
    </div>
    <!-- /.form-box -->
    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Registo</h4>
          </div>
          <div id="modalText" class="modal-body">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.register-box -->
  <!-- jQuery 2.2.3 -->
  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
  <!-- Bootstrap 3.3.6 -->
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="js/jquery.validate.min.js"></script>
  <script src="plugins/additional-methods.js"></script>
  <!-- iCheck -->
  <script src="plugins/iCheck/icheck.min.js"></script>
  <script>
    $(document).ready(function() {
     jQuery.validator.addMethod("lettersonly", function(value, element) {
       return this.optional(element) || /^[a-z\s]+$/i.test(value);
     });

     $('form').validate({
      rules: {
       Utilizador: {
        required: true,
        minlength: 4,
        maxlength: 64
      },
      Nome: {
        required: true,
        lettersonly: true,
        minlength: 5,
        maxlength: 200
      },
      NumeroAluno: {
        required: true
      },
      Numero:{
        required: true,
        minlength: 9,
        maxlength: 9
      },
      Email: {
        required: true,
        email: true,
        pattern: /.+@ipt\.pt$/
      },
      Password: {
        required: true,
        minlength: 5,
      },
      PasswordConfirmar:{
        required: true,
        minlength : 5,
        equalTo : "#Password"
      }
    },
    messages: { 
     Utilizador: {
      required: "Este campo \u00e9 obrigatorio!",
      minlength: "Tem de ter pelo menos 4 caracteres!",
      maxlength: "S\u00f3 pode conter 64 caracteres!"
    },
    Nome: {
      lettersonly: "Apenas letras!",
      required: "Este campo \u00e9 obrigatorio!",
      maxlength: "S\u00f3 pode conter 200 caracteres!"
    },   
    NumeroAluno: {
      required: "Este campo \u00e9 obrigatorio!"
    },
    Numero:{
      required: "Este campo \u00e9 obrigatorio!",
      minlength: "Tem de ter 9 numeros!",
      maxlength: "Tem de ter 9 numeros!"
    },
    Email: {
      required: "Este campo \u00e9 obrigatorio!",
      email:	"Insira um email valido!",
      pattern: "Insira um email @ipt.pt"
    },
    Password: {
      required: "Este campo \u00e9 obrigatorio!",
      minlength: "Tem de ter 5 caracteres!"
    },
    PasswordConfirmar:{
     required: "Este campo \u00e9 obrigatorio!",
     minlength: "Tem de ter 5 caracteres!",
     equalTo : "Tem de ser igual \u00e1 de cima!"
   }
 },
        submitHandler: function(form) { // for demo
          $.ajax( {
            type: "POST",
            url: "registar_proc.php",
            data: $("#formRegistar").serialize(),
            success: function( response ) {
             console.log( response );
             $("#modalText").html("<p>"+response+"</p>");
             $('#myModal').modal('show');
           }
         } );
          return false;
        }
      });
   });
    $('#myModal').on('hidden.bs.modal', function () {
      window.location.href = "index.php";
    })

    $(function () {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
    });
  </script>
</body>
</html>
