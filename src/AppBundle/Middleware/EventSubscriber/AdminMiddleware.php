<?php
/**
 * Created by PhpStorm.
 * User: xoka
 * Date: 22.08.17
 * Time: 13:03
 */

namespace AppBundle\Middleware\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use AppBundle\Middleware\Contracts\AdminMiddlewareContract as Contract;

class AdminMiddleware implements EventSubscriberInterface
{
    private const ADMIN_USERNAME = 'admin';
    private const PASSWORD = '123456';
    private $headers;

    public function handle(FilterControllerEvent $event)
    {
        $this->headers = $event->getRequest()->headers;
        $controller = $event->getController();

        if ($this->isSubscribedController($controller) && !$this->validAdmin()) {
            throw new UnauthorizedHttpException('You are not allowed to perform this action.');
        }
    }

    private function isSubscribedController($controller): bool
    {
        return is_array($controller) && $controller[0] instanceof Contract;
    }

    private function validAdmin(): bool
    {
        return $this->hasAdmin() && $this->passwordValid();
    }

    private function hasAdmin(): bool
    {
        return $this->headers->get('X-UserName') === self::ADMIN_USERNAME;
    }

    private function passwordValid(): bool
    {
        return $this->headers->get('X-Password') === sha1(self::PASSWORD);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'handle',
        ];
    }
}