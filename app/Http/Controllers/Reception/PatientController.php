<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function search()
    {
        return view('reception.search');
    }

    public function searchPost(Request $request)
    {
        $request->validate([
            'national_id' => [
                'required',
                'string',
                'size:18',
                'regex:/^[0-9]{18}$/',
            ],
        ], [
            'national_id.size'  => 'National ID must be exactly 18 digits.',
            'national_id.regex' => 'National ID must contain only digits.',
        ]);

        $patient = Patient::where('national_id', $request->national_id)->first();

        if ($patient) {
            return redirect()->route('reception.invoice.create', $patient);
        }

        return redirect()->route('reception.patients.create')
                         ->with('national_id', $request->national_id);
    }

    public function create()
    {
        $nationalId = session('national_id', '');
        return view('reception.patients.create', compact('nationalId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'national_id'   => 'required|string|size:18|unique:patients,national_id',
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'date_of_birth' => 'nullable|date',
            'gender'        => 'nullable|in:male,female',
            'phone'         => 'nullable|string|max:20',
        ]);

        // Auto-generate credentials
        $username = 'patient_' . substr($request->national_id, -4) . rand(10, 99);
        $password = Str::random(4) . rand(1000, 9999);

        $patient = Patient::create([
            'national_id'   => $request->national_id,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'gender'        => $request->gender,
            'phone'         => $request->phone,
            'username'      => $username,
            'password'      => Hash::make($password),
        ]);

        // حفظ الـ password الـ plain text في session لعرضه في الفاتورة
        session(['plain_password_' . $patient->id => $password]);

        return redirect()->route('reception.invoice.create', $patient)
                         ->with('success', 'Patient registered successfully.');
    }
}