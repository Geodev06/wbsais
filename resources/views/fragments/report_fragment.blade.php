 <style>
     .c-fs {
         font-size: 13px;
     }
 </style>
 <script src="{{ asset('chartjs/package/dist/chart.js')}}"></script>
 <script src="{{ asset('assets/dataTables/datatables.js') }}"></script>
 <link rel="stylesheet" href="{{ asset('assets/dataTables/datatables.min.css') }}" />
 <link rel="stylesheet" href="{{ asset('assets/dataTables/datatables.css') }}" />
 <script src="{{ asset('assets/dataTables/datatables.min.js') }}"></script>
 <div class="row mb-5">
     <div class="col-sm-12 col-lg-6 col-md-12 mt-5">
         <p class="fw-bold">Generate report</p>
         <div class="d-flex mb-4">
             <div class="mb-1">
                 <label for="validationCustomdate" class="form-label text-muted c-fs">Start date</label>
                 <div class="input-group">
                     <div class="input-group-text" id="basicAddondate"><i class="bx bx-calendar"></i></div>
                     <input type="date" onchange="loadTable()" value="<?php echo date('Y-m-d'); ?>" name="expiry" id="from" class="text-muted form-control" />
                 </div>
                 <span class="error-text error_date"></span>
             </div>
             <div class="mb-1 ms-auto">
                 <label for="validationCustomdate" class="form-label text-muted c-fs">End date</label>
                 <div class="input-group">
                     <div class="input-group-text" id="basicAddondate"><i class="bx bx-calendar"></i></div>
                     <input type="date" onchange="loadTable()" value="<?php echo date('Y-m-d'); ?>" name="expiry" id="to" class="text-muted form-control" />
                 </div>
                 <span class="error-text error_date"></span>
             </div>
         </div>
         <div>
             <!-- table -->
             <table id="table-report" class="display nowrap w-100 table-striped">
                 <thead>
                     <tr style="height: 10px;">
                         <th>Total Revenue</th>
                         <th>Date</th>
                     </tr>
                 </thead>

             </table>
             <!-- end table -->
         </div>
     </div>

     <div class="col-sm-12 col-lg-6 col-md-12 mt-5">
         <p class="fw-bold float-end">Daily Revenue chart</p>
         <div class="mb-1">
             <label for="e-category" class="form-label text-muted c-fs">Pick numbers of past days</label>
             <div class="input-group w-50">
                 <div class="input-group-text" id="basicAddon1"><i class="bx bx-calendar-alt"></i></div>
                 <select id="days" type="select" name="days" class="text-muted form-select" onchange="getdata()">
                     <option value="3">3 days</option>
                     <option value="7">7 days</option>
                     <option value="15">15 days</option>
                     <option value="30">1 month</option>
                     <option value="182">6 months</option>
                     <option value="365">1 year</option>
                     <option value="0">All time</option>
                 </select>
             </div>

         </div>
         <div>
             <!-- chart -->
             <div class="report-div">
                 <canvas id="revenueChart" height="80%" width="100%"></canvas>
             </div>
         </div>
     </div>
 </div>
 <script type="text/javascript">
     var table = $('#table-report').DataTable({
         dom: 'Bfrtip',
         button: ['copy', 'csv', 'excel', 'pdf', 'print'],
         order: [
             [1, 'desc']
         ],
         'lengthMenu': [
             [10, 10, 15, 20, 50, -1],
             [10, 10, 15, 20, 50, 'All'],
         ],
     })

     function loadTable() {
         let from = document.getElementById('from').value;
         let to = document.getElementById('to').value;
         console.log(from)

         let uri = "{{ route('report.get') }}";
         return $.ajax({
             url: uri,
             type: 'post',
             data: {
                 _token: '{{ csrf_token() }}',
                 from: from,
                 to: to
             },
             dataType: 'json',
             beforeSend: function() {
                 $('#loader').css('display', 'flex');
             }
         }).done(function(data) {
             console.log(data.transactions)
             $('#loader').css('display', 'none')
             table.clear().draw()

             for (let i = 0; i < data.transactions.length; i++) {
                 table.row.add(["\u20B1 " + data.transactions[i].amount, data.transactions[i].date, ]).draw()
             }
         })
     }
     loadTable();

     function load_chart(chart_data) {

         var labels = [];
         var data_points = []
         for (let i = 0; i < chart_data.length; i++) {
             labels.push(chart_data[i].date)
             data_points.push(chart_data[i].daily_revenue)
         }
         var ctx = document.getElementById('revenueChart')
         var chart = new Chart(ctx, {
             type: 'line',
             data: {
                 labels: labels,
                 datasets: [{
                     label: ['Daily revenue'],
                     data: data_points,
                     backgroundColor: [
                         '#800080',
                     ],
                     borderColor: [
                         '#800080'
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

     function getdata() {

         let e = document.getElementById('days');
         let value = e.value;
         console.log(value)
         $.ajax({
             url: "{{ route('revenue.get') }}",
             type: 'post',
             dataType: 'json',
             data: {
                 _token: '{{ csrf_token() }}',
                 days: value,
             },
             beforeSend: function() {}
         }).done(function(data) {
             if (data.status == 200) {
                 $('#revenueChart').remove();
                 $('.report-div').append('<canvas id="revenueChart" height="80%" width="100%"></canvas>')
                 console.log(data.details)
                 load_chart(data.details)
             }
         })
     }
     getdata()
 </script>