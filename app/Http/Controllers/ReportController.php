<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ReportController extends Controller
{
    /**
     * 1. دالة لإرسال شكوى جديدة (يستخدمها الزبون عند التعرض للنصب)
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المرسلة من الواجهات
        $request->validate([
            'reported_user_id' => 'required',
            'reason'           => 'required|string|max:255',
            'details'          => 'nullable|string|max:1000',
        ]);

        // حفظ الشكوى في قاعدة البيانات
        $report = Report::create([
'reporter_id' => Auth::id() ?? 1, // معرف الزبون المُبلِّغ (نضع 1 مؤقتاً للتجربة)
            'reported_user_id' => $request->reported_user_id,
            'reason'           => $request->reason,
            'details'          => $request->details,
            'status'           => 'pending', // الحالة الافتراضية للشكوى هي "قيد الانتظار"
        ]);

        return response()->json([
            'message' => 'تم إرسال بلاغك بنجاح. ستقوم الإدارة بمراجعته فوراً.',
            'report'  => $report
        ], 201);
    }

    /**
     * 2. دالة لعرض جميع الشكاوى (يستخدمها الآدمن في لوحة التحكم لمراقبة المحتالين)
     */
    public function index()
    {
        // جلب الشكاوى
        $reports = Report::latest()->get();

        return response()->json([
            'total_reports' => $reports->count(),
            'reports'       => $reports
        ], 200);
    }

    /**
     * 3. دالة لتحديث حالة الشكوى (مثلاً: تم الحل وحظر السمسار المحتال)
     */
    public function updateStatus(Request $request,int $id)
    {
        $request->validate([
            'status' => 'required|in:pending,resolved,investigating'
        ]);

        $report = Report::findOrFail($id);
        $report->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'تم تحديث حالة الشكوى بنجاح.',
            'report'  => $report
        ], 200);
    }
}
