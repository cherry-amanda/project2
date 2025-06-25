<!DOCTYPE html>
<html lang="en">
 
<head>
  
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="{{asset('template')}}/assets/img/favicon.png">
  <meta name="csrf-token" content="{{ csrf_token() }}">


  
 
  <title>@yield('title_page')</title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="{{asset('template')}}/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="{{asset('template')}}/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="{{asset('template')}}/assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
  /* === THEME UPGRADE: Infinity Wedding Look === */
  body {
    background:rgb(111, 118, 148);
    font-family: 'Poppins', sans-serif;
    color: #444;
}

h1, h2, h3, h4, h5, h6 {
    font-weight: 600;
}

.dashboard-hero {
    background: linear-gradient(135deg,rgb(111, 118, 148),rgb(111, 118, 148));
    color: #fff;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.dashboard-hero h3 {
    font-size: 1.8rem;
    font-weight: 700;
}

.dashboard-hero p {
    font-size: 1rem;
    opacity: 0.95;
    font-weight: 400;
}

.stat-card {
    background:rgb(162, 177, 206);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    text-align: center;
    transition: 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    opacity: 0.85;
}

.stat-card .fw-bold {
    font-size: 1.6rem;
    font-weight: 600;
    color: #333;
}

.stat-card .text-muted {
    font-size: 0.9rem;
    color: #888;
}

.section-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #7e22ce;
    margin-bottom: 1rem;
}

.list-group-item {
    border: none;
    padding: 0.75rem 1rem;
    background-color: #f8f9fa;
    margin-bottom: 8px;
    border-radius: 10px !important;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: 0.3s ease;
}

.list-group-item:hover {
    background-color: #f3e8ff;
    color: #7e22ce;
}

.badge.bg-primary {
    background-color: #9333ea !important;
    font-size: 0.75rem;
    padding: 0.4em 0.8em;
    border-radius: 12px;
}

footer {
    text-align: center;
    margin-top: 2rem;
    font-size: 0.9rem;
    color: #aaa;
}

.link-card {
    text-decoration: none;
    color: inherit;
}

.link-card:hover {
    text-decoration: none;
    color: inherit;
}


</style>


</head>
 
<body class="g-sidenav-show  bg-gray-100">
 
    @include('layout.v_nav')
 
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
          <div class="container-fluid py-1 px-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm">
                  <a class="opacity-5 text-dark" href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                @yield('breadcrumb')
              </ol>
            </nav>
            
              </div>
              <ul class="navbar-nav d-flex align-items-center  justify-content-end">
                <li class="nav-item d-flex align-items-center">
                  
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
        @yield('content')
 
    </main>
    {{-- <div id="content-wrapper" class="d-flex flex-column">
 
        <!-- Main Content -->
        @yield('content')
        <!-- End of Main Content -->
 
        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; SI-B 2025</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->
 
    </div> --}}
 
  <!--   Core JS Files   -->
  <script src="{{asset('template')}}/assets/js/core/popper.min.js"></script>
  <script src="{{asset('template')}}/assets/js/core/bootstrap.min.js"></script>
  <script src="{{asset('template')}}/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="{{asset('template')}}/assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="{{asset('template')}}/assets/js/plugins/chartjs.min.js"></script>
  <script>
    var ctx = document.getElementById("chart-bars").getContext("2d");
 
    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["M", "T", "W", "T", "F", "S", "S"],
        datasets: [{
          label: "Views",
          tension: 0.4,
          borderWidth: 0,
          borderRadius: 4,
          borderSkipped: false,
          backgroundColor: "#43A047",
          data: [50, 45, 22, 28, 50, 60, 76],
          barThickness: 'flex'
        }, ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5],
              color: '#e5e5e5'
            },
            ticks: {
              suggestedMin: 0,
              suggestedMax: 500,
              beginAtZero: true,
              padding: 10,
              font: {
                size: 14,
                lineHeight: 2
              },
              color: "#737373"
            },
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#737373',
              padding: 10,
              font: {
                size: 14,
                lineHeight: 2
              },
            }
          },
        },
      },
    });
 
 
    var ctx2 = document.getElementById("chart-line").getContext("2d");
 
    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"],
        datasets: [{
          label: "Sales",
          tension: 0,
          borderWidth: 2,
          pointRadius: 3,
          pointBackgroundColor: "#43A047",
          pointBorderColor: "transparent",
          borderColor: "#43A047",
          backgroundColor: "transparent",
          fill: true,
          data: [120, 230, 130, 440, 250, 360, 270, 180, 90, 300, 310, 220],
          maxBarThickness: 6
 
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            callbacks: {
              title: function(context) {
                const fullMonths = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                return fullMonths[context[0].dataIndex];
              }
            }
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [4, 4],
              color: '#e5e5e5'
            },
            ticks: {
              display: true,
              color: '#737373',
              padding: 10,
              font: {
                size: 12,
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#737373',
              padding: 10,
              font: {
                size: 12,
                lineHeight: 2
              },
            }
          },
        },
      },
    });
 
    var ctx3 = document.getElementById("chart-line-tasks").getContext("2d");
 
    new Chart(ctx3, {
      type: "line",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Tasks",
          tension: 0,
          borderWidth: 2,
          pointRadius: 3,
          pointBackgroundColor: "#43A047",
          pointBorderColor: "transparent",
          borderColor: "#43A047",
          backgroundColor: "transparent",
          fill: true,
          data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
          maxBarThickness: 6
 
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [4, 4],
              color: '#e5e5e5'
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#737373',
              font: {
                size: 14,
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [4, 4]
            },
            ticks: {
              display: true,
              color: '#737373',
              padding: 10,
              font: {
                size: 14,
                lineHeight: 2
              },
            }
          },
        },
      },
    });
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
 
</body>
 <style>
    .img-preview {
        transition: transform 0.2s ease;
    }

    .img-preview:hover {
        transform: scale(1.03);
        cursor: zoom-in;
    }
</style>

</html>