<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Backup Database
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('home') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Backup Database</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="box">
            <div class="row">
                <div class="col-md-5">
                    <div class="box-header">
                        <!-- <label class="text text-red">Klik tombol "Backup DB" untuk membackup database. Lakukan secara berkala untuk keamanan data!</label> -->
                        <div class="callout callout-warning">
                            <h4><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;&nbsp;Warning</h4>
                            <p>Klik tombol "Backup DB" untuk membackup database. Lakukan secara berkala untuk keamanan data!</p>
                        </div>
                    </div>
                    <!-- /.box-header -->
                </div>
            </div>
            <div class="box-body">
                <div class="input-group">
                    <a href="<?= site_url('backupdb/backup') ?>" class="btn btn-primary"><i class="fa fa-download"></i>&nbsp;&nbsp;Backup DB</a>
                </div>
            </div>
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->