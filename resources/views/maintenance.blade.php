<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ env('APP_NAME') }}</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

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

    <main class="main">

        <!-- Contact Section -->
        <section id="hero" class="section m-0 p-0 flex flex-column justify-content-center align-items-center"
            style="min-height: 80vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">

            <div class="container">
                <div class="hero-wrapper text-center">

                    <div class="hero-main-content">
                        <h1 class="hero-title" data-aos="zoom-in" data-aos-delay="200">
                            Oops…<br>
                            <span class="typed"
                                data-typed-items="Advanced Analytics,Seamless Integration,Robust Security"></span>
                        </h1>
                        <h1 class="hero-title" data-aos="zoom-in" data-aos-delay="200">
                            Kami Lagi Beres-Beres! 🛠️<br>
                            <span class="typed"
                                data-typed-items="Advanced Analytics,Seamless Integration,Robust Security"></span>
                        </h1>

                        <p class="hero-description" data-aos="fade-up" data-aos-delay="300">
                            Sebentar ya, kami sedang merapikan dan menyempurnakan sistem supaya Anda bisa menikmati
                            layanan kami dengan lebih baik lagi.
                        </p>
                        <p class="hero-description" data-aos="fade-up" data-aos-delay="300">
                            Kembali online sebentar lagi. Terima kasih sudah menunggu!
                        </p>
                    </div>

                </div>
            </div>
        </section>
        <!-- /Contact Section -->

    </main>


    <footer id="footer" class="footer light-background">

        <div class="container copyright text-center">
            <div class="credits">
                Designed and created by <a href="https://khaerulummam.github.io/" target="blank">khaerul Ummam Aulia
                    Fadillah | Ilmu Komputer</a>
            </div>
        </div>

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

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



    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/typed.js') }}/typed.umd.js')}}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

</body>

</html>
