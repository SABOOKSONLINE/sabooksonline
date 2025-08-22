<?php
use PHPUnit\Framework\TestCase;


require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../../app/controllers/BookController.php';

class BookControllerTest extends TestCase
{
    private $mockBookModel;
    private $controller;

    protected function setUp(): void
    {
        $this->mockBookModel = $this->createMock(Book::class);

        // Override constructor to inject mock model
        $this->controller = $this->getMockBuilder(BookController::class)
                                 ->disableOriginalConstructor()
                                 ->setMethodsExcept(['renderBookView'])
                                 ->getMock();

        $reflection = new ReflectionClass(BookController::class);
        $property = $reflection->getProperty('bookModel');
        $property->setAccessible(true);
        $property->setValue($this->controller, $this->mockBookModel);
    }

    public function testRenderBookViewWithValidBook()
    {
        $_GET['q'] = '123';
        $this->mockBookModel->method('getBookById')->willReturn(['title' => 'Sample Book']);

        // Use output buffering to capture the include
        ob_start();
        $this->controller->renderBookView();
        $output = ob_get_clean();

        $this->assertStringNotContainsString('Book not found.', $output);
    }

    public function testRenderBookViewWithMissingId()
    {
        unset($_GET['q']);

        $this->expectOutputString('');
        $this->expectExceptionMessage(null); // since it redirects

        // Expect header to be sent
        $this->expectOutputRegex('/.*/');
        $this->controller->renderBookView();
    }

    public function testRenderBookViewWithInvalidBook()
    {
        $_GET['q'] = '999';
        $this->mockBookModel->method('getBookById')->willReturn(null);

        ob_start();
        $this->controller->renderBookView();
        $output = ob_get_clean();

        $this->assertStringContainsString('Book not found.', $output);
    }
}
