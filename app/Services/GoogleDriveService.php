<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\UploadedFile;
use Exception;

class GoogleDriveService
{
    protected Client $client;
    protected Drive $drive;

    public function __construct()
    {
        $this->client = new Client();

        /*
        |--------------------------------------------------------------------------
        | GOOGLE CLIENT CONFIG
        |--------------------------------------------------------------------------
        */

        $this->client->setClientId(
            config('services.google.client_id')
        );

        $this->client->setClientSecret(
            config('services.google.client_secret')
        );

        $this->client->setRedirectUri(
            config('services.google.redirect')
        );

        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');

        /*
        |--------------------------------------------------------------------------
        | SCOPES
        |--------------------------------------------------------------------------
        */

        $this->client->setScopes([
            Drive::DRIVE,
        ]);

        /*
        |--------------------------------------------------------------------------
        | REFRESH TOKEN
        |--------------------------------------------------------------------------
        */

        $refreshToken = config(
            'services.google.refresh_token'
        );

        if (!$refreshToken) {
            throw new Exception(
                'GOOGLE_REFRESH_TOKEN belum diatur di file .env'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SET REFRESH TOKEN
        |--------------------------------------------------------------------------
        */

        $this->client->refreshToken(
            $refreshToken
        );

        /*
        |--------------------------------------------------------------------------
        | ACCESS TOKEN
        |--------------------------------------------------------------------------
        */

        $accessToken = $this->client->getAccessToken();

        if (!$accessToken) {
            throw new Exception(
                'Access Token Google tidak berhasil dibuat.'
            );
        }

        $this->client->setAccessToken(
            $accessToken
        );

        /*
        |--------------------------------------------------------------------------
        | DRIVE SERVICE
        |--------------------------------------------------------------------------
        */

        $this->drive = new Drive(
            $this->client
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPLOAD FILE
    |--------------------------------------------------------------------------
    */

    public function upload(
        UploadedFile $file,
        string $categoryName
    )
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | CARI / BUAT FOLDER KATEGORI
            |--------------------------------------------------------------------------
            */

            $folderId = $this->getOrCreateFolder(
                $categoryName
            );

            /*
            |--------------------------------------------------------------------------
            | FILE METADATA
            |--------------------------------------------------------------------------
            */

            $driveFile = new DriveFile();

            $driveFile->setName(
                $file->getClientOriginalName()
            );

            $driveFile->setParents([
                $folderId
            ]);

            /*
            |--------------------------------------------------------------------------
            | UPLOAD
            |--------------------------------------------------------------------------
            */

            $uploadedFile = $this->drive->files->create(
                $driveFile,
                [
                    'data' =>
                        file_get_contents(
                            $file->getRealPath()
                        ),

                    'mimeType' =>
                        $file->getMimeType(),

                    'uploadType' =>
                        'multipart',

                    'fields' =>
                        'id,name,mimeType,size,webViewLink',
                ]
            );

            return $uploadedFile;

        } catch (Exception $e) {

            throw new Exception(
                'Gagal upload ke Google Drive: '
                . $e->getMessage()
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CARI / BUAT FOLDER
    |--------------------------------------------------------------------------
    */

    public function getOrCreateFolder(
        string $folderName
    )
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | ROOT FOLDER DARI .ENV
            |--------------------------------------------------------------------------
            */

            $rootFolderId =
                config(
                    'services.google.drive_folder_id'
                );

            /*
            |--------------------------------------------------------------------------
            | CARI FOLDER
            |--------------------------------------------------------------------------
            */

            $query =
                "name = '"
                . addslashes($folderName)
                . "' "
                . "and mimeType = "
                . "'application/vnd.google-apps.folder' "
                . "and trashed = false";

            if ($rootFolderId) {

                $query .=
                    " and '"
                    . $rootFolderId
                    . "' in parents";

            }

            $folders =
                $this->drive->files->listFiles([
                    'q' => $query,

                    'spaces' => 'drive',

                    'fields' =>
                        'files(id,name,parents)',

                    'pageSize' => 10,
                ]);

            /*
            |--------------------------------------------------------------------------
            | KALAU SUDAH ADA
            |--------------------------------------------------------------------------
            */

            if (
                $folders->getFiles()
                &&
                count(
                    $folders->getFiles()
                ) > 0
            ) {

                return
                    $folders
                    ->getFiles()[0]
                    ->getId();
            }

            /*
            |--------------------------------------------------------------------------
            | BUAT FOLDER BARU
            |--------------------------------------------------------------------------
            */

            $folder =
                new DriveFile();

            $folder->setName(
                $folderName
            );

            $folder->setMimeType(
                'application/vnd.google-apps.folder'
            );

            if ($rootFolderId) {

                $folder->setParents([
                    $rootFolderId
                ]);

            }

            $createdFolder =
                $this->drive->files->create(
                    $folder,
                    [
                        'fields' =>
                            'id,name',
                    ]
                );

            return
                $createdFolder
                ->getId();

        } catch (Exception $e) {

            throw new Exception(
                'Gagal membuat/mencari folder Google Drive: '
                . $e->getMessage()
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE FILE
    |--------------------------------------------------------------------------
    */

    public function delete(
        string $fileId
    )
    {
        try {

            return $this->drive
                ->files
                ->delete(
                    $fileId
                );

        } catch (Exception $e) {

            throw new Exception(
                'Gagal menghapus file Google Drive: '
                . $e->getMessage()
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GET FILE
    |--------------------------------------------------------------------------
    */

    public function getFile(
        string $fileId
    )
    {
        return $this->drive
            ->files
            ->get(
                $fileId,
                [
                    'fields' =>
                        'id,name,mimeType,size,webViewLink',
                ]
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TEST CONNECTION
    |--------------------------------------------------------------------------
    */

    public function testConnection()
    {
        try {

            $result =
                $this->drive
                    ->about
                    ->get([
                        'fields' =>
                            'user(displayName,emailAddress)',
                    ]);

            return $result;

        } catch (Exception $e) {

            throw new Exception(
                'Koneksi Google Drive gagal: '
                . $e->getMessage()
            );
        }
    }
}