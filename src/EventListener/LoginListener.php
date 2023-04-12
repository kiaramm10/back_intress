<?php

namespace App\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LoginListener
{
    private UrlGeneratorInterface $urlGenerator;
    private TokenStorageInterface $tokenStorage;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        TokenStorageInterface $tokenStorage
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->tokenStorage = $tokenStorage;
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $roles = $user->getRoles();

        // Redirigir al usuario a la página correspondiente según su rol
        if (in_array('ROLE_ADMIN', $roles)) {
            $url = $this->urlGenerator->generate('admin_home');
        } elseif (in_array('ROLE_MASTER', $roles)) {
            $url = $this->urlGenerator->generate('master_home');
        } else {
            $url = $this->urlGenerator->generate('personal_home');
        }

        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }
}
