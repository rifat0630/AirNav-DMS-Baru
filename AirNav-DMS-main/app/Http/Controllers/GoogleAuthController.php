<?php

namespace App\Http\Controllers;

use App\Services\GoogleDriveService;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    public function redirect(GoogleDriveService $google)
    {
        $client = $google->client();

        return redirect()->away(
            $client->createAuthUrl()
        );
    }


    public function callback(
        Request $request,
        GoogleDriveService $google
    ) {
        $client = $google->client();

        $token = $client->fetchAccessTokenWithAuthCode(
            $request->code
        );


        if (isset($token['error'])) {
            return $token;
        }


        file_put_contents(
            storage_path('app/google/token.json'),
            json_encode($token, JSON_PRETTY_PRINT)
        );


        return "Google Drive berhasil terhubung";
    }
}