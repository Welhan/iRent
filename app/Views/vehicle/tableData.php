 <?php if (session()->getFlashdata('message')) : ?>
     <div class="row">
         <div class="col-lg">
             <div class="alert alert-success mt-3" role="alert">
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
             <th class="text-center">Status</th>
             <th></th>
         </thead>
         <tbody>
             <?php $no = 1; ?>
             <?php foreach ($vehicles as $v) : ?>
                 <tr class="text-center">
                     <td><?= $no++; ?></td>
                     <td><?= ucwords($v->merek); ?></td>
                     <td><?= ucwords($v->tipe); ?> (<?= $v->transmisi; ?>)</td>
                     <td><input type="checkbox" class="btn-check" id="btn-check-2-outlined" autocomplete="off" <?= ((($v->active) ? 'checked' : '')); ?> disabled>
                         <label class="btn <?= ($v->active ? 'btn-outline-success' : 'btn-outline-danger'); ?> btn-sm" for="btn-check-2-outlined"><?= (($v->active) ? 'Active' : 'Not Active'); ?></label><br>
                     </td>
                     <td>
                         <button type="button" class="btn btn-primary btn-sm"><i class="fa-solid fa-pen-to-square"></i></button>
                     </td>
                 </tr>
             <?php endforeach; ?>
         </tbody>
     </table>
 </div>

 <script>
     $('.dataTable').DataTable()

     $(document).ready(function() {
         // For Allert Purpose
         window.setTimeout(function() {
             $(".alert").fadeTo(1000, 0).slideUp(1000, function() {
                 $(this).remove();
             });
         }, 5000);
     });
 </script>