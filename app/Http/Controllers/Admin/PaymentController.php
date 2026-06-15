<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with([
            'enrollment.student.person',
            'enrollment.training.course',
            'paymentMethod',
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method_id')) {
            $query->where('payment_method_id', $request->payment_method_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $payments = $query->orderBy('date', 'desc')->get();

        $paymentMethods = PaymentMethod::all();
        $statuses = ['A' => 'Activo', 'I' => 'Inactivo'];

        return view('admin.payments.index', compact('payments', 'paymentMethods', 'statuses'));
    }

    public function create()
    {
        $enrollments = Enrollment::with(['student.person', 'training.course'])
            ->where('status', 'A')
            ->get();

        $paymentMethods = PaymentMethod::all();

        return view('admin.payments.create', compact('enrollments', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        Payment::create($this->paymentData($request));

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pago registrado correctamente.');
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $enrollments = Enrollment::with(['student.person', 'training.course'])
            ->where('status', 'A')
            ->orWhere('enrollment_id', $payment->enrollment_id)
            ->get();

        $paymentMethods = PaymentMethod::all();

        return view('admin.payments.edit', compact('payment', 'enrollments', 'paymentMethods'));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update($this->paymentData($request, $payment->status));

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pago eliminado correctamente.');
    }

    public function show($id)
    {
        $payment = Payment::with([
            'enrollment.student.person',
            'enrollment.training.course',
            'paymentMethod',
        ])->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    private function paymentData(Request $request, string $defaultStatus = 'A'): array
    {
        $data = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,enrollment_id',
            'payment_method_id' => 'required|exists:payment_methods,method_id',
            'date' => 'required|date',
            'installment' => 'required|numeric|min:0.01',
            'amount' => 'required|numeric|min:0.01',
            'status' => 'sometimes|in:A,I',
        ]);

        $data['status'] = $data['status'] ?? $defaultStatus;

        return $data;
    }
}
