<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<h1 class="mt-4 mb-4">User Menu</h1>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></button>
            </div>
            <div class="card-body">
                <div id="tableData"></div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
<script>
    function getData() {
        $.ajax({
            url: '/user/getData',
            dataType: 'json',
            beforeSend: function() {
                $('#tableData').hide();
            },
            success: function(response) {
                if (response.error) {
                    if (response.error.logout) {
                        window.location.href = response.error.logout
                    }
                } else {
                    $('#tableData').show();
                    $('#tableData').html(response.data);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    $(document).ready(function() {
        getData();
    })
</script>
<?= $this->endSection(); ?>