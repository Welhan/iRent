<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Vehicle Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="vehicle/delete" class="formSubmit" autocomplete="off" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="id" value="<?= $vehicle->id; ?>">
                <div class="modal-body">
                    <div class="mx-auto text-center error-modal" style="width: 100%; display: none;">
                        <label id="global_message" class="text-danger pt-2 px-2"></label>
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <th>Brand</th>
                            <td><?= ucwords($vehicle->brand); ?></td>
                        </tr>
                        <tr>
                            <th>Model</th>
                            <td><?= ucwords($vehicle->type); ?></td>
                        </tr>
                        <tr>
                            <th>Fuel</th>
                            <td><?= ucwords($vehicle->fuel); ?></td>
                        </tr>
                        <tr>
                            <th>Transmition</th>
                            <td><?= ucwords($vehicle->transmition); ?></td>
                        </tr>
                        <tr>
                            <th>Year</th>
                            <td><?= ucwords($vehicle->year); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary" id="btnProcess">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
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


                    } else {
                        $('#deleteModal').modal('hide');
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