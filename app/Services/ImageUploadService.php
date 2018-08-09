<?php
namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageUploadService
{
    protected $fileSystem = null;

    protected $path;

    function __construct()
    {
        $this->fileSystem = app("files");
        $this->path = public_path("storage/listings");
    }

    public function saveImage(UploadedFile $image) {

        do{
            $hash = $this->expandPath(bin2hex(random_bytes(16)));
            $subPath = $hash . "." . $image->getClientOriginalExtension();
            $fullPath = "{$this->path}/$subPath";
        }while($this->fileSystem->exists($fullPath));

        $this->fileSystem->putFileForce($fullPath, file_get_contents($image->getRealPath()));

        return $subPath;
    }

    private function expandPath(string $path): string
    {
        return sprintf(
            '%s/%s/%s',
            substr($path, 0, 2),
            substr($path, 2, 2),
            substr($path, 4)
        );
    }

    private function has(string $path) : bool
    {
        return $this->filesystem->exists($path);
    }

    public function removePhoto(string $path)
    {
        return $this->fileSystem->delete($path);
    }



}