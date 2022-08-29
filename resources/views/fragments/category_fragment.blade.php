<div class="container">
    <div class="row">
        <div class="col-sm-12 p-5 col-lg-10 col-md-12">
            <h1 class="fs-4 card-title fw-bolder mb-4 mt-4">Product categories </h1>
            <table id="category-table" class="display nowrap w-100 table-striped">
                <thead>
                    <tr style="height: 10px;">
                        <th>id</th>
                        <th>Category</th>
                        <th>Operation</th>
                    </tr>
                </thead>
            </table>
            <hr>
            <form method="POST" autocomplete="off" id="category-form">
                @csrf
                <div class="mb-1 w-50">
                    <label for="validationCustom01cat" class="form-label text-muted c-fs">Category</label>
                    <div class="input-group">
                        <div class="input-group-text" id="basicAddon"><i class="bx bx-category"></i></div>
                        <input type="text" name="category" placeholder="Category" id="validationCustom01cat" class="form-control" />
                    </div>
                    <span class="error-text error_category"></span>
                </div>
                <div class="mt-4">
                    <button type="submit" class="y-btn w-25 d-flex align-items-center">
                        <i class="bx bx-save fs-4 me-1"></i> Save
                    </button>
                </div>
            </form>
            <!-- Error modal -->
            <div class="modal fade" id="alert-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0">
                        <div class=" flex-alert-container">
                            <div class="flex-alert-header p-5 rounded-left">
                                <i class="bx bx-x-circle mx-1 text-danger" style="font-size: 5em;"></i>
                            </div>
                            <div class="flex-alert-body bg-white p-5">
                                <h1 class="fs-3 card-title">Login Error</h1>
                                <span id="msg-error" style="font-size: 13px;" class="text-muted">Error</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End -->
            <script type="text/javascript">
                function load_categories() {
                    return $.ajax({

                        url: "{{ route('category.get') }}",
                        type: 'get',
                        dataType: 'json',
                        beforeSend: function() {}
                    }).done(function(data) {
                        //  $('#loader').hide()
                        //  console.log(data.products)
                        let category_data = data.categories.map(({
                            id,
                            category
                        }) => [id, category])

                        categoryTables.clear().draw()

                        for (let i = 0; i < category_data.length; i++) {
                            var operations_cat = '<a class="btn-edit" data-id="' + category_data[i][0] + '" data-cat="' + category_data[i][1] + '"> <i class="bx bx-edit me-1"></i></a>'
                            categoryTables.row.add([category_data[i][0], category_data[i][1], operations_cat]).draw()
                        }
                    }).fail(function(e) {
                        $('#msg-error').text(e.responseJSON.message);
                        $('#alert-modal').modal('toggle');
                    })
                }

                var categoryTables = $('#category-table').DataTable({
                    'lengthMenu': [
                        [5, 10, 15, 20, 50, -1],
                        [5, 10, 15, 20, 50, 'All'],
                    ],
                    'order': [
                        [0, 'desc']
                    ]
                })

                load_categories()
                $(function() {
                    //store category
                    $('#category-form').on('submit', function(e) {
                        e.preventDefault()
                        $.ajax({
                            url: "{{ route('category.store') }}",
                            type: 'post',
                            data: new FormData(this),
                            dataType: 'json',
                            processData: false,
                            contentType: false,
                            beforeSend: function() {
                                $('.error-text').text('');
                                $('#category-form :input').prop("disabled", true);
                            },
                            success: function(data) {
                                $('#category-form :input').prop("disabled", false);
                                if (data.status == 0) {
                                    $.each(data.error, function(prefix, val) {
                                        $('.error_' + prefix).text(val[0]);
                                    })
                                } else {
                                    load_categories()
                                    $('#msg-content').text(data.msg);
                                    $('#alert-modal-success').modal('toggle');
                                    $('#category-form')[0].reset();
                                }
                            }

                        });
                    });

                    //open modal
                    $('#category-table tbody').on('click', 'tr td .btn-edit', function() {
                        $('#e-category').val($(this)[0].dataset.cat)
                        let cat_id = $(this)[0].dataset.id;
                        $('#update-cat').attr('data-id', cat_id);
                        $('#delete-cat').attr('data-id', cat_id);
                        $('#edit-category-modal').modal('toggle')
                    })

                    $('#delete-cat').on('click', function() {
                        let temp_uri = "{{ route('category.destroy',':id') }}";
                        $.ajax({
                            url: temp_uri.replace(':id', $(this)[0].dataset.id),
                            type: 'post',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: 'json',
                            beforeSend: function() {},
                            success: function(data) {
                                if (data.status == 200) {
                                    $('#alert-modal-success').modal('toggle')
                                    $('#msg-content').text(data.msg)
                                    load_categories()
                                }
                            },
                            error: function(err) {
                                console.log(err)
                            }

                        });
                    })

                    $('#edit-category-form').on('submit', function(e) {
                        e.preventDefault()
                        var temp_uri = "{{ route('category.update',':id') }}";
                        $.ajax({
                            url: temp_uri.replace(':id', $('#update-cat')[0].dataset.id),
                            type: 'post',
                            data: new FormData(this),
                            dataType: 'json',
                            processData: false,
                            contentType: false,
                            beforeSend: function() {
                                $('.error-text').text('');
                                $('#edit-category-form :input').prop("disabled", true);
                            },
                            success: function(data) {
                                $('#edit-category-form :input').prop("disabled", false);
                                if (data.status == 0) {
                                    $.each(data.error, function(prefix, val) {
                                        $('.edit_error_' + prefix).text(val[0]);
                                    })
                                } else {
                                    $('#edit-category-form')[0].reset();
                                    $('#msg-content').text(data.msg)
                                    load_categories()
                                    $('#edit-category-modal').modal('toggle');
                                    $('#alert-modal-success').modal('toggle');
                                }
                            }

                        });
                    })

                })
            </script>
        </div>
    </div>
</div>

<!-- Update product modal -->
<div class="modal fade" id="edit-category-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- form -->
                            <form method="POST" autocomplete="off" id="edit-category-form" class="p-5">
                                @csrf
                                <h1 class="fs-4 card-title fw-bolder mb-4">Update Category </h1>
                                <div class="mb-1">
                                    <label for="e-product-name" class="form-label text-muted c-fs">Category name</label>
                                    <div class="input-group">
                                        <div class="input-group-text" id="basicAddon1"><i class="bx bx-info-square"></i></div>
                                        <input id="e-category" type="text" name="category" placeholder="Category" class="form-control" />
                                    </div>
                                    <span class="error-text edit_error_category"></span>
                                </div>
                                <div class="mt-4 d-flex">
                                    <button id="update-cat" type="submit" class="y-btn d-flex align-items-center me-2 w-50">
                                        <i class="bx bx-check fs-4 me-1"></i> Save changes
                                    </button>
                                    <button id="delete-cat" type="button" data-bs-dismiss="modal" class="n-btn d-flex align-items-center me-2 w-50">
                                        <i class="bx bx-x fs-4 me-1"></i> Delete
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End modal -->
<!-- Success modal -->
<div class="modal fade" id="alert-modal-success" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class=" flex-alert-container">
                <div class="flex-alert-header p-5 rounded-left">
                    <i class="bx bx-check mx-1 text-success" style="font-size: 5em;"></i>
                </div>
                <div class="flex-alert-body bg-white p-5">
                    <h1 class="fs-3 card-title">Success!</h1>
                    <span id="msg-content" style="font-size: 13px;" class="text-muted"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End modal -->