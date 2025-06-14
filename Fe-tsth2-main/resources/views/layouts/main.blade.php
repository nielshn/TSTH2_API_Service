<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{ asset($web['web_logo']) }}" type="image/png">
    <title>{{ $web['web_nama'] }}</title>
    @stack('css')

    <link href="{{ asset('template/assets/fonts/inter/inter.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Font Awesome (icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link href="{{ asset('template/assets/icons/phosphor/styles.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/ltr/all.min.css') }}" id="stylesheet" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet" />
    <link href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/tippy.js@6/themes/light.css" rel="stylesheet" />
    <link href="{{ asset('template/assets/icons/icomoon/styles.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/icons/material/styles.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/assets/icons/fontawesome/styles.min.css') }}" rel="stylesheet">

    <style>
        .loader-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loader {
            position: relative;
            width: 100%;
            height: 100%;
            background: #fff;
            border-radius: 4px;
        }

        .loader:before {
            content: '';
            position: absolute;
            width: 54%;
            height: 19%;
            left: 50%;
            top: 0;
            background-image:
                radial-gradient(ellipse at center, #0000 24%, #de3500 25%, #de3500 64%, #0000 65%),
                linear-gradient(to bottom, #0000 34%, #de3500 35%);
            background-size: 12%;
            background-repeat: no-repeat;
            background-position: center top;
            transform: translate(-50%, -65%);
            box-shadow: 0 -3px rgba(0, 0, 0, 0.25) inset;
        }

        .loader:after {
            content: '';
            position: absolute;
            left: 50%;
            top: 20%;
            transform: translateX(-50%);
            width: 66%;
            height: 60%;
            background: linear-gradient(to bottom, #f79577 30%, #0000 31%);
            background-size: 100% 15%;
            animation: writeDown 2s ease-out infinite;
        }

        @keyframes writeDown {
            0% {
                height: 0%;
                opacity: 0;
            }

            20% {
                height: 0%;
                opacity: 1;
            }

            80% {
                height: 65%;
                opacity: 1;
            }

            100% {
                height: 65%;
                opacity: 0;
            }
        }
    </style>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="page-loader"
        style="position: fixed; z-index: 9999; background-color: #010a26; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
        <div class="loader-container" style="width: 80px; height: 104px;">
            <span class="loader"></span>
        </div>
    </div>

    @include('layouts.navbar')

    <div class="page-content">
        <div class="sidebar sidebar-dark sidebar-main sidebar-expand-lg">
            @include('layouts.sidebar')
        </div>

        <div class="content-wrapper">
            <div class="content-inner">
                @include('components.page_header')

                <div class="content">
                    @yield('content')
                </div>

                @include('layouts.footer')
            </div>
        </div>
    </div>

    @include('components.demo_config')
    @include('components.notifications')

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="{{ asset('template/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/d3.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/vendor/notifications/noty.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/vendor/notifications/sweet_alert.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
    <script src="{{ asset('template/assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/vendor/tables/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/vendor/tables/datatables/extensions/pdfmake/vfs_fonts.min.js') }}">
    </script>
    <script src="{{ asset('template/assets/js/vendor/tables/datatables/extensions/buttons.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/vendor/tables/datatables/extensions/key_table.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/vendor/ui/fullcalendar/main.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs@2.9.3/dist/parsley.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>
    <script src="{{ asset('template/assets/demo/demo_configurator.js') }}"></script>
    <script src="{{ asset('template/assets/demo/pages/datatables_extension_buttons_html5.js') }}"></script>
    <script src="{{ asset('template/assets/demo/pages/datatables_extension_key_table.js') }}"></script>
    <script src="{{ asset('template/assets/demo/pages/fullcalendar_styling.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('template/assets/demo/pages/dashboard.js') }}"></script>

    <script>
        if (typeof d3.arc === 'function') {
            d3.svg = d3.svg || {};
            d3.svg.arc = d3.arc;
        }
    </script>
    <script src="{{ asset('template/assets/demo/charts/pages/dashboard/streamgraph.js') }}"></script>
    <script src="{{ asset('template/assets/demo/charts/pages/dashboard/progress.js') }}"></script>
    <script src="{{ asset('template/assets/demo/charts/pages/dashboard/heatmaps.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loader = document.getElementById('page-loader');
            if (loader) {
                window.addEventListener('load', function () {
                    loader.style.transition = 'opacity 0.3s ease';
                    loader.style.opacity = '0';
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 300);
                });

                setTimeout(() => {
                    if (loader.style.display !== 'none') {
                        loader.style.transition = 'opacity 0.3s ease';
                        loader.style.opacity = '0';
                        setTimeout(() => {
                            loader.style.display = 'none';
                        }, 300);
                    }
                }, 700);
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swalCombineElement = document.querySelector('#sweet_combine');
            if (swalCombineElement) {
                swalCombineElement.addEventListener('click', function () {
                    Swal.fire({
                        title: 'Logout Confirmation',
                        text: 'Are you sure you want to logout now?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: '<i class="ph-sign-out"></i> Yes, log me out',
                        cancelButtonText: '<i class="ph-x-circle"></i> Nope, stay logged in',
                        buttonsStyling: false,
                        reverseButtons: true,
                        background: '#fdfdfd',
                        color: '#333',
                        iconColor: '#0d6efd',
                        customClass: {
                            popup: 'rounded-4 shadow-lg border border-light-subtle',
                            title: 'fw-semibold fs-5',
                            confirmButton: 'btn btn-primary px-4 py-2 me-2',
                            cancelButton: 'btn btn-outline-secondary px-4 py-2'
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            const logoutForm = document.getElementById('logoutForm');
                            if (logoutForm) {
                                logoutForm.submit();
                            }
                        }
                    });
                });
            }
        });
    </script>

    @stack('js')
</body>

</html>
