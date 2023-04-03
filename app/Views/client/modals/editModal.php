<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Client</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="client/editClient" class="formSubmit" autocomplete="off">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $client->id; ?>">
                    <input type="hidden" name="expDate" value="<?= $client->valid_until; ?>">

                    <div class="mx-auto text-center error-modal" style="width: 100%; display: none;">
                        <label id="global_message" class="text-danger pt-2 px-2"></label>
                    </div>

                    <div class="row mb-3">
                        <label for="client" class="col-sm-2 col-form-label">Client</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="client" name="client" value="<?= $client->nama; ?>">
                            <div id="errClient" class="invalid-feedback"></div>
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
                        <input class="form-check-input" type="checkbox" role="switch" id="active" name="active" value="1" <?= ($client->active) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="active">Active</label>
                    </div>
                    <!-- Update Information -->
                    <?php if ($client->userUpdate) : ?>
                        <div class="row mt-3">
                            <label class="text-secondary">Last Update: <?= ucwords(user_profile($client->userUpdate)->nama); ?> (<?= date('d-M-Y', strtotime($client->dateUpdated)); ?>)</label>
                        </div>
                    <?php endif; ?>
                    <!-- End Update Information -->
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
                        $('#editModal').modal('hide');
                        getData();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        })
    })
</script>