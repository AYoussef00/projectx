<?php

namespace App\Http\Controllers;

use App\Services\CertificatePdfBuilder;
use App\Support\CertificateSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CertificateDashboardController extends Controller
{
    public function edit(Request $request): Response
    {
        $code = (string) $request->query('code', CertificateSettings::defaultCode());

        if (! CertificateSettings::exists($code)) {
            $code = CertificateSettings::defaultCode();
        }

        return Inertia::render('Dashboard', [
            'certificates' => collect(CertificateSettings::codes())
                ->map(fn (string $item) => [
                    'code' => $item,
                    'page_url' => url('/'.$item),
                    'pdf_url' => url('/certificates/'.$item.'.pdf'),
                    'active' => $item === $code,
                ])
                ->values()
                ->all(),
            'certificate' => $this->formCertificate($code),
        ]);
    }

    public function store(Request $request, CertificatePdfBuilder $pdfBuilder): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'regex:/^[0-9]{4,12}$/', function (string $attribute, mixed $value, \Closure $fail): void {
                if (CertificateSettings::exists((string) $value)) {
                    $fail('الرابط موجود بالفعل.');
                }
            }],
            'copy_from' => ['required', 'string', 'regex:/^[0-9]{4,12}$/'],
        ]);

        $copyFrom = CertificateSettings::exists($validated['copy_from'])
            ? $validated['copy_from']
            : CertificateSettings::defaultCode();

        CertificateSettings::duplicate($copyFrom, $validated['code']);
        $result = $pdfBuilder->rebuild($validated['code']);

        $redirect = redirect()->route('dashboard', ['code' => $validated['code']]);

        if ($result['ok']) {
            return $redirect->with('success', 'تم إنشاء الصفحة /'.$validated['code'].' وملف الـ PDF نسخة من /'.$copyFrom.'.');
        }

        return $redirect
            ->with('success', 'تم إنشاء الصفحة /'.$validated['code'].' نسخة من البيانات.')
            ->with('error', $result['message']);
    }

    public function update(Request $request, CertificatePdfBuilder $pdfBuilder): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'regex:/^[0-9]{4,12}$/'],
            'contractor_name' => ['required', 'string', 'max:255'],
            'operation_description' => ['required', 'string', 'max:1000'],
            'works_line' => ['required', 'string', 'max:100'],
            'contract_number' => ['nullable', 'string', 'max:50'],
            'extract_code' => ['required', 'string', 'max:50'],
            'extract_value' => ['required', 'string', 'max:50'],
            'extract_type' => ['required', 'string', 'max:100'],
            'extract_start_date' => ['required', 'string', 'max:30'],
            'extract_end_date' => ['required', 'string', 'max:30'],
            'receipt_no' => ['required', 'string', 'max:50'],
            'amount' => ['required', 'string', 'max:50'],
            'tafqeet' => ['required', 'string', 'max:500'],
            'ministry_code' => ['required', 'string', 'max:50'],
            'clearance_number' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'max:100'],
            'approval_date' => ['required', 'string', 'max:30'],
        ]);

        $code = $validated['code'];
        abort_unless(CertificateSettings::exists($code), 404);

        $worksLabel = 'اعمال/';
        $worksCode = $validated['works_line'];
        if (preg_match('/^(.*?)(\d+)\s*$/u', trim($validated['works_line']), $matches)) {
            $worksLabel = trim($matches[1]) !== '' ? trim($matches[1]) : 'اعمال/';
            $worksCode = $matches[2];
        }

        CertificateSettings::update($code, [
            'contractor_name' => $validated['contractor_name'],
            'operation_description' => $validated['operation_description'],
            'works_label' => $worksLabel,
            'works_code' => $worksCode,
            'contract_number' => $validated['contract_number'] ?? '',
            'extract_code' => $validated['extract_code'],
            'extract_value' => $validated['extract_value'],
            'extract_type' => $validated['extract_type'],
            'extract_start_date' => $validated['extract_start_date'],
            'extract_end_date' => $validated['extract_end_date'],
            'receipt_no' => $validated['receipt_no'],
            'amount' => $validated['amount'],
            'tafqeet' => $validated['tafqeet'],
            'ministry_code' => $validated['ministry_code'],
            'clearance_number' => $validated['clearance_number'],
            'password' => $validated['password'],
            'approval_date' => $validated['approval_date'],
        ]);

        $result = $pdfBuilder->rebuild($code);
        $redirect = redirect()->route('dashboard', ['code' => $code]);

        if ($result['ok']) {
            return $redirect->with('success', $result['message']);
        }

        return $redirect
            ->with('success', 'تم حفظ بيانات الصفحة /'.$code.'.')
            ->with('error', $result['message']);
    }

    public function destroy(string $code): RedirectResponse
    {
        abort_unless(CertificateSettings::exists($code), 404);

        if (count(CertificateSettings::codes()) <= 1) {
            return back()->with('error', 'لا يمكن حذف آخر صفحة.');
        }

        CertificateSettings::delete($code);

        return redirect()
            ->route('dashboard')
            ->with('success', 'تم حذف الصفحة /'.$code.' وملف الـ PDF.');
    }

    /**
     * @return array<string, string>
     */
    private function formCertificate(string $code): array
    {
        $settings = CertificateSettings::for($code);

        return [
            'code' => $code,
            'page_url' => url('/'.$code),
            'pdf_url' => url('/certificates/'.$code.'.pdf'),
            'contractor_name' => $settings['contractor_name'],
            'operation_description' => $settings['operation_description'],
            'works_line' => trim($settings['works_label'].' '.$settings['works_code']),
            'contract_number' => $settings['contract_number'],
            'extract_code' => $settings['extract_code'],
            'extract_value' => $settings['extract_value'],
            'extract_type' => $settings['extract_type'],
            'extract_start_date' => $settings['extract_start_date'],
            'extract_end_date' => $settings['extract_end_date'],
            'receipt_no' => $settings['receipt_no'],
            'amount' => $settings['amount'],
            'tafqeet' => $settings['tafqeet'],
            'ministry_code' => $settings['ministry_code'],
            'clearance_number' => $settings['clearance_number'],
            'password' => $settings['password'],
            'approval_date' => $settings['approval_date'],
        ];
    }
}
