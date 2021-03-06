<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EspaceMembers\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use EspaceMembers\MainBundle\Controller\BaseController as Controller;

/**
 * CommunityController
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class CommunityController extends Controller
{
    public function indexAction(Request $request, $page)
    {
        $em = $this->getDoctrine()->getManager();

        $pagerfanta = $this->getPagerfantaByArrayResults(
            $em->getRepository('ApplicationSonataUserBundle:User')
                ->findTeachersAndStudents()
        );

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Community:community.html.twig', array(
            'paginator' => $pagerfanta,
            'users'     => $pagerfanta->getCurrentPageResults(),
            'groups'    => $em->getRepository('ApplicationSonataUserBundle:Group')->findAllNames(),
        ));
    }

    public function filterByGroupAction(Request $request, $page)
    {
        $em = $this->getDoctrine()->getManager();

        $pagerfanta = $this->getPagerfantaByArrayResults(
            $em->getRepository('ApplicationSonataUserBundle:User')
                ->findTeacherAndStudentsByGroup($request->get('group_name'))
        );

        $this->setCurrentPageOr404($pagerfanta, $page);

        return $this->render('EspaceMembersMainBundle:Community:community.html.twig', array(
            'paginator' => $pagerfanta,
            'users'     => $pagerfanta->getCurrentPageResults(),
            'groups' => $em->getRepository('ApplicationSonataUserBundle:Group')->findAllNames(),
        ));
    }

}
