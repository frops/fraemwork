<?php
/**
 * @author Ildar Asanov <i.asanov@corp.mail.ru>
 */

namespace Simplex\Tests;

use PHPUnit\Framework\TestCase;
use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing;
use Throwable;

class FrameworkTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testNotFoundHandling()
    {
        $framework = $this->getFrameworkForException(new Routing\Exception\ResourceNotFoundException());
        $response = $framework->handle(new Request());
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testErrorHandling()
    {
        $framework = $this->getFrameworkForException(new \RuntimeException());
        $response = $framework->handle(new Request());
        $this->assertEquals(500, $response->getStatusCode());
    }

    /**
     * @throws Throwable
     */
    public function testControllerResponse()
    {
        $matcher = $this->createMock(Routing\Matcher\UrlMatcherInterface::class);

        $matcher->expects($this->once())->method('match')->will($this->returnValue([
                '_route' => 'foo',
                'name' => 'frops',
                '_controller' => function ($name) {
                    return new Response("Hello {$name}");
                }
            ]));

        $matcher->expects($this->once())->method('getContext')
            ->will($this->returnValue($this->createMock(Routing\RequestContext::class)));

        $framework = new Framework($matcher, new ControllerResolver(), new ArgumentResolver());
        $response = $framework->handle(new Request());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains("Hello frops", $response->getContent());
    }

    /**
     * @param Throwable $exception
     * @return Framework
     * @throws Throwable
     */
    private function getFrameworkForException(Throwable $exception)
    {
        $matcher = $this->createMock(Routing\Matcher\UrlMatcherInterface::class);

        $matcher->expects($this->once())->method('match')->will($this->throwException($exception));

        $matcher->expects($this->once())->method('getContext')
            ->will($this->returnValue($this->createMock(Routing\RequestContext::class)));

        $controllerResolver = $this->createMock(ControllerResolverInterface::class);
        $argumentResolver = $this->createMock(ArgumentResolverInterface::class);

        return new Framework($matcher, $controllerResolver, $argumentResolver);
    }
}