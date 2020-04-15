<?php

/**
 * @coversDefaultClass \App\UI\Http\Controllers\SwaggerController
 */
class SwaggerControllerTest extends TestCase
{
    /**
     * @covers ::index
     */
    public function testIndexPage(): void
    {
        $this->get('/');
        $this->assertEquals(302, $this->response->getStatusCode());
    }

    /**
     * @covers ::update
     */
    public function testUpdate(): void
    {
        $this->get('/swagger/update');
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertNotFalse((string)strpos($this->response->getContent(), 'Cloud-storage API'));
    }
}
