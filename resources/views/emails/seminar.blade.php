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
                <table width="600" cellpadding="0" cellspacing="0"
                    style="position: relative; width: 420px; height:650px;">
                    <tr>
                        <td background="{{ asset('storage/background/TICKET2.png') }}"
                            style="padding: 200px 80px 0px 80px;" align="center">
                            <img src="{{ asset('storage/' . $registrant->qr_code_path) }}" width="180"
                                alt="QR Code">
                            <h1 style="color: #000; font-size: 12px; margin: 8px 0px; text-align: center;">Nama :
                                {{ $registrant->name }}</h1>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
