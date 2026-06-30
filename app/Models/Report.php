<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    // تحديد الحقول المسموح بتعبئتها لحماية جدول الشكاوى
    protected $fillable = [
        'reporter_id',
        'reported_user_id',
        'reason',
        'details',
        'status'
    ];

    // علاقة: الشخص الذي قدّم الشكوى (الزبون)
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    // علاقة: الشخص المُشتكى عليه (صاحب العقار)
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }
}
