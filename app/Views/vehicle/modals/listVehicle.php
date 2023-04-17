<!-- Modal -->
<div class="modal fade" id="listModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Vehicle Data</h1>
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
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.dataTable').DataTable()
</script>