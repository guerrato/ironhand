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

    public function normalizeString(string $string)
    {
        $conversion_list = [
            'Š'=>'S',
            'š'=>'s',
            'Ž'=>'Z',
            'ž'=>'z',
            'À'=>'A',
            'Á'=>'A',
            'Â'=>'A',
            'Ã'=>'A',
            'Ä'=>'A',
            'Å'=>'A',
            'Æ'=>'A',
            'Ç'=>'C',
            'È'=>'E',
            'É'=>'E',
            'Ê'=>'E',
            'Ë'=>'E',
            'Ì'=>'I',
            'Í'=>'I',
            'Î'=>'I',
            'Ï'=>'I',
            'Ñ'=>'N',
            'Ò'=>'O',
            'Ó'=>'O',
            'Ô'=>'O',
            'Õ'=>'O',
            'Ö'=>'O',
            'Ø'=>'O',
            'Ù'=>'U',
            'Ú'=>'U',
            'Û'=>'U',
            'Ü'=>'U',
            'Ý'=>'Y',
            'Þ'=>'B',
            'ß'=>'Ss',
            'à'=>'a',
            'á'=>'a',
            'â'=>'a',
            'ã'=>'a',
            'ä'=>'a',
            'å'=>'a',
            'æ'=>'a',
            'ç'=>'c',
            'è'=>'e',
            'é'=>'e',
            'ê'=>'e',
            'ë'=>'e',
            'ì'=>'i',
            'í'=>'i',
            'î'=>'i',
            'ï'=>'i',
            'ð'=>'o',
            'ñ'=>'n',
            'ò'=>'o',
            'ó'=>'o',
            'ô'=>'o',
            'õ'=>'o',
            'ö'=>'o',
            'ø'=>'o',
            'ù'=>'u',
            'ú'=>'u',
            'û'=>'u',
            'ý'=>'y',
            'þ'=>'b',
            'ÿ'=>'y',
            'Ğ'=>'G',
            'İ'=>'I',
            'Ş'=>'S',
            'ğ'=>'g',
            'ı'=>'i',
            'ş'=>'s',
            'ü'=>'u',
            'ă'=>'a',
            'Ă'=>'A',
            'ș'=>'s',
            'Ș'=>'S',
            'ț'=>'t',
            'Ț'=>'T'
        ];

        $string = strtolower(strtr($string, $conversion_list));
        $string = preg_replace('/[^a-z\d]+/i', ' ', $string);
        return trim($string);
    }

    public function getSimilarity($str1, $str2)
    {
        $percentage = 0;
        $sim = similar_text($str1, $str2, $percentage);

        return [
            'similarity' => $sim,
            'percentage' => $percentage
        ];
    }

    public function getLevenshteinSimilarity($str1, $str2)
    {
        return levenshtein($str1, $str2, 1, 1, 3);
    }
}
