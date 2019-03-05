<?php

namespace App\Helpers;

use File;
use Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Utils
{
    private $path;

    public function __construct()
    {
        $this->path = '/%s/%s/%s/';
    }

    public function saveImage($image, $folder)
    {
        $img = Image::make($image)->resize(300, 300);
        $mime = explode('/', $img->mime());
        $mime = key_exists(1, $mime) ? $mime[1] : 'jpg';
        $fileName = Str::uuid() . '.' . $mime;
        $this->path = sprintf($this->path, $folder, substr($fileName, 0, 2), substr($fileName, 2, 2));
        $dest = $this->path . $fileName;

        Storage::put('public'. $dest, $img->stream());

        return $dest;
    }

    public function saveFile(UploadedFile $archive, $folder)
    {
        $eOrigName = explode('.', $archive->getClientOriginalName());
        $fileName = Str::uuid() . '.' . $eOrigName[(count($eOrigName) - 1)];
        $this->path = sprintf($this->path, $folder, substr($fileName, 0, 2), substr($fileName, 2, 2));
        $dest = storage_path('app/public') . $this->path;
        $archive->move($dest, studly_case($fileName));
        return $this->path . studly_case($fileName);
    }

    public function removeFile($filePath)
    {
        $root = storage_path('app/public');
        $folder = $root . File::dirname($filePath);
        $fileName = File::name($filePath);

        if (!File::exists($root . $filePath)) {
            return true;
        }

        File::delete($root . $filePath);
        $this->removeLinkedFile($folder, $fileName);
    }

    private function removeLinkedFile($folder, $fileName)
    {
        $files = File::files($folder);

        foreach ($files as $file) {
            $name = explode('-', File::name($file))[0];
            if ($name == $fileName) {
                File::delete($file);
            }
        }

        $this->removeEmptyFolder($folder);
    }

    private function removeEmptyFolder($folder)
    {
        $files = File::files($folder);
        $directories = File::directories($folder);

        if (File::basename($folder) == 'uploads') {
            return true;
        }

        if (count($files) == 0) {
            if (count($directories) == 0) {
                File::deleteDirectory($folder);
                $this->removeEmptyFolder(File::dirname($folder));
            }
        }

        return true;
    }

    public static function getUpdateRules($tableName, array $rules, $id)
    {
        foreach ($rules as $key => $value) {
            if (strpos($value, 'unique:') !== false) {
                $ruleValue = '';
                $validations = explode("|", $value);

                foreach ($validations as $vl) {
                    if (strpos($vl, 'unique:') !== false) {
                        $un = explode(",", $vl);
                        $ruleValue .= sprintf('%s,%s,%d|', $un[0], $un[1], $id);
                    } else {
                        $ruleValue .= $vl . '|';
                    }
                }

                $rules[$key] = $ruleValue;
            }
        }

        $rules['id'] = 'required|exists:' . $tableName . ',id';

        foreach ($rules as $key => $value) {
            if (substr($value, -1) === '|') {
                $rules[$key] = substr($value, 0, -1);
            }
        }

        return $rules;
    }
}
