     <script src="{{ asset('chartjs/package/dist/chart.js')}}"></script>
     <div class="row p-5">
         <div class="col-md-6 col-sm-12 col-lg-3 p-2">
             <div class="card-body shadow-sm d-flex">
                 <div class="col">
                     <div class="icon-container">
                         <i class="bx bx-box text-white nav_icon"></i>
                     </div>
                 </div>
                 <div class="col text-center">
                     <h2 class="mb-0">{{ $data['inventory']}}</h1>
                         <P class="text-muted" style="font-size: 13px;">Inventory</P>
                 </div>
             </div>
         </div>

         <div class="col-md-6 col-sm-12 col-lg-3 p-2">
             <div class="card-body shadow-sm d-flex">
                 <div class="col">
                     <div class="icon-container">
                         <i class="bx bx-package text-white nav_icon"></i>
                     </div>
                 </div>
                 <div class="col text-center">
                     <h2 class="mb-0">{{ $data['supplier']}}</h1>
                         <P class="text-muted" style="font-size: 13px;">Supplier</P>
                 </div>
             </div>
         </div>

         <div class="col-md-6 col-sm-12 col-lg-3 p-2">
             <div class="card-body shadow-sm d-flex">
                 <div class="col me-2">
                     <div class="icon-container">
                         <i class="bx bx-money text-white nav_icon"></i>
                     </div>
                 </div>
                 <div class="col text-center">
                     <h2 class="mb-0 d-flex"> <span>&#8369 </span>{{ $data['daily_rev']}}</h2>
                     <P class="text-muted" style="font-size: 13px;">Daily revenue</P>
                 </div>
             </div>
         </div>

         <div class="col-md-6 col-sm-12 col-lg-3 p-2">
             <div class="card-body shadow-sm d-flex">
                 <div class="col">
                     <div class="icon-container">
                         <i class="bx bx-error text-white nav_icon"></i>
                     </div>
                 </div>
                 <div class="col text-center">
                     <h2 class="mb-0">{{ $data['critical_items']}}</h2>
                     <P class="text-muted" style="font-size: 13px;">Critical items</P>
                 </div>
             </div>
         </div>

         <div class="col-md-6 col-sm-12 col-lg-6">
             <div class="card-body">
                 <!-- <i class=" bx bx-stats nav_icon"></i> -->
                 <h1 class="fs-6 fw-bolder">Recent transactions.</h1>
                 <table class="table table-striped" style="font-size: 12px">
                     <thead>
                         <th>Transaction id</th>
                         <th>Amount</th>
                         <th>Date</th>
                     </thead>
                     <tbody>
                         @foreach($recent_transactions as $transaction)
                         <tr>
                             <td>{{$transaction->transaction_id}}</td>
                             <td class="text-success">&#8369 {{$transaction->amount}}</td>
                             <td>{{$transaction->created_at->format('l jS, \of F, Y  g:i  A')}}</td>
                         </tr>
                         @endforeach
                     </tbody>
                 </table>
             </div>
         </div>

         <div class="col-md-6 col-sm-12 col-lg-6">
             <div class="card-body ">
                 <h1 class="fs-6 fw-bolder mb-2">Top grossing products. all time</h1>
                 <canvas id="topGrossingChart" height="80%" width="100%"></canvas>
                 <div class="loader-container" id="loader">
                     <img src="{{asset('assets/img/load.gif')}}" />
                 </div>
             </div>
         </div>
     </div>
     <script type="text/javascript">
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
                             '#800080',
                         ],
                         borderColor: [
                             '#ffffff'
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
                     console.log(data.details)
                     load_chart(data.details)
                 }
             })
         }
         getdata()
         //  let chart_data = getdata();
         //  console.log(chart_data)
     </script>