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
                transform: scaleX(-1);
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
    </style>
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container mt-3">

            <a href="" class="logo items-center text-center me-auto me-xl-0">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <h1 class="sitename">Galery Investasi</h1>
            </a>

        </div>
    </header>

    <main class="main">

        <!-- Hero Section -->
        <section>

        </section>

        <section id="hero" class="hero section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="form-check form-switch position-relative d-flex justify-content-center pb-3">
                    <input class="form-check-input" type="checkbox" value="" id="switch-input">
                    <label class="form-check-label ms-2" for="Switch" id="switch-label">
                        Kamera Depan
                    </label>
                </div>
                <div class="position-relative d-flex justify-content-center">

                    <div id="qr-video" class="w-100 rounded-4 shadow overflow-hidden border border-5 border-warning"
                        style="max-width: 1080px; aspect-ratio: 4 / 3; transform: scaleX(-1);">
                        <!-- HTML5Qrcode akan render video di sini -->
                    </div>

                    <!-- popup -->
                    <div id="popup" class="position-absolute top-0 start-50 translate-middle-x mt-5 d-none">
                        <div id="popup-content"
                            class="bg-white text-dark px-3 py-2 rounded-3 shadow-sm small text-center border border-3">
                            Tiket Tidak Valid
                        </div>
                    </div>

                    <!-- notif -->
                    <div id="notif" class="position-absolute bottom-0 start-50 translate-middle-x mb-5 w-75 d-none">
                        <div id="notif-content"
                            class="bg-white text-dark p-3 rounded-4 shadow-lg text-center border border-5">
                            <p class="mb-0" id="notif-nama"></p>
                            <p class="mb-0" id="notif-jabatan"></p>
                            <p class="mb-0" id="notif-message"></p>
                        </div>
                    </div>


                </div>
            </div>
        </section><!-- /Hero Section -->


    </main>

    <footer id="footer" class="footer light-background">

        <div class="container copyright text-center">
            <div class="credits">
                Designed and created by <a href="https://khaerulummam.github.io/" target="blank">khaerul Ummam Aulia
                    Fadillah |
                    Ilmu Komputer</a>
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
    <script src="{{ asset('assets/vendor/typed.js/typed.umd.js') }}')}}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        const html5QrCode = new Html5Qrcode("qr-video");
        const popup = document.getElementById("popup");
        const popupContent = document.getElementById("popup-content");
        const notif = document.getElementById("notif");
        const notifContent = document.getElementById("notif-content");
        const notifNama = document.getElementById("notif-nama");
        const notifJabatan = document.getElementById("notif-jabatan");
        const notifMessage = document.getElementById("notif-message");
        const switchInput = document.getElementById("switch-input");
        const switchLabel = document.getElementById("switch-label");

        const qrConfig = {
            fps: 15,
            qrbox: {
                width: 250,
                height: 250
            },
        };

        let lastScanned = null;
        let borderResetTimeout = null;

        let positionCammera = 'user';

        function switchCamera() {
            if (switchInput.checked) {
                const positionCammera = 'environment';
                switchLabel.textContent = 'Kamera belakang';
            } else {
                const positionCammera = 'user'
                switchLabel.textContent = 'Kamera depan';
            }
        }

        function showPopup(message, status) {
            popup.classList.remove("d-none");
            popupContent.textContent = message;

            if (status === "success") {
                popupContent.classList.remove("border-error");
                popupContent.classList.add("border-success");
            } else {
                popupContent.classList.remove("border-success");
                popupContent.classList.add("border-error");
            }

            popup.classList.add("fade-in");
            setTimeout(() => {
                popup.classList.remove("fade-in");
                popup.classList.add("fade-out");
            }, 4000);

            setTimeout(() => {
                popup.classList.remove("fade-out");
                popup.classList.add("d-none");
            }, 5000);
        }

        function showNotif(data, status) {
            notif.classList.remove("d-none");


            if (status === "success") {
                notifContent.classList.remove("border-error");
                notifContent.classList.add("border-success");
                notifNama.textContent = `Nama: ${data.nama || 'Tidak tersedia'}`;
                notifJabatan.textContent = `Jabatan: ${data.jabatan || 'Tidak tersedia'}`;
                notifMessage.textContent = `Message: ${data.message || 'Selamat datang di Galeri Investasi!'}`;
            } else {
                notifContent.classList.remove("border-error");
                notifContent.classList.add("border-error");
                notifNama.textContent = `Nama: ${data.nama || 'Tidak tersedia'}`;
                notifJabatan.textContent = `Jabatan: ${data.jabatan || 'Tidak tersedia'}`;
                notifMessage.textContent = `Message: ${data.message || 'Tiket tidak valid atau tidak ditemukan.'}`;
            }

            notif.classList.add("fade-in");
            setTimeout(() => {
                notif.classList.remove("fade-in");
                notif.classList.add("fade-out");
            }, 4000);

            setTimeout(() => {
                notif.classList.remove("fade-out");
                notif.classList.add("d-none");
            }, 5000);
        }

        function setBorderStatus(status) {
            readerEl.classList.remove("border-success", "border-danger", "border-warning");

            if (status === "success") {
                readerEl.classList.add("border-success");
            } else if (status === "error") {
                readerEl.classList.add("border-danger");
            }

            if (borderResetTimeout) clearTimeout(borderResetTimeout);
            borderResetTimeout = setTimeout(() => {
                readerEl.classList.remove("border-success", "border-danger");
                readerEl.classList.add("border-warning");
            }, 5000);
        }

        function resetScan() {
            setTimeout(() => {
                lastScanned = null;
            }, 2000);
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (decodedText === lastScanned) return;
            lastScanned = decodedText;

            // Ekstrak UUID dari URL
            const uuid = decodedText.split('/').pop();

            fetch("/scan-result", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        unique_code: uuid
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "valid") {
                        showPopup("✅ Tiket Valid", "success");
                        showNotif(data, "success");
                        setBorderStatus("success");
                        resetScan();
                    } else {
                        showPopup("❌ Tiket Tidak Valid", "error");
                        showNotif(data, "error");
                        setBorderStatus("error");
                        resetScan();
                    }
                })
                .catch(err => {
                    showPopup("❌ Gagal Kirim Data", "error");
                    showNotif("Gagal Kirim Data", "error");
                    setBorderStatus("error");
                    console.error("Gagal:", err);
                });
        }

        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                html5QrCode.start({
                        facingMode: positionCammera
                    },
                    qrConfig,
                    onScanSuccess);
            } else {
                showNotif("Tidak ada kamera yang tersedia.", "error");
            }
        }).catch(err => {
            showNotif("Tidak dapat akses kamera: " + err, "error");
        });

        switchCamera();
        switchInput.addEventListener("change", switchCamera);
    </script>



</body>

</html>
