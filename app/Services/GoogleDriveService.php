<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\UploadedFile;


class GoogleDriveService
{

    public function client()
    {

        $client = new Client();


        $client->setAuthConfig(
            storage_path('app/google/credentials.json')
        );


        $client->addScope(
            Drive::DRIVE
        );


        $client->setAccessType('offline');

        $client->setPrompt(
            'select_account consent'
        );


        $tokenPath =
            storage_path('app/google/token.json');


        if(file_exists($tokenPath))
        {

            $token = json_decode(
                file_get_contents($tokenPath),
                true
            );


            $client->setAccessToken($token);


            if($client->isAccessTokenExpired())
            {

                if($client->getRefreshToken())
                {

                    $newToken =
                    $client->fetchAccessTokenWithRefreshToken(
                        $client->getRefreshToken()
                    );


                    $token = array_merge(
                        $token,
                        $newToken
                    );


                    file_put_contents(
                        $tokenPath,
                        json_encode($token)
                    );


                    $client->setAccessToken($token);

                }

            }

        }


        return $client;

    }




    public function upload(
        UploadedFile $file,
        $category
    )
    {

        $drive = new Drive(
            $this->client()
        );


        // folder kategori
        $folderId =
        $this->getOrCreateFolder(
            $category
        );



        $fileMetadata = new DriveFile([

            'name' =>
            $file->getClientOriginalName(),

            'parents'=>[
                $folderId
            ]

        ]);



        $uploaded =
        $drive->files->create(

            $fileMetadata,

            [

                'data'=>
                file_get_contents(
                    $file->getRealPath()
                ),

                'mimeType'=>
                $file->getMimeType(),

                'uploadType'=>
                'multipart',

                'fields'=>
                'id,name'

            ]

        );


        return $uploaded;

    }




    public function getOrCreateFolder($name)
    {

        $drive = new Drive(
            $this->client()
        );


        $search =
        $drive->files->listFiles([


            'q'=>
            "name='$name'
            and mimeType='application/vnd.google-apps.folder'
            and trashed=false",


            'fields'=>
            'files(id,name)'

        ]);



        if(count($search->files) > 0)
        {

            return $search->files[0]->id;

        }



        $folder = new DriveFile([


            'name'=>$name,


            'mimeType'=>
            'application/vnd.google-apps.folder',


            'parents'=>[

                env('GOOGLE_DRIVE_FOLDER_ID')

            ]

        ]);



        $created =
        $drive->files->create(

            $folder,

            [
                'fields'=>'id'
            ]

        );


        return $created->id;

    }





    public function delete($id)
    {

        $drive =
        new Drive(
            $this->client()
        );


        $drive->files->delete($id);

    }


}