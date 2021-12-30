<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/') ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/') ?>bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/') ?>bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/') ?>dist/css/AdminLTE.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
        .spinner {
            display: none;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <i class="fa fa-paper-plane"></i> <b>NADINE</b>
            <p>
            <h2>(Naskah Dinas Elektronik)</h2>
            </p>
            <p>
            <h4>Bakorwil Pamekasan</h4>
            </p>
        </div>
        <?php if ($this->session->flashdata('failed')) { ?>
            <div class="alert alert-danger" role="alert">
                <?= $this->session->flashdata('failed') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } elseif ($this->session->flashdata('success')) { ?>
            <div class="alert alert-success" role="alert">
                <?= $this->session->flashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form action="<?= base_url('auth') ?>" method="post" class="form">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="username" placeholder="Username" value="<?= set_value('username'); ?>" autocomplete="off" autofocus>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <?= form_error('username', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" name="password" placeholder="Password" value="<?= set_value('password'); ?>">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <?= form_error('password', '<small class="text-danger pl-3">', '</small>'); ?>
                </div>
                <div class="row">
                    <div class="col-xs-8"></div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-success btn-block btn-flat btn-submit">
                            <div class="spinner"><i class="fa fa-refresh fa-spin"></i> Sign In</div>
                            <div class=" submit-text">Sign In</div>
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery 3 -->
    <script src="<?= base_url('assets/AdminLTE/') ?>bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?= base_url('assets/AdminLTE/') ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script>
        $(function() {
            $('.form').on('submit', function() {
                $('.btn-submit').attr('disabled', 'true');
                $('.spinner').show();
                $('.submit-text').hide();
            })();
        });
    </script>
</body>

</html>