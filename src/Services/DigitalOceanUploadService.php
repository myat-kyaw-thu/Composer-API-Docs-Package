<?php

namespace Primebeyonder\LaravelUploadService\Services;

use Illuminate\Support\Facades\Storage;
use PrimeBeyonder\UploadService\Contracts\UploadInterface;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

class DigitalOceanUploadService implements UploadInterface
{
    protected $disk;

    public function __construct()
    {
        $this->disk = Storage::disk('do_spaces');

        if (!$this->disk) {
            throw new InvalidArgumentException('DigitalOcean Spaces disk is not configured.');
        }
    }

    public function upload($file, string $path, array $options = [])
    {
        $filename = is_string($file)
            ? basename($file)
            : $file->getClientOriginalName();

        $storagePath = $path . '/' . uniqid() . '_' . $filename;

        if (is_string($file)) {
            $this->disk->put($storagePath, file_get_contents($file), $options);
        } elseif ($file instanceof UploadedFile) {
            $this->disk->putFileAs($path, $file, $storagePath, $options);
        } else {
            throw new InvalidArgumentException('Unsupported file type.');
        }

        return $storagePath;
    }

    public function getUrl(string $fileIdentifier): string
    {
        return $this->disk->url($fileIdentifier);
    }

    public function delete(string $fileIdentifier): bool
    {
        return $this->disk->delete($fileIdentifier);
    }

    public function update(string $fileIdentifier, array $data)
    {
        // Optional – not implemented yet.
        return false;
    }
}
