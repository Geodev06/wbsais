<div class="row p-5">
    <h1 class="fs-4 fw-bold ">Register expenses</h1>
    <div class="d-flex">
        <button type="button" class="c-btn d-flex align-items-center me-2" data-bs-toggle="modal" data-bs-target="#add-expenses-modal">
            <i class="bx bx-plus fs-4 me-1"></i> Add expenses
        </button>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 mt-3">

        <!-- expenses datatable -->
        <div id="sub-content">
            <table id="table-expenses" class="display nowrap w-100 table-striped">
                <thead>
                    <tr style="height: 10px;">
                        <th>Record id</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Date created</th>
                        <th>Operation</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="col-sm-12 col-md-12 col-lg-12 mt-3">
        <hr>
        <h1 class="fs-4 fw-bold ">Expenses chart</h1>
        <div class="d-flex mb-4">
            <div class="mb-1">
                <label for="validationCustomdate" class="form-label text-muted c-fs">Start date</label>
                <div class="input-group">
                    <div class="input-group-text" id="basicAddondate"><i class="bx bx-calendar"></i></div>
                    <input type="date" onchange="getdatapoints()" value="<?php echo date('Y-m-d', strtotime('-7 days')); ?>" name="expiry" id="from" class="text-muted form-control" />
                </div>
                <span class="error-text error_date"></span>
            </div>
            <div class="mb-1 ms-auto">
                <label for="validationCustomdate" class="form-label text-muted c-fs">End date</label>
                <div class="input-group">
                    <div class="input-group-text" id="basicAddondate"><i class="bx bx-calendar"></i></div>
                    <input type="date" onchange="getdatapoints()" value="<?php echo date('Y-m-d'); ?>" name="expiry" id="to" class="text-muted form-control" />
                </div>
                <span class="error-text error_date"></span>
            </div>
        </div>
        <div class="chart-div">
            <canvas id="expensesChart" height="60%" width="60%"></canvas>
        </div>
    </div>
</div>
<!-- Add expenses modal -->
<div class="modal fade" id="add-expenses-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <form method="POST" autocomplete="off" id="expenses-form" class="p-5">
                                <h1 class="fs-4 card-title fw-bolder mb-4">Add new expenses </h1>
                                @csrf
                                <div class="mb-1 w-100">
                                    <label for="validationCustom02" class="form-label text-muted c-fs">Expense type</label>
                                    <div class="input-group">
                                        <div class="input-group-text" id="basicAddon1"><i class="bx bx-category"></i></div>
                                        <select type="select" name="type" id="validationCustom02" class="text-muted form-select">
                                            <option value="">None</option>
                                            <option value="Supplier deal">Supplier deal</option>
                                            <option value="Bill">Bill</option>
                                            <option value="Tax">Tax</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <span class="error-text error_supplier"></span>
                                </div>
                                <div class="mb-3 w-100">
                                    <label for="validationCustom01catx" class="form-label text-muted c-fs">Expense description</label>
                                    <div class="input-group">
                                        <div class="input-group-text" id="basicAddon"><i class="bx bx-category"></i></div>
                                        <input type="text" name="description" placeholder="description" id="validationCustom01catx" class="form-control" />
                                    </div>
                                    <span class="error-text error_description"></span>
                                </div>
                                <div class="mb-3 w-100">
                                    <label for="validationCustom01cat" class="form-label text-muted c-fs">Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-text" id="basicAddonx"><i class="bx bx-money"></i></div>
                                        <input type="text" name="amount" placeholder="amount" id="validationCustom01cat" class="form-control" />
                                    </div>
                                    <span class="error-text error_amount"></span>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="c-btn w-50 d-flex align-items-center">
                                        <i class="bx bx-save fs-4 me-1"></i> Save
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

<!-- Edit expenses modal -->
<div class="modal fade" id="edit-expenses-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <form method="POST" autocomplete="off" id="edit-expenses-form" class="p-5">
                                <h1 class="fs-4 card-title fw-bolder mb-4">Edit expenses record </h1>
                                @csrf
                                <div class="mb-1 w-100">
                                    <label for="cbType" class="form-label text-muted c-fs">Expense type</label>
                                    <div class="input-group">
                                        <div class="input-group-text" id="basicAddon1"><i class="bx bx-category"></i></div>
                                        <select type="select" name="type" id="cbType" class="text-muted form-select">
                                            <option value="None">None</option>
                                            <option value="Supplier deal">Supplier deal</option>
                                            <option value="Bill">Bill</option>
                                            <option value="Tax">Tax</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 w-100">
                                    <label for="txtdesc" class="form-label text-muted c-fs">Expense description</label>
                                    <div class="input-group">
                                        <div class="input-group-text" id="basicAddon"><i class="bx bx-category"></i></div>
                                        <input type="text" name="description" placeholder="description" id="txtdesc" class="form-control" />
                                    </div>
                                    <span class="error-text error_editdescription"></span>
                                </div>
                                <div class="mb-3 w-100">
                                    <label for="txtamount" class="form-label text-muted c-fs">Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-text" id="basicAddonx"><i class="bx bx-money"></i></div>
                                        <input type="text" name="amount" placeholder="amount" id="txtamount" class="form-control" />
                                    </div>
                                    <span class="error-text error_editamount"></span>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="c-btn w-50 d-flex align-items-center">
                                        <i class="bx bx-save fs-4 me-1"></i> Save
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
<!-- confrmation modal -->
<div class="modal fade" id="confirmation-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="container">
                <div class=" bg-white p-5">
                    <h1 class="fs-3 card-title">Prompt?</h1>
                    <span id="msg-error" style="font-size: 13px;" class="text-muted">Delete this record now?</span>
                    <ul style="font-size: 13px;">
                        <li>record id: <span id="r-id"></span></li>
                        <li>expenses type: <span id="r-type"></span></li>
                        <li>date created: <span id="r-date"></span></li>
                    </ul>
                    <div class="mt-4 d-flex">
                        <button type="button" id="confirm-delete" class="y-btn d-flex align-items-center me-2 w-50">
                            <i class="bx bx-check fs-4 me-1"></i> Yes
                        </button>
                        <button type="button" data-bs-dismiss="modal" class="n-btn d-flex align-items-center me-2 w-50">
                            <i class="bx bx-x fs-4 me-1"></i> No
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End modal -->
<script type="text/javascript">
    var table = $('#table-expenses').DataTable({
        'lengthMenu': [
            [10, 10, 15, 20, 50, -1],
            [10, 10, 15, 20, 50, 'All'],
        ],
        'order': [
            [0, 'desc']
        ]
    })

    function loadExpenses() {

        $.ajax({
            url: "{{ route('expenses.get')}}",
            type: 'get',
            dataType: 'json',
            contentType: false,
            processData: false,
            beforeSend: function() {

            }
        }).done(function(data) {
            table.clear().draw()
            for (let i = 0; i < data.data.length; i++) {
                var operations = '<a class="btn-edit" data-id="' + data.data[i].id + '" data-type="' + data.data[i].type + '" data-description="' + data.data[i].description + '" data-amount="' + data.data[i].amount + '"><i class="bx bx-trash"></i></a><a class="btn-delete" data-id="' + data.data[i].id + '" data-type="' + data.data[i].type + '" data-date="' + data.data[i].date + '"><i class="bx bx-trash"></i></a>'
                table.row.add([data.data[i].id, data.data[i].type, data.data[i].description, data.data[i].amount, data.data[i].date, operations]).draw()
            }
        })
    }

    loadExpenses()

    $('#table-expenses tbody').on('click', 'tr td .btn-delete', function() {
        $('#confirmation-modal').modal('show')
        $('#r-id').text($(this)[0].dataset.id)
        $('#r-type').text($(this)[0].dataset.type)
        $('#r-date').text($(this)[0].dataset.date)

        $('#confirm-delete').attr('data-id', $(this)[0].dataset.id)
    })

    $('#table-expenses tbody').on('click', 'tr td .btn-edit', function() {
        $('#edit-expenses-modal').modal('show')

        $('#cbType').val($(this)[0].dataset.type)
        $('#txtdesc').val($(this)[0].dataset.description)
        $('#txtamount').val($(this)[0].dataset.amount)
        $('#cbType').attr('data-id', $(this)[0].dataset.id)
    })

    $(document).ready(function() {

        $('#confirm-delete').on('click', function(e) {
            e.preventDefault()

            let temp_uri = "{{ route('expenses.destroy',':id') }}"
            $.ajax({
                url: temp_uri.replace(':id', $(this)[0].dataset.id),
                type: 'get',
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function() {}
            }).done(function(data) {
                $('#msg-content').text(data.msg);
                $('#alert-modal-success').modal('toggle');
                $('#confirmation-modal').modal('hide')
                loadExpenses()
            }).fail(function(err) {
                alert(err)
            })
        })

        $('#edit-expenses-form').on('submit', function(e) {
            e.preventDefault()
            let temp_uri = "{{ route('expenses.update', ':id') }}"
            $.ajax({
                url: temp_uri.replace(':id', $('#cbType')[0].dataset.id),
                type: 'post',
                data: new FormData(this),
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('.error-text').text('');
                    $('#edit-expenses-form :input').prop("disabled", true);
                },
                success: function(data) {
                    $('#edit-expenses-form :input').prop("disabled", false);
                    if (data.status == 401) {
                        $.each(data.error, function(prefix, val) {
                            $('.error_edit' + prefix).text(val[0]);
                        })
                    } else {
                        $('#msg-content').text(data.msg);
                        $('#alert-modal-success').modal('toggle');
                        $('#edit-expenses-form')[0].reset();
                        $('#edit-expenses-modal').modal('hide')
                        loadExpenses()
                    }
                }

            });
        })

        $('#expenses-form').on('submit', function(e) {
            e.preventDefault()
            $.ajax({
                url: "{{ route('expense.store')}}",
                type: 'post',
                data: new FormData(this),
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $('.error-text').text('');
                    $('#expenses-form :input').prop("disabled", true);
                },
                success: function(data) {
                    $('#expenses-form :input').prop("disabled", false);
                    if (data.status == 401) {
                        $.each(data.error, function(prefix, val) {
                            $('.error_' + prefix).text(val[0]);
                        })
                    } else {
                        $('#msg-content').text(data.msg);
                        $('#alert-modal-success').modal('toggle');
                        $('#expenses-form')[0].reset();
                        $('#add-expenses-modal').modal('hide')
                        loadExpenses()
                        getdatapoints()
                    }
                }

            });
        })
    })

    function load_chart(expenses_data, revenue_data) {

        var exp_labels = [];
        var exp_data_points = []

        var revenue_datapoints = []

        for (let i = 0; i < expenses_data.length; i++) {
            exp_labels.push(expenses_data[i].date)
            exp_data_points.push(expenses_data[i].amount)
        }
        for (let i = 0; i < revenue_data.length; i++) {
            revenue_datapoints.push(revenue_data[i].amount)
        }
        var ctx = document.getElementById('expensesChart')
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: exp_labels,
                datasets: [{
                    label: ['Expenses'],
                    data: exp_data_points,
                    backgroundColor: [
                        'dodgerblue',
                    ],
                    borderColor: [
                        'dodgerblue'
                    ],
                    borderWidth: 1
                }, {
                    label: ['Revenue'],
                    data: revenue_datapoints,
                    backgroundColor: [
                        'crimson',
                    ],
                    borderColor: [
                        'crimson'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        })
    }

    function getdatapoints() {

        let from = document.getElementById('from').value
        let to = document.getElementById('to').value

        $.ajax({
            url: "{{ route('expenses_summary.get')}}",
            type: 'get',
            dataType: 'json',
            data: {
                _token: '{{ csrf_token() }}',
                from: from,
                to: to
            },
            beforeSend: function() {}
        }).done(function(data) {
            console.log(data.expenses_datapoints)
            console.log(data.revenue_datapoints)
            if (data.status == 200) {
                $('#expensesChart').remove();
                $('.chart-div').append('<canvas id="expensesChart" height="60%" width="60%"></canvas>')
                load_chart(data.expenses_datapoints, data.revenue_datapoints)
            }
        }).fail(function(e) {
            $('#msg-error').text(e.responseJSON.message);
            $('#alert-modal').modal('toggle');
        })
    }

    getdatapoints()
</script>