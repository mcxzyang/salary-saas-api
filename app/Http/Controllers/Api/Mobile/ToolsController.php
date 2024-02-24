<?php
/**
 * This file is part of the finance
 *
 * (c) cherrybeal <mcxzyang@gmail.com>
 *
 * This source file is subject to the MIT license is bundled
 * with the source code in the file LICENSE
 */

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class ToolsController extends Controller
{
    public function uploadPic(Request $request)
    {
        $config = config('uploader.strategies.default');
        $inputName = 'pic';
        $directory = Arr::get($config, 'directory', '{Y}/{m}/{d}');
        $disk = 'qiniu';

        if (!$request->hasFile($inputName)) {
            return $this->failed('请上传图片');
        }

        $file = $request->file($inputName);
        $filename = $this->getFilename($file, $config);

        $result = app(FileUploadService::class)->store($file, $disk, $filename, $directory, 500);
        return $this->success($result);
    }

    public function getFilename(UploadedFile $file, $config)
    {
        switch (Arr::get($config, 'filename_hash', 'default')) {
            case 'origional':
                return $file->getClientOriginalName();
            case 'md5_file':
                return md5_file($file->getRealPath()).'.'.$file->guessExtension();

                break;
            case 'random':
            default:
                return $file->hashName();
        }
    }
}
