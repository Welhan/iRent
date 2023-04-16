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
             <th class="text-center">Nama Provinsi</th>
             <th class="text-center">Nama Kota</th>
         </thead>
         <tbody>
             <?php $no = 1; ?>
             <?php foreach ($kota as $k) : ?>
                 <tr class="text-center">
                     <td><?= $no++; ?></td>
                     <td><?= ucwords($k->provinsi); ?></td>
                     <td><?= ucwords($k->kota); ?></td>
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
             $(".alertMsg").fadeTo(1000, 0).slideUp(1000, function() {
                 $(this).remove();
             });
         }, 5000);
     });
 </script>