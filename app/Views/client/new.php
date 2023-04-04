<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<h1 class="mt-4 mb-4">New Client</h1>

<div class="row justify-content-center">
    <div class="col-lg-11">
        <div class="card">
            <div class="card-header bg-primary-subtle">
                <!-- <button class="btn btn-primary btn-sm" id="btnNew"><i class="fas fa-plus"></i></button> -->
                <h5>Create New Client</h5>
            </div>
            <div class="card-body">
                <form action="client/saveClient" class="formSubmit" autocomplete="off">
                    <?= csrf_field(); ?>


                    <div class="mx-auto text-center error-modal" style="width: 100%; display: none;">
                        <label id="global_message" class="text-danger pt-2 px-2"></label>
                    </div>

                    <div class="row mb-3">
                        <label for="client" class="col-sm-2 col-form-label">Client</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="client" name="client">
                            <div id="errClient" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="kota" class="col-sm-2 col-form-label">Kota</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" class="form-control" id="kota" name="kota" readonly>
                                <button class="btn btn-secondary" type="button" id="button-addon2" data-bs-toggle="modal" data-bs-target="#kotaModal">...</button>
                            </div>
                            <div id="errKota" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="provinsi" class="col-sm-2 col-form-label">Province</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="provinsi" name="provinsi" readonly>
                            <div id="errProvinsi" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="valid" class="col-sm-2 col-form-label">Valid</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="valid" name="valid" value="0">
                            <div id="errValid" class="invalid-feedback"></div>
                        </div>
                        <label class="col-sm-2 col-form-label">Month</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="active" name="active" value="1">
                        <label class="form-check-label" for="active">Active</label>
                    </div>

                    <hr>

                    <div class="row mt-3">
                        <div class="col-auto">
                            <a href="/client" type="button" class="btn btn-secondary">Close</a>
                            <button type="submit" class="btn btn-primary" id="btnProcess">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="kotaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Select City</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover dataTable">
                    <thead>
                        <th>#</th>
                        <th>City</th>
                        <th>Province</th>
                        <th>Select</th>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($kota as $k) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= ucwords($k->provinsi); ?></td>
                                <td><?= ucwords($k->kota); ?></td>
                                <td><button type="button" class="btn btn-info btn-sm" onclick="inputProv('<?= $k->provinsi; ?>','<?= $k->kota; ?>')" data-bs-dismiss="modal"><i class="fa-regular fa-circle-check"></i></button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
<script>
    $('.dataTable').DataTable()

    function inputProv(provinsi, kota) {
        let provinsiVal = document.querySelector('#provinsi');
        let kotaVal = document.querySelector('#kota');

        provinsiVal.value = provinsi;
        kotaVal.value = kota
    }

    $(document).ready(function() {

        $('.formSubmit').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'post',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'json',
                beforeSend: function() {
                    $('#btnProcess').attr('disabled', 'disabled');
                    $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                success: function(response) {
                    if (response.error) {
                        $('#btnProcess').removeAttr('disabled');
                        $('#btnProcess').html('Save');
                        if (response.error.logout) {
                            window.location.href = response.error.logout
                        }
                        if (response.error.global) {
                            $('.error-modal').show();
                            $('#global_message').html(response.error.global)
                        } else {
                            $('#global_message').html('')
                            $('.error-modal').hide();
                        }

                        if (response.error.client) {
                            $('#client').addClass('is-invalid');
                            $('#errClient').html(response.error.client)
                        } else {
                            $('#client').removeClass('is-invalid');
                            $('#errClient').html('')
                        }

                        if (response.error.valid) {
                            $('#valid').addClass('is-invalid');
                            $('#errValid').html(response.error.valid)
                        } else {
                            $('#valid').removeClass('is-invalid');
                            $('#errValid').html('')
                        }
                    } else {
                        window.location.href = response.url;
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        })
    })
</script>
<?= $this->endSection(); ?>