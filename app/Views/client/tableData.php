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
            <?php foreach ($clients as $client) : ?>
                <tr class="text-center">
                    <td><?= $no++; ?></td>
                    <td><?= ucwords($client->nama); ?></td>
                    <td><?= ucwords($client->valid_until); ?></td>
                    <td>
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