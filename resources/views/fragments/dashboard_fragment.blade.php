<style>
    .transaction_count {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 200px;
    }

    .p-font {
        font-size: 13px;
    }

    .btn-overview {
        font-size: 10px;
        border-radius: 30px;
        outline: none;
        width: 100px;
        display: flex;
        justify-content: center;
        border: 1.5px solid rgb(89, 89, 89);
        color: black;
        background-color: transparent;
        height: auto;
        padding: 8px;
        margin-left: 2px;
        cursor: pointer;
    }

    .btn-overview:hover {
        color: white;
        background-color: rgb(50, 50, 50);
    }

    .overview-active {
        color: white;
        background-color: rgb(50, 50, 50);
    }
</style>
<div class="row p-5">
    <div class="col-md-6 col-sm-12 col-lg-3 p-2">
        <div class="card-body border rounded d-flex">
            <div class="col">
                <div class="icon-container" style="background-color: dodgerblue;">
                    <i class="bx bx-box text-white nav_icon"></i>
                </div>
            </div>
            <div class="col text-center text-mint">
                <h2 class="mb-0">{{ $data['inventory']}}</h1>
                    <P class="text-muted" style="font-size: 13px;">Inventory</P>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12 col-lg-3 p-2">
        <div class="card-body border rounded d-flex">
            <div class="col">
                <div class="icon-container" style="background-color: rgb(25, 171, 103);">
                    <i class="bx bx-package text-white nav_icon"></i>
                </div>
            </div>
            <div class="col text-center">
                <h2 class="mb-0">{{ $data['supplier']}}</h1>
                    <P class="text-muted" style="font-size: 13px;">Supplier</P>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12 col-lg-3 p-2" id="exp-card">
        <div class="card-body border rounded d-flex">
            <div class="col me-2">
                <div class="icon-container" style="background-color: rgb(219, 19, 62);">
                    <i class="bx bx-calendar-x text-white nav_icon"></i>
                </div>
            </div>
            <div class="col text-center">
                <h2 class="mb-0">{{ $data['exp_items_no']}} </h2>
                <P class="text-muted" style="font-size: 13px;">Expiry items</P>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12 col-lg-3 p-2" id="critical-card">
        <div class="card-body border rounded d-flex">
            <div class="col">
                <div class="icon-container" style="background-color: #F5DF4D;">
                    <i class="bx bx-error text-white nav_icon"></i>
                </div>
            </div>
            <div class="col text-center">
                <h2 class="mb-0">{{ $data['critical_items']}}</h2>
                <P class="text-muted" style="font-size: 13px;">Critical items</P>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12 col-lg-6 p-2">
        <div class="card-body border rounded d-flex">
            <div class="col">
                <div class="icon-container" style="background-color: #9f00a7;">
                    <i class="bx bx-coin-stack text-white nav_icon"></i>
                </div>
            </div>
            <div class="col text-center">
                <h2 class="mb-0"><span>&#8369 </span>{{ $data['stock_value']}}</h2>
                <P class="text-muted" style="font-size: 13px;">Overall stock value</P>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12 col-lg-6 p-2">
        <div class="card-body border rounded d-flex">
            <div class="col">
                <div class="icon-container" style="background-color:  #D2386C;">
                    <i class="bx bx-money text-white nav_icon"></i>
                </div>
            </div>
            <div class="col text-center">
                <h2 class="mb-0"><span>&#8369 </span>{{ $data['daily_rev']}} </h2>
                <P class="text-muted" style="font-size: 13px;">Daily revenue
                    @if($data['yesterday_rev'] > $data['daily_rev'])
                    <i class="bx bx-down-arrow-alt text-danger"></i>
                    @else
                    <i class="bx bx-up-arrow-alt text-success"></i>
                    @endif
                </P>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12 col-lg-6">
        <div class="card-body border rounded">
            <h1 class="fs-6 fw-bolder">Recent transactions.</h1>
            <table class="table table-striped" style="font-size: 11px" id="table-recent">
                <thead>
                    <th>Transaction id</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>No. of items</th>
                </thead>
                <tbody>
                    @foreach($recent_transactions as $transaction)
                    <tr style="cursor:pointer" data-id="{{$transaction->transaction_id}}">
                        <td>{{$transaction->transaction_id}}</td>
                        <td class="text-success">&#8369 {{$transaction->amount}}</td>
                        <td>{{$transaction->created_at->format(' jS, \of F, Y  g:i  A')}}</td>
                        <td><b class="text-secondary">{{$transaction->no_of_items}}</b></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- top grossing chart -->
    <div class="col-md-6 col-sm-12 col-lg-6">
        <div class="card-body border rounded">
            <h1 class="fs-6 fw-bolder mb-2">Top grossing products. all time</h1>
            <canvas id="topGrossingChart" height="100%" width="100%"></canvas>
        </div>
    </div>
    <!-- end chart -->

    <div class="col-md-4 col-sm-12 col-lg-4 mt-2">
        <div class="card-body border rounded">
            <h1 class="fs-6 fw-bolder mb-2">Inventory composition</h1>
            <canvas id="inventoryCompositionChart" height="100%" width="100%"></canvas>
        </div>
    </div>

    <div class="col-md-4 col-sm-12 col-lg-4 mt-2">
        <div class="card-body border rounded">
            <h1 class="fs-6 fw-bolder mb-2">No. of transactions today.</h1>
            <div class="transaction_count">
                <h1 style="font-size: 6em; font-weight: 600; opacity: 0.75">
                    {{ $data['transaction_t']}}
                </h1>
            </div>
            <div class="d-flex align-items-center p-3">
                @if($data['transaction_t'] > $data['transaction_y'])
                <i class="bx bx-up-arrow-alt text-success fs-1"></i>
                <p class="p-font">{{$data['change_pct']}}% more than yesterday</p>
                @else
                <i class="bx bx-down-arrow-alt text-danger fs-1"></i>
                <p class="p-font">{{$data['change_pct']}}% less than yesterday</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12 col-lg-4 mt-2">
        <div class="card-body border rounded">
            <h1 class="fs-6 fw-bolder mb-2">Top 10 products quantity(Monthly)</h1>
            <canvas id="topproductsqtyChart" height="100%" width="100%"></canvas>
        </div>
    </div>

    <div class="col-md-12 col-sm-12 col-lg-12 mt-2">
        <div class="card-body border rounded">
            <h1 class="fs-6 fw-bolder mb-2">Sales overview</h1>
            <div class="d-flex">
                <span class="btn-overview overview-active" id="btn-weekly">Weekly</span>
                <span class="btn-overview" id="btn-monthly">Monthly</span>
                <span class="btn-overview" id="btn-annually">Annually</span>
            </div>
            <div id="overview-div">
                <canvas id="overviewChart" height="50%" width="100%"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-sm-12 col-lg-12 mt-2">
        <div class="card-body border rounded">
            <h1 class="fs-6 fw-bolder mb-2">Transactions as of {{ date('jS, \of F, Y  g:i  A')}}</h1>
            <canvas id="transactionsChart" height="30%" width="100%"></canvas>
        </div>
    </div>
</div>

<!-- Error modal -->
<div class="modal fade" id="alert-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class=" flex-alert-container">
                <div class="flex-alert-header p-5 rounded-left">
                    <i class="bx bx-x-circle mx-1 text-danger" style="font-size: 5em;"></i>
                </div>
                <div class="flex-alert-body bg-white p-5">
                    <h1 class="fs-3 card-title"> Error</h1>
                    <span id="msg-error" style="font-size: 13px;" class="text-muted">Error</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End -->

<!-- exp-card modal -->
<div class="modal fade" id="exp-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="container">
                <div class="-body bg-white p-5">
                    @if(count($data['exp_items']) > 0)

                    <h1 class="fs-6 card-title">Products that will expire soon.</h1>
                    <table class="w-100">
                        <tr>
                            <th>Product id</th>
                            <th>Product name</th>
                            <th>Expiration date</th>
                        </tr>
                        @foreach($data['exp_items'] as $exp_items)
                        <tr>
                            <td>{{$exp_items->id}}</td>
                            <td>{{$exp_items->product_name}}</td>
                            <td>{{$exp_items->expiry}}</td>
                        </tr>
                        @endforeach
                    </table>
                    @else
                    <p>No products that will expire soon.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End -->

<!-- critical items-card modal -->
<div class="modal fade" id="critical-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="container">
                <div class="-body bg-white p-5">
                    @if(count($data['critical_items_list']) > 0)

                    <h1 class="fs-6 card-title">Products that has quantity below 10</h1>
                    <table class="w-100">
                        <tr style="font-size: 13px">
                            <th>Product id</th>
                            <th>Product name</th>
                            <th>Expiration date</th>
                            <th>Price</th>
                            <th>Remaining qty.</th>
                        </tr>
                        @foreach($data['critical_items_list'] as $item)
                        <tr style="font-size: 13px">
                            <td>{{$item->id}}</td>
                            <td>{{$item->product_name}}</td>
                            <td>{{$item->expiry}}</td>
                            <td>{{$item->price}}</td>
                            <td class="text-danger"><strong>{{$item->qty}}</strong></td>
                        </tr>
                        @endforeach
                    </table>
                    @else
                    <p>No products that has critical stock quantity.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End -->
<script type="text/javascript">
    $(function() {

        $('#table-recent tbody').on('click', 'tr', function() {
            let link = "{{ route('recent.get',':tid')}}"
            window.open(link.replace(':tid', $(this)[0].dataset.id))
        })
    })

    function load_chart(chart_data) {

        var labels = [];
        var data_points = []
        for (let i = 0; i < chart_data.length; i++) {
            labels.push(chart_data[i].product_name)
            data_points.push(chart_data[i].total)
        }
        var ctx = document.getElementById('topGrossingChart')
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: ['Quantity sold'],
                    data: data_points,
                    backgroundColor: [
                        'rgb(230, 0, 92)',
                    ],
                    borderColor: [
                        'transparent'
                    ],
                    borderWidth: 1
                }]
            },
            plugins: [ChartDataLabels],
            options: {
                plugins: {
                    datalabels: {
                        display: true,
                        formatter: (value, ctx) => {
                            return ctx.chart.data.datasets[0].data[ctx.dataIndex]
                        },
                        backgroundColor: 'rgb(90,90,90)',
                        color: 'white',
                        borderRadius: 3,
                        font: {
                            size: 8
                        }
                    }
                }
            }
        })
    }

    function getdata() {
        $.ajax({
            url: "{{ route('top_products.get') }}",
            type: 'get',
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function() {

            }
        }).done(function(data) {
            if (data.status == 200) {
                load_chart(data.details)
            }
        }).fail(function(e) {
            $('#msg-error').text(e.responseJSON.message);
            $('#alert-modal').modal('toggle');
        })
    }

    getdata()

    $('#exp-card').on('click', function() {
        $('#exp-modal').modal('toggle')
    })
    $('#critical-card').on('click', function() {
        $('#critical-modal').modal('toggle')
    })

    function dynamicColors() {
        var r = Math.floor(Math.random() * 255)
        var g = Math.floor(Math.random() * 255)
        var b = Math.floor(Math.random() * 255)
        return "rgb(" + r + "," + g + "," + b + ")"
    }

    function load_inventory_composition(chart_data, sumqty) {

        var product_labels = [];
        var product_data_points = []
        var bgColors = []
        for (let i = 0; i < chart_data.length; i++) {
            product_labels.push(chart_data[i].category)
            product_data_points.push(((chart_data[i].qty / sumqty) * 100).toFixed(2))
            bgColors.push(dynamicColors())
        }
        var ctx_inv = document.getElementById('inventoryCompositionChart')
        var chart_inv = new Chart(ctx_inv, {
            type: 'doughnut',
            data: {
                labels: product_labels,
                datasets: [{
                    label: ['Product quantities'],
                    data: product_data_points,
                    backgroundColor: bgColors,
                    borderColor: [
                        'transparent'

                    ],
                    borderWidth: 1
                }]
            },

            plugins: [ChartDataLabels],
            options: {
                plugins: {
                    datalabels: {
                        display: true,
                        formatter: (value, ctx) => {
                            return ctx.chart.data.datasets[0].data[ctx.dataIndex] + "%"
                        },
                        backgroundColor: 'dodgerblue',
                        color: 'white',
                        borderRadius: 3,
                        font: {
                            size: 8
                        }
                    }
                }
            }
        })
    }

    function get_inventory_composition_data() {
        $.ajax({
            url: "{{ route('products_composition.get') }}",
            type: 'get',
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function() {

            }
        }).done(function(data) {
            if (data.status == 200) {

                const sumqty = data.details.reduce((acc, obj) => {
                    return acc + parseFloat(obj.qty)
                }, 0)

                load_inventory_composition(data.details, sumqty)
            }
        }).fail(function(e) {
            $('#msg-error').text(e.responseJSON.message);
            $('#alert-modal').modal('toggle');
        })
    }

    get_inventory_composition_data()

    function top_products_qtyChart(chart_data, sumqty) {

        var t_product_labels = [];
        var t_product_data_points = []
        var t_product_rem_data_points = []
        for (let i = 0; i < chart_data.length; i++) {
            t_product_labels.push(chart_data[i].product_name)
            t_product_data_points.push(chart_data[i].total)
            t_product_rem_data_points.push(chart_data[i].rem_qty)
        }
        var ctx_t = document.getElementById('topproductsqtyChart')
        var chart_t = new Chart(ctx_t, {
            type: 'bar',
            data: {
                labels: t_product_labels,
                datasets: [{
                        label: ['Sold quantity'],
                        data: t_product_data_points,
                        backgroundColor: [
                            'rgb(92, 214, 92)',
                        ],
                        borderColor: [
                            'transparent'

                        ],
                        borderWidth: 1
                    },
                    {
                        label: ['Remaining quantity'],
                        data: t_product_rem_data_points,
                        backgroundColor: [
                            'rgb(134, 0, 179)',
                        ],
                        borderColor: [
                            'transparent'

                        ],
                        borderWidth: 1
                    }
                ]
            },

            plugins: [ChartDataLabels],
            options: {
                indexAxis: 'y',
                plugins: {
                    datalabels: {
                        display: true,
                        backgroundColor: 'dodgerblue',
                        color: 'white',
                        borderRadius: 3,
                        font: {
                            size: 8
                        }
                    }
                }
            }
        })
    }

    function getTopProduct_chartdata() {

        $.ajax({
            url: "{{ route('productsqtydata.get') }}",
            type: 'get',
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function() {

            }
        }).done(function(data) {
            if (data.status == 200) {

                top_products_qtyChart(data.details)
            }
        }).fail(function(e) {
            $('#msg-error').text(e.responseJSON.message);
            $('#alert-modal').modal('toggle');
        })
    }

    getTopProduct_chartdata()

    //overview chart
    function load_overview_chart(chart_data) {

        var o_product_labels = [];
        var o_product_data_points = []

        for (let i = 0; i < chart_data.length; i++) {
            o_product_labels.push(chart_data[i].date)
            o_product_data_points.push(chart_data[i].total)

        }
        var ctx_o = document.getElementById('overviewChart')
        var chart_o = new Chart(ctx_o, {
            type: 'line',
            data: {
                labels: o_product_labels,
                datasets: [{
                    label: ['Sales revenue'],
                    data: o_product_data_points,
                    backgroundColor: [
                        '#6B5876',
                    ],
                    borderColor: [
                        'transparent'

                    ],
                    borderWidth: 1,
                    fill: true,
                    tension: 0.5
                }]
            },

            plugins: [ChartDataLabels],
            options: {
                scales: {
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Sales amount'
                        }
                    }
                },
                responsive: true,
                plugins: {
                    datalabels: {
                        display: true,
                        formatter: (value, ctx) => {
                            return "\u20B1 " + ctx.chart.data.datasets[0].data[ctx.dataIndex]
                        },
                        backgroundColor: 'crimson',
                        color: 'white',
                        borderRadius: 3,
                        font: {
                            size: 8
                        }
                    }
                }
            }
        })
    }

    function overview_data(category) {

        var temp_uri = "{{ route('overview.get', ':category') }}";
        $.ajax({
            url: temp_uri.replace(':category', category),
            type: 'get',
            dataType: 'json',
            beforeSend: function() {}
        }).done(function(data) {
            if (data.status == 200) {
                console.log(data)
                load_overview_chart(data.data)
            }
        }).fail(function(e) {
            $('#msg-error').text(e.responseJSON.message);
            $('#alert-modal').modal('toggle');
        })
    }

    //default weekly
    overview_data(7)

    $('#btn-weekly').on('click', function(e) {
        e.preventDefault()
        $('#btn-weekly').addClass('overview-active')
        $('#btn-annually').removeClass('overview-active')
        $('#btn-monthly').removeClass('overview-active')
        $('#overviewChart').remove();
        $('#overview-div').html('<canvas id="overviewChart" height="50%" width="100%"></canvas>')
        overview_data(7)
    })

    $('#btn-monthly').on('click', function(e) {
        e.preventDefault()
        $('#btn-weekly').removeClass('overview-active')
        $('#btn-annually').removeClass('overview-active')
        $('#btn-monthly').addClass('overview-active')
        $('#overviewChart').remove();
        $('#overview-div').html('<canvas id="overviewChart" height="50%" width="100%"></canvas>')
        overview_data(30)
    })

    $('#btn-annually').on('click', function(e) {
        e.preventDefault()
        $('#btn-weekly').removeClass('overview-active')
        $('#btn-annually').addClass('overview-active')
        $('#btn-monthly').removeClass('overview-active')
        $('#overviewChart').remove();
        $('#overview-div').html('<canvas id="overviewChart" height="50%" width="100%"></canvas>')
        overview_data(365)
    })

    //transaction chart
    function load_transactionschart(chart_data) {

        var tr_product_labels = [];
        var tr_product_data_points = []
        for (let i = 0; i < chart_data.length; i++) {
            tr_product_labels.push("ID:" + chart_data[i].transaction_id)
            tr_product_data_points.push(chart_data[i].total)

        }
        var ctx_tr = document.getElementById('transactionsChart')

        //animations 
        const totalDuration = 2000
        const delay = totalDuration / chart_data.length
        const prevY = (ctx_tr) => ctx_tr.index === 0 ?
            ctx_tr.chart.scales.y.getPixelForValue(100) :
            ctx_tr.chart.getDatasetMeta(ctx_tr.datasetIndex).data[ctx_tr.index - 1].getProps(['y'], true).y

        const animation = {
            x: {
                type: 'number',
                easing: 'linear',
                duration: delay,
                from: NaN,
                delay(ctx) {
                    if (ctx.type !== 'data' || ctx.xStarted) {
                        return 0
                    }
                    ctx.xStarted = true
                    return ctx.index * delay;
                }
            },
            y: {
                type: 'number',
                easing: 'linear',
                duration: delay,
                from: prevY,
                delay(ctx) {
                    if (ctx.type !== 'data' || ctx.yStarted) {
                        return 0
                    }
                    ctx.yStarted = true
                    return ctx.index * delay;
                }
            }
        }
        var chart_tr = new Chart(ctx_tr, {
            type: 'line',
            data: {
                labels: tr_product_labels,
                datasets: [{
                    label: ['Transactions'],
                    data: tr_product_data_points,
                    backgroundColor: [
                        '#DD4132',
                    ],
                    borderColor: [
                        '#DD4132'
                    ],
                    borderWidth: 1,
                    stepped: true
                }]
            },

            plugins: [ChartDataLabels],
            options: {
                animation,
                elements: {
                    point: {
                        radius: 0
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        display: true,
                        title: {
                            display: true,
                            text: 'Amount'
                        }
                    },
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Transactions'
                        }
                    }
                },
                responsive: true,
                plugins: {
                    datalabels: {
                        display: true,
                        formatter: (value, ctx) => {
                            return "\u20B1 " + ctx.chart.data.datasets[0].data[ctx.dataIndex]
                        },
                        backgroundColor: 'dodgerblue',
                        color: 'white',
                        borderRadius: 3,
                        font: {
                            size: 8
                        }
                    }
                }
            }
        })
    }

    function load_transactions_chart_data() {

        $.ajax({
            url: "{{ route('transactionschart_data.get') }}",
            type: 'get',
            dataType: 'json',
            beforeSend: function() {}
        }).done(function(data) {
            if (data.status == 200) {
                load_transactionschart(data.details)
            }
        }).fail(function(e) {
            $('#msg-error').text(e.responseJSON.message);
            $('#alert-modal').modal('toggle');
        })
    }

    load_transactions_chart_data()
</script>