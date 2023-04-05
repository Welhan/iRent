<div class="table-responsive">
    <table class="table table-hover table-bordered" id="datatablesSimple">
        <thead class="bg-primary-subtle">
            <th class="text-center">#</th>
            <th class="text-center">Nama</th>
            <th class="text-center">Client</th>
            <th class="text-center"></th>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($users as $user) : ?>
                <tr class="text-center">
                    <td><?= $no++; ?></td>
                    <td><?= ucwords($user->nama); ?></td>
                    <td><?= ucwords($user->client); ?></td>
                    <td>
                        <form action="access" method="post">
                            <input type="hidden" name="id" value="<?= $user->id; ?>">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-screwdriver-wrench"></i></button>
                            <button type="button" class="btn btn-info btn-sm" onclick="edit(<?= $user->id; ?>)"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteUser(<?= $user->id; ?>)"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $('#datatablesSimple').DataTable()

    function edit(id) {
        $.ajax({
            type: 'POST',
            url: '/user/edit',
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

    function deleteUser(id) {
        $.ajax({
            type: 'POST',
            url: '/user/deleteUser',
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
                    $('#deleteModal').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        })
    }
</script>