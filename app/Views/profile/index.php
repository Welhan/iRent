<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<h1 class="mt-4 mb-4">Profile</h1>

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-end">
                <button class="btn btn-primary btn-sm" onclick="changePass(<?= session('userID'); ?>)">Change Password</button>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('message')) : ?>
                    <div class="row">
                        <div class="col-lg">
                            <div class="alert alert-success mt-3" role="alert">
                                <?= session()->getFlashdata('message'); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="d-flex justify-content-center">
                            <?php if (!$profile->img) : ?>
                                <span class="fa-stack fa-10x border border-primary-subtle rounded-4 bg-primary-subtle">
                                    <!-- <i class="fa fa-circle fa-stack-2x"></i> -->
                                    <strong class="fa-stack-2x text-primary" style="cursor: default"><?= substr($profile->nama, 0, 1); ?></strong>
                                </span>
                            <?php else : ?>
                                <figure class="figure">
                                    <img src="assets/img/profile/<?= $profile->img; ?>" class="figure-img img-fluid object-fit-scale border rounded" alt="<?= $profile->img; ?>" style="max-height: 450px;">
                                    <!-- <figcaption class="figure-caption">A caption for the above image.</figcaption> -->
                                </figure>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5>Profile Details
                                </h5>
                                <?php if ($profile->roleID == 1) : ?>
                                    <i class="fas fa-crown mt-1" style="color: D1B642"></i>
                                <?php elseif ($profile->roleID == 2) : ?>
                                    <i class="fa-solid fa-user-tie mt-1 text-primary"></i>
                                <?php else : ?>
                                    <i class="fa-solid fa-chess-pawn mt-1 text-primary"></i>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th>Name</th>
                                        <td><?= ucwords($profile->nama); ?></td>
                                    </tr>

                                    <tr>
                                        <th>Username</th>
                                        <td><?= $profile->username; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td><?= $profile->email; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td><?= ucwords($profile->telp); ?></td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Address
                                        </th>
                                        <td><?= ucwords($profile->alamat); ?></td>
                                    </tr>
                                </table>
                                <button class="btn btn-primary mt-3" id="btnEdit" onclick="editProfile(<?= session('userID'); ?>)">Edit Profile</button>
                                <?php if ($profile->img) : ?>
                                    <button class="btn btn-warning mt-3" id="btnRemovePic" onclick="removePic(<?= session('userID'); ?>)">Remove Profile</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="viewModal" style="display: none;"></div>

<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
<script>
    function editProfile(id) {
        $.ajax({
            type: 'POST',
            url: '/profile/edit',
            data: {
                id: id
            },
            dataType: 'json',
            beforeSend: function() {
                $('.btn').attr('disabled', 'disabled');
            },
            success: function(response) {
                $('.btn').removeAttr('disabled');
                if (response.error) {
                    if (response.error.logout) {
                        window.location.href = response.error.logout
                    }
                } else {
                    $('#viewModal').html(response.data).show();
                    $('#editModal').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })

    }

    function changePass(id) {
        $.ajax({
            type: 'POST',
            url: '/profile/changePass',
            data: {
                id
            },
            dataType: 'json',
            beforeSend: function() {
                $('.btn').attr('disabled', 'disabled');
            },
            success: function(response) {
                $('.btn').removeAttr('disabled');
                if (response.error) {
                    if (response.error.logout) {
                        window.location.href = response.error.logout
                    }
                } else {
                    $('#viewModal').html(response.data).show();
                    $('#changePassModal').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    }

    function removePic(id) {
        $.ajax({
            type: 'POST',
            url: '/profile/remove',
            data: {
                id
            },
            dataType: 'json',
            beforeSend: function() {
                $('.btn').attr('disabled', 'disabled');
            },
            success: function(response) {
                $('.btn').removeAttr('disabled');
                if (response.error) {
                    if (response.error.logout) {
                        window.location.href = response.error.logout
                    }
                } else {
                    $('#viewModal').html(response.data).show();
                    $('#changePassModal').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    }
</script>
<?= $this->endSection(); ?>