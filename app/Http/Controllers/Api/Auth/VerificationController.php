<?php

namespace App\Http\Controllers\Api\Auth;

use App\Abstracts\Http\MobileController;
use App\Constants\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends MobileController
{
    public function verify($id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals($hash, sha1($user->getEmailForVerification())))
        {
            return $this->sendResponse("400", "Invalid verification link.");
        }

        $user->markEmailAsVerified();

        return $this->sendResponse("000", "Email successfully verified.");
    }

    // Resend the verification email
    public function resend(Request $request)
    {
        $user = $request->user();

        // Check if the user's email is already verified
        if ($user->hasVerifiedEmail()) {
            return $this->sendResponse("400", "Email already verified.");
        }

        // Send verification email again
        $user->sendEmailVerificationNotification();

        return $this->sendResponse("000", "Verification email resent.");
    }
}
