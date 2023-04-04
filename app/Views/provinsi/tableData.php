 <?php if (session()->getFlashdata('message')) : ?>
     <div class="row">
         <div class="col-lg">
             <div class="alert alert-success mt-3" role="alert">
                 <?= session()->getFlashdata('message'); ?>
             </div>
         </div>
     </div>
 <?php endif; ?>

 <ul class="nav nav-tabs" id="myTab" role="tablist">
     <li class="nav-item" role="presentation">
         <button class="nav-link active" id="provinsi-tab" data-bs-toggle="tab" data-bs-target="#provinsi-tab-pane" type="button" role="tab" aria-controls="provinsi-tab-pane" aria-selected="true">Provinsi</button>
     </li>
     <li class="nav-item" role="presentation">
         <button class="nav-link" id="kota-tab" data-bs-toggle="tab" data-bs-target="#kota-tab-pane" type="button" role="tab" aria-controls="kota-tab-pane" aria-selected="false">Kota</button>
     </li>

 </ul>
 <div class="tab-content" id="myTabContent">
     <div class="tab-pane fade show active" id="provinsi-tab-pane" role="tabpanel" aria-labelledby="provinsi-tab" tabindex="0">
         <div class="table-responsive mt-3">
             <table class="table table-hover table-bordered dataTable">
                 <thead class="bg-primary-subtle">
                     <th class="text-center">#</th>
                     <th class="text-center">Nama Provinsi</th>
                 </thead>
                 <tbody>
                     <?php $no = 1; ?>
                     <?php foreach ($provinsi as $prov) : ?>
                         <tr class="text-center">
                             <td><?= $no++; ?></td>
                             <td><?= ucwords($prov->provinsi); ?></td>
                         </tr>
                     <?php endforeach; ?>
                 </tbody>
             </table>
         </div>
     </div>
     <div class="tab-pane fade" id="kota-tab-pane" role="tabpanel" aria-labelledby="kota-tab" tabindex="0">
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
     </div>
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