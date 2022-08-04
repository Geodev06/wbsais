 <style>
     .c-fs {
         font-size: 13px;
     }
 </style>
 <script src="{{ asset('assets/dataTables/datatables.js') }}"></script>
 <link rel="stylesheet" href="{{ asset('assets/dataTables/datatables.min.css') }}" />
 <link rel="stylesheet" href="{{ asset('assets/dataTables/datatables.css') }}" />
 <script src="{{ asset('assets/dataTables/datatables.min.js') }}"></script>
 <div class="row">
     <div class="col-lg-12">
         <div class="mt-4 d-flex">
         </div>
         <hr>
         <!-- datatables -->
         <div class="container">
             <div class="row">
                 <div class="col-lg-6 col-md-6-col-sm-12 col-xs-12 mb-5">
                     <div>
                         <form method="POST" autocomplete="off" id="supplier-form" class="w-75">
                             @csrf
                             <h1 class="fs-4 card-title fw-bolder mb-4">Add new Supplier</h1>
                             <div class="mb-1">
                                 <label for="validationCustom01" class="form-label text-muted c-fs">Supplier name</label>
                                 <div class="input-group">
                                     <div class="input-group-text" id="basicAddon1"><i class="bx bx-info-square"></i></div>
                                     <input type="text" name="supplier" placeholder="Supplier name" id="validationCustom01" class="form-control" />
                                 </div>
                                 <span class="error-text error_supplier"></span>
                             </div>

                             <div class="mb-1">
                                 <label for="validationCustom02" class="form-label text-muted c-fs">Address</label>
                                 <div class="input-group">
                                     <div class="input-group-text" id="basicAddon2"><i class="bx bx-map"></i></div>
                                     <input type="text" name="address" placeholder="Address" id="validationCustom02" class="form-control" />
                                 </div>
                                 <span class="error-text error_address"></span>
                             </div>

                             <div class="mb-1">
                                 <label for="validationCustom03" class="form-label text-muted c-fs">Contact</label>
                                 <div class="input-group">
                                     <div class="input-group-text" id="basicAddon3"><i class="bx bx-phone"></i></div>
                                     <input type="number" name="contact" placeholder="Contact" id="validationCustom03" class="form-control" />
                                 </div>
                                 <span class="error-text error_contact"></span>
                             </div>

                             <div class="mt-4">
                                 <button type="submit" class="y-btn d-flex align-items-center">
                                     <i class="bx bx-save fs-4 me-1"></i> Save
                                 </button>
                             </div>
                         </form>
                     </div>
                 </div>
                 <div class="col-lg-6 col-md-6-col-sm-12 col-xs-12">
                     <div>
                         <table id="table-data" class="display nowrap w-100 table-striped">
                             <thead>
                                 <tr>
                                     <th>id</th>
                                     <th>Supplier</th>
                                     <th>Address</th>
                                     <th>Contact</th>
                                     <th>Operation</th>
                                 </tr>
                             </thead>
                         </table>
                     </div>
                 </div>
             </div>
         </div>
         <script type="text/javascript">
             var table = $('#table-data').DataTable({
                 responsive: true,
                 'lengthMenu': [
                     [10, 10, 15, 20, 50, -1],
                     [10, 10, 15, 20, 50, 'All'],
                 ],
                 'order': [
                     [0, 'desc']
                 ]
             })

             function load_data() {
                 return $.ajax({
                     url: "{{ route('supplier.get') }}",
                     type: 'get',
                     dataType: 'json',
                     beforeSend: function() {
                         $('#loader').css('display', 'flex');
                     }
                 }).done(function(data) {
                     $('#loader').css('display', 'none')
                     let record = data.suppliers.map(({
                         id,
                         supplier,
                         address,
                         contact,
                     }) => [id, supplier, address, contact])
                     table.clear().draw()
                     for (let i = 0; i < record.length; i++) {
                         var operations = '<a class="btn-edit" data-id="' + record[i][0] + '" data-supplier="' + record[i][1] + '" data-address="' + record[i][2] + '" data-contact="' + record[i][3] + '"> <i class="bx bx-edit me-1"></i></a> <a class="btn-delete" data-id="' + record[i][0] + '" data-supplier="' + record[i][1] + '" ><i class="bx bx-trash me-1"></i></a>'
                         table.row.add([record[i][0], record[i][1], record[i][2], record[i][3], operations]).draw()
                     }
                 })
             }
             load_data()

             $(document).ready(function() {

                 $('#table-data tbody').on('click', 'tr td .btn-edit', function() {
                     $('#edit-supplier-modal').modal('toggle')
                     $('#btn-update').attr('data-id', $(this)[0].dataset.id)
                     $('#e-supplier').val($(this)[0].dataset.supplier)
                     $('#e-address').val($(this)[0].dataset.address)
                     $('#e-contact').val($(this)[0].dataset.contact)

                 })
                 $('#table-data tbody').on('click', 'tr td .btn-delete', function() {
                     $('#delete-supplier-modal').modal('toggle')
                     $('#confirm-delete').attr('data-id', $(this)[0].dataset.id)
                     $('#delete-supplier').text($(this)[0].dataset.supplier)
                     $('#delete-id').text($(this)[0].dataset.id)
                 })
             })
         </script>
     </div>
 </div>
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

 <!-- Add product modal -->
 <div class="modal fade" id="edit-supplier-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-body">
                 <div class="container">
                     <div class="row">
                         <div class="col-sm-12">
                             <form method="POST" autocomplete="off" id="edit-supplier-form" class="w-75">
                                 @csrf
                                 <h1 class="fs-4 card-title fw-bolder mb-4">Update Supplier</h1>
                                 <div class="mb-1">
                                     <label for="validationCustom01" class="form-label text-muted c-fs">Supplier name</label>
                                     <div class="input-group">
                                         <div class="input-group-text" id="basicAddon1"><i class="bx bx-info-square"></i></div>
                                         <input type="text" id="e-supplier" name="supplier" placeholder="Supplier name" id="validationCustom01" class="form-control" />
                                     </div>
                                     <span class="error-text edit_error_supplier"></span>
                                 </div>

                                 <div class="mb-1">
                                     <label for="validationCustom02" class="form-label text-muted c-fs">Address</label>
                                     <div class="input-group">
                                         <div class="input-group-text" id="basicAddon2"><i class="bx bx-map"></i></div>
                                         <input type="text" id="e-address" name="address" placeholder="Address" id="validationCustom02" class="form-control" />
                                     </div>
                                     <span class="error-text edit_error_address"></span>
                                 </div>

                                 <div class="mb-1">
                                     <label for="validationCustom03" class="form-label text-muted c-fs">Contact</label>
                                     <div class="input-group">
                                         <div class="input-group-text" id="basicAddon3"><i class="bx bx-phone"></i></div>
                                         <input type="number" id="e-contact" name="contact" placeholder="Contact" id="validationCustom03" class="form-control" />
                                     </div>
                                     <span class="error-text edit_error_contact"></span>
                                 </div>

                                 <div class="mt-4 d-flex">
                                     <button id="btn-update" type="submit" class="me-2 y-btn d-flex align-items-center">
                                         <i class="bx bx-save fs-4 me-1"></i> Save
                                     </button>
                                     <button data-bs-dismiss="modal" type="button" class="n-btn d-flex align-items-center">
                                         <i class="bx bx-save fs-4 me-1"></i> Cancel
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

 <!-- delete supplier modal -->
 <div class="modal fade" id="delete-supplier-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content border-0">
             <div class=" flex-alert-container">
                 <div class="flex-alert-header p-5 rounded-left">
                     <i class="bx bx-shield mx-1 text-danger" style="font-size: 5em;"></i>
                 </div>
                 <div class="flex-alert-body bg-white p-5">
                     <h1 class="fs-3 card-title">Prompt?</h1>
                     <ul class="list-group disabled" style="font-size: 13px">
                         <li class="list-group-item">Id : <span id="delete-id" style="font-size: 15px;" class="text-muted"></span></li>
                         <li class="list-group-item">Supplier name : <span id="delete-supplier" style="font-size: 15px;" class="text-muted"></span></li>
                     </ul>
                     <span id="msg-error" style="font-size: 13px;" class="text-muted">Are you sure you want to remove this supplier?</span>
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
     $(function() {
         //store supplier
         $('#supplier-form').on('submit', function(e) {
             e.preventDefault()
             $.ajax({
                 url: "{{ route('supplier.store') }}",
                 type: 'post',
                 data: new FormData(this),
                 dataType: 'json',
                 processData: false,
                 contentType: false,
                 beforeSend: function() {
                     $('.error-text').text('');
                     $('#supplier-form :input').prop("disabled", true);
                 },
                 success: function(data) {
                     $('#supplier-form :input').prop("disabled", false);
                     if (data.status == 0) {
                         $.each(data.error, function(prefix, val) {
                             $('.error_' + prefix).text(val[0]);
                         })
                     } else {
                         $('#supplier-form')[0].reset();
                         $('#msg-content').text(data.msg)
                         load_data();
                         $('#alert-modal-success').modal('toggle');
                     }
                 }

             });
         });
         //update supplier
         $('#edit-supplier-form').on('submit', function(e) {
             e.preventDefault()
             let temp_uri = "{{ route('supplier.update',':id') }}";
             $.ajax({
                 url: temp_uri.replace(':id', $('#btn-update')[0].dataset.id),
                 type: 'post',
                 data: new FormData(this),
                 dataType: 'json',
                 processData: false,
                 contentType: false,
                 beforeSend: function() {
                     $('.error-text').text('');
                     $('#edit-supplier-form :input').prop("disabled", true);
                 },
                 success: function(data) {
                     $('#edit-supplier-form :input').prop("disabled", false);
                     if (data.status == 0) {
                         $.each(data.error, function(prefix, val) {
                             $('.edit_error_' + prefix).text(val[0]);
                         })
                     } else {
                         $('#edit-supplier-modal').modal('toggle')
                         $('#edit-supplier-form')[0].reset();
                         $('#msg-content').text(data.msg)
                         load_data();
                         $('#alert-modal-success').modal('toggle');
                     }
                 }

             });
         });
         //delete supplier
         $('#confirm-delete').on('click', function() {
             let temp_uri = "{{ route('supplier.destroy',':id') }}";
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
                         $('#delete-supplier-modal').modal('toggle')
                         $('#alert-modal-success').modal('toggle')
                         $('#msg-content').text(data.msg)
                         load_data()
                     }
                 },
                 error: function(err) {
                     console.log(err)
                 }

             });
         })
     })
 </script>