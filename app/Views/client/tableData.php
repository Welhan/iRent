 <?php if (session()->getFlashdata('message')) : ?>
     <div class="row">
         <div class="col-lg">
             <div class="alert alert-success mt-3 alertMsg" role="alert">
                 <?= session()->getFlashdata('message'); ?>
             </div>
         </div>
     </div>
 <?php endif; ?>

 <div class="table-responsive">
     <table class="table table-hover table-bordered" id="datatablesSimple">
         <thead class="bg-primary-subtle">
             <th class="text-center">#</th>
             <th class="text-center">Client</th>
             <th class="text-center">Daerah</th>
             <th class="text-center">Status</th>
             <th class="text-center">Tanggal Expired</th>
             <th class="text-center"></th>
         </thead>
         <tbody>
             <?php $no = 1; ?>
             <?php foreach ($clients as $client) : ?>
                 <tr class="text-center <?= (check_client($client->id) ? 'bg-warning' : ''); ?>">
                     <td><?= $no++; ?></td>
                     <td><?= ucwords($client->nama); ?></td>
                     <td><?= ucwords($client->kota); ?> (<?= ucwords($client->provinsi); ?>)</td>
                     <td>
                         <input type="checkbox" class="btn-check" id="btn-check-2-outlined" autocomplete="off" <?= (!check_client($client->id) ? (($client->active) ? 'checked' : '') : ''); ?> disabled>
                         <label class="btn <?= check_client($client->id) ? 'btn-outline-danger' : ($client->active ? 'btn-outline-success' : 'btn-outline-danger'); ?> btn-sm" for="btn-check-2-outlined"><?= (check_client($client->id)) ? 'Expired Soon' : (($client->active) ? 'Active' : 'Not Active'); ?></label><br>
                     </td>
                     <td><?= ucwords(date('d-M-Y', strtotime($client->valid_until))); ?></td>
                     <td>
                         <button type="button" class="btn btn-secondary btn-sm"><i class="fa-solid fa-eye"></i></button>
                         <button type="button" class="btn btn-info btn-sm" onclick="edit(<?= $client->id; ?>)"><i class="fa-solid fa-pen-to-square"></i></button>
                         <button type="button" class="btn btn-danger btn-sm" onclick="deleteClient(<?= $client->id; ?>)"><i class="fa-solid fa-trash"></i></button>
                     </td>
                 </tr>
             <?php endforeach; ?>
         </tbody>
     </table>
 </div>

 <script>
     $('#datatablesSimple').DataTable()

     // function edit(id) {
     //     $.ajax({
     //         type: 'POST',
     //         url: '/client/edit',
     //         data: {
     //             id: id
     //         },
     //         dataType: 'json',
     //         beforeSend: function() {
     //             $('.btn').attr('disabled', 'disabled');
     //         },
     //         success: function(response) {
     //             $('.btn').removeAttr('disabled');
     //             if (response.error) {
     //                 if (response.error.logout) {
     //                     window.location.href = response.error.logout
     //                 }
     //             } else {
     //                 $('#viewModal').html(response.data).show();
     //                 $('#editModal').modal('show');
     //             }
     //         },
     //         error: function(xhr, ajaxOptions, thrownError) {
     //             alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
     //         }
     //     })
     // }

     function edit(id) {
         window.location.href = 'editClient?id=' + id;
     }

     function deleteClient(id) {
         $.ajax({
             type: 'POST',
             url: '/client/delete',
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