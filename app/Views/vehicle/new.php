<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<h1 class="mt-4 mb-4">New Vehicles</h1>

<div class="row justify-content-center">
    <div class="col-lg-11">
        <div class="card">
            <div class="card-header d-flex justify-content-end">
                <button class="btn btn-danger btn-sm" onclick="back()"><i class="fa-sharp fa-solid fa-rectangle-xmark"></i></button>
            </div>
            <form action="vehicle/saveVehicle" class="formSubmit" enctype="multipart/form-data">
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="row mb-3">
                                <label for="brand" class="col-sm-2 col-form-label">Brand</label>
                                <div class="col-sm-10">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="brand" name="brand" readonly>
                                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#vehicleModal"><i class="fa-solid fa-magnifying-glass"></i></button>
                                        <div id="errBrand" class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="row mb-3">
                                        <label for="type" class="col-sm-3 col-form-label">Type</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="type" name="type" readonly>
                                            <div id="errType" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="row mb-3">
                                        <label for="capacity" class="col-sm-5 col-form-label">Capacity</label>
                                        <div class="col-sm-7">
                                            <input type="number" class="form-control" id="capacity" name="capacity">
                                            <div id="errCapacity" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="row mb-3">
                                        <label for="transmition" class="col-sm-3 col-form-label">Transmition</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="transmition" name="transmition" readonly>
                                            <div id="errTransmition" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="row mb-3">
                                        <label for="fuel" class="col-sm-3 col-form-label">Fuel</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="fuel" name="fuel" readonly>
                                            <div id="errFuel" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="description" class="col-sm-2 col-form-label">Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                    <div id="errDescription" class="invalid-feedback"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="row mb-3">
                                        <label for="price" class="col-sm-3 col-form-label">Rental Price</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="price" name="price">
                                            <div id="errPrice" class="invalid-feedback"></div>
                                        </div>
                                        <label for="price" class="col-sm-3 col-form-label">/ day</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="row mb-3">
                                        <label for="year" class="col-sm-6 col-form-label">Year of Car</label>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control" id="year" name="year">
                                            <div id="errYear" class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="active" name="active" value="1">
                                <label class="form-check-label" for="active">Active</label>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <img src="assets/img/vehicle/default.jpg" class="img-thumbnail img-preview " style="height: 320px; width: 320px">
                            <div class="input-group mt-3">
                                <input type="file" class="form-control" id="pic" name="pic" onchange="previewImg()">
                                <div id="errPic" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
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
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="vehicleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary-subtle">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Vehicle List</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table bordered table-striped dataTable">
                        <thead>
                            <th class="text-center">#</th>
                            <th class="text-center">Brand</th>
                            <th class="text-center">Model</th>
                            <th class="text-center">Transmition</th>
                            <th class="text-center">Fuel</th>
                            <th class="text-center">Capacity</th>
                            <th class="text-center">Choose</th>
                        </thead>
                        <tbody>
                            <?php $no = 1;; ?>
                            <?php foreach ($vehicles as $ve) : ?>
                                <tr class="text-center">
                                    <td><?= $no++; ?></td>
                                    <td><?= ucwords($ve->brand); ?></td>
                                    <td><?= ucwords($ve->type); ?></td>
                                    <td><?= ucwords($ve->transmition); ?></td>
                                    <td><?= ucwords($ve->fuel); ?></td>
                                    <td><?= $ve->capacity ?> Seater</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" onclick="inputCar('<?= ucwords($ve->brand); ?>','<?= ucwords($ve->type); ?>','<?= ucwords($ve->transmition); ?>','<?= ucwords($ve->fuel); ?>','<?= $ve->capacity; ?>')" data-bs-dismiss="modal"><i class="fa-regular fa-circle-check"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
<script>
    $('.dataTable').DataTable({
        ordering: false
    })

    function back() {
        window.location.href = '/vehicle';
    }

    new AutoNumeric('#price', {
        unformatOnSubmit: true,
        minimumValue: "0",
        decimalPlaces: "0"
        // decimalCharacter: ",",
        // digitGroupSeparator: "."
    });

    function inputCar(brand, type, transmition, fuel, capacity) {
        let brandVal = document.querySelector('#brand');
        let typeVal = document.querySelector('#type');
        let transmitionVal = document.querySelector('#transmition');
        let fuelVal = document.querySelector('#fuel');
        let capacityVal = document.querySelector('#capacity');

        brandVal.value = brand;
        typeVal.value = type;
        transmitionVal.value = transmition;
        fuelVal.value = fuel;
        capacityVal.value = capacity;
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

                        if (response.error.brand) {
                            $('#brand').addClass('is-invalid');
                            $('#errBrand').html(response.error.brand)
                        } else {
                            $('#brand').removeClass('is-invalid');
                            $('#errBrand').html('')
                        }

                        if (response.error.type) {
                            $('#type').addClass('is-invalid');
                            $('#errType').html(response.error.type)
                        } else {
                            $('#type').removeClass('is-invalid');
                            $('#errType').html('')
                        }

                        if (response.error.capacity) {
                            $('#capacity').addClass('is-invalid');
                            $('#errCapacity').html(response.error.capacity)
                        } else {
                            $('#capacity').removeClass('is-invalid');
                            $('#errCapacity').html('')
                        }

                        if (response.error.transmition) {
                            $('#transmition').addClass('is-invalid');
                            $('#errTransmition').html(response.error.transmition)
                        } else {
                            $('#transmition').removeClass('is-invalid');
                            $('#errTransmition').html('')
                        }

                        if (response.error.fuel) {
                            $('#fuel').addClass('is-invalid');
                            $('#errFuel').html(response.error.fuel)
                        } else {
                            $('#fuel').removeClass('is-invalid');
                            $('#errFuel').html('')
                        }

                        if (response.error.price) {
                            $('#price').addClass('is-invalid');
                            $('#errPrice').html(response.error.price)
                        } else {
                            $('#price').removeClass('is-invalid');
                            $('#errPrice').html('')
                        }

                        if (response.error.year) {
                            $('#year').addClass('is-invalid');
                            $('#errYear').html(response.error.year)
                        } else {
                            $('#year').removeClass('is-invalid');
                            $('#errYear').html('')
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
                        window.location.reload();
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