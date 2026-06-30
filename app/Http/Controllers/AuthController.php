<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // تسجيل مستخدم جديد
  public function register(Request $request)
{
    // التحقق من البيانات
    $request->validate([
        'name'     => 'required|string|min:3|max:100',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'phone'    => 'required|string|max:20',
      'role' => 'required|in:owner,tenant,investor',
    ]);

    // إنشاء المستخدم
    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => bcrypt($request->password),
        'phone'    => $request->phone,
        'role'     => $request->role,
    ]);

    // إنشاء التوكن
    $token = $user->createToken('sakani_token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user'  => $user
    ], 201);
}


    // تسجيل الدخول
 public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|min:6',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
        ], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('sakani_token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user'  => $user
    ]);
}

    // تسجيل خروج
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ]);
    }

    // بيانات المستخدم الحالي
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
