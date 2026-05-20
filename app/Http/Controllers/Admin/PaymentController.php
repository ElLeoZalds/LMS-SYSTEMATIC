<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Enrollment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of all payments.
     */
    public function index(Request $request)
    {
        $query = Payment::with([
            'enrollment.student.person',
            'enrollment.training.course',
            'paymentMethod'
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

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        $enrollments = Enrollment::with(['student.person', 'training.course'])
            ->where('status', 'A')
            ->get();

        $paymentMethods = PaymentMethod::all();

        return view('admin.payments.create', compact('enrollments', 'paymentMethods'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,enrollment_id',
            'payment_method_id' => 'required|exists:payment_methods,method_id',
            'date' => 'required|date',
            'installment' => 'required|numeric|min:0.01',
            'amount' => 'required|numeric|min:0.01',
            'status' => 'sometimes|in:A,I',
        ]);

        Payment::create([
            'enrollment_id' => $request->enrollment_id,
            'payment_method_id' => $request->payment_method_id,
            'date' => $request->date,
            'installment' => $request->installment,
            'amount' => $request->amount,
            'status' => $request->status ?? 'A',
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pago registrado correctamente.');
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        // Evitamos traer todo el universo de matrículas históricas: solo activas o la matrícula asociada al pago
        $enrollments = Enrollment::with(['student.person', 'training.course'])
            ->where('status', 'A')
            ->orWhere('enrollment_id', $payment->enrollment_id)
            ->get();

        $paymentMethods = PaymentMethod::all();

        return view('admin.payments.edit', compact('payment', 'enrollments', 'paymentMethods'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,enrollment_id',
            'payment_method_id' => 'required|exists:payment_methods,method_id',
            'date' => 'required|date',
            'installment' => 'required|numeric|min:0.01',
            'amount' => 'required|numeric|min:0.01',
            'status' => 'sometimes|in:A,I',
        ]);

        $payment = Payment::findOrFail($id);

        $payment->update([
            'enrollment_id' => $request->enrollment_id,
            'payment_method_id' => $request->payment_method_id,
            'date' => $request->date,
            'installment' => $request->installment,
            'amount' => $request->amount,
            'status' => $request->status ?? $payment->status,
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pago actualizado correctamente.');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pago eliminado correctamente.');
    }

    /**
     * Show payment details for auditing purposes.
     */
    public function show($id)
    {
        $payment = Payment::with([
            'enrollment.student.person',
            'enrollment.training.course',
            'paymentMethod'
        ])->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }
}
