<?php

namespace PrimeBeyonder\UploadService\Services;

use PrimeBeyonder\UploadService\Contracts\UploadInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class LocalUploadService implements UploadInterface
{
    /**
     * The filesystem disk instance.
     *
     * @var \Illuminate\Filesystem\FilesystemAdapter
     */
    protected $disk;

    /**
     * Instantiate the local upload service.
     */
    public function __construct()
    {
        // You may change 'local' to a config value if needed
        $this->disk = Storage::disk('local');
    }

    /**
     * {@inheritdoc}
     */
    public function upload($file, string $path, array $options = [])
    {
        // If it's an UploadedFile, use the built-in store method
        if ($file instanceof UploadedFile) {
            return $file->store($path, array_merge(['disk' => 'local'], $options));
        }

        // If it's a file path, stream it into the disk
        if (is_string($file) && file_exists($file)) {
            $filename = basename($file);
            $stream = fopen($file, 'r+');
            $this->disk->put($path . '/' . $filename, $stream, $options);
            fclose($stream);
            return $path . '/' . $filename;
        }

        throw new \InvalidArgumentException('Invalid file provided for upload');
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl(string $fileIdentifier): string
    {
        return $this->disk->url($fileIdentifier);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(string $fileIdentifier): bool
    {
        return $this->disk->delete($fileIdentifier);
    }

    /**
     * {@inheritdoc}
     */
    public function update(string $fileIdentifier, array $data)
    {
        // Local filesystem typically doesn't support metadata updates out of the box.
        // You could implement setting visibility or timestamps here if needed.
        return false;
    }
}
