<?php

namespace App\Domain\Message\Template\Service;

use App\Application\Message\Template\DTO\TemplateCreateDTO;
use App\Domain\Message\Response\ApiResponse;
use App\Domain\Message\Response\ApiResponseInterface;
use App\Domain\Message\Template\Exception\TemplateException;
use App\Domain\Message\Template\ValueObject\Template;
use App\Infrastructure\HttpClient\ResponseDataExtractorInterface;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Throwable;

final class TemplateAdapter
{
    private const TEMPLATES_URI = '/api/admin/templates/';
    private const TEMPLATE_URI = '/api/admin/templates/%s';

    private string $host;
    private ClientInterface $client;
    private ResponseDataExtractorInterface $responseDataExtractor;

    public function __construct(
        ClientInterface $client,
        ResponseDataExtractorInterface $responseDataExtractor,
        string $host
    ) {
        $this->host = $host;
        $this->client = $client;
        $this->responseDataExtractor = $responseDataExtractor;
    }

    public function createTemplate(Template $template): ApiResponseInterface
    {
        try {
            $payload = json_encode(TemplateCreateDTO::transform($template), JSON_THROW_ON_ERROR);
            $request = new Request('POST', $this->host . self::TEMPLATES_URI, [], $payload);
            $response = $this->client->sendRequest($request);
            $dtaExtract = $this->responseDataExtractor->extract($response);

            return new ApiResponse($dtaExtract);
        } catch (Throwable $exception) {
            throw TemplateException::createTemplateError($exception->getMessage());
        }
    }

    public function getAllTemplates(): ApiResponseInterface
    {
        try {
            $request = new Request('GET', $this->host . self::TEMPLATES_URI);
            $response = $this->client->sendRequest($request);
            $dataExtract = $this->responseDataExtractor->extract($response);

            return new ApiResponse($dataExtract);
        } catch (Throwable $exception) {
            throw TemplateException::getAllTemplatesError($exception->getMessage());
        }
    }

    public function getTemplateById(string $id): ApiResponseInterface
    {
        try {
            $request = new Request('GET', $this->host . sprintf(self::TEMPLATE_URI, $id));
            $response = $this->client->sendRequest($request);
            $dataExtract = $this->responseDataExtractor->extract($response);

            return new ApiResponse($dataExtract);
        } catch (Throwable $exception) {
            throw TemplateException::getTemplateError($id, $exception->getMessage());
        }
    }

    public function updateTemplateById(string $id, array $data): ApiResponseInterface
    {
        try {
            $payload = json_encode($data, JSON_THROW_ON_ERROR);
            $request = new Request('PUT', $this->host . sprintf(self::TEMPLATE_URI, $id), [], $payload);
            $response = $this->client->sendRequest($request);
            $dataExtract = $this->responseDataExtractor->extract($response);

            return new ApiResponse($dataExtract);
        } catch (Throwable $exception) {
            throw TemplateException::updateTemplateError($id, $exception->getMessage());
        }
    }

    public function deleteTemplateById(string $id): ApiResponseInterface
    {
        try {
            $request = new Request('DELETE', $this->host . sprintf(self::TEMPLATE_URI, $id));
            $response = $this->client->sendRequest($request);
            $dataExtract = $this->responseDataExtractor->extract($response);

            return new ApiResponse($dataExtract);
        } catch (Throwable $exception) {
            throw TemplateException::deleteTemplateError($id, $exception->getMessage());
        }
    }
}
