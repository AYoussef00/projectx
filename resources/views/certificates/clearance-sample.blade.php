<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>شهادة مخالصة — نسخة مطابقة للأصل</title>
    <style>
        * { box-sizing: border-box; }
        html, body {
            margin: 0;
            height: 100%;
            background: #111;
            font-family: "Times New Roman", Times, serif;
        }
        .bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            background: #1f2937;
            color: #fff;
            font-family: Tahoma, Arial, sans-serif;
            font-size: 14px;
        }
        .bar a, .bar button {
            color: #111;
            background: #fff;
            text-decoration: none;
            border: 0;
            border-radius: 6px;
            padding: 8px 12px;
            font-weight: 700;
            cursor: pointer;
            font-family: Tahoma, Arial, sans-serif;
        }
        .actions { display: flex; gap: 8px; flex-wrap: wrap; }
        iframe, embed, object {
            width: 100%;
            height: calc(100vh - 48px);
            border: 0;
            background: #525659;
        }
    </style>
</head>
<body>
    <div class="bar">
        <span>نسخة مطابقة 100% للملف الأصلي (نفس الخطوط والتصميم)</span>
        <div class="actions">
            <a href="{{ asset('certificates/sample-clearance.pdf') }}" target="_blank" rel="noopener">فتح PDF</a>
            <a href="{{ asset('certificates/sample-clearance.pdf') }}" download="شهادة-مخالصة-سامبل.pdf">تحميل</a>
        </div>
    </div>
    <iframe
        title="شهادة مخالصة"
        src="{{ asset('certificates/sample-clearance.pdf') }}#toolbar=1&navpanes=0&view=FitH"
    ></iframe>
</body>
</html>
