<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EspaceMembers\SecurityBundle\Component\Authentication\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

/**
 * LoginSuccessHandler
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    protected $router;
    protected $security;

    public function __construct(RouterInterface $router, SecurityContext $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {

        if ($this->security->isGranted('ROLE_SUPER_ADMIN')
            || $this->security->isGranted('ROLE_ADMIN')
        ) {
            $response = new RedirectResponse($this->router->generate('sonata_admin_dashboard'));
        } elseif ($this->security->isGranted('ROLE_USER')) {
            // redirect the user to where they were
            // before the login process begun.
            $referer_url = $request->headers->get('referer');

            $response = new RedirectResponse($referer_url);
        }

        return $response;
    }

}
