<?php

namespace YourPackage\Contracts;

interface UploadInterface
{
    /**
     * Upload a file to the storage.
     *
     * @param mixed  $file   The file instance (could be an instance of UploadedFile or a file path).
     * @param string $path   The destination path or folder.
     * @param array  $options Optional parameters for the upload.
     *
     * @return mixed Returns the file identifier or URL on success.
     */
    public function upload($file, string $path, array $options = []);

    /**
     * Get the URL for a stored file.
     *
     * @param string $fileIdentifier The identifier or path of the file.
     *
     * @return string The accessible URL of the file.
     */
    public function getUrl(string $fileIdentifier): string;

    /**
     * Delete a stored file.
     *
     * @param string $fileIdentifier The identifier or path of the file.
     *
     * @return bool True on success, false otherwise.
     */
    public function delete(string $fileIdentifier): bool;

    /**
     * (Optional) Update file metadata or perform other file related updates.
     *
     * @param string $fileIdentifier The identifier or path of the file.
     * @param array  $data           Data for updating the file.
     *
     * @return mixed Returns the updated file information or boolean status.
     */
    public function update(string $fileIdentifier, array $data);
}
