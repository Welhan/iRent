 <?php if (session()->getFlashdata('message')) : ?>
     <div class="row">
         <div class="col-lg">
             <div class="alert alert-success mt-3 alertMsg" role="alert">
                 <?= session()->getFlashdata('message'); ?>
             </div>
         </div>
     </div>
 <?php endif; ?>

 <div class="table-responsive mt-3">
     <table class="table table-hover table-bordered dataTable">
         <thead class="bg-primary-subtle">
             <th class="text-center">#</th>
             <th class="text-center">Merek</th>
             <th class="text-center">Model</th>
             <?php if (session('clientID') == 1) : ?>
                 <th class="text-center">Client</th>
             <?php endif; ?>
             <th class="text-center">Status</th>
             <th></th>
         </thead>
         <tbody>
             <?php $no = 1; ?>
             <?php foreach ($vehicles as $v) : ?>
                 <tr class="text-center">
                     <td><?= $no++; ?></td>
                     <td><?= ucwords($v->brand); ?></td>
                     <td><?= ucwords($v->type); ?> (<?= $v->transmition; ?>)</td>
                     <?php if (session('clientID') == 1) : ?>
                         <td class="text-center"><?= ucwords($v->client); ?></td>
                     <?php endif; ?>
                     <td><input type="checkbox" class="btn-check" id="btn-check-2-outlined" autocomplete="off" <?= ((($v->active) ? 'checked' : '')); ?> disabled>
                         <label class="btn <?= ($v->active ? 'btn-outline-success' : 'btn-outline-danger'); ?> btn-sm" for="btn-check-2-outlined"><?= (($v->active) ? 'Active' : 'Not Active'); ?></label><br>
                     </td>
                     <td>
                         <button type="button" class="btn btn-primary btn-sm" onclick="updateVehicle(<?= $v->id; ?>)"><i class="fa-solid fa-pen-to-square"></i></button>
                     </td>
                 </tr>
             <?php endforeach; ?>
         </tbody>
     </table>
 </div>

 <script>
     $('.dataTable').DataTable({
         "lengthMenu": [
             [10, 25, 50, -1],
             [10, 25, 50, "All"]
         ]
     })

     $(document).ready(function() {
         // For Allert Purpose
         window.setTimeout(function() {
             $(".alertMsg").fadeTo(1000, 0).slideUp(1000, function() {
                 $(this).remove();
             });
         }, 5000);
     });

     function updateVehicle(id) {
         $.ajax({
             type: 'POST',
             url: '/vehicle/edit',
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
 </script>