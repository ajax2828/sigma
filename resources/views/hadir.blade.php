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

    <main class="main mt-5" style="margin-top: 1000px;">
        <!-- About Section -->
        <!-- Team Section -->
        <section id="team" class="team section py-0">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="team-header" data-aos="fade-up" data-aos-delay="200">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h2>Daftar Peserta</h2>
                        </div>
                        <div class="col-lg-6 d-flex justify-content-lg-end contact-form-wrapper">
                            <form id="searchForm" class="form-regist">
                                <div class="mb-2">
                                    <input type="text" name="name" class="form-control w-150" id="contactName"
                                        placeholder="Cari Daftar Peserta" required>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="team-slider swiper init-swiper p-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="row g-4" id="registrantsList">
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Institusi</th>
                                    <th>Email</th>
                                    <th>file</th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($data as $index => $registrant)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $registrant->name }}</td>
                                        <td>{{ $registrant->Institute }}</td>
                                        <td>{{ $registrant->email }}</td>
                                        <td>
                                            @if (!empty($files[$registrant->id]))
                                                {{ $files[$registrant->id] }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                {{-- <tr>
                                    <td>1</td>
                                    <td>John Doe</td>
                                    <td>Universitas Indonesia</td>
                                    <td>example@test.com</td>
                                </tr> --}}
                            </tbody>
                        </table>
                        {{-- @foreach ($registrants as $registrant)
                            <div class="col-md-3">
                                <div class="team-member">
                                    <div class="member-image">
                                        <div class="text-center p-3 bg-white">
                                            <img src="{{ asset('storage/' . $registrant->qr_code_path) }}"
                                                class="img-fluid" alt="" loading="lazy" style="padding: 20px;">
                                        </div>
                                    </div>
                                    <div class="member-content">
                                        <h3>{{ $registrant->name }}</h3>
                                        <span class="mb-0">{{ $registrant->study }} |
                                            {{ $registrant->Institute }}</span>
                                        <p>{{ $registrant->email }}</p>
                                        <p>{{ $registrant->phone }}</p>
                                        <div
                                            class="cta-buttons d-flex align-items-center gap-3 justify-content-between mt-3">
                                            <a href="#" class="btn btn-warning rounded text-white">Kirim Ulang
                                                Tiket</a>
                                            @if ($registrant->is_scanned)
                                                <span
                                                    class="badge bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-0"
                                                    style="width: 30px; height: 30px;">Sudah Di-Scan</span>
                                            @else
                                                <span
                                                    class="badge bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-0"
                                                    style="width: 30px; height: 30px;">belum Di-Scan</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach --}}
                    </div>
                    <div class="pagination" id="pagination"></div>
                </div>
            </div>
        </section><!-- /Team Section -->
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
    @if (session('status'))
        <script>
            Swal.fire({
                position: "top-end",
                text: '{{ session('status') }}',
                customClass: {
                    popup: 'custom-swal-success' // CSS kustom
                },
                showConfirmButton: false,
                timer: 5000
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                position: "top-end",
                text: '{{ session('status') }}',
                customClass: {
                    popup: 'custom-swal-error' // CSS kustom
                },
                showConfirmButton: false,
                timer: 5000
            });
        </script>
    @endif


</body>

</html>
