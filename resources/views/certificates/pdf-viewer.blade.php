<!DOCTYPE html>
<html>
<head>
    <title>وزارة العمل</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/img/man-power/logo.ico') }}">
    <style>
        html, body {
            margin: 0;
            height: 100%;
            background: #525659;
        }
        iframe {
            display: block;
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>
</head>
<body>
    @php
        $pdfPath = \App\Support\CertificateSettings::resolvedPdfPath($code);
        $pdfUrl = route('certificates.pdf.file', $code).'?v='.($pdfPath ? filemtime($pdfPath) : time());
    @endphp
    <iframe title="وزارة العمل" src="{{ $pdfUrl }}"></iframe>
</body>
</html>
