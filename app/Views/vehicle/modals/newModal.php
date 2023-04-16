<!-- Modal -->
<div class="modal fade" id="newModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h1 class="modal-title fs-5" id="exampleModalLabel">New Vehicle Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="vehicle/saveVehicle" class="formSubmit" autocomplete="off" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <div class="mx-auto text-center error-modal" style="width: 100%; display: none;">
                        <label id="global_message" class="text-danger pt-2 px-2"></label>
                    </div>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row mb-3">
                                <label for="brand" class="col-sm-2 col-form-label">Brand</label>
                                <div class="col-sm-10">
                                    <select class="form-select select2" style="width: 100%;" id="brand" name="brand">
                                        <option value="">Choose Brand</option>
                                        <?php foreach ($vehicles as $ve) : ?>
                                            <option value="<?= $ve->id; ?>"><?= $ve->brand; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div id="errBrand" class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="type" class="col-sm-2 col-form-label">Type</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="type" name="type">
                                    <div id="errType" class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="transmition" class="col-sm-2 col-form-label">Transmition</label>
                                <div class="col-sm-10">
                                    <select class="form-select" style="width: 100%;" id="transmition" name="transmition">
                                        <option value="Automatic" selected>Automatic</option>
                                        <option value="Manual">Manual</option>
                                    </select>
                                    <div id="errTransmition" class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="fuel" class="col-sm-2 col-form-label">Fuel</label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="fuel" name="fuel">
                                        <option value="Gasoline" selected>Gasoline</option>
                                        <option value="Diesel">Diesel</option>
                                    </select>
                                    <div id="errFuel" class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="description" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                    <div id="errDescription" class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="active" name="active" value="1">
                                <label class="form-check-label" for="active">Active</label>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <img src="assets/img/vehicle/default.jpg" class="img-thumbnail img-preview " style="height: 250px; width: 250px">
                            <div class="input-group mt-3">
                                <input type="file" class="form-control" id="pic" name="pic" onchange="previewImg()">
                                <div id="errPic" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnProcess">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('.select2').select2({
        dropdownParent: $('#newModal')
    })
    $(document).ready(function() {

        $('.formSubmit').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'post',
                url: $(this).attr('action'),
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                processData: false,
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

                        if (response.error.brand) {
                            $('#brand').addClass('is-invalid');
                            $('#errBrand').html(response.error.brand)
                        } else {
                            $('#brand').removeClass('is-invalid');
                            $('#errBrand').html('')
                        }

                        if (response.error.phone) {
                            $('#phone').addClass('is-invalid');
                            $('#errPhone').html(response.error.phone)
                        } else {
                            $('#phone').removeClass('is-invalid');
                            $('#errPhone').html('')
                        }

                        if (response.error.address) {
                            $('#address').addClass('is-invalid');
                            $('#errAddress').html(response.error.address)
                        } else {
                            $('#address').removeClass('is-invalid');
                            $('#errAddress').html('')
                        }

                        if (response.error.client) {
                            $('#client').addClass('is-invalid');
                            $('#errClient').html(response.error.client)
                        } else {
                            $('#client').removeClass('is-invalid');
                            $('#errClient').html('')
                        }

                        if (response.error.level) {
                            $('#level').addClass('is-invalid');
                            $('#errLevel').html(response.error.level)
                        } else {
                            $('#level').removeClass('is-invalid');
                            $('#errLevel').html('')
                        }

                        if (response.error.username) {
                            $('#username').addClass('is-invalid');
                            $('#errUsername').html(response.error.username)
                        } else {
                            $('#username').removeClass('is-invalid');
                            $('#errUsername').html('')
                        }

                        if (response.error.password) {
                            $('#password').addClass('is-invalid');
                            $('#errPassword').html(response.error.password)
                        } else {
                            $('#password').removeClass('is-invalid');
                            $('#errPassword').html('')
                        }
                    } else {
                        $('#newModal').modal('hide');
                        getDataVehicle();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        })
    })
</script>