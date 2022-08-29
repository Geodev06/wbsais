$(function () {
    $('#table-recent tbody').on('click', 'tr', function () {
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
        beforeSend: function () {

        }
    }).done(function (data) {
        if (data.status == 200) {
            load_chart(data.details)
        }
    }).fail(function (e) {
        $('#msg-error').text(e.responseJSON.message);
        $('#alert-modal').modal('toggle');
    })
}

getdata()

$('#exp-card').on('click', function () {
    $('#exp-modal').modal('toggle')
})
$('#critical-card').on('click', function () {
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
        beforeSend: function () {

        }
    }).done(function (data) {
        if (data.status == 200) {

            const sumqty = data.details.reduce((acc, obj) => {
                return acc + parseFloat(obj.qty)
            }, 0)

            load_inventory_composition(data.details, sumqty)
        }
    }).fail(function (e) {
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
        beforeSend: function () {

        }
    }).done(function (data) {
        if (data.status == 200) {
            console.log(data)
            top_products_qtyChart(data.details)
        }
    }).fail(function (e) {
        $('#msg-error').text(e.responseJSON.message);
        $('#alert-modal').modal('toggle');
    })
}
getTopProduct_chartdata()