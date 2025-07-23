<?php

namespace myatKyawThu\LaravelApiVisibility;

use myatKyawThu\LaravelApiVisibility\Contracts\DocumentationGeneratorInterface;
use myatKyawThu\LaravelApiVisibility\Contracts\ResponsePreviewerInterface;

class FacadeManager
{
    /**
     * @var DocumentationGeneratorInterface
     */
    protected $documentationGenerator;

    /**
     * @var ResponsePreviewerInterface
     */
    protected $responsePreviewer;

    /**
     * Create a new facade manager instance.
     *
     * @param DocumentationGeneratorInterface $documentationGenerator
     * @param ResponsePreviewerInterface $responsePreviewer
     */
    public function __construct(
        DocumentationGeneratorInterface $documentationGenerator,
        ResponsePreviewerInterface $responsePreviewer
    ) {
        $this->documentationGenerator = $documentationGenerator;
        $this->responsePreviewer = $responsePreviewer;
    }

    /**
     * Get the API documentation.
     *
     * @return array
     */
    public function getDocumentation()
    {
        return $this->documentationGenerator->generate();
    }

    /**
     * Preview a response for a route.
     *
     * @param string $routeName
     * @param array $parameters
     * @return mixed
     */
    public function previewResponse(string $routeName, array $parameters = [])
    {
        return $this->responsePreviewer->preview($routeName, $parameters);
    }
}
