<!-- Modal -->
<div class="modal fade" id="changePassModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Alert</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="profile/removePic" class="formSubmit" autocomplete="off">
                <?= csrf_field(); ?>
                <input type="hidden" name="id" value="<?= $id; ?>">
                <div class="modal-body">
                    <div class="mx-auto text-center error-modal" style="width: 100%; display: none;">
                        <label id="global_message" class="text-danger pt-2 px-2"></label>
                    </div>
                    <h5>Are You Sure to Remove Your Profile Picture?</h5>
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
    // Visible & invisible password
    let password = document.querySelectorAll('.password')
    let togglepass = document.querySelectorAll('.password .togglePassword')
    for (let i = 0; i < password.length; i++) {

        (password[i].getElementsByTagName('input'))[1].addEventListener('change', () => {
            // console.log('Ok')
            let inputTag = password[i].getElementsByTagName('input')[0];
            const type = inputTag.getAttribute('type') === 'password' ? 'text' : 'password';

            inputTag.setAttribute('type', type);
        })
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
                        $('#btnProcess').html('Yes');
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