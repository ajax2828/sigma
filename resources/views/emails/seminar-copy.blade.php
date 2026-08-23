<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tiket Peserta</title>

    <style type="text/css">
        @media only screen and (max-width: 600px) {
            table[role="presentation"] {
                width: 100% !important;
                margin: 0 auto;
            }

            td {
                padding: 15px 10px !important;
            }

            h1 {
                font-size: 20px !important;
            }

            h2 {
                font-size: 18px !important;
            }

            p {
                font-size: 14px !important;
            }

            img {
                width: 250px !important;
                height: auto !important;
            }
        }
    </style>
</head>

<body style="margin:0; padding:0; background-color:#f4f4f4; font-family:Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 30px 0;">
                <!-- Container -->
                {{-- <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                    style="background-color:#ffffff; border-radius:6px; box-shadow:0 4px 8px rgba(0,0,0,0.05); overflow:hidden; max-width:600px;">

                    <!-- Header -->
                    <tr>
                        <td style="background-color:#E4A11B; height:6px;"></td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:0 10px;">
                            <h1 style="color:#000; font-size:24px; margin:5px; text-align: center;">Sekolah Pasar Modal
                                UYM</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:0;">
                            <h2 style="color:#000; text-align: center; font-size:20px;">Tiket Anda</h2>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 20px;">
                            <img src="{{ asset('storage/' . $registrant->qr_code_path) }}" width="300" alt="QR Code"
                                style="max-width:100%;" />
                            <p style="font-size:16px; color:#555; text-align: center;">Tiket ini hanya berlaku 1x scan
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 20px;">
                            <p style="font-size:16px; color:#555; text-align: center;">Data Peserta:</p>
                            <p style="font-size:16px; color:#555; text-align: center; margin-top: 0;">Nama :
                                {{ $registrant->name }}</p>
                            <p style="font-size:16px; color:#555; text-align: center; margin-top: 0;">Asal Institusi :
                                {{ $registrant->Institute }}</p>
                            <p style="font-size:16px; color:#555; text-align: center; margin-top: 0;">Status :
                                {{ $registrant->user_status }}</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding:20px; font-size:12px; color:#888;">
                            <p style="margin:5px; text-align: center;"><a href="{{ env('APP_URL') }}"
                                    style="color:#888; text-decoration:none;">{{ env('APP_NAME') }}</a></p>
                        </td>
                    </tr>

                </table> --}}

                <table width="600" cellpadding="0" cellspacing="0"
                    style="position: relative; width: 1000px; height:1280px;">
                    <tr>
                        <td background="{{ asset('storage/background/background-email.jpg') }}"
                            style="padding: 450px 80px 0px 80px;" align="center">
                            <img src="{{ asset('storage/qr-codes/qr_1753007163.png') }}" width="400" alt="QR Code">
                            <h1 style="color: #000; font-size: 32px; margin: 30px 0px; text-align: center;">Nama :
                                Lorem,
                                ipsum dolor.</h1>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
