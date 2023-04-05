<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="row">
        <div class="col-lg">
            <div class="alert <?= session()->getFlashdata('alert'); ?> mt-3" role="alert">
                <?= session()->getFlashdata('message'); ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<h1 class="mt-4 mb-4">User Access</h1>
<div class="card mt-4">
    <div class="card-header">
        User <b><?= ucwords($user->nama); ?></b>
    </div>

    <div class="card-body">
        <table class="table">
            <thead class="text-center">
                <th>Menu</th>
                <th>View</th>
                <th>Add</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Spesial</th>
            </thead>
            <?php foreach ($menus as $menu) : ?>
                <tr class="bg-primary text-white">
                    <td><?= $menu->menu; ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php $subs = generateSubmenu($menu->id, '', session('clientID')); ?>
                <?php foreach ($subs as $sub) : ?>
                    <tr class="text-center">
                        <td><?= $sub->submenu; ?></td>
                        <td>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" role="switch" id="viewToggle<?= $sub->id; ?>" <?= check_access($user->id, $sub->id, 'view') ? 'checked' : ''; ?> onchange="updateAccess(<?= $user->id; ?>, <?= $sub->id; ?>, 'view')">
                                <label class="form-check-label" for="viewToggle<?= $sub->id; ?>"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" role="switch" id="addToggle<?= $sub->id; ?>" <?= check_access($user->id, $sub->id, 'add') ? 'checked' : ''; ?> onchange="updateAccess(<?= $user->id; ?>, <?= $sub->id; ?>, 'add')">
                                <label class="form-check-label" for="addToggle<?= $sub->id; ?>"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" role="switch" id="editToggle<?= $sub->id; ?>" <?= check_access($user->id, $sub->id, 'edit') ? 'checked' : ''; ?> onchange="updateAccess(<?= $user->id; ?>, <?= $sub->id; ?>, 'edit')">
                                <label class="form-check-label" for="editToggle<?= $sub->id; ?>"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" role="switch" id="deleteToggle<?= $sub->id; ?>" <?= check_access($user->id, $sub->id, 'delete') ? 'checked' : ''; ?> onchange="updateAccess(<?= $user->id; ?>, <?= $sub->id; ?>, 'delete')">
                                <label class="form-check-label" for="deleteToggle<?= $sub->id; ?>"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" role="switch" id="controlToggle<?= $sub->id; ?>" <?= check_access($user->id, $sub->id, 'control') ? 'checked' : ''; ?> onchange="updateAccess(<?= $user->id; ?>, <?= $sub->id; ?>, 'control')">
                                <label class="form-check-label" for="controlToggle<?= $sub->id; ?>"></label>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
<script>
    $('.dataTable').DataTable()

    $(document).ready(function() {
        // For Allert Purpose
        window.setTimeout(function() {
            $(".alert").fadeTo(1000, 0).slideUp(1000, function() {
                $(this).remove();
            });
        }, 3000);
    });
</script>

<script>
    function updateAccess(id, subID, flag) {
        $.ajax({
            method: 'post',
            url: '<?= base_url('accessMenu'); ?>',
            data: {
                id,
                subID,
                flag
            },
            dataType: 'json',
            beforeSend: function() {

            },
            success: function(response) {
                if (response.error) {
                    if (response.error.logout) {
                        window.location.href = response.error.logout
                    }
                } else {
                    window.location.reload()
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    }
</script>
<?= $this->endSection(); ?>