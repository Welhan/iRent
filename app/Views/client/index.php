<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<h1 class="mt-4 mb-4">Client List</h1>

<div class="row justify-content-center">
    <div class="col-lg-11">
        <div class="card">
            <div class="card-header">
                <!-- <button class="btn btn-primary btn-sm" id="btnNew"><i class="fas fa-plus"></i></button> -->
                <a href="/newClient" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i></a>
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
            url: '/client/getData',
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
                url: '/client/newClient',
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