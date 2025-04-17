<?php

namespace PrimeBeyonder\UploadService\Contracts;

interface UploadInterface
{
    /**
     * Upload a file to the specified path.
     *
     * @param mixed $file
     * @param string $path
     * @param array $options
     * @return mixed
     */
    public function upload($file, string $path, array $options = []);

    /**
     * Get the URL of a file by its identifier.
     *
     * @param string $fileIdentifier
     * @return string
     */
    public function getUrl(string $fileIdentifier): string;

    /**
     * Delete a file by its identifier.
     *
     * @param string $fileIdentifier
     * @return bool
     */
    public function delete(string $fileIdentifier): bool;

    /**
     * Update file metadata or properties.
     *
     * @param string $fileIdentifier
     * @param array $data
     * @return mixed
     */
    public function update(string $fileIdentifier, array $data);
}
