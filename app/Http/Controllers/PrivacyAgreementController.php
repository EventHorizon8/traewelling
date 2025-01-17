<?php

namespace App\Http\Controllers;

use App\Models\PrivacyAgreement;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivacyAgreementController extends Controller
{

    public function intercept(): Renderable {
        $agreement = PrivacyAgreement::where('valid_at', '<=', date("Y-m-d H:i:s"))->orderByDesc('valid_at')->take(1)->first();
        $user      = Auth::user();

        return view('privacy-interception', ['agreement' => $agreement, 'user' => $user]);
    }

    public function ack(Request $request): RedirectResponse|JsonResponse {
        $user                 = Auth::user();
        $user->privacy_ack_at = now();
        $user->save();
        if ($request->is('api*')) {
            return response()->json(['message' => 'privacy agreement successfully accepted'], 202);
        }

        return redirect()->route('dashboard');
    }
}
