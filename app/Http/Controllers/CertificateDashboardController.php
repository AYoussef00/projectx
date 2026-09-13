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
    public function edit(): Response
    {
        $settings = CertificateSettings::all();

        return Inertia::render('Dashboard', [
            'certificate' => [
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
                'password' => $settings['password'],
                'approval_date' => $settings['approval_date'],
            ],
        ]);
    }

    public function update(Request $request, CertificatePdfBuilder $pdfBuilder): RedirectResponse
    {
        $validated = $request->validate([
            'contractor_name' => ['required', 'string', 'max:255'],
            'operation_description' => ['required', 'string', 'max:1000'],
            'works_line' => ['required', 'string', 'max:100'],
            'contract_number' => ['required', 'string', 'max:50'],
            'extract_code' => ['required', 'string', 'max:50'],
            'extract_value' => ['required', 'string', 'max:50'],
            'extract_type' => ['required', 'string', 'max:100'],
            'extract_start_date' => ['required', 'string', 'max:30'],
            'extract_end_date' => ['required', 'string', 'max:30'],
            'receipt_no' => ['required', 'string', 'max:50'],
            'amount' => ['required', 'string', 'max:50'],
            'tafqeet' => ['required', 'string', 'max:500'],
            'ministry_code' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'max:100'],
            'approval_date' => ['required', 'string', 'max:30'],
        ]);

        $worksLabel = 'اعمال/';
        $worksCode = $validated['works_line'];
        if (preg_match('/^(.*?)(\d+)\s*$/u', trim($validated['works_line']), $matches)) {
            $worksLabel = trim($matches[1]) !== '' ? trim($matches[1]) : 'اعمال/';
            $worksCode = $matches[2];
        }

        CertificateSettings::update([
            'contractor_name' => $validated['contractor_name'],
            'operation_description' => $validated['operation_description'],
            'works_label' => $worksLabel,
            'works_code' => $worksCode,
            'contract_number' => $validated['contract_number'],
            'extract_code' => $validated['extract_code'],
            'extract_value' => $validated['extract_value'],
            'extract_type' => $validated['extract_type'],
            'extract_start_date' => $validated['extract_start_date'],
            'extract_end_date' => $validated['extract_end_date'],
            'receipt_no' => $validated['receipt_no'],
            'amount' => $validated['amount'],
            'tafqeet' => $validated['tafqeet'],
            'ministry_code' => $validated['ministry_code'],
            'password' => $validated['password'],
            'approval_date' => $validated['approval_date'],
        ]);

        $pdfBuilder->rebuild();

        return back()->with('success', 'تم تحديث بيانات الشهادة في الصفحة الرئيسية وملف الـ PDF.');
    }
}
