<?php
/**
 * Copyright (c) 2020 JOOservices Ltd
 * @author Viet Vu <jooservices@gmail.com>
 * @package XGallery
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Oauth\Services\Google;

use App\Models\Oauth;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GoogleDrive
{
    private Google_Client $client;
    private Google_Service_Drive $drive;

    public function __construct(Google_Client $client)
    {
        if (!$goauth = Oauth::where(['client' => 'google'])->first()) {
            return;
        }

        $this->client = $client;
        $this->client->setAccessToken($goauth->token);
        $this->drive = new Google_Service_Drive($this->client);
    }

    /**
     * @param  string  $id
     * @return Google_Service_Drive_DriveFile
     */
    public function listFolder(string $id = 'root'): Google_Service_Drive_DriveFile
    {
        $query = "mimeType='application/vnd.google-apps.folder' and '".$id."' in parents and trashed=false";

        $optParams = [
            'fields' => 'files(id, name)',
            'q' => $query
        ];

        $results = $this->drive->files->listFiles($optParams);

        return $results->getFiles();
    }

    public function createFolder($folderName)
    {
        $folderMeta = new Google_Service_Drive_DriveFile(array(
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder'));
        $folder = $this->drive->files->create($folderMeta, array(
            'fields' => 'id'));
        return $folder->id;
    }

    public function createFile($file, $parentId = 'root'): string
    {
        $name = gettype($file) === 'object' ? $file->getClientOriginalName() : $file;
        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => $name,
            'parent' => $parentId
        ]);

        $content = gettype($file) === 'object' ?  File::get($file) : Storage::get($file);
        $mimeType = gettype($file) === 'object' ? File::mimeType($file) : Storage::mimeType($file);

        $file = $this->drive->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $mimeType,
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        return $file->id;
    }

    public function isFolderExists(string $name)
    {
    }
}
