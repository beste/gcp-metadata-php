<?php

declare(strict_types=1);

namespace Kreait;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Kreait\GcpMetadata\Error;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class GcpMetadataTest extends TestCase
{
    private ClientInterface $client;
    private GcpMetadata $metadata;

    protected function setUp(): void
    {
        $this->client = $this->createStub(ClientInterface::class);

        $this->metadata = new GcpMetadata($this->client);
    }

    public function testItUsesADefaultClient(): void
    {
        $metadata = new GcpMetadata();

        // We cannot unit test this with Gcp being available, but the default client expects it to be
        $this->expectException(Error::class);
        $metadata->instance();
    }

    public function testItIsAvailable(): void
    {
        $this->client->method('request')->willReturn($this->createResponse());

        $this->assertTrue($this->metadata->isAvailable());
    }

    public function testItIsNotAvailable(): void
    {
        $this->client->method('request')
            ->willThrowException(new ConnectException('Connection refused', new Request('GET', GcpMetadata::baseUrl)));

        $this->assertFalse($this->metadata->isAvailable());
    }

    public function testItRequiresCertainHttpResponseHeaders(): void
    {
        $this->client->method('request')->willReturn(new Response(200));

        $this->assertFalse($this->metadata->isAvailable());
    }

    public function testItRequiresASuccessfulHttpResponse(): void
    {
        $this->client->method('request')->willReturn($this->createResponse(500, 'details'));

        $this->expectException(Error::class);
        $this->metadata->instance();
    }

    #[DataProvider('responseStrings')]
    public function testItParsesHttpResponsesContaining($expectedResult, $responseString): void
    {
        $this->client->method('request')->willReturn($this->createResponse(200, $responseString));

        $this->assertSame($expectedResult, $this->metadata->instance('foo'));
        $this->assertSame($expectedResult, $this->metadata->project('foo'));
    }

    public static function responseStrings(): array
    {
        return [
            'an empty body' => ['', null],
            'a single line' => ['foo', 'foo'],
            'multiple lines' => [['foo', 'bar'], "foo\nbar"],
        ];
    }

    private function createResponse(int $status = 200, $body = ''): Response
    {
        $headers = [GcpMetadata::flavorHeaderName => GcpMetadata::flavorHeaderValue];

        return new Response($status, $headers, $body);
    }
}
