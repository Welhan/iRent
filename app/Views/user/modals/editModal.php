<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="user/updateUser" class="formSubmit" autocomplete="off">
                <?= csrf_field(); ?>
                <input type="hidden" name="id" value="<?= $user->id; ?>">
                <div class="modal-body">
                    <div class="mx-auto text-center error-modal" style="width: 100%; display: none;">
                        <label id="global_message" class="text-danger pt-2 px-2"></label>
                    </div>

                    <div class="row mb-3">
                        <label for="name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" value="<?= $user->nama; ?>">
                            <div id="errName" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= $user->telp; ?>">
                            <div id="errPhone" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="address" class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                            <textarea name="address" id="address" class="form-control" cols="30" rows="3"><?= $user->alamat; ?></textarea>
                            <div id="errAddress" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <?php if (session('clientID') == 1) : ?>
                        <div class="row mb-3">
                            <label for="client" class="col-sm-2 col-form-label">Client</label>
                            <div class="col-sm-10">
                                <select class="form-select select2" style="width: 100%;" id="client" name="client">
                                    <option>Choose Client</option>
                                    <?php foreach ($clients as $client) : ?>
                                        <option value="<?= $client->id; ?>" <?= ($user->clientID == $client->id) ? 'selected' : ''; ?>><?= ucwords($client->nama); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div id="errClient" class="invalid-feedback"></div>
                            </div>
                        </div>
                    <?php else : ?>
                        <input type="hidden" name="client" value="<?= session('clientID'); ?>">
                    <?php endif; ?>

                    <div class="row mb-3">
                        <label for="level" class="col-sm-2 col-form-label">Level</label>
                        <div class="col-sm-10">
                            <select class="form-select" id="level" name="level">
                                <?php foreach ($levels as $level) : ?>
                                    <option value="<?= $level->id; ?>" <?= ($user->roleID == $level->id) ? 'selected' : ''; ?>><?= ucwords($level->role); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div id="errLevel" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="username" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="username" name="username" value="<?= $user->username; ?>">
                            <div id="errUsername" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="active" name="active" value="1" <?= ($user->active) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="active">Active</label>
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
        dropdownParent: $('#editModal')
    })

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

                        if (response.error.name) {
                            $('#name').addClass('is-invalid');
                            $('#errName').html(response.error.name)
                        } else {
                            $('#name').removeClass('is-invalid');
                            $('#errName').html('')
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

                        if (response.error.level) {
                            $('#level').addClass('is-invalid');
                            $('#errLevel').html(response.error.level)
                        } else {
                            $('#level').removeClass('is-invalid');
                            $('#errLevel').html('')
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
                        $('#editModal').modal('hide');
                        getDataUser();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        })
    })
</script>