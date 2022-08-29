<div class="row">
    <div class="col-sm-12 col-md-10 col-lg-6 mt-5">
        <h1 class="fw-bold">Analysis</h1>
        <p class="text-primary" style="font-size: 13px;">Analysis is made using Apriori Algorithm. by Agrawal and Srikant(1994).</p>
        <div class="p-5">
            <div class="d-flex">
                <span class="badge bg-danger me-3">Min. support 0.2</span>
                <span class="badge bg-success me-3">Min. confidence 0.5</span>
                <span class="badge bg-dark">'=>' Association rules</span>
            </div>
            <canvas id="analysisChart" height="100%" width="100%"></canvas>
        </div>
    </div>
    <div class="col-sm-12 col-md-10 col-lg-6 mt-5 mb-5">
        <h1 class="fw-bold">Results</h1>
        <p class="text-muted" style="font-size: 13px;">Base on the analysis. They would like to buy.</p>
        <div class="mb-5">
            <table id="table-analysis" class="display nowrap w-100 table-striped w-100">
                <thead>
                    <tr style="height: 10px;">
                        <th>If-Then</th>
                        <th>Support</th>
                        <th>Confidence</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    function load_chart(chart_data) {

        var labels = [];
        var confidence = []
        var support = []
        for (let i = 0; i < chart_data.length; i++) {
            labels.push(chart_data[i].antecedent + " => " + chart_data[i].consequent)
            confidence.push(chart_data[i].confidence)
            support.push(chart_data[i].support)
        }
        var ctx = document.getElementById('analysisChart')
        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: ['Support'],
                        data: support,
                        backgroundColor: [
                            '#da532c',
                        ],
                        borderColor: [
                            '#ffffff',
                        ],
                        borderWidth: 1
                    },
                    {
                        label: ['Confidence'],
                        data: confidence,
                        backgroundColor: [
                            'dodgerblue',
                        ],
                        borderColor: [
                            '#ffffff',
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Association rule chart',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        })
    }

    var table = $('#table-analysis').DataTable({
        'columns': [{
            'width': '60%'
        }, null, null],
        'lengthMenu': [
            [10, 10, 15, 20, 50, -1],
            [10, 10, 15, 20, 50, 'All'],
        ],
        'order': [
            [0, 'desc']
        ]
    })

    function getdata() {
        $.ajax({
            url: "{{ route('analysis.get') }}",
            type: 'get',
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#loader').css('display', 'flex')
            }
        }).done(function(data) {
            if (data.status == 200) {
                $('#loader').css('display', 'none')
                console.log(data.details.length)
                if (data.details.length < 100) {
                    load_chart(data.details)

                    for (let i = 0; i < data.details.length; i++) {
                        table.row.add([data.details[i].antecedent.join(' , ') + " with " + data.details[i].consequent.join(' , '), data.details[i].support.toFixed(2) + "%", data.details[i].confidence.toFixed(2) + "%"]).draw()
                    }
                }


            }
        }).fail(function(e) {
            $('#msg-error').text(e.responseJSON.message);
            $('#alert-modal').modal('toggle');
        })
    }
    getdata()
</script>