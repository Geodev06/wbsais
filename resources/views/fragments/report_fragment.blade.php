 <style>
     .c-fs {
         font-size: 13px;
     }

     .label-inv {
         color: crimson;
         font-size: 13px;
         content: '';
     }
 </style>
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
             <table id="table-report" class="display nowrap w-100 table-striped" width="100%">
                 <thead>
                     <tr style="height: 10px;">
                         <th>Total Revenue</th>
                         <th>No. of item sold</th>
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
     var table = $('#table-report').DataTable({
         columns: [{
             "width": "50%"
         }, {
             "width": "25%"
         }, {
             "width": "25%"
         }],

         dom: 'Bfrtip',
         buttons: ['copy',
             {
                 extend: 'print',
                 title: "{{ $data['store_name']}} [Inventory Report]",
                 messageTop: function() {
                     return 'Revenue from : ' + $('#from').val() + ' to : ' + $('#to').val()
                 },
                 messageBottom: function() {
                     var today = new Date()
                     var dd = String(today.getDate()).padStart(2, '0')
                     var mm = String(today.getMonth() + 1).padStart(2, '0')
                     var yyyy = today.getFullYear()
                     today = mm + '-' + dd + '-' + yyyy
                     return 'Report generated at ' + today
                 },
                 className: 'n-btn'
             }
         ],
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

             const ttl = data.transactions.reduce((acc, obj) => {
                 return acc + parseFloat(obj.amount)
             }, 0)

             const tqty = data.transactions.reduce((acc, obj) => {
                 return acc + parseFloat(obj.totalqty)
             }, 0)

             $('#loader').css('display', 'none')
             table.clear().draw()

             for (let i = 0; i < data.transactions.length; i++) {
                 table.row.add(["\u20B1 " + data.transactions[i].amount, data.transactions[i].totalqty, data.transactions[i].date, ]).draw()
             }

             table.row.add(['<b>Total revenue </b>: \u20B1 ' + ttl.toFixed(2), '<b>Total No. of items sold : </b>' + tqty, '']).draw()

             table.row.add(['', '<span class="label-inv">Inventory Details</span>', '']).draw()
             table.row.add(['<b>Items in Inventory</b> : ' + "{{ $data['inventory']}} ", '<b>Items in Inventory(qty)</b> : ' + "{{ $data['inventory_qty']}} ", '']).draw()
             table.row.add(['<b>Expiry items</b> : ' + "{{ $data['exp_items_no']}} ", '<b>Critical items</b> : ' + "{{ $data['critical_items']}} ", '']).draw()
             table.row.add(['<b>No of supplier</b> : ' + "{{ $data['supplier']}} ", '<b>Connected accounts</b> : ' + "{{ $data['connected']}} ", '']).draw()
             table.row.add(['<b>Total expenses</b> : \u20B1 ' + data.expenses[0].amount, '', '']).draw()
         }).fail(function(e) {
             $('#msg-error').text(e.responseJSON.message);
             $('#alert-modal').modal('toggle');
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
                 load_chart(data.details)
             }
         }).fail(function(e) {
             $('#msg-error').text(e.responseJSON.message);
             $('#alert-modal').modal('toggle');
         })
     }
     getdata()
 </script>