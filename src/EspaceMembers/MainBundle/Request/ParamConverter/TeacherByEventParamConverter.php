<?php
/**
 * This file is part of the EspaceMembers project.
 *
 * (c) Stanislav Stepanenko <dsazztazz@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EspaceMembers\MainBundle\Request\ParamConverter;

use Doctrine\Common\Persistence\ManagerRegistry;
use EspaceMembers\MainBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * TeacherByEventParamConverter
 *
 * @author Stepanenko Stanislav <dsazztazz@gmail.com>
 */
class TeacherByEventParamConverter implements ParamConverterInterface
{
    /**
     * @var ManagerRegistry $registry Manager registry
     */
    private $registry;

    /**
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry = null)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     *
     * Check the object is supported by our converter
     */
    public function supports(ParamConverter $configuration)
    {
        if (null === $this->registry || !count($this->registry->getManagers())) {
            return false;
        }

        if (null === $configuration->getClass()) {
            return false;
        }

        $em = $this->registry->getManagerForClass($configuration->getClass());

        if ('Application\Sonata\UserBundle\Entity\User' !== $em->getClassMetadata($configuration->getClass())->getName()) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * Treat the object and save it in request
     *
     * @throws \InvalidArgumentException If no attributes are routed
     * @throws NotFoundHttpException     If not found object
     *
     * @return void
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $eventId = $request->attributes->get('event_id');
        $teacherId = $request->attributes->get('teacher_id');

        if (null === $eventId || null === $teacherId) {
            throw new \InvalidArgumentException('Route attribute is missing');
        }

        $em = $this->registry->getManagerForClass($configuration->getClass());

        $userRepository = $em->getRepository($configuration->getClass());

        $teacher = $userRepository->findTeacherByEvent($teacherId, $eventId);

        if (null === $teacher || !($teacher instanceof UserInterface)) {
            throw new NotFoundHttpException(sprintf('%s object not found.', $configuration->getClass()));
        }

        $request->attributes->set($configuration->getName(), $teacher);
    }
}
