<?php

namespace App\Http\Controllers;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GOOGLE CLIENT
    |--------------------------------------------------------------------------
    */

    private function googleClient()
    {
        $client = new Client();

        $client->setClientId(
            env('GOOGLE_CLIENT_ID')
        );

        $client->setClientSecret(
            env('GOOGLE_CLIENT_SECRET')
        );

        $client->setRedirectUri(
            env('GOOGLE_REDIRECT_URI')
        );

        /*
        |--------------------------------------------------------------------------
        | AKSES OFFLINE
        |--------------------------------------------------------------------------
        | Dibutuhkan agar Google memberikan refresh_token.
        |--------------------------------------------------------------------------
        */

        $client->setAccessType('offline');

        /*
        |--------------------------------------------------------------------------
        | PAKSA CONSENT
        |--------------------------------------------------------------------------
        | Supaya Google mengirim refresh_token.
        |--------------------------------------------------------------------------
        */

        $client->setPrompt('consent');

        /*
        |--------------------------------------------------------------------------
        | GOOGLE DRIVE PERMISSION
        |--------------------------------------------------------------------------
        */

        $client->setScopes([
            Drive::DRIVE,
        ]);

        return $client;
    }


    /*
    |--------------------------------------------------------------------------
    | GOOGLE AUTH
    |--------------------------------------------------------------------------
    */

    public function redirect()
    {
        /*
        |--------------------------------------------------------------------------
        | PENTING
        |--------------------------------------------------------------------------
        | Jangan panggil GoogleDriveService di sini.
        |--------------------------------------------------------------------------
        */

        $client = $this->googleClient();

        return redirect(
            $client->createAuthUrl()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GOOGLE CALLBACK
    |--------------------------------------------------------------------------
    */

    public function callback(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | CEK ERROR
        |--------------------------------------------------------------------------
        */

        if ($request->has('error')) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Google menolak autentikasi.',

                'error' =>
                    $request->get('error'),

            ], 400);
        }


        /*
        |--------------------------------------------------------------------------
        | CEK CODE
        |--------------------------------------------------------------------------
        */

        if (!$request->filled('code')) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Authorization code Google tidak ditemukan.',

            ], 400);
        }


        /*
        |--------------------------------------------------------------------------
        | BUAT CLIENT
        |--------------------------------------------------------------------------
        */

        $client =
            $this->googleClient();


        /*
        |--------------------------------------------------------------------------
        | TUKAR CODE MENJADI TOKEN
        |--------------------------------------------------------------------------
        */

        $token =
            $client->fetchAccessTokenWithAuthCode(
                $request->code
            );


        /*
        |--------------------------------------------------------------------------
        | CEK ERROR TOKEN
        |--------------------------------------------------------------------------
        */

        if (isset($token['error'])) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Gagal mendapatkan token Google.',

                'error' =>
                    $token['error'],

                'description' =>
                    $token['error_description']
                    ?? null,

            ], 400);
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL REFRESH TOKEN
        |--------------------------------------------------------------------------
        */

        $refreshToken =
            $token['refresh_token']
            ?? null;


        /*
        |--------------------------------------------------------------------------
        | REFRESH TOKEN TIDAK ADA
        |--------------------------------------------------------------------------
        */

        if (!$refreshToken) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Google tidak mengirim refresh token.',

                'penyebab' =>
                    'Biasanya karena akun Google sebelumnya sudah memberikan izin. Cabut akses aplikasi dari akun Google lalu coba lagi.',

                'token' =>
                    $token,

            ], 400);
        }


        /*
        |--------------------------------------------------------------------------
        | HASIL
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'message' =>
                'Google Drive berhasil terhubung.',

            'refresh_token' =>
                $refreshToken,

            'access_token' =>
                $token['access_token']
                ?? null,

            'expires_in' =>
                $token['expires_in']
                ?? null,

        ]);
    }
}