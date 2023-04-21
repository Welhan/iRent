<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Detail Images <?= ucwords($vehicle->brand); ?> - <?= ucwords($vehicle->type); ?></h5>
                <button class="btn btn-danger btn-sm" onclick="back()"><i class="fa-sharp fa-solid fa-rectangle-xmark"></i></button>
            </div>
            <div class="card-body">

                <div class="mx-auto text-center error-modal" style="width: 100%; display: none;">
                    <label id="global_message" class="text-danger pt-2 px-2"></label>
                </div>

                <form action="vehicle/saveImg" class="formSubmit" enctype="multipart/form-data" autocomplete="off">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="id" value="<?= $vehicle->id; ?>">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="input-group mt-3">
                                <input type="file" class="form-control" id="pic" name="pic" onchange="previewImg()">
                                <div id="errPic" class="invalid-feedback"></div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-2">
                                    <button type="submit" class="btn btn-primary" id="btnProcess">Save</button>
                                </div>
                                <div class="col-lg-10">
                                    <div class="mx-auto text-center error-modal" style="width: 100%; display: none;">
                                        <label id="global_message" class="text-danger pt-2 px-2"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <img src="assets/img/vehicle/default.jpg" class="img-thumbnail img-preview object-fit-fill" style="height: 320px;">
                        </div>
                    </div>
                </form>
                <?php if ($detailImg) : ?>
                    <hr>
                    <div class="row justify-content-center">
                        <?php foreach ($detailImg as $detail) : ?>
                            <div class="col-lg-3">
                                <div class="card mt-3" style="width: 18rem;">
                                    <img src="assets/img/vehicle/<?= $client; ?>/<?= $vehicle->brand; ?>/<?= $vehicle->type; ?>/<?= $detail->img; ?>" class="card-img-top mt-2">
                                    <div class="card-body">
                                        <h6>Added By: <?= user_profile($detail->userAdded)->nama; ?></h6>
                                        <button type="button" class="btn btn-danger" onclick="deleteImg(<?= $detail->id; ?>,'<?= $client; ?>','<?= $vehicle->brand; ?>','<?= $vehicle->type; ?>','<?= $detail->img; ?>')">Delete</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<script>
    function back() {
        getDataVehicle();
    }

    function deleteImg(id, client, brand, type, img) {
        $.ajax({
            type: 'POST',
            url: '/vehicle/deleteImg',
            data: {
                id,
                client,
                brand,
                type,
                img
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

                    if (response.error.global) {
                        $('.error-modal').show();
                        $('#global_message').html(response.error.global)
                    } else {
                        $('#global_message').html('')
                        $('.error-modal').hide();
                    }
                } else {
                    window.location.href = 'vehicle';
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    }

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

                        if (response.error.pic) {
                            $('#pic').addClass('is-invalid');
                            $('#errPic').html(response.error.pic)
                        } else {
                            $('#pic').removeClass('is-invalid');
                            $('#errPic').html('')
                        }
                    } else {
                        // $('#editModal').modal('hide');
                        window.location.href = 'vehicle';
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        })
    })
</script>