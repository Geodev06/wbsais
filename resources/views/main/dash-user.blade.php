<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>WBSAIS Dashboard</title>

  <link rel="stylesheet" href="{{ asset('assets/bs/css/bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/bs/boxicons.min.css') }}" />
  <script src="{{ asset('assets/js/jquery-3.5.1.js')}}"></script>
  <script src="{{ asset('assets/js/popper.min.js') }}"></script>
  <script src="{{ asset('assets/bs/js/bootstrap.bundle.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />

  <style type="text/css">
    .custom-i-size {
      font-size: 42px;
    }

    :root {
      --header-height: 3rem;
      --nav-width: 68px;
      --first-color: rgb(54, 54, 54);
      --first-color-light: #afa5d9;
      --white-color: #f7f6fb;
      --body-font: "Nunito", sans-serif;
      --normal-font-size: 1rem;
      --z-fixed: 100;
    }

    *,
    ::before,
    ::after {
      box-sizing: border-box;
    }

    body {
      position: relative;
      margin: var(--header-height) 0 0 0;
      padding: 0 1rem;
      font-family: var(--body-font);
      font-size: var(--normal-font-size);
      transition: 0.5s;
    }

    a {
      text-decoration: none;
    }

    .header {
      width: 100%;
      height: var(--header-height);
      position: fixed;
      top: 0;
      left: 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1rem;
      background-color: var(--white-color);
      z-index: var(--z-fixed);
      transition: 0.5s;
    }

    .header_toggle {
      color: var(--first-color);
      font-size: 1.5rem;
      cursor: pointer;
    }

    .l-navbar {
      position: fixed;
      top: 0;
      left: -30%;
      width: var(--nav-width);
      height: 100vh;
      background-color: var(--first-color);
      padding: 0.5rem 1rem 0 0;
      transition: 0.5s;
      z-index: var(--z-fixed);
    }

    .nav {
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      overflow: hidden;
    }

    .nav a {
      font-size: 13px;
      letter-spacing: 1px;
    }

    .nav_logo,
    .nav_link {
      display: grid;
      grid-template-columns: max-content max-content;
      align-items: center;
      column-gap: 1rem;
      padding: 0.5rem 0 0.5rem 1.5rem;
    }

    .nav_logo {
      margin-bottom: 2rem;
    }

    .nav_logo-icon {
      font-size: 1.25rem;
      color: var(--white-color);
    }

    .nav_logo-name {
      color: var(--white-color);
      font-weight: 700;
    }

    .nav_link {
      position: relative;
      color: var(--first-color-light);
      margin-bottom: 10px;
      transition: 0.3s;
    }

    .nav_link:hover {
      color: var(--white-color);
    }

    .nav_icon {
      font-size: 1.25rem;
    }

    .show_ {
      left: 0;
    }

    .body-pd {
      padding-left: calc(var(--nav-width) + 1rem);
    }

    .active {
      color: var(--white-color);
    }

    .active::before {
      content: "";
      position: absolute;
      left: 0;
      width: 2px;
      height: 32px;
      background-color: var(--white-color);
    }

    .height-100 {
      height: 100vh;
    }

    @media screen and (min-width: 768px) {
      body {
        margin: calc(var(--header-height) + 1rem) 0 0 0;
        padding-left: calc(var(--nav-width) + 2rem);
      }

      .header {
        height: calc(var(--header-height) + 1rem);
        padding: 0 2rem 0 calc(var(--nav-width) + 2rem);
      }

      .l-navbar {
        left: 0;
        padding: 1rem 1rem 0 0;
      }

      .show_ {
        width: calc(var(--nav-width) + 156px);
      }

      .body-pd {
        padding-left: calc(var(--nav-width) + 188px);
      }
    }

    .icon-container {
      height: 60px;
      width: 60px;
      background: linear-gradient(to top left, #660066 0%, #cc0099 100%);
      border-radius: 12px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .icon-container i {
      font-size: 40px;
    }

    .ds-name {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: rgb(45, 45, 45);
      font-weight: 600;
    }
  </style>
</head>

<body id="body-pd">
  <header class="header" id="header">
    <div class="header_toggle">
      <i class="bx bx-menu" id="header-toggle"> <span class="ds-name"></span></i>
    </div>
  </header>

  <div class="l-navbar" id="nav-bar">
    <nav class="nav">
      <div>
        <a href="#" class="nav_logo">
          <i class="bx bx-layer nav_logo-icon"></i>
          <span class="nav_logo-name">WBSAIS</span>
        </a>

        <div class="nav_list">
          <a href="" class="nav_link active" id="dashboard">
            <i class="bx bx-home-alt-2 nav_icon"></i>
            <span class="nav_name">Dashboard</span>
          </a>
          <a href="" class="nav_link" id="product-view">
            <i class="bx bx-grid-alt nav_icon"></i>
            <span class="nav_name">Products</span>
          </a>
          <a href="" class="nav_link" id="category-view">
            <i class="bx bx-category nav_icon"></i>
            <span class="nav_name">Categories</span>
          </a>
          <a href="" class="nav_link" id="supplier-view">
            <i class="bx bx-package nav_icon"></i>
            <span class="nav_name">Suppliers</span>
          </a>
          <a href="" class="nav_link" id="sale-view">
            <i class="bx bx-cart-alt nav_icon"></i>
            <span class="nav_name">Sale</span>
          </a>
          <a href="" class="nav_link" id="setting-view">
            <i class="bx bx-user nav_icon"></i>
            <span class="nav_name">Profile</span>
          </a>
          <a href="" class="nav_link" id="report-view">
            <i class="bx bx-receipt nav_icon"></i>
            <span class="nav_name">Reports</span>
          </a>
        </div>
      </div>
      <a href="" id="btn-log-out" class="nav_link">
        <i class="bx bx-log-out nav_icon"></i>
        <span class="nav_name">SignOut</span>
      </a>
    </nav>
  </div>

  <!--Container Main start-->
  <div class="loader-container" id="loader">
    <img src="{{asset('assets/img/load.gif')}}" />
  </div>
  <div class="">
    <div class="container " id="content">
    </div>
  </div>
  <!-- logout modal -->
  <div class="modal fade" id="logout-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0">
        <div class=" flex-alert-container">
          <div class="flex-alert-header p-5 rounded-left">
            <i class="bx bx-question-mark mx-1 text-primary" style="font-size: 5em;"></i>
          </div>
          <div class="flex-alert-body bg-white p-5">
            <h1 class="fs-3 card-title">Sign out now?</h1>
            <span id="msg-error" style="font-size: 13px;" class="text-muted">Are you sure you want to sign out now?</span>
            <div class="mt-4 d-flex">
              <button type="button" id="confirm-logout" class="y-btn d-flex align-items-center me-2 w-50">
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
</body>
<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function(event) {
    const showNavbar = (toggleId, navId, bodyId, headerId) => {
      const toggle = document.getElementById(toggleId),
        nav = document.getElementById(navId),
        bodypd = document.getElementById(bodyId),
        headerpd = document.getElementById(headerId);
      // Validate that all variables exist
      if (toggle && nav && bodypd && headerpd) {
        toggle.addEventListener("click", () => {
          // show navbar
          nav.classList.toggle("show_");
          // change icon
          toggle.classList.toggle("bx-x");
          // add padding to body
          bodypd.classList.toggle("body-pd");
          // add padding to header
          headerpd.classList.toggle("body-pd");
        });
      }
    };
    showNavbar("header-toggle", "nav-bar", "body-pd", "header");
    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll(".nav_link");

    function colorLink() {
      if (linkColor) {
        linkColor.forEach((l) => l.classList.remove("active"));
        this.classList.add("active");
      }
    }
    linkColor.forEach((l) => l.addEventListener("click", colorLink));
    // Your code to run since DOM is loaded and ready
  });

  $(function() {
    //product fragment
    $('#dashboard').on('click', function(e) {
      e.preventDefault();
      $.ajax({
        url: "{{ route('dashboard.fragment') }}",
        type: 'get',
        dataType: 'json',
        processData: false,
        contentType: false,
        beforeSend: function() {
          $('#content').html('')
          $('#loader').css('display', 'flex')
        }
      }).done(function(data) {
        $('#loader').css('display', 'none')
        $('#content').html(data.content)
      });
    });
    //product fragment
    $('#product-view').on('click', function(e) {
      e.preventDefault();
      $.ajax({
        url: "{{ route('product.fragment')}}",
        type: 'get',
        dataType: 'json',
        processData: false,
        contentType: false,
        beforeSend: function() {
          $('#content').html('')
          $('#loader').css('display', 'flex')
        },
        success: function(data) {
          $('#loader').css('display', 'none')
          $('#content').html(data.content)
        }
      })
    })
    //category
    $('#category-view').on('click', function(e) {
      e.preventDefault();
      $.ajax({
        url: "{{ route('category.fragment')}}",
        type: 'get',
        dataType: 'json',
        processData: false,
        contentType: false,
        beforeSend: function() {
          $('#content').html('')
          $('#loader').css('display', 'flex')
        },
        success: function(data) {
          $('#loader').css('display', 'none')
          $('#content').html(data.content)
        }
      })
    })
    //supplier fragment
    $('#supplier-view').on('click', function(e) {
      e.preventDefault();
      $.ajax({
        url: "{{ route('supplier.fragment')}}",
        type: 'get',
        dataType: 'json',
        processData: false,
        contentType: false,
        beforeSend: function() {
          $('#content').html('')
          $('#loader').css('display', 'flex')
        },
        success: function(data) {
          $('#loader').css('display', 'none')
          $('#content').html(data.content)
        }
      })
    })
    //sale fragment
    $('#sale-view').on('click', function(e) {
      e.preventDefault();
      $.ajax({
        url: "{{ route('sale.fragment')}}",
        type: 'get',
        dataType: 'json',
        processData: false,
        contentType: false,
        beforeSend: function() {
          $('#content').html('')
          $('#loader').css('display', 'flex')
        },
        success: function(data) {
          $('#loader').css('display', 'none')
          $('#content').html(data.content)
        }
      })
    })

    //setting fragment
    $('#setting-view').on('click', function(e) {
      e.preventDefault();
      $.ajax({
        url: "{{ route('setting.fragment')}}",
        type: 'get',
        dataType: 'json',
        processData: false,
        contentType: false,
        beforeSend: function() {
          $('#content').html('')
          $('#loader').css('display', 'flex')
        },
        success: function(data) {
          $('#loader').css('display', 'none')
          $('#content').html(data.content)
        }
      })
    })

    //report fragment
    $('#report-view').on('click', function(e) {
      e.preventDefault();
      $.ajax({
        url: "{{ route('report.fragment')}}",
        type: 'get',
        dataType: 'json',
        processData: false,
        contentType: false,
        beforeSend: function() {
          $('#content').html('')
          $('#loader').css('display', 'flex')
        },
        success: function(data) {
          $('#loader').css('display', 'none')
          $('#content').html(data.content)
        }
      })
    })
    //logout
    $('#btn-log-out').on('click', function(e) {
      $('#logout-modal').modal('toggle')
      e.preventDefault()
    });

    $('#confirm-logout').on('click', function(e) {
      e.preventDefault();
      $.ajax({
        url: "{{ route('logout') }} ",
        type: 'get',
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(data) {
          if (data.status == 1) {
            window.location.replace("{{ route('wbsais.login') }}");
          }
        }
      });
    });

  })

  $(window).on('load', function() {
    $.ajax({
      url: "{{ route('report.fragment') }}",
      type: 'get',
      dataType: 'json',
      processData: false,
      contentType: false,
      beforeSend: function() {
        $('#content').html('')
        $('#loader').css('display', 'flex')
      }
    }).done(function(data) {
      $('#loader').css('display', 'none')
      $('#content').html(data.content)
    })
  })
</script>


</html>