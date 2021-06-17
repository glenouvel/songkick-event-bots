<?php

namespace App\Tests;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;

trait ResponseMockTrait
{
    /**
     * @param string $fileContent
     * @return Response
     */
    protected function getResponseMock(string $fileContent)
    {
        $stream = $this->getMockBuilder(Stream::class)->disableOriginalConstructor()->getMock();
        $stream->method('getContents')->willReturn(file_get_contents($fileContent));
        $response = $this->getMockBuilder(Response::class)->getMock();

        $response->method('getBody')->willReturn($stream);

        return $response;
    }
}
