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
             <button type="button" class="c-btn d-flex align-items-center me-2" data-bs-toggle="modal" data-bs-target="#add-product-modal">
                 <i class="bx bx-plus fs-4 me-1"></i> Add product
             </button>
         </div>
         <hr>
         <!-- datatables -->
         <div id="sub-content">
             <table id="table-data" class="display nowrap w-100 table-striped">
                 <thead>
                     <tr style="height: 10px;">
                         <th>id</th>
                         <th data-priority="2">Product name</th>
                         <th>Supplier</th>
                         <th>Category</th>
                         <th>Expiry</th>
                         <th>Quantity</th>
                         <th>Price</th>
                         <th data-priority="1">Operation</th>
                     </tr>
                 </thead>

             </table>
         </div>
         <script type="text/javascript">
             var table = $('#table-data').DataTable({
                 responsive: true,
                 columnDefs: [{
                     responsivePriority: 2,
                     targets: 0
                 }, {
                     responsivePriority: 1,
                     targets: 6
                 }],
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
                     url: "{{ route('product.get') }}",
                     type: 'get',
                     dataType: 'json',
                     beforeSend: function() {
                         $('#loader').css('display', 'flex');
                     }
                 }).done(function(data) {
                     //  $('#loader').hide()
                     //  console.log(data.products)
                     $('#loader').css('display', 'none')
                     let record = data.products.map(({
                         id,
                         product_name,
                         supplier,
                         category,
                         expiry,
                         qty,
                         price
                     }) => [id, product_name, supplier, category, expiry, qty, price])

                     table.clear().draw()

                     for (let i = 0; i < record.length; i++) {
                         var operations = '<a class="btn-edit" data-id="' + record[i][0] + '" data-pname="' + record[i][1] + '" data-supplier="' + record[i][2] + '" data-category="' + record[i][3] + '" data-expiry="' + record[i][4] + '" data-qty="' + record[i][5] + '" data-price="' + record[i][6] + '"> <i class="bx bx-edit me-1"></i></a> <a class="btn-delete" data-id="' + record[i][0] + '" data-pname="' + record[i][1] + '" data-supp="' + record[i][2] + '" data-cat="' + record[i][3] + '" data-exp="' + record[i][4] + '"><i class="bx bx-trash me-1"></i></a>'
                         table.row.add([record[i][0], record[i][1], record[i][2], record[i][3], record[i][4], record[i][5], "\u20B1 " + record[i][6], operations]).draw()
                     }
                 })
             }

             load_data()

             $(document).ready(function() {

                 $('#table-data tbody').on('click', 'tr td .btn-edit', function() {
                     $('#edit-product-modal').modal('toggle')
                     $('#e-id').val($(this)[0].dataset.id)
                     $('#e-product-name').val($(this)[0].dataset.pname)
                     $('#e-supplier').val($(this)[0].dataset.supplier)
                     $('#e-category').val($(this)[0].dataset.category)
                     $('#e-expiry').val($(this)[0].dataset.expiry)
                     $('#e-qty').val($(this)[0].dataset.qty)
                     $('#e-price').val($(this)[0].dataset.price)
                 })

                 $('#table-data tbody').on('click', 'tr td .btn-delete', function() {
                     $('#delete-product-modal').modal('toggle')
                     $('#confirm-delete').attr('data-id', $(this)[0].dataset.id)

                     $('#delete-id').text($(this)[0].dataset.id)
                     $('#delete-product-name').text($(this)[0].dataset.pname)
                     $('#delete-supplier').text($(this)[0].dataset.supp)
                     $('#delete-category').text($(this)[0].dataset.cat)
                     $('#delete-expiry').text($(this)[0].dataset.exp)
                 })

                 $('#confirm-delete').on('click', function() {
                     let temp_uri = "{{ route('product.destroy',':id') }}";
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
                                 $('#delete-product-modal').modal('toggle')
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
 <div class="modal fade" id="add-product-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-body">
                 <div class="container">
                     <div class="row">
                         <div class="col-sm-12">
                             <form method="POST" autocomplete="off" id="product-form" class="p-5">
                                 @csrf
                                 <h1 class="fs-4 card-title fw-bolder mb-4">Add new product </h1>
                                 <div class="mb-1">
                                     <label for="validationCustom01" class="form-label text-muted c-fs">Product name</label>
                                     <div class="input-group">
                                         <div class="input-group-text" id="basicAddon"><i class="bx bx-info-square"></i></div>
                                         <input type="text" name="product_name" placeholder="Product name" id="validationCustom01" class="form-control" />
                                     </div>
                                     <span class="error-text error_product_name"></span>
                                 </div>

                                 <div class="mb-1">
                                     <label for="validationCustom02" class="form-label text-muted c-fs">Product Supplier <span>(optional)</span></label>
                                     <div class="input-group">
                                         <div class="input-group-text" id="basicAddon1"><i class="bx bx-building-house"></i></div>
                                         <select type="select" name="supplier" id="validationCustom02" class="text-muted form-select">
                                             <option value="">None</option>
                                             @foreach($suppliers as $supplier)
                                             <option value="{{$supplier->supplier}}">{{$supplier->supplier}}</option>
                                             @endforeach
                                         </select>
                                     </div>
                                     <span class="error-text error_supplier"></span>
                                 </div>

                                 <div class="mb-1">
                                     <label for="validationCustom03" class="form-label text-muted c-fs">Product Category</label>
                                     <div class="input-group">
                                         <div class="input-group-text" id="basicAddon1"><i class="bx bx-category"></i></div>
                                         <select type="select" name="category" id="validationCustom03" class="text-muted form-select">
                                             @foreach($categories as $category)
                                             <option value="{{$category->category}}">{{$category->category}}</option>
                                             @endforeach
                                         </select>
                                     </div>
                                     <span class="error-text error_category"></span>
                                 </div>

                                 <div class="mb-1">
                                     <label for="validationCustomdate" class="form-label text-muted c-fs">Expiry</label>
                                     <div class="input-group">
                                         <div class="input-group-text" id="basicAddondate"><i class="bx bx-category"></i></div>
                                         <input type="date" value="<?php echo date('Y-m-d'); ?>" name="expiry" id="validationCustomdate" class="text-muted form-control" />
                                     </div>
                                     <span class="error-text error_date"></span>
                                 </div>

                                 <div class="mb-1 d-flex">
                                     <div class="col me-3">
                                         <label for="validationCustom04" class="form-label text-muted c-fs">Product quantity</label>
                                         <div class="input-group">
                                             <div class="input-group-text" id="basicAddon1"><i class="bx bx-layer"></i></div>
                                             <input type="number" name="qty" placeholder="Quantity" id="validationCustom04" class="form-control" />
                                         </div>
                                         <span class="error-text error_qty"></span>
                                     </div>
                                     <div class="col">
                                         <label for="validationCustom05" class="form-label text-muted c-fs">Product price</label>
                                         <div class="input-group">
                                             <div class="input-group-text" id="basicAddon1"><i class="bx bx-money"></i></div>
                                             <input type="text" name="price" placeholder="Price" id="validationCustom05" class="form-control" />
                                         </div>
                                         <span class="error-text error_price"></span>
                                     </div>
                                 </div>

                                 <div class="mt-4">
                                     <button type="submit" class="y-btn w-25 d-flex align-items-center">
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

 <!-- Update product modal -->
 <div class="modal fade" id="edit-product-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-body">
                 <div class="container">
                     <div class="row">
                         <div class="col-sm-12">
                             <form method="POST" autocomplete="off" id="edit-product-form" class="p-5">
                                 @csrf
                                 <h1 class="fs-4 card-title fw-bolder mb-4">Update product </h1>
                                 <input type="hidden" name="id" id="e-id" />
                                 <div class="mb-1">
                                     <label for="e-product-name" class="form-label text-muted c-fs">Product name</label>
                                     <div class="input-group">
                                         <div class="input-group-text" id="basicAddon"><i class="bx bx-info-square"></i></div>
                                         <input id="e-product-name" type="text" name="product_name" placeholder="Product name" class="form-control" />
                                     </div>
                                     <span class="error-text edit_error_product_name"></span>
                                 </div>

                                 <div class="mb-1">
                                     <label for="e-supplier" class="form-label text-muted c-fs">Product Supplier <span>(optional)</span></label>
                                     <div class="input-group">
                                         <div class="input-group-text" id="basicAddon1"><i class="bx bx-building-house"></i></div>
                                         <select id="e-supplier" type="select" name="supplier" class="text-muted form-select">
                                             <option value="">None</option>
                                             @foreach($suppliers as $supplier)
                                             <option value="{{$supplier->supplier}}">{{$supplier->supplier}}</option>
                                             @endforeach
                                         </select>
                                     </div>
                                     <span class="error-text edit_error_supplier"></span>
                                 </div>

                                 <div class="mb-1">
                                     <label for="e-category" class="form-label text-muted c-fs">Product Category</label>
                                     <div class="input-group">
                                         <div class="input-group-text" id="basicAddon1"><i class="bx bx-category"></i></div>
                                         <select id="e-category" type="select" name="category" class="text-muted form-select">
                                             @foreach($categories as $category)
                                             <option value="{{$category->category}}">{{$category->category}}</option>
                                             @endforeach
                                         </select>
                                     </div>
                                     <span class="error-text edit_error_category"></span>
                                 </div>

                                 <div class="mb-1">
                                     <label for="validationCustomdate" class="form-label text-muted c-fs">Expiry</label>
                                     <div class="input-group">
                                         <div class="input-group-text" id="basicAddondate"><i class="bx bx-category"></i></div>
                                         <input type="date" id="e-expiry" value="" name="expiry" id="validationCustomdate" class="text-muted form-control" />
                                     </div>
                                     <span class="error-text edit_error_date"></span>
                                 </div>

                                 <div class="mb-1 d-flex">
                                     <div class="col me-3">
                                         <label for="e-qty" class="form-label text-muted c-fs">Product quantity</label>
                                         <div class="input-group">
                                             <div class="input-group-text" id="basicAddon1"><i class="bx bx-layer"></i></div>
                                             <input id="e-qty" type="text" name="qty" placeholder="Quantity" class="form-control" />
                                         </div>
                                         <span class="error-text edit_error_qty"></span>
                                     </div>
                                     <div class="col">
                                         <label for="e-price" class="form-label text-muted c-fs">Product price</label>
                                         <div class="input-group">
                                             <div class="input-group-text" id="basicAddon1"><i class="bx bx-money"></i></div>
                                             <input id="e-price" type="text" name="price" placeholder="Price" class="form-control" />
                                         </div>
                                         <span class="error-text edit_error_price"></span>
                                     </div>
                                 </div>

                                 <div class="mt-4">
                                     <button type="submit" class="y-btn w-25 d-flex align-items-center">
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

 <!-- delete modal -->
 <div class="modal fade" id="delete-product-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
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
                         <li class="list-group-item">Product name : <span id="delete-product-name" style="font-size: 15px;" class="text-muted"></span></li>
                         <li class="list-group-item">Supplier : <span id="delete-supplier" style="font-size: 15px;" class="text-muted"></span></li>
                         <li class="list-group-item">Category : <span id="delete-category" style="font-size: 15px;" class="text-muted"></span></li>
                         <li class="list-group-item">Expiry : <span id="delete-expiry" style="font-size: 15px;" class="text-muted"></span></li>
                     </ul>
                     <span id="msg-error" style="font-size: 13px;" class="text-muted">Are you sure you want to remove this item?</span>
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

         $('#product-form').on('submit', function(e) {
             e.preventDefault()
             $.ajax({
                 url: "{{ route('product.store') }}",
                 type: 'post',
                 data: new FormData(this),
                 dataType: 'json',
                 processData: false,
                 contentType: false,
                 beforeSend: function() {
                     $('.error-text').text('');
                     $('#product-form :input').prop("disabled", true);
                 },
                 success: function(data) {
                     $('#product-form :input').prop("disabled", false);
                     if (data.status == 0) {
                         $.each(data.error, function(prefix, val) {
                             $('.error_' + prefix).text(val[0]);
                         })
                     } else {
                         $('#product-form')[0].reset();
                         $('#msg-content').text(data.msg)
                         load_data();
                         $('#add-product-modal').modal('toggle');
                         $('#alert-modal-success').modal('toggle');
                     }
                 }

             });
         });
         //update product
         $('#edit-product-form').on('submit', function(e) {
             e.preventDefault()
             $.ajax({
                 url: "{{ route('product.update') }}",
                 type: 'post',
                 data: new FormData(this),
                 dataType: 'json',
                 processData: false,
                 contentType: false,
                 beforeSend: function() {
                     $('.error-text').text('');
                     $('#edit-product-form :input').prop("disabled", true);
                 },
                 success: function(data) {
                     $('#edit-product-form :input').prop("disabled", false);
                     if (data.status == 0) {
                         $.each(data.error, function(prefix, val) {
                             $('.edit_error_' + prefix).text(val[0]);
                         })
                     } else {
                         $('#edit-product-form')[0].reset();
                         $('#msg-content').text(data.msg)
                         load_data();
                         $('#edit-product-modal').modal('toggle');
                         $('#alert-modal-success').modal('toggle');
                     }
                 }

             });
         });
     })
 </script>