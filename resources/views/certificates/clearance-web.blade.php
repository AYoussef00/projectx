<!DOCTYPE html>
<html>
<head>
    <title>وزارة العمل</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/img/man-power/logo.ico') }}">

    {{-- Exact original CSS stack --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap/css/bootstrap-flipped.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/headers/header-v4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/footers/footer-v2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }}">

    {{-- Arabic fonts + AR overrides (original) --}}
    <link rel="stylesheet" href="{{ asset('assets/ar/bootstrap-flipped.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ar/DroidArabicKufi/css/RTL-fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ar/DroidNaskh/css/RTL-fonts_regular.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ar/DINNEXTLTARABIC-LIGHT/css/font-LIGHT.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ar/ArabicKufiSSK/stylesheet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/headers/header-v4-ar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/ar/custom-ar.css') }}">

    <style>
        html, body {
            direction: rtl;
        }
        .breadcrumbs h1, .breadcrumb a {
            color: rgb(255, 255, 255) !important;
        }
        .breadcrumb > li + li:before {
            color: rgb(255, 255, 255) !important;
            font-weight: bold;
            content: ">";
        }
        button, input, select, textarea {
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
            color: black;
        }
        button, html input[type=button], input[type=reset], input[type=submit] {
            -webkit-appearance: button;
            cursor: pointer;
            min-width: 150px !important;
        }
        .btn-success {
            color: #fff;
            background-color: #1268B3 !important;
            border-color: #1268B3 !important;
        }
        .btn-success:hover,
        .btn-success:focus {
            background-color: #3095b4 !important;
            border-color: #3095b4 !important;
            color: #fff;
        }
        .header-top .header-sticky .logo-title {
            display: flex;
            align-items: center;
        }
        .header-top .header-sticky {
            display: flex;
            align-items: center;
            justify-content: flex-start !important;
            padding: 8px 0 6px;
        }
        .header-top .header-sticky .logo-title .logo a img {
            width: 75px;
            height: 75px;
        }
        /* Exact original next-to-logo styles */
        .header-top .header-sticky .logo-title .next-to-logo {
            color: #404040;
            font-size: 22px;
            margin-right: 33px;
            max-width: 205px;
            line-height: 24px;
            font-family: 'DroidArabicKufi', Tahoma, Arial, sans-serif !important;
            font-weight: normal !important;
            text-decoration: none !important;
        }
        /* Original cascade: a { color: #1268b3 !important } wins over #404040 */
        .header-top .header-sticky .logo-title a.next-to-logo {
            color: #1268b3 !important;
        }
        /* Blue bar — original markup + colors only (natural Bootstrap sizes) */
        .breadcrumbs {
            background-image: none !important;
            background: #1268B3 !important;
            padding: 10px 0 6px !important;
            margin-bottom: 20px !important;
            border: 0 !important;
            box-shadow: none !important;
        }
        .breadcrumbs .container:after {
            content: "";
            display: table;
            clear: both;
        }
        .breadcrumbs h1 {
            color: #fff !important;
            font-size: 22px;
            margin-top: 8px;
            margin-bottom: 8px;
            font-family: 'DroidArabicKufi', Tahoma, Arial, sans-serif;
            font-weight: normal;
        }
        .breadcrumbs h1.pull-left {
            float: right !important;
        }
        .breadcrumbs ul.breadcrumb.pull-right {
            float: left !important;
        }
        .breadcrumbs h1,
        .breadcrumb a {
            color: #fff !important;
        }
        .breadcrumb {
            background: none !important;
            margin-bottom: 0;
            padding: 8px 0;
            font-size: 14px;
            font-family: 'DroidArabicKufi', Tahoma, Arial, sans-serif;
            font-weight: normal;
        }
        .breadcrumb > li {
            color: #fff !important;
        }
        .breadcrumb > li + li:before {
            color: #fff !important;
            font-weight: bold;
            content: ">" !important;
            padding: 0 5px;
        }
        .breadcrumb > li.active,
        .breadcrumb > li a:hover {
            color: #fff !important;
        }
        .breadcrumb #LogOut {
            color: #fff !important;
        }
        .breadcrumb #LogOut .fa {
            color: #fff !important;
        }
        .btn-u, .btn-u:focus, .btn-u:active, .btn-u.active, .open .dropdown-toggle.btn-u {
            background: #1268B3;
        }
        .btn-u:hover, .btn-u:focus, .btn-u:active, .btn-u.active, .open .dropdown-toggle.btn-u {
            background: #0D3770;
        }
        .color-green {
            color: #1268B3 !important;
        }
        .footer-v2 .copyright {
            border-top: none;
            background: #303741;
            padding: 20px 0 15px;
        }
        .copyright {
            font-weight: bold;
            color: #fff;
        }
        .copyright a { color: #fff; }
        #C_DirectorateName_log {
            color: #555;
            margin-top: 5px;
            text-shadow: none;
            font-weight: normal;
            font-size: 18px;
            font-family: 'DroidArabicKufi', "Open Sans", Arial, sans-serif;
        }
        /* Original heading styles (from blocks.css on live site) */
        #pirntClearance h1,
        #pirntClearance h2,
        #pirntClearance h3,
        #pirntClearance h4,
        #pirntClearance h5,
        #pirntClearance h6 {
            color: #555;
            margin-top: 5px;
            text-shadow: none;
            font-weight: normal;
            font-family: 'DroidArabicKufi', "Open Sans", Arial, sans-serif;
        }
        #pirntClearance h3 {
            font-size: 20px;
            line-height: 27px;
        }
        #pirntClearance h3[style*="text-align: center"] {
            text-align: center;
            margin-top: 10px;
            color: #555;
            font-size: 20px;
            line-height: 27px;
            font-weight: normal;
            text-shadow: none;
            font-family: 'DroidArabicKufi', "Open Sans", Arial, sans-serif;
        }
        @media print {
            .header-top, .breadcrumbs, .footer-v2, .margenset { display: none !important; }
        }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="header-sticky">
                        <div class="logo-title">
                            <div class="logo">
                                <a href="https://www.labour.gov.eg/" target="_blank" rel="noopener"><img src="{{ asset('img/logo.png') }}" alt=""></a>
                            </div>
                            <a href="https://www.labour.gov.eg/" target="_blank" rel="noopener" class="next-to-logo">جمهورية مصر العربية <br />وزارة العمل</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="breadcrumbs tag-box tag-box-v2 box-shadow shadow-effect-1">
                    <div class="container">
                        <h1 class="pull-left">
                            شهادة مخالصة سداد نسبة العمالة غير المنتظمة
                        </h1>
                        <ul class="pull-right breadcrumb">
                            <li><a href="https://www.labour.gov.eg/" target="_blank" rel="noopener">الصفحة الرئيسية</a></li>
                            <li class="active">
                                شهادة مخالصة سداد نسبة العاملة غير منتظمة
                            </li>
                            <li>
                                <a id="LogOut" href="#">
                                    تسجيل الخروج <i class="fa fa-power-off"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section id="main-container">
        <section id="min-wrapper">
            <div id="main-content">
                <div class="container-fluid">
                    <div class="container">
                        <div class="row" id="pirnt_Clearance">
                            <div style="width: 100%; float: right " class="container-fluid" id="pirntClearance">

                                <div style="width: 100%; float: right; text-align: right; margin: 7px 0px 8px 0px " class="row">
                                    <div style="width: 30%; float: right; text-align: right" class="col-md-3">
                                        <h3 id="C_DirectorateName_log">مديرية عمل القاهرة</h3>
                                    </div>
                                    <div style="width: 40%; float: right; margin-top: 10px; text-align: center " class="row">
                                        <img style=" width:125px;height:125px;text-align:center " src="{{ asset('img/logo.png') }}" />
                                    </div>
                                    <div style="width: 30%; float: left; margin-top: 10px; text-align: center" class="row">
                                        <img id="imgid2" src="{{ asset('certificates/qr.png') }}?v={{ is_file(public_path('certificates/qr.png')) ? filemtime(public_path('certificates/qr.png')) : time() }}" width="125" height="125" />
                                    </div>
                                </div>
                                <br />
                                <div style="width: 100%; float: right " class="container-fluid">
                                    <h3 style="text-align: center; margin-top: 10px">
                                        شهادة مخالصة سداد نسبة العمالة غير المنتظمة
                                    </h3>
                                </div>
                                <br />

                                <div style="width:100% ; float:right ;margin:7px 0px 8px 0px" class="row">
                                    <div style="width: 33%; float: right; text-align: right" class="col-md-3">
                                        <span style="margin :0px 10px 0px 10px">السادة جهة الاسناد</span>
                                    </div>
                                    <div style="width:66% ; float:right ;text-align:right" class="col-md-3">
                                        <span id="C_CompanyClientNameId">وزارة الدفاع - الهيئة الهندسية للقوات المسلحة - ادارة المهندسين العسكريين - فرع التموين</span>
                                    </div>
                                </div>
                                <br />
                                <div style="width: 100%; float: right; text-align: right; margin: 7px 0px 8px 0px " class="row">
                                    <div style="width: 100%; float: right; text-align: right" class="col-md-3">
                                        <span>اعمالا لاحكام المادة (26) من قانون العمل الصادر بالقانون رقم 12 لسنة 2003 و تنفيزا للقرار الوزاري رقم 168 لسنة 2007 و المعدل بالقرار الوزاري 162 لسنة 2019 تشهد ادارة العمالة غير المنتظمة ب <span id="C_DirectorateName">مديرية عمل القاهرة</span></span>
                                    </div>
                                </div>
                                <br />
                                <div style="width:100% ;float:right ;margin:7px 0px 8px 0px" class="row">
                                    <div style="width: 33%; float: right; text-align: right " class="col-md-3">
                                        <span style="margin :0px 10px 0px 10px">ايماء الي الطلب المقدم من جهة التنفيذ</span>
                                    </div>
                                    <div style="width:67% ; float:right ;text-align:right" class="col-md-3">
                                        <span id="C_FullName">شركة الحسام للمقاولات العامة والتوريدات</span>
                                    </div>
                                </div>
                                <br />
                                <div style="width:100% ; float:right ;margin:7px 0px 8px 0px" class="row">
                                    <div style="width: 33%; float: right; text-align: right " class="col-md-3">
                                        <span style="margin :0px 10px 0px 10px">و المسند اليها عملية</span>
                                    </div>
                                    <div style="width:67% ; float:right ;text-align:right" class="col-md-3">
                                        <span id="C_OperationDescription">أعمال الطرق لذمة زيادة القدرة التصريفية لخور وقناة مفيض توشكي ( قناة المفيض التكميلية) من محطة 13+250 الي محطة 13+500 القطاع الثاني</span>
                                    </div>
                                </div>
                                <br />
                                <div style="width:100% ; float:right ;margin:7px 0px 8px 0px" class="row">
                                    <div style="width: 25%; float: right; text-align: right " class="col-md-3">
                                        <div><span>عقد رقم</span></div>
                                    </div>
                                    <div style="width:25% ; float:right ;text-align:right" class="col-md-3">
                                        <div><span id="C_OperationWorks">اعمال/ 15740</span></div>
                                        <div><span id="C_OperationCode">261008004796</span></div>
                                    </div>
                                    <div style="width: 25%; float: right; text-align: right " class="col-md-3"><span>بقيمة اجمالية</span></div>
                                    <div style="width:25% ; float:right ;text-align:right" class="col-md-3"><span id="C_TotalContractValue"></span></div>
                                </div>
                                <br />
                                <div style="width:100% ; float:right ;margin:7px 0px 8px 0px" class="row">
                                    <div style="width: 25%; float: right; text-align: right " class="col-md-3"><span>عن مستخلص </span></div>
                                    <div style="width:25% ; float:right ;text-align:right" class="col-md-3"><span id="C_ExtractCode">1523469</span></div>
                                    <div style="width: 25%; float: right; text-align: right " class="col-md-3"><span>بقيمة </span></div>
                                    <div style="width:25% ; float:right ;text-align:right" class="col-md-3"><span style="border:1px solid #000000" id="C_ExtractTotalValue">8442850</span></div>
                                </div>
                                <br />
                                <div style="width:100% ; float:right ;margin:7px 0px 8px 0px" class="row">
                                    <div style="width: 25%; float: right; text-align: right " class="col-md-3"><span style="margin :0px 10px 0px 10px">عن جارى من او فواتير </span></div>
                                    <div style="width:75% ; float:right ;text-align:right" class="col-md-3"><span id="C_OperationExtractDes">جاري من 1 والختامي</span></div>
                                </div>
                                <div style="width:100% ; float:right ;margin:7px 0px 8px 0px" class="row">
                                    <div style="width: 16%; float: right; text-align: right " class="col-md-3"><span>نوع المخالصة </span></div>
                                    <div style="width:16% ; float:right ;text-align:right" class="col-md-3"><span id="C_ExtractTypeName">أول وختامي</span></div>
                                    <div style="width: 17%; float: right; text-align: right " class="col-md-3"><span>في مدة من  </span></div>
                                    <div style="width:16% ; float:right ;text-align:right" class="col-md-3"><span id="C_ExtractStartDate">24-02-2026</span></div>
                                    <div style="width: 16%; float: right; text-align: right " class="col-md-3"><span>الي </span></div>
                                    <div style="width:16% ; float:right ;text-align:right" class="col-md-3"><span id="C_ExtractEndDate">24-04-2026</span></div>
                                </div>
                                <br /><br /><br />
                                <hr />
                                <br />
                                <div style="width:100% ; float:right ;margin:10px 0px 8px 0px" class="row">
                                    <div style="width: 67%; float: right; text-align: right " class="col-md-3">
                                        <span>و قد قامت بسداد النسبة القانونية الاداره تشغيل ورعاية و حماية العمالة غير المنتظمة ب <span id="C_DirectorateName2">مديرية عمل القاهرة</span></span>
                                    </div>
                                </div>
                                <br />
                                <div style="width:100% ; float:right;margin:7px 0px 8px 0px" class="row">
                                    <div style="width: 25%; float: right; text-align: right " class="col-md-3"><span style="margin :0px 10px 0px 10px">بايصال رقم</span></div>
                                    <div style="width:25% ; float:right ;text-align:right" class="col-md-3"><span id="C_ReceiptNo">20260818436122</span></div>
                                    <div style="width: 25%; float: right; text-align: right " class="col-md-3"><span>بحساب البريد المصري رقم </span></div>
                                    <div style="width:25% ; float:right ;text-align:right" class="col-md-3"><span id="C_AccountNumber">0130213001028818</span></div>
                                </div>
                                <br />
                                <div style="width:100% ; float:right;margin:7px 0px 8px 0px" class="row">
                                    <div style="width: 15%; float: right; text-align: right " class="col-md-3"><span style="margin :0px 10px 0px 10px">مبلغ وقدره</span></div>
                                    <div style="width:20% ; float:right ;text-align:right" class="col-md-3"><span style="border:1px solid #000000" id="C_RequiredTotalValue">25413</span></div>
                                    <div style="width:65% ; float:right ;text-align:right" class="col-md-3"><span style="border:1px solid #000000" id="C_tafeet">فقط خمسة وعشرون الفا وربعمائة وثلاثة عشر جنيها مصري لا غير</span></div>
                                </div>
                                <br />
                                <div style="width:100% ; float:right;margin:7px 0px 8px 0px" class="row">
                                    <div style="width: 25%; float: right; text-align: right " class="col-md-3"><span style="margin :0px 10px 0px 10px">ملحوظة</span></div>
                                    <div style="width:75% ; float:right ;text-align:right" class="col-md-3"><span style="border:1px solid #000000" id="C_Note">تم سداد المبلغ</span></div>
                                </div>
                                <div style="width:100% ; float:right;margin:7px 0px 8px 0px" class="row">
                                    <div style="width: 100%; float: right; text-align: right " class="col-md-3">
                                        <span>و بذلك لا يوجد مانع من صرف مستحقاته في نطاق البيانات المشار اليها بعالية</span>
                                    </div>
                                </div>
                                <br /><br />
                                <div style="width:100% ; float:right ;margin:7px 0px 8px 0px" class="row">
                                    <div style="width: 17%; float: right; text-align: right " class="col-md-3"><span>كود العملية بالوزارة </span></div>
                                    <div style="width:16% ; float:right ;text-align:right" class="col-md-3"><span id="C_OperationId">421165</span></div>
                                    <div style="width: 16%; float: right; text-align: right " class="col-md-3"><span>رقم المخالصة </span></div>
                                    <div style="width:16% ; float:right ;text-align:right" class="col-md-3"><span id="C_ExtractCode_p">1523469</span></div>
                                    <div style="width: 16%; float: right; text-align: right " class="col-md-3"><span>كلمة المرور </span></div>
                                    <div style="width:16% ; float:right ;text-align:right" class="col-md-3"><span id="C_PrintCode">F22z5s941e</span></div>
                                </div>
                                <br />
                                <div style="width:100% ; float:right;margin:7px 0px 8px 0px" class="row">
                                    <div style="width: 25%; float: right; text-align: right " class="col-md-3"><span>تم اعتماد المخالصة فى تاريخ</span></div>
                                    <div style="width: 25%; float: right; text-align: right " class="col-md-3"><span id="C_MovementDate">24-08-2026</span></div>
                                </div>
                                <br /><br />
                                <div style="width:100% ; float:right ;margin:7px 0px 8px 0px" class="row">
                                    <div style="width: 90%; float: right; text-align: right " class="col-md-3">
                                        <span>يمكنك الاستعلام عن المخالصة عن طريق المسح على الكيو ار كود او من رقم المخاصة وكلمة المرور من خلال الموقع </span>
                                    </div>
                                </div>
                                <div style="width:100% ; float:right ;margin:7px 0px 8px 0px" class="row">
                                    <div style="width: 90%; float: right; text-align: right " class="col-md-3">
                                        <span> https://inform.manpower.gov.eg </span>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4 float-right margenset">
                                <input type="button" class="btn btn-success " id="btnPrintClearance" value="طباعة  ">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>

    <div id="footer-v2" class="footer-v2">
        <div class="copyright">
            <div class="container">
                <div class="text-center">جميع الحقوق محفوظة <a href="#">لوزارة العمل</a> &copy; 2023</div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
<script>
    $("#btnPrintClearance").click(function () {
        var w = window.open();
        w.document.write($('#pirntClearance').html());
        w.document.close();
        w.focus();
        w.print();
        w.close();
    });
</script>
</body>
</html>
