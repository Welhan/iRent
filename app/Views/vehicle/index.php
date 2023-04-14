<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<h1 class="mt-4 mb-4">Vehicles Menu</h1>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-primary btn-sm" id="btnNew"><i class="fa-solid fa-plus"></i></button>
            </div>
            <div class="card-body">
                <div id="tableData"></div>
            </div>
        </div>
    </div>
</div>

<div id="viewModal" style="display: none;"></div>

<?= $this->endSection(); ?>

<?= $this->section('javascript'); ?>
<script>
    function getData() {
        $.ajax({
            url: '/vehicle/getData',
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

        $('#btnNew').click(function(e) {
            e.preventDefault();

            $.ajax({
                url: '/vehicle/new',
                dataType: 'json',
                beforeSend: function() {
                    $('.btn').attr('disabled', 'disabled');
                    $('#btnNew').html('<i class="fa fa-spin fa-spinner"></i>');
                },
                success: function(response) {
                    $('.btn').removeAttr('disabled');
                    $('#btnNew').html('<i class="fa-solid fa-arrows-rotate"></i>');
                    if (response.error) {
                        if (response.error.logout) {
                            window.location.href = response.error.logout
                        }
                    } else {
                        $('#viewModal').html(response.data).show();
                        $('#newModal').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        })
    })
</script>
<?= $this->endSection(); ?>