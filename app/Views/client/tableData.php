<div class="table-responsive">
    <table class="table table-hover table-bordered" id="datatablesSimple">
        <thead class="bg-primary-subtle">
            <th class="text-center">#</th>
            <th class="text-center">Client</th>
            <th class="text-center">Status</th>
            <th class="text-center">Tanggal Expired</th>
            <th class="text-center"></th>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php foreach ($clients as $client) : ?>
                <tr class="text-center <?= (check_Expired(session('userID')) ? 'bg-warning' : ''); ?>">
                    <td><?= $no++; ?></td>
                    <td><?= ucwords($client->nama); ?></td>
                    <td>
                        <input type="checkbox" class="btn-check" id="btn-check-2-outlined" autocomplete="off" <?= (!check_Expired(session('userID')) ? (($client->active) ? 'checked' : '') : ''); ?> disabled>
                        <label class="btn <?= check_Expired(session('userID')) ? 'btn-outline-info' : ($client->active ? 'btn-outline-success' : 'btn-outline-danger'); ?> btn-sm" for="btn-check-2-outlined"><?= (check_Expired(session('userID'))) ? 'Expired Soon' : (($client->active) ? 'Active' : 'Not Active'); ?></label><br>
                    </td>
                    <td><?= ucwords(date('d-M-Y', strtotime($client->valid_until))); ?></td>
                    <td>
                        <button type="button" class="btn btn-secondary btn-sm"><i class="fa-solid fa-eye"></i></button>
                        <button type="button" class="btn btn-info btn-sm"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button type="button" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    $('#datatablesSimple').DataTable()
</script>