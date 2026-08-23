<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Valid - {{ $registrant->name }}</title>
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
<div class="container py-5">
    <div class="card mx-auto" style="max-width: 600px;">
        <div class="card-header bg-success text-white">QR Valid</div>
        <div class="card-body">
            <h5 class="card-title">{{ $registrant->name }}</h5>
            <p class="card-text">
                <strong>Email:</strong> {{ $registrant->email }}<br>
                <strong>Institusi:</strong> {{ $registrant->Institute }}<br>
                <strong>Status:</strong> {{ $registrant->user_status }}<br>
                <strong>Study:</strong> {{ $registrant->study }}<br>
                <strong>Kode:</strong> {{ $registrant->unique_code }}<br>
                <strong>Scanned:</strong> {{ $registrant->is_scanned ? 'Ya ('.$registrant->scanned_at.')' : 'Belum' }}
            </p>
            <img src="{{ asset('storage/'.$registrant->qr_code_path) }}" alt="QR" class="img-fluid" style="max-width:200px;">
            <div class="mt-3">
                <a href="{{ route('scan') }}" class="btn btn-primary">Kembali Scan</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
