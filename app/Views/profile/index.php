<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<h1 class="mt-4 mb-4">Provinsi & Kota Menu</h1>

<div class="row justify-content-center">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary btn-sm" id="btnPassword">Change Password</button>
            </div>
            <div class="card-body">
                <div id="tableData"></div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>