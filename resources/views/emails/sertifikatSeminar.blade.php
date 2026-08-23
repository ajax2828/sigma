<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Terima Kasih - Seminar & Workshop</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f6f8fb;
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
        }

        .container {
            max-width: 650px;
            margin: auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
        }

        .header {
            background: #0b66ff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
        }

        .content {
            padding: 20px;
            color: #16324f;
            font-size: 15px;
            line-height: 1.5;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            background: #ffd400;
            color: #09243a;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
        }

        .cert-box {
            background: #fbfdff;
            border: 1px dashed #e6eefc;
            border-radius: 8px;
            padding: 12px;
            margin: 16px 0;
        }

        .footer {
            background: #fafcff;
            padding: 14px;
            font-size: 13px;
            text-align: center;
            color: #5f6f85;
        }

        @media(max-width:480px) {
            .content {
                padding: 16px;
                font-size: 14px;
            }

            .header h1 {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <table role="presentation" width="100%" bgcolor="#f6f8fb" cellpadding="0" cellspacing="0">
        <tr>
            <td align="left" style="padding:20px;">
                <div class="container">
                    <!-- Header -->
                    <div class="header">
                        <h1>Terima Kasih Telah Mengikuti!</h1>
                        <div style="font-size:14px; margin-top: 8px;">
                            Seminar & Workshop — From Campus to Capital Market
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="content">
                        <p>Halo <strong>{{ $registrant->name }}</strong>,</p>
                        <p>Terima kasih telah hadir dalam Seminar & Workshop: From Campus to Capital Market dengan tema:
                        </p>
                        <p><strong>"Menjebatani Dunia Akademik dan Pasar Modal demi Mencetak Lulusan Adaptif dan Berdaya
                                Saing"</strong></p>

                        {{-- <ul style="">
                            <li><strong>Tanggal:</strong> Kamis 14 Agustus 2025</li>
                            <li><strong>Waktu:</strong> 07.30 WIB - Selesai</li>
                            <li><strong>Tempat:</strong> Auditorium Universitas Yatsi Madani Lt. 4<br>Jl. Aria Santika
                                No.40A, RT.005/RW.011, Margasari, Karawaci, Kota Tangerang, Banten 15114</li>
                        </ul> --}}

                        <div class="cert-box">
                            Sebagai apresiasi, kami lampirkan Sertifikat Anda:.<br>
                            <small>Nama file: {{ Str::upper($registrant->name) }}.pdf</small>
                        </div>

                        <p>Semoga materi dan pengalaman yang diperoleh bermanfaat bagi pengembangan diri dan karier
                            Anda.</p>
                        <p style="margin-top:18px; font-size:14px;">Apabila terjadi kesalahan atau mengajukan pertanyaan
                            lebih lanjut terkait Sertifikat,bisa menghubungi kami di
                            <strong><a href="https://wa.me/+6289670327994">0896-7032-7994</a></strong>.
                        </p>
                        <ul style="">
                            <li>Salam hangat,</li>
                            <li>Panitia Seminar & Workshop</li>
                            <li> Sekolah Pasar Modal UYM</li>
                        </ul>
                    </div>

                    <!-- Footer -->
                    <div class="footer">
                        &copy; 2025 Panitia Seminar & Workshop — Sekolah Pasar Modal UYM
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
