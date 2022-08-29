<style>
    .c-font {
        font-size: 13px;
        color: dodgerblue;
    }
</style>
<div class="row">
    <div class="col-md-6 col-lg-5 col-sm-12 p-5">
        <h1 class="fs-4 card-title fw-light mb-4">Account settings</h1>
        <div class="d-flex float-end">
            <p class="c-font mb-0 "> Basic information</p>
        </div>
        <form method="POST" autocomplete="off" id="basic-information-form" class="mt-5">
            @csrf
            <div class="mb-3">
                <label for="validationCustom01" class="form-label text-muted c-font">Name</label>
                <div class="input-group">
                    <div class="input-group-text" id="basicAddon1"><i class="bx bx-user"></i></div>
                    <input type="text" name="name" placeholder="Your name" id="validationCustom01" class="form-control" value="{{ $credentials['name']}}" required />
                </div>
                <span class="error-text error_name"></span>
            </div>

            <div class="mb-3">
                <label for="validationCustom02" class="form-label text-muted c-font">Email address</label>
                <div class="input-group">
                    <div class="input-group-text" id="basicAddon2"><i class="bx bx-user"></i></div>
                    <input disabled type="email" name="email" placeholder="Email address" value="{{ $credentials['email']}}" id="validationCustom02" class="form-control" required />
                </div>
                <span class="error-text error_email"></span>
            </div>

            <div class="mb-3">
                <label for="validationCustom03" class="form-label text-muted c-font">Contact no.</label>
                <div class="input-group">
                    <div class="input-group-text" id="basicAddon3"><i class="bx bx-phone"></i></div>
                    <input type="number" name="contact" placeholder="Contact no." value="{{ $credentials['contact'] }}" id="validationCustom03" class="form-control" />
                </div>
                <span class="error-text error_contact"></span>
            </div>

            <div class="d-flex align-items-center">
                <input type="submit" class="btn btn-primary ms-auto w-25" value="Save"></input>
            </div>
        </form>
    </div>

    <!-- Password form -->
    <div class="col-md-6 col-lg-5 col-sm-12 p-5">
        <h1 class="fs-4 card-title fw-light mb-4">Password settings</h1>
        <div class="d-flex float-end">
            <p class="c-font mb-0">Passwords configurations</p>
        </div>
        <form method="POST" autocomplete="off" id="password-form" class="mt-5">
            @csrf
            <div class="mb-3">
                <div class="mb-2 w-100">
                    <label class="text-muted c-font" for="password">Old Password</label>
                </div>
                <div class="input-group">
                    <div class="input-group-text" id="basicAddon4"><i class="bx bx-lock"></i></div>
                    <input type="password" name="old_password" placeholder="Password" id="validationCustom04" class="form-control" />
                </div>
                <span class="error-text error_old_password"></span>
            </div>

            <div class="mb-3">
                <div class="mb-2 w-100">
                    <label class="text-muted c-font" for="password">New Password</label>
                </div>
                <div class="input-group">
                    <div class="input-group-text" id="basicAddon5"><i class="bx bx-lock"></i></div>
                    <input type="password" name="password" placeholder="Password" id="validationCustom05" class="form-control" />
                    <div class="input-group-text" id="basicAddon4"><i class="bx bx-hide" id="pwdhide"></i></div>
                </div>
                <span class="error-text error_password"></span>
            </div>

            <div class="mb-3">
                <div class="mb-2 w-100">
                    <label class="text-muted c-font" for="password">Confirm Password</label>
                </div>
                <div class="input-group">
                    <div class="input-group-text" id="basicAddon6"><i class="bx bx-lock"></i></div>
                    <input type="password" name="password_confirmation" placeholder="Password" id="validationCustom06" class="form-control" />
                </div>
                <span class="error-text error_password"></span>
            </div>

            <div class="d-flex align-items-center">
                <input type="submit" class="btn btn-primary ms-auto w-25" value="Save"></input>
            </div>
        </form>
    </div>

    <div class="col-md-6 col-lg-5 col-sm-12 p-5">
        <h1 class="fs-4 card-title fw-light mb-4">Address settings</h1>
        <div class="d-flex float-end">
            <p class="c-font mb-0">Location information</p>
        </div>
        <form method="POST" autocomplete="off" id="address-form" class="mt-5">
            @csrf
            <div class="mb-3">
                <label for="validationCustom011" class="form-label text-muted c-font">Store name</label>
                <div class="input-group">
                    <div class="input-group-text" id="basicAddon11"><i class="bx bx-store"></i></div>
                    <input type="text" name="storename" placeholder="Store name" id="validationCustom011" value="{{ $credentials['store_name']}}" class="form-control" required />
                </div>
                <span class="error-text error_storename"></span>
            </div>

            <div class="mb-3">
                <div class="mb-2 w-100">
                    <label class="text-muted c-font" for="validationCustom07">Physical address</label>
                </div>
                <div class="input-group">
                    <div class="input-group-text" id="basicAddon7"><i class="bx bx-map-alt"></i></div>
                    <input type="text" value="{{ $credentials['address']}}" name="address" placeholder="Physical address" id="validationCustom07" class="form-control" required />
                </div>
                <p class="mb-0 mt-2" style="font-size: 12px;">Current lat : {{ $credentials['lat']}}</p>
                <p class="mb-0" style="font-size: 12px;">Current long : {{ $credentials['long']}}</p>
                <input type="hidden" name="lat" id="t_lat" value="{{ $credentials['lat']}}" />
                <input type="hidden" name="long" id="t_long" value="{{ $credentials['long']}}" />
                <span class="error-text error_address"></span>
            </div>

            <div class="d-flex align-items-center">
                <input type="button" id="btnget-geo" class="btn btn-outline-primary me-1 w-50" value="Enable Geolocation"></input>
                <input type="submit" class="btn btn-primary ms-auto w-25" value="Save"></input>
            </div>
        </form>
    </div>

    <div class="col-md-6 col-lg-5 col-sm-12 p-5">
        <h1 class="fs-4 card-title fw-light mb-4">Others Setting</h1>
        <form method="POST" autocomplete="off" id="others-form" class="mt-5">
            @csrf
            <h1 class="fs-6 card-title fw-normal text-muted">Inventory sharing</h1>
            <span class="badge bg-success" id="btn-generate-iid" style="cursor: pointer;"> <i class="bx bx-key me-1"></i> Generate new key</span>
            <span class="badge bg-primary" id="btn-show-iid" style="cursor: pointer;"> <i class="bx bx-show me-1"></i> Show my inventory key</span>
            <p class="c-font text-dark" id="iid" style="display: none;"><span class="badge bg-secondary">{{ $credentials['inventory_id']}}</span></p>
            <p class="c-font mb-2 fw-light text-muted">Inventory sharing allows you to inherit data from another inventory by binding their inventory key to yours.</p>
            <input type="button" class="btn btn-outline-primary me-1 w-50" data-bs-toggle="modal" data-bs-target="#iid-modal" value="Bind inventory key"></input>
            <hr>
            <h1 class="fs-6 card-title fw-normal text-muted">Account deletion</h1>
            <p class="c-font mb-2 fw-light text-muted">Permanently delete all the information and data of account. Including inventory, transaction logs and account credentials</p>
            <div class="d-flex align-items-center mb-3">
                <input type="button" class="n-btn w-50" id="btn-destroy-user" value="Delete account"></input>
            </div>
        </form>
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

<!-- destroy modal -->
<div class="modal fade" id="destroy-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class=" flex-alert-container bg-danger">
                <div class="flex-alert-header p-5 rounded-left">
                    <i class="bx bx-shield-x mx-1 text-white" style="font-size: 5em;"></i>
                </div>
                <div class="flex-alert-body bg-white p-5">
                    <h1 class="fs-3 card-title">Delete my Account?</h1>
                    <span id="msg-error" style="font-size: 13px;" class="text-muted">Are you sure of this action? you can't undo your action after this.</span>
                    <div class="mt-4 d-flex">
                        <button type="button" id="destroy-user" class="y-btn d-flex align-items-center me-2 w-50">
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

<!-- Update product modal -->
<div class="modal fade" id="iid-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- form -->
                            <form method="POST" autocomplete="off" id="iid-form" class="p-5">
                                @csrf
                                <h1 class="fs-4 card-title fw-bolder mb-4">Bind Inventory </h1>
                                <div class="mb-1">
                                    <label for="e-product-name" class="form-label text-muted c-fs">Inventory id</label>
                                    <p style="font-size: 12px;" class="text-danger">Note : By Entering a valid inventory key you will be able to get all the data from the inventory you are inheriting. you will be logout after this.</p>
                                    <div class="input-group">
                                        <div class="input-group-text" id="basicAddon1"><i class="bx bx-info-square"></i></div>
                                        <input id="iid" type="text" name="inventory_id" placeholder="Inventory id" class="form-control" />
                                    </div>
                                    <span class="error-text error_key"></span>
                                    <span class="error-text error_inventory_id"></span>
                                </div>
                                <div class="mt-4 d-flex">
                                    <button id="btn-iid" type="submit" class="btn btn-danger d-flex align-items-center me-2 w-50">
                                        <i class="bx bx-check fs-4 me-1"></i> Bind
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

<!-- generate confirmation modal -->
<div class="modal fade" id="confirm-generate-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class=" flex-alert-container">
                <div class="flex-alert-header p-5 rounded-left">
                    <i class="bx bx-question-mark mx-1 text-primary" style="font-size: 5em;"></i>
                </div>
                <div class="flex-alert-body bg-white p-5">
                    <h1 class="fs-3 card-title">Generate new key?</h1>
                    <span id="msg-error" style="font-size: 13px;" class="text-danger">Are you sure about this action? generating a new key means generating new inventory your current data will be lost. you will be redirected again to the dashboard after this.</span>
                    <div class="mt-4 d-flex">
                        <button type="button" id="confirm-generate" class="y-btn d-flex align-items-center me-2 w-50">
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
<script>
    $(document).ready(function() {

        $('#btnget-geo').on('click', function(e) {
            e.preventDefault()
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showposition, showErr);
            } else {
                alert('Your browser doesn\'t support geolocation');
            }
        })

        function showposition(position) {
            $('#t_lat').val(position.coords.latitude)
            $('#t_long').val(position.coords.longitude)
        }

        function showErr(error) {
            if (error.PERMISSION_DENIED) {
                alert('Please make sure that you have internet connection and you\'ve turned on your geolocation.')
            }
        }
        $('#btn-generate-iid').on('click', function() {
            $('#confirm-generate-modal').modal('show')
        })
        $('#confirm-generate').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('key.generate')}}",
                method: 'get',
                processData: false,
                contentType: false,
                beforeSend: () => {},
                success: (data) => {
                    if (data.status == 200) {
                        $('#confirm-generate-modal').modal('hide')
                        $('#msg-content').text(data.msg)
                        $('#alert-modal-success').modal('toggle');
                        window.location.reload()

                    }
                }
            })
        })
        $('#btn-show-iid').on('click', function() {
            $('#iid').css('display', 'block')
        })
        $('#basic-information-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('basicinfo.update')}}",
                method: 'post',
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('#basic-information-form :input').prop("disabled", true);
                    $('.error-text').text('')
                },
                success: (data) => {
                    $('#basic-information-form :input').prop("disabled", false);
                    if (data.status == 0) {
                        $.each(data.error, function(prefix, val) {
                            $('.error_' + prefix).text(val[0]);
                        })
                    } else {
                        $('#msg-content').text(data.msg)
                        $('#alert-modal-success').modal('toggle');
                    }
                }
            })
        })

        $('#password-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('settingpassword.update')}}",
                method: 'post',
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('#password-form :input').prop("disabled", true);
                    $('.error-text').text('')
                },
                success: (data) => {
                    $('#password-form :input').prop("disabled", false);
                    if (data.status == 2) {
                        $('.error_old_password').text(data.error);
                    }
                    if (data.status == 0) {
                        $.each(data.error, function(prefix, val) {
                            $('.error_' + prefix).text(val[0]);
                        })
                    }
                    if (data.status == 200) {
                        $('#password-form')[0].reset()
                        $('#msg-content').text(data.msg)
                        $('#alert-modal-success').modal('toggle');
                    }
                }
            })
        })

        $('#address-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('storeinfo.update')}}",
                method: 'post',
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('#address-form :input').prop("disabled", true);
                    $('.error-text').text('')
                },
                success: (data) => {
                    $('#address-form :input').prop("disabled", false);
                    if (data.status == 0) {
                        $.each(data.error, function(prefix, val) {
                            $('.error_' + prefix).text(val[0]);
                        })
                    }
                    if (data.status == 200) {
                        $('#msg-content').text(data.msg)
                        $('#alert-modal-success').modal('toggle');
                    }
                }
            })
        })

        $('#btn-destroy-user').on('click', function(e) {
            $('#destroy-modal').modal('toggle')
        })
        $('#destroy-user').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('user.destroy')}}",
                method: 'get',
                processData: false,
                contentType: false,
                beforeSend: () => {},
                success: (data) => {
                    if (data.status == 200) {
                        $('#destroy-modal').modal('toggle')
                        $('#msg-content').text(data.msg)
                        $('#alert-modal-success').modal('toggle');
                        window.location.replace(data.link);
                    }
                }
            })
        })

        $('#pwdhide').on('click', function() {
            const pwd = document.getElementById('validationCustom05');
            const confirm_pwd = document.getElementById('validationCustom06');
            if ($('#pwdhide').hasClass('bx-hide')) {
                $('#pwdhide').removeClass('bx-hide');
                $('#pwdhide').addClass('bx-show');
                pwd.type = "text";
                confirm_pwd.type = "text";

            } else {
                $('#pwdhide').removeClass('bx-show');
                $('#pwdhide').addClass('bx-hide');
                pwd.type = "password";
                confirm_pwd.type = "password";

            }
        })

        $('#iid-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('inventory.bind')}}",
                method: 'post',
                data: new FormData(this),
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $('#iid-form :input').prop("disabled", true);
                    $('.error-text').text('')
                },
                success: (data) => {
                    $('#iid-form :input').prop("disabled", false);
                    if (data.status == 400) {
                        console.log(data.msg)
                        $('.error_key').text(data.msg);
                    }
                    if (data.status == 0) {
                        $.each(data.error, function(prefix, val) {
                            $('.error_' + prefix).text(val[0]);
                        })
                    }
                    if (data.status == 200) {
                        $('#iid-form')[0].reset()
                        $('#iid-modal').modal('hide');
                        $('#msg-content').text(data.msg)
                        $('#alert-modal-success').modal('toggle');
                        window.location.reload()
                    }
                }
            })
        })
    })
</script>