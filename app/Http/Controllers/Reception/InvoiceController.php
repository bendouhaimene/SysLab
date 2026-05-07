<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Test;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $invoiceService) {}

    public function index()
    {
        $invoices = Invoice::with(['patient', 'receptionist', 'tests'])
            ->where('receptionist_id', auth()->id())
            ->latest()->paginate(15);

        return view('reception.invoices.index', compact('invoices'));
    }

    public function create(Patient $patient)
    {
        $tests = Test::active()->orderBy('category')->orderBy('name')->get();
        return view('reception.invoice.create', compact('patient', 'tests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'test_ids'   => 'required|array|min:1',
            'test_ids.*' => 'exists:tests,id',
        ], [
            'test_ids.required' => 'Please select at least one test.',
        ]);

        $patient = Patient::findOrFail($request->patient_id);

        $invoice = $this->invoiceService->createInvoice(
            $patient,
            $request->test_ids,
            auth()->id()
        );

        return redirect()->route('reception.invoice.print', $invoice)
                         ->with('success', 'Invoice created successfully.');
    }

    public function printInvoice(Invoice $invoice)
    {
        $invoice->load(['patient', 'receptionist', 'tests']);
        $plainPassword = session('plain_password_' . $invoice->patient_id);
        return view('reception.invoice.print', compact('invoice', 'plainPassword'));
    }
}