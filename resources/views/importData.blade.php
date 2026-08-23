<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ env('APP_NAME') }}</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link href="{{ asset('storage/img/LOGO UKM SPM.jpg') }}" rel="icon">
    <link href="{{ asset('storage/img/LOGO UKM SPM.jpg') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/toastr/toastr.min.css') }}" rel="stylesheet">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1 0 auto;
            /* Mengisi ruang yang tersedia */
        }

        footer {
            flex-shrink: 0;
            /* Mencegah footer menyusut */
            margin-top: auto;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
        }

        .pagination button {
            padding: 5px 10px;
            border: 1px solid #ccc;
            background: #fff;
            cursor: pointer;
        }

        .pagination button:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        .badge-circle {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 14px;
        }

        .custom-swal-success {
            border: 2px solid #28a745;
            border-radius: 10px;

        }

        .custom-swal-error {
            border: 2px solid #dc3545;
            border-radius: 10px;
        }
    </style>
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container position-relative d-flex align-items-center justify-content-between">
            <a href="" class="logo d-flex align-items-center me-auto me-xl-0">
                <h1 class="sitename">Galery Investasi</h1>
            </a>
            <x-navbar></x-navbar>
        </div>
    </header>

    <main class="main">
        <!-- Contact Section -->
        <section id="contact" class="contact section">
            <div class="container pt-5" data-aos="fade-up" data-aos-delay="100">
                <div class="row mt-5 justify-content-center">
                    <div class="col-lg-6 mx-auto" data-aos="fade-left" data-aos-delay="200">
                        <div class="contact-form-wrapper">
                            <div class="form-header">
                                <h3>Registrasi Seminar</h3>
                            </div>

                            <form action="{{ route('registrasi.Seminar.import') }}" method="POST" class="form-regist"
                                id="importForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="excel" class="form-label">Import Data Excel</label>
                                    <input type="file" name="excel" class="form-control" id="excel"
                                        placeholder="Import Data Excel" required>
                                    <small class="text-muted">Format: xlsx, xls, csv. Pastikan kolom sesuai (Email,
                                        Nama, HP, Institusi, Status).</small>
                                </div>

                                <button type="submit" class="submit-btn">
                                    <span>Import</span>
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /Contact Section -->
    </main>

    <footer id="footer" class="footer light-background">
        <div class="container copyright text-center">
            <div class="credits">
                Designed and created by <a href="https://khaerulummam.github.io/" target="blank">Khaerul Ummam Aulia
                    Fadillah | Ilmu Komputer</a>
            </div>
        </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/typed.js/typed.umd.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/toastr/toastr.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <!-- Existing SweetAlert2 Script Section -->
    <!-- Inside the <body> tag, after vendor JS files -->
    <!-- Inside the <body> tag, after vendor JS files -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const importForm = document.getElementById('importForm');

            // Handle Import Form Submission
            if (importForm) {
                importForm.addEventListener('submit', function(event) {
                    event.preventDefault();

                    Swal.fire({
                        title: 'Processing...',
                        text: 'Import has started in the background. You can continue using the application.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'swal2-popup',
                            title: 'swal2-title',
                            htmlContainer: 'swal2-html-container',
                            confirmButton: 'swal2-confirm'
                        },
                        timer: 3000, // Auto-close after 3 seconds
                        timerProgressBar: true,
                        showConfirmButton: false,
                        background: '#1a1a1a',
                        color: '#fff',
                        loaderHtml: '<div class="swal2-loader" style="border-top-color: #f4a261; border-left-color: #f4a261;"></div>'
                    }).then(() => {
                        fetch(importForm.action, {
                                method: 'POST',
                                body: new FormData(importForm),
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Server error');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: data.success,
                                        confirmButtonText: 'OK',
                                        background: '#1a1a1a',
                                        color: '#fff',
                                        confirmButtonColor: '#f4a261'
                                    }).then(() => {
                                        importForm.reset();
                                    });
                                } else if (data.error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Import gagal: ' + data.error,
                                        confirmButtonText: 'OK',
                                        background: '#1a1a1a',
                                        color: '#fff',
                                        confirmButtonColor: '#f4a261'
                                    });
                                }
                            })
                            .catch(error => {
                                // Only trigger if there's a network or server error
                                if (error.message !== 'Server error') {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'A network error occurred. Please try again later or contact support.',
                                        confirmButtonText: 'OK',
                                        background: '#1a1a1a',
                                        color: '#fff',
                                        confirmButtonColor: '#f4a261'
                                    });
                                }
                            });
                    });
                });
            }

            // Handle session-based notifications
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK',
                    background: '#1a1a1a',
                    color: '#fff',
                    confirmButtonColor: '#f4a261'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Pendaftaran/Import gagal: {{ session('error') }}',
                    confirmButtonText: 'OK',
                    background: '#1a1a1a',
                    color: '#fff',
                    confirmButtonColor: '#f4a261'
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: `<ul>${@foreach ($errors->all() as $error)
                    '<li>{{ $error }}</li>'
                @endforeach}</ul>`,
                    confirmButtonText: 'OK',
                    background: '#1a1a1a',
                    color: '#fff',
                    confirmButtonColor: '#f4a261'
                });
            @endif
        });
    </script>


</body>

</html>
