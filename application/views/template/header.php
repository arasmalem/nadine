<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $title ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/') ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/') ?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/') ?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/') ?>dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/') ?>dist/css/skins/skin-green.min.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/') ?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/') ?>bower_components/select2/dist/css/select2.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/') ?>plugins/iCheck/all.css">
  <!-- zTree view -->
  <link rel="stylesheet" href="<?= base_url('assets/zTree/css/') ?>zTreeStyle/zTreeStyle.css">
  <!-- Sweetalert -->
  <link rel="stylesheet" href="<?= base_url('assets/AdminLTE/') ?>plugins/sweetalert2/sweetalert2.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <style>
    .avatar {
      vertical-align: middle;
      width: 50px;
      height: 50px;
      border-radius: 50%;
    }
  </style>
  <style>
    .zoom {
      -webkit-transition: all 0.35s ease-in-out;
      -moz-transition: all 0.35s ease-in-out;
      transition: all 0.35s ease-in-out;
      cursor: -webkit-zoom-in;
      cursor: -moz-zoom-in;
      cursor: zoom-in;
    }

    .zoom:hover,
    .zoom:active,
    .zoom:focus {
      /**adjust scale to desired size, add browser prefixes**/
      -ms-transform: scale(2.5);
      -moz-transform: scale(2.5);
      -webkit-transform: scale(2.5);
      -o-transform: scale(2.5);
      transform: scale(2.5);
      position: relative;
      z-index: 100;
    }

    /**To keep upscaled images visible on mobile, increase left & right margins a bit**/
    @media only screen and (min-width: 768px) and (max-width: 991px) {
      ul.gallery {
        margin-left: 15vw;
        margin-right: 15vw;
      }

      /**TIP: Easy escape for touch screens, give gallery's parent container a cursor: pointer.**/
      .DivName {
        cursor: pointer
      }
    }

    @media only screen and (min-width: 480px) and (max-width: 767px) {
      ul.gallery {
        margin-left: 15vw;
        margin-right: 15vw;
      }

      /**TIP: Easy escape for touch screens, give gallery's parent container a cursor: pointer.**/
      .DivName {
        cursor: pointer
      }
    }
  </style>
</head>

<body class="hold-transition skin-green sidebar-mini">
  <div class="wrapper">