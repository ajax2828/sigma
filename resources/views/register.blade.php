<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>{{ env('APP_NAME') }}</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('storage/img/LOGO UKM SPM.jpg') }}" rel="icon">
    <link href="{{ asset('storage/img/LOGO UKM SPM.jpg') }}" rel="apple-touch-icon">

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
        @media (max-width: 720px) {
            #qr-video {
                aspect-ratio: 9 / 16 !important;
                height: calc(100% - 100px);
                min-width: 320px;
                min-height: 320px;
            }
        }

        @media (min-width: 768px) and (max-width: 1024px) {
            #qr-video {
                aspect-ratio: 9 / 12 !important;
                height: calc(100% - 100px);
            }
        }

        .success-border {
            border-color: #28a745 !important;
        }

        .error-border {
            border-color: #dc3545 !important;
        }

        .border-success {
            border-color: #28a745 !important;
        }

        .border-error {
            border-color: #dc3545 !important;
        }

        .fade-in {
            opacity: 1;
            transition: opacity 0.5s ease-in;
        }

        .fade-out {
            opacity: 0;
            transition: opacity 0.5s ease-out;
        }

        /* Custom SweetAlert2 Theme */
        .swal2-popup {
            background-color: #1a1a1a !important;
            color: #fff !important;
            border-radius: 10px !important;
        }

        .swal2-title {
            color: #fff !important;
        }

        .swal2-html-container {
            color: #ccc !important;
        }

        .swal2-confirm,
        .swal2-cancel {
            background-color: #f4a261 !important;
            /* Orange from the page */
            color: #fff !important;
            border: none !important;
        }

        .swal2-confirm:hover,
        .swal2-cancel:hover {
            background-color: #e76f51 !important;
            /* Darker orange on hover */
        }

        .swal2-loader {
            border-top-color: #f4a261 !important;
            border-left-color: #f4a261 !important;
        }
    </style>
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container mt-3">
            <a href="" class="logo items-center text-center me-auto me-xl-0">
                <h1 class="sitename">Galery Investasi</h1>
            </a>
        </div>
    </header>

    <main class="main">
        <!-- Contact Section -->
        <section id="contact" class="contact section">
            <div class="container pt-5" data-aos="fade-up" data-aos-delay="100">
                <div class="row mt-5">
                    <div class="col-lg-6 mb-5" data-aos="fade-right" data-aos-delay="200">
                        <div class="contact-info-section">
                            <div class="contact-info-grid">
                                <div class="info-item" data-aos="zoom-in" data-aos-delay="250">
                                    <div class="info-icon">
                                        <i class="bi bi-book-fill"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>Tema</h5>
                                        <p>Lorem ipsum dolor sit amet.</p>
                                    </div>
                                </div>

                                <div class="info-item" data-aos="zoom-in" data-aos-delay="250">
                                    <div class="info-icon">
                                        <i class="bi bi-journal-text"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>Judul</h5>
                                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Esse, possimus!</p>
                                    </div>
                                </div>

                                <div class="info-item" data-aos="zoom-in" data-aos-delay="400">
                                    <div class="info-icon">
                                        <i class="bi bi-clock-fill"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>Tanggal dan Waktu</h5>
                                        <p>Tanggal: 10 08 2025</p>
                                        <p>Waktu: 8AM - 7PM</p>
                                    </div>
                                </div>

                                <div class="info-item" data-aos="zoom-in" data-aos-delay="250">
                                    <div class="info-icon">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>Tempat</h5>
                                        <p>Universitas Yatsi Madani</p>
                                        <p>Jl. Aria Santika No.40A, RT.005/RW.011, Margasari, Kec. Karawaci, Kota
                                            Tangerang, Banten 15114</p>
                                    </div>
                                </div>

                                <div class="info-item" data-aos="zoom-in" data-aos-delay="350">
                                    <div class="info-icon">
                                        <i class="bi bi-telephone-fill"></i>
                                    </div>
                                    <div class="info-content">
                                        <h5>Kontak Info</h5>
                                        <p>+62 646-892-3456</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                        <div class="contact-form-wrapper">
                            <div class="form-header">
                                <h3>Registrasi Seminar</h3>
                            </div>

                            <form action="{{ route('registrasi.Seminar') }}" method="POST" class="form-regist"
                                id="registrationForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="contactName" class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" id="contactName"
                                        placeholder="Masukan Nama Lengkap Anda" required>
                                </div>

                                <div class="mb-3">
                                    <label for="contactEmail" class="form-label">Email Aktif</label>
                                    <input type="email" class="form-control" name="email" id="contactEmail"
                                        placeholder="Masukan Alamat Email Anda" required>
                                </div>

                                <div class="mb-3">
                                    <label for="contactPhone" class="form-label">Nomor HP / WhatsApp</label>
                                    <input type="tel" class="form-control" name="phone" id="contactPhone"
                                        placeholder="Masukkan Nomor Ponsel Anda" required>
                                </div>
                                <div class="mb-3">
                                    <label for="contactInstitute" class="form-label">Asal Institusi / Organisasi /
                                        Universitas</label>
                                    <input type="text" class="form-control" name="Institute"
                                        id="contactInstitute"
                                        placeholder="Masukan Asal Institusi / Organisasi / Universitas Anda" required>
                                </div>
                                <input type="hidden" name="study" value="-">
                                <div class="mb-5">
                                    <label for="statusUser" class="form-label">Status Peserta</label>
                                    <select class="form-select form-control" aria-label="Default select example"
                                        id="statusUser" name="user_status" required>
                                        <option selected>Pilih Status Anda</option>
                                        <option value="Mahasiswa">Mahasiswa</option>
                                        <option value="Dosen">Dosen</option>
                                        <option value="Profesional">Profesional</option>
                                        <option value="Umum">Umum</option>
                                    </select>
                                </div>

                                <button type="submit" class="submit-btn">
                                    <span>Registrasi</span>
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
                Designed and created by <a href="https://khaerulummam.github.io/" target="blank">khaerul Ummam Aulia
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

    <!-- Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- SweetAlert2 Form Submission with Loader -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                // Show custom loading popup
                Swal.fire({
                    title: 'Sedang memproses...',
                    text: 'Silakan tunggu sementara pendaftaran Anda sedang diproses.',
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
                    showConfirmButton: false,
                    background: '#1a1a1a',
                    color: '#fff',
                    loaderHtml: '<div class="swal2-loader" style="border-top-color: #f4a261; border-left-color: #f4a261;"></div>'
                });

                // Submit form via AJAX
                fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close(); // Close the loading popup
                        if (data.success) {
                            // Success notification
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: data.success,
                                confirmButtonText: 'OK',
                                background: '#1a1a1a',
                                color: '#fff',
                                confirmButtonColor: '#f4a261'
                            }).then(() => {
                                form.reset(); // Reset form after success
                            });
                        } else if (data.error) {
                            // Error notification
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.error,
                                confirmButtonText: 'OK',
                                background: '#1a1a1a',
                                color: '#fff',
                                confirmButtonColor: '#f4a261'
                            });
                        } else if (data.errors) {
                            // Validation errors
                            let errorList = '<ul>';
                            Object.values(data.errors).forEach(error => {
                                errorList += `<li>${error[0]}</li>`;
                            });
                            errorList += '</ul>';
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: errorList,
                                confirmButtonText: 'OK',
                                background: '#1a1a1a',
                                color: '#fff',
                                confirmButtonColor: '#f4a261'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close(); // Close the loading popup
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan yang tidak terduga. Silakan coba lagi nanti.',
                            confirmButtonText: 'OK',
                            background: '#1a1a1a',
                            color: '#fff',
                            confirmButtonColor: '#f4a261'
                        });
                    });
            });

            // Handle session-based notifications (for non-AJAX fallback)
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
                    text: '{{ session('error') }}',
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
