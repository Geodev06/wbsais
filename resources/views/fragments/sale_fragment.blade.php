 <style>
     .c-fs {
         font-size: 13px;
     }

     table.dataTable tbody th,
     table.dataTable tbody td {
         padding: 8px 10px;
     }
 </style>
 <div class="row">
     <h1 class="fw-bold fs-4 mt-4 mb-0">Sell items</h1>
     <div class="col-lg-6 col-md-6 col-sm-12 mt-2">

         <div>
             <table id="table-data" class="display nowrap w-100 table-striped">
                 <thead>
                     <tr style="height: 10px;">
                         <th>Product name</th>
                         <th>Category</th>
                         <th>Stock</th>
                         <th>Price</th>
                         <th>Operation</th>
                     </tr>
                 </thead>

             </table>
         </div>
     </div>

     <div class="col-lg-6 col-md-6 col-sm-12 mt-2">
         <div>
             <table id="stash-table-data" class="display nowrap w-100 table-striped">
                 <thead>
                     <tr style="height: 10px;">
                         <th>Product name</th>
                         <th>Category</th>
                         <th>Qty.</th>
                         <th>Price</th>
                         <th>Operation</th>
                     </tr>
                 </thead>
             </table>
         </div>
         <div class="p-3">
             <div class="row">
                 <div class="col d-flex">
                     <h1 class="fs-3 card-title fw-bold">Total : &#8369 <span id="total-amount"></span> </h1>
                 </div>
                 <div class="col"> <button id="transact-prompt" class="y-btn w-100 float-end">Done</button></div>
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
     var table = $('#table-data').DataTable({
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
             url: "{{ route('product_list.get') }}",
             type: 'get',
             dataType: 'json',
             beforeSend: function() {
                 $('#loader').css('display', 'flex');
                 $('#transact-prompt').attr('disabled', true)
             }
         }).done(function(data) {
             $('#transact-prompt').attr('disabled', false)
             $('#loader').css('display', 'none')
             let record = data.products.map(({
                 id,
                 product_name,
                 category,
                 qty,
                 price,
                 user_id
             }) => [id, product_name, category, qty, price, user_id])

             table.clear().draw()

             for (let i = 0; i < record.length; i++) {
                 var operations = '<a class="y-btn add-to-stash-btn w-100" data-id="' + record[i][0] + '"  data-pname="' + record[i][1] + '" data-category="' + record[i][2] + '" data-qty="' + record[i][3] + '" data-price="' + record[i][4] + '" data-uid="' + record[i][5] + '" > <i class="bx bx-cart-add me-1"></i></a>'
                 table.row.add([record[i][1], record[i][2], record[i][3], "\u20B1 " + record[i][4], operations]).draw()
             }
         }).fail(function(e) {
             $('#msg-error').text(e.responseJSON.message);
             $('#alert-modal').modal('toggle');
         })
     }

     load_data()

     $(document).ready(function() {
         //end transaction
         $('#btn-create-transaction').on('click', function(e) {
             e.preventDefault();

             if (parseFloat($('#user_amount').val()) >= parseFloat($('#user_amount')[0].dataset.due)) {

                 let temp_uri = "{{ route('transaction.store',':user_amount') }}";
                 $.ajax({
                     url: temp_uri.replace(':user_amount', $('#user_amount').val()),
                     type: 'get',
                     data: {
                         _token: '{{ csrf_token() }}'
                     },
                     dataType: 'json',
                     beforeSend: function() {
                         $(this).attr('disabled', true)
                     },
                     success: function(data) {
                         $(this).attr('disabled', false)
                         $('#user_amount').val(0)
                         if (data.status == 200) {
                             window.open(data.link)
                             load_data()
                             load_stash_data()
                             $('#finish-modal').modal('toggle')
                             $('#alert-modal-success').modal('toggle')
                             $('#msg-content').text(data.msg)
                         }
                     },
                     error: function(e) {
                         $('#msg-error').text(e.responseJSON.message);
                         $('#alert-modal').modal('toggle');
                     }
                 })

             } else {
                 $('#error-amount').text('Entered amount is invalid!')
             }

         })
         $('#table-data tbody').on('click', 'tr td .add-to-stash-btn', function() {
             $('#add-to-cart-modal').modal('toggle')
             $('#product-name').text($(this)[0].dataset.pname)

             $('#product-category').text($(this)[0].dataset.category)
             $('#product-price').text($(this)[0].dataset.price)
             $('#product-price').attr('data-price', $(this)[0].dataset.price)
             $('#product-name').attr('data-id', $(this)[0].dataset.id)
             $('#p-id').val($(this)[0].dataset.id)
             $('#p-name').val($(this)[0].dataset.pname)
             $('#p-cat').val($(this)[0].dataset.category)
             $('#p-prc').val($(this)[0].dataset.price)
             $('.error-text').text('')
         })
         //update item from stash
         $('#stash-table-data tbody').on('click', 'tr td .edit-stash-btn', function() {
             $('#edit-cart-modal').modal('toggle')
             $('#product-price').attr('data-price', $(this)[0].dataset.price)
             $('#s-product-name').text($(this)[0].dataset.pname)
             $('#s-product-category').text($(this)[0].dataset.category)
             $('#s-product-price').text($(this)[0].dataset.price)
             $('#s-product-qty').text($(this)[0].dataset.qty)
             $('#s-product-price').attr('data-price', $(this)[0].dataset.price)
             $('#s-product-name').attr('data-id', $(this)[0].dataset.id)
             var original_price = ($(this)[0].dataset.price / $(this)[0].dataset.qty)
             $('#s-p-prc').attr('data-original', original_price)
             $('#s-p-id').val($(this)[0].dataset.id)
             $('#s-p-name').val($(this)[0].dataset.pname)
             $('#s-p-cat').val($(this)[0].dataset.category)
             $('#s-qty').val($(this)[0].dataset.qty)
             $('#s-p-prc').val($(this)[0].dataset.price)
             $('.error-text').text('')
         })
         //remove from stash
         $('#stash-table-data tbody').on('click', 'tr td .remove-stash-btn', function() {
             $('#remove-item-modal').modal('toggle')
             $('#delete-id').text($(this)[0].dataset.id)
             $('#delete-pname').text($(this)[0].dataset.pname)
             $('#confirm-delete').attr('data-id', $(this)[0].dataset.id)
         })
     })

     //Add focus on textfields
     $('#add-to-cart-modal').on('shown.bs.modal', function() {
         $(this).find('input[type="number"]').focus()
     })
     $('#edit-cart-modal').on('shown.bs.modal', function() {
         $(this).find('input[type="number"]').focus()
     })
     $('#finish-modal').on('shown.bs.modal', function() {
         $(this).find('input[type="number"]').focus()
     })
     //addto cart submit
     $('#product-form').on('submit', function(e) {

         e.preventDefault()
         let temp_uri = "{{ route('product.add_to_cart',':id') }}";

         $.ajax({
             url: temp_uri.replace(':id', $('#product-name')[0].dataset.id),
             type: 'post',
             data: new FormData(this),
             dataType: 'json',
             processData: false,
             contentType: false,
             beforeSend: function() {
                 $('.error-text').text('')
                 $('#product-form :input').prop("disabled", true);
             },
             success: function(data) {
                 $('#product-form :input').prop("disabled", false);
                 if (data.status == 0) {
                     $.each(data.error, function(prefix, val) {
                         $('.error_' + prefix).text(val[0]);
                     })
                 }
                 if (data.status == 200) {
                     load_stash_data()
                     $('#product-form')[0].reset()
                     $('#add-to-cart-modal').modal('toggle')
                 }
                 if (data.status == 123) {
                     $('.error_request').text(data.error_request)
                 }
             },
             error: function(e) {
                 $('#msg-error').text(e.responseJSON.message);
                 $('#alert-modal').modal('toggle');
             }

         });
     });

     function increment_qty() {
         let qty = $('#qty').val()
         if ($('#qty').val() == '') {
             $('#product-price').text($('#product-price')[0].dataset.price)
         } else {
             $('#product-price').text((qty * $('#product-price')[0].dataset.price).toFixed(2))
             $('#s-product-price').text(($('#s-qty').val() * $('#s-p-prc')[0].dataset.original).toFixed(2))

             $('#product-name').attr('data-price', (qty * $('#product-price')[0].dataset.price).toFixed(2))
             $('#p-prc').val((qty * $('#product-price')[0].dataset.price).toFixed(2))
         }
         if ($('#s-qty').val() == '') {
             $('#s-product-price').text($('#s-p-prc')[0].dataset.original)
         }
     }

     //stash table
     var table_stash = $('#stash-table-data').DataTable({
         'lengthMenu': [
             [7, 10, 15, 20, 50, -1],
             [7, 10, 15, 20, 50, 'All'],
         ],
         'order': [
             [0, 'desc']
         ]
     })

     //open modal
     $('#transact-prompt').on('click', function(e) {
         e.preventDefault()
         if (table_stash.data().rows().count() > 0) {
             $('#finish-modal').modal('toggle')
         }
         if (table_stash.data().rows().count() <= 0) {
             $('#alert-error-modal').modal('toggle')
             $('#msg-error-text').text('There is no item in stash.');
         }
     })

     function load_stash_data() {

         return $.ajax({
             url: "{{ route('stash.get') }}",
             type: 'get',
             dataType: 'json',
             beforeSend: function() {
                 $('#loader').css('display', 'flex');
                 $('#transact-prompt').attr('disabled', true)
             }
         }).done(function(data) {
             $('#transact-prompt').attr('disabled', false)

             $('#total-amount').text(data.amount)
             $('#user_amount').attr('data-due', data.amount);
             $('#loader').css('display', 'none')
             let stash_items = data.products.map(({
                 id,
                 product_id,
                 product_name,
                 category,
                 qty,
                 price,
                 user_id
             }) => [id, product_id, product_name, category, qty, price, user_id])

             table_stash.clear().draw()

             for (let i = 0; i < stash_items.length; i++) {
                 var operations = '<a class="y-btn edit-stash-btn w-100" data-id="' + stash_items[i][1] + '"  data-pname="' + stash_items[i][2] + '" data-category="' + stash_items[i][3] + '" data-qty="' + stash_items[i][4] + '" data-price="' + stash_items[i][5] + '" data-uid="' + stash_items[i][6] + '" > <i class="bx bx-dots-horizontal me-1"></i></a> <a class="n-btn remove-stash-btn w-100" data-id="' + stash_items[i][1] + '"  data-pname="' + stash_items[i][2] + '" data-category="' + stash_items[i][2] + '" data-qty="' + stash_items[i][3] + '" data-price="' + stash_items[i][4] + '" data-uid="' + stash_items[i][5] + '" > <i class="bx bx-trash me-1"></i></a>'
                 table_stash.row.add([stash_items[i][2], stash_items[i][3], stash_items[i][4], "\u20B1 " + stash_items[i][5], operations]).draw()
             }
         }).fail(function(e) {
             $('#msg-error').text(e.responseJSON.message);
             $('#alert-modal').modal('toggle');
         })
     }

     load_stash_data()

     $('#edit-product-form').on('submit', function(e) {

         e.preventDefault()
         let temp_uri = "{{ route('product.update_cart',':id') }}";

         $.ajax({
             url: temp_uri.replace(':id', $('#s-product-name')[0].dataset.id),
             type: 'post',
             data: new FormData(this),
             dataType: 'json',
             processData: false,
             contentType: false,
             beforeSend: function() {
                 $('.error-text').text('')
                 $('#edit-product-form :input').prop("disabled", true);
             },
             success: function(data) {
                 $('#edit-product-form :input').prop("disabled", false);
                 if (data.status == 0) {
                     $.each(data.error, function(prefix, val) {
                         $('.error_' + prefix).text(val[0]);
                     })
                 }
                 if (data.status == 200) {
                     load_stash_data()
                     $('#edit-product-form')[0].reset()
                     $('#edit-cart-modal').modal('toggle')
                 }
                 if (data.status == 123) {
                     $('.error_request').text(data.error_request)
                 }
             },
             error: function(e) {
                 $('#msg-error').text(e.responseJSON.message);
                 $('#alert-modal').modal('toggle');
             }

         });
     });

     //remove item from stash
     $('#confirm-delete').on('click', function() {
         let temp_uri = "{{ route('product.remove_to_cart',':id') }}";
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
                     $('#remove-item-modal').modal('toggle')
                     $('#alert-modal-success').modal('toggle')
                     $('#msg-content').text(data.msg)
                     load_stash_data()
                 }
             },
             error: function(e) {
                 $('#msg-error').text(e.responseJSON.message);
                 $('#alert-modal').modal('toggle');
             }

         });
     })

     var user_amount_input = document.getElementById('user_amount')
     user_amount_input.addEventListener('keypress', (e) => {
         if (e.key === "Enter") {
             e.preventDefault()
             document.getElementById('btn-create-transaction').click()
         }
     })
 </script>
 <!-- Add product modal -->
 <div class="modal fade" id="add-to-cart-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-body">
                 <div class="container">
                     <div class="row">
                         <div class="col-sm-12">
                             <form autocomplete="off" id="product-form" class="p-5">
                                 @csrf
                                 <h1 class="fs-4 card-title fw-bolder mb-4"><i class="bx bx-shield text-primary"></i> Prompt? </h1>
                                 <ul class="list-group disabled" style="font-size: 13px">
                                     <li class="list-group-item"><i class="bx bx-error-circle text-primary"></i> Product name : <span id="product-name" style="font-size: 15px;" class="text-muted"></span></li>
                                     <li class="list-group-item"><i class="bx bx-category text-primary"></i> Category : <span id="product-category" style="font-size: 15px;" class="text-muted"></span></li>
                                     <li class="list-group-item"><i class="bx bx-money text-primary"></i> Price : &#8369 <span id="product-price" style="font-size: 15px;" class="text-muted"></span></li>
                                 </ul>

                                 <div class="mb-1 d-flex">
                                     <div class="col">
                                         <label for="validationCustom04" class="form-label text-muted c-fs">Product quantity</label>
                                         <div class="input-group">
                                             <div class="input-group-text" id="basicAddon1"><i class="bx bx-layer text-primary"></i></div>
                                             <input type="hidden" name="product_id" id="p-id">
                                             <input type="hidden" name="product_name" id="p-name">
                                             <input type="hidden" name="category" id="p-cat">
                                             <input type="hidden" name="price" id="p-prc">
                                             <input type="number" value="1" name="qty" onkeydown="increment_qty()" onkeyup="increment_qty()" onchange="increment_qty()" placeholder="Quantity" id="qty" class="form-control" />
                                         </div>
                                         <span class="error-text error_qty"></span>
                                         <span class="error-text error_request"></span>
                                     </div>
                                 </div>

                                 <div class="mt-4">
                                     <button type="submit" class="y-btn w-auto d-flex align-items-center">
                                         <i class="bx bx-cart-add fs-4 me-1"></i> Add to stash
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

 <!-- Add product modal -->
 <div class="modal fade" id="edit-cart-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content">
             <div class="modal-body">
                 <div class="container">
                     <div class="row">
                         <div class="col-sm-12">
                             <form autocomplete="off" id="edit-product-form" class="p-5">
                                 @csrf
                                 <h1 class="fs-4 card-title fw-bolder mb-4"><i class="bx bx-shield text-primary"></i> Prompt? </h1>
                                 <ul class="list-group disabled" style="font-size: 13px">
                                     <li class="list-group-item"><i class="bx bx-error-circle text-primary"></i> Product name : <span id="s-product-name" style="font-size: 15px;" class="text-muted"></span></li>
                                     <li class="list-group-item"><i class="bx bx-category text-primary"></i> Category : <span id="s-product-category" style="font-size: 15px;" class="text-muted"></span></li>
                                     <li class="list-group-item"><i class="bx bx-category text-primary"></i> Current qty. : <span id="s-product-qty" style="font-size: 15px;" class="text-muted"></span></li>
                                     <li class="list-group-item"><i class="bx bx-money text-primary"></i>Current Total Price : &#8369 <span id="s-product-price" style="font-size: 15px;" class="text-muted"></span></li>
                                 </ul>

                                 <div class="mb-1 d-flex">
                                     <div class="col">
                                         <label for="validationCustom04" class="form-label text-muted c-fs">Change product quantity to.</label>
                                         <div class="input-group">
                                             <div class="input-group-text" id="basicAddon1"><i class="bx bx-layer text-primary"></i></div>
                                             <input type="hidden" name="product_id" id="s-p-id">
                                             <input type="hidden" name="product_name" id="s-p-name">
                                             <input type="hidden" name="category" id="s-p-cat">
                                             <input type="hidden" name="price" id="s-p-prc">
                                             <input type="number" value="1" name="qty" onkeydown="increment_qty()" onkeyup="increment_qty()" onchange="increment_qty()" placeholder="Quantity" id="s-qty" class="form-control" />
                                         </div>
                                         <span class="error-text error_qty"></span>
                                         <span class="error-text error_request"></span>
                                     </div>
                                 </div>

                                 <div class="mt-4">
                                     <button type="submit" class="y-btn w-auto d-flex align-items-center">
                                         <i class="bx bx-cart-add fs-4 me-1"></i> Update
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

 <!-- remove item from stash modal -->
 <div class="modal fade" id="remove-item-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content border-0">
             <div class=" flex-alert-container">
                 <div class="flex-alert-header p-5 rounded-left">
                     <i class="bx bx-shield mx-1 text-danger" style="font-size: 5em;"></i>
                 </div>
                 <div class="flex-alert-body bg-white p-5">
                     <h1 class="fs-3 card-title">Prompt?</h1>
                     <ul class="list-group disabled" style="font-size: 13px">
                         <li class="list-group-item">Product Id : <span id="delete-id" style="font-size: 15px;" class="text-muted"></span></li>
                         <li class="list-group-item">Product name : <span id="delete-pname" style="font-size: 15px;" class="text-muted"></span></li>
                     </ul>
                     <span id="msg-error" style="font-size: 13px;" class="text-muted">Are you sure you want to remove this product from stash?</span>
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

 <!-- End transaction modal -->
 <div class="modal fade" id="finish-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content border-0">
             <div class=" flex-alert-container">
                 <div class="flex-alert-header p-5 rounded-left">
                     <i class="bx bx-shield mx-1 text-danger" style="font-size: 5em;"></i>
                 </div>
                 <div class="flex-alert-body bg-white p-5">
                     <h1 class="fs-3 card-title">Prompt?</h1>
                     <span id="msg-error" style="font-size: 13px;" class="text-muted">You are about to commit this transaction, is that all?</span>
                     <div class="mb-2 d-flex mt-4">
                         <label for="validationCustomx" class="form-label c-fs">Enter amount.</label>
                         <div class="input-group">
                             <input type="number" id="user_amount" name="user_amount" class="form-control" />
                         </div>

                     </div>
                     <span class="error-text" id="error-amount"></span>

                     <div class="mt-4 d-flex">
                         <button type="button" id="btn-create-transaction" class="y-btn d-flex align-items-center me-2 w-50">
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

 <!-- Error modal -->
 <div class="modal fade" id="alert-error-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content border-0">
             <div class=" flex-alert-container">
                 <div class="flex-alert-header p-5 rounded-left">
                     <i class="bx bx-x-circle mx-1 text-danger" style="font-size: 5em;"></i>
                 </div>
                 <div class="flex-alert-body bg-white p-5">
                     <h1 class="fs-3 card-title">Notice</h1>
                     <span id="msg-error-text" style="font-size: 13px;" class="text-muted"></span>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- End -->

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