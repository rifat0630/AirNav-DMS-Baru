<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\UploadedFile;

class GoogleDriveService
{
    /**
     * Membuat Google Client
     */
    public function client()
{
    $client = new Client();

    $client->setAuthConfig(
        storage_path('app/google/credentials.json')
    );

    $client->setClientId(
        config('services.google.client_id')
    );

    $client->setClientSecret(
        config('services.google.client_secret')
    );

    $client->setRedirectUri(
        config('services.google.redirect')
    );

    $client->addScope(Drive::DRIVE);

    $client->setAccessType('offline');

    $client->setPrompt('select_account consent');

    $tokenPath = storage_path('app/google/token.json');

    if (file_exists($tokenPath)) {

        $token = json_decode(
            file_get_contents($tokenPath),
            true
        );

        $client->setAccessToken($token);

        if ($client->isAccessTokenExpired()) {

            if ($client->getRefreshToken()) {

                $newToken =
                    $client->fetchAccessTokenWithRefreshToken(
                        $client->getRefreshToken()
                    );

                $token = array_merge($token, $newToken);

                file_put_contents(
                    $tokenPath,
                    json_encode($token, JSON_PRETTY_PRINT)
                );

                $client->setAccessToken($token);
            }
        }
    }

    return $client;
}

    /**
     * Upload file ke Google Drive
     */
    public function upload(UploadedFile $file, $category)
    {
        $drive = new Drive($this->client());

        $folderId = $this->getOrCreateFolder($category);

        $metadata = new DriveFile([
            'name' => $file->getClientOriginalName(),
            'parents' => [$folderId],
        ]);

        return $drive->files->create(
            $metadata,
            [
                'data' => file_get_contents($file->getRealPath()),
                'mimeType' => $file->getMimeType(),
                'uploadType' => 'multipart',
                'fields' => 'id,name',
            ]
        );
    }

    /**
     * Membuat folder kategori jika belum ada
     */
    public function getOrCreateFolder($folderName)
    {
        $drive = new Drive($this->client());

        $parentFolder = env('GOOGLE_DRIVE_FOLDER_ID');

        $query =
            "name='{$folderName}' and " .
            "mimeType='application/vnd.google-apps.folder' and " .
            "'{$parentFolder}' in parents and trashed=false";

        $folders = $drive->files->listFiles([
            'q' => $query,
            'fields' => 'files(id,name)',
        ]);

        if (count($folders->getFiles()) > 0) {
            return $folders->getFiles()[0]->getId();
        }

        $folderMetadata = new DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$parentFolder],
        ]);

        $folder = $drive->files->create(
            $folderMetadata,
            [
                'fields' => 'id',
            ]
        );

        return $folder->getId();
    }

    /**
     * Hapus file dari Google Drive
     */
    public function delete($fileId)
    {
        $drive = new Drive($this->client());

        try {
            $drive->files->delete($fileId);
        } catch (\Exception $e) {
            // Abaikan jika file sudah tidak ada
        }
    }
}