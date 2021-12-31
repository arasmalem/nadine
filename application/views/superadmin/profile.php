<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Profil User
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('home') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Profil User</li>
        </ol>
        <!-- Flashdata -->
        <?php if ($this->session->flashdata('success')) { ?>
            <div class="flash-data" data-status="success" data-flashdata="<?= $this->session->flashdata('success') ?>"></div>
        <?php } elseif ($this->session->flashdata('failed')) { ?>
            <div class="flash-data" data-status="failed" data-flashdata="<?= $this->session->flashdata('failed') ?>"></div>
        <?php } ?>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-5">

                <!-- Profile Image -->
                <div class="box box-success">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="<?= base_url('assets/images/') . $user->foto ?>" alt="User profile picture">

                        <h3 class="profile-username text-center"><?= $user->nama ?></h3>

                        <p class="text-muted text-center"><?= $user->role ?></p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Username</b> <span class="pull-right label label-warning"><?= $user->username ?></span>
                            </li>
                            <li class="list-group-item">
                                <b>Bidang</b>
                            </li>
                            <li class="list-group-item">
                                <b>Sub Bidang</b>
                            </li>
                            <li class="list-group-item">
                                <b>Status</b>
                                <?php if ($user->status == 'Active') { ?>
                                    <span class="pull-right badge bg-navy"><?= $user->status ?></span>
                                <?php } else { ?>
                                    <span class="pull-right badge bg-red"><?= $user->status ?></span>
                                <?php } ?>
                            </li>
                        </ul>

                        <button class="btn btn-success btn-block" data-toggle="modal" data-target="#editProfil">Edit Profil</button>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal Edit Profil -->
<div class="modal fade" id="editProfil" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-olive">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title"><i class="fa fa-user"></i> &nbsp;&nbsp;Edit Profil</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= site_url('profile/edit') ?>" enctype="multipart/form-data" class="form">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
                    <input type="hidden" name="old_image" value="<?= $user->foto ?>">
                    <input type="hidden" name="old_password" value="<?= $user->password ?>">
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?= $user->nama ?>" required />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="text text-danger">Kosongkan jika password tidak diganti</small>
                    </div>
                    <div class="form-group">
                        <label for="file">Foto</label>
                        <input type="file" id="file" name="foto">

                        <p class="help-block" style="color:red;">Kosongkan jika foto tidak diganti! Ukuran file maksimal 200KB dan bertipe jpg/jpeg/png</p>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success btn-submit">
                    <div class="spinner"><i class="fa fa-refresh fa-spin"></i> Loading..</div>
                    <div class=" submit-text">Update</div>
                </button>
            </div>
            </form>
        </div>
    </div>
</div>