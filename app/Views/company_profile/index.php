<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<h1 class="mt-4 mb-4">Company Profile</h1>

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                Detail Company Profile
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('message')) : ?>
                    <div class="row">
                        <div class="col-lg">
                            <div class="alert alert-success mt-3 alertMsg" role="alert">
                                <?= session()->getFlashdata('message'); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="d-flex justify-content-center">
                            <?php if (!$client->logo) : ?>
                                <figure class="figure">
                                    <img src="assets/img/logo/images.png" class="figure-img img-fluid object-fit-scale rounded" alt="<?= $client->nama; ?>" style="max-height: 450px;">
                                </figure>
                            <?php else : ?>
                                <figure class="figure">
                                    <img src="assets/img/logo/<?= $client->logo; ?>" class="figure-img img-fluid object-fit-scale rounded" alt="<?= $client->nama; ?>" style="max-height: 450px;">
                                </figure>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Company Details</h5>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th>Name</th>
                                        <td><?= ucwords($client->nama); ?></td>
                                    </tr>

                                    <tr>
                                        <th>Places</th>
                                        <td><?= $client->kota; ?>, <?= $client->provinsi; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Valid Until</th>
                                        <td class="<?= (check_client(session('clientID')) ? 'bg-danger text-white' : ''); ?>"><?= date('d-M-Y', strtotime($client->valid_until)); ?></td>
                                    </tr>
                                </table>
                                <button class="btn btn-primary mt-3" id="btnEdit" onclick="editCompany(<?= session('clientID'); ?>)">Edit Profile</button>
                                <?php if ($client->logo) : ?>
                                    <button class="btn btn-warning mt-3" id="btnRemovePic" onclick="removeLogo(<?= session('clientID'); ?>)">Remove Logo</button>
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
    function editCompany(id) {
        $.ajax({
            type: 'POST',
            url: '/company/edit',
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