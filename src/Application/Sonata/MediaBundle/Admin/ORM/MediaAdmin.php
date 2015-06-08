<?php
/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Application\Sonata\MediaBundle\Admin\ORM;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\ClassificationBundle\Model\CategoryManagerInterface;
use Sonata\CoreBundle\Model\Metadata;
use Sonata\MediaBundle\Provider\Pool;
use Sonata\MediaBundle\Form\DataTransformer\ProviderDataTransformer;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Knp\Menu\ItemInterface as MenuItemInterface;

use Sonata\MediaBundle\Admin\BaseMediaAdmin as BaseMediaAdmin;

class MediaAdmin extends BaseMediaAdmin
{
    /**
     * {@inheritdoc}
     */
    //protected function configureListFields(ListMapper $listMapper)
    //{
        //unset($this->listModes['mosaic']);

        //$listMapper
            //->addIdentifier('name')
            //->add('description')
            //->add('enabled')
            //->add('size')
        //;
    //}

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $media = $this->getSubject();
        if (!$media) {
            $media = $this->getNewInstance();
        }

        if (!$media || !$media->getProviderName()) {
            return;
        }

        $formMapper->add('providerName', 'hidden');
        $formMapper->getFormBuilder()->addModelTransformer(new ProviderDataTransformer($this->pool, $this->getClass()), true);
        $provider = $this->pool->getProvider($media->getProviderName());
        if ($media->getId()) {
            $provider->buildEditForm($formMapper);
        } else {
            $provider->buildCreateForm($formMapper);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prePersist($media)
    {
        $parameters = $this->getPersistentParameters();
        $media->setContext($parameters['context']);
    }
    /**
     * {@inheritdoc}
     */
    public function getPersistentParameters()
    {
        $parameters = parent::getPersistentParameters();

        if (!$this->hasRequest()) {
            return $parameters;
        }

        if ($filter = $this->getRequest()->get('filter')  && array_key_exists('context', $this->getRequest()->get('filter'))) {
            $context = $filter['context']['value'];
        } else {
            $context   = $this->getRequest()->get('context', $this->pool->getDefaultContext());
        }

        $providers = $this->pool->getProvidersByContext($context);
        $provider  = $this->getRequest()->get('provider');

        //
        // if the context has only one provider, set it into the request
        // so the intermediate provider selection is skipped
        if (count($providers) == 1 && null === $provider) {
            $provider = array_shift($providers)->getName();
            $this->getRequest()->query->set('provider', $provider);
        }

        return array_merge($parameters,array(
            'context'      => $context,
            'category'     => $this->categoryManager->getCategories($context)[0],//TODO: get value from database
            'hide_context' => (bool)$this->getRequest()->get('hide_context')
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function getNewInstance()
    {
        $media = parent::getNewInstance();

        if ($this->hasRequest()) {
            if ($this->getRequest()->isMethod('POST')) {
                $media->setProviderName($this->getRequest()->get(sprintf('%s[providerName]', $this->getUniqid()), null, true));
            } else {
                $media->setProviderName($this->getRequest()->get('provider'));
            }

            $media->setContext($context = $this->getRequest()->get('context'));

            if ($category = $this->getPersistentParameter('category')) {
                if ($category && $category->getContext()->getId() == $context) {
                    $media->setCategory($category);
                }
            }
        }

        return $media;
    }

    /**
     * @return null|\Sonata\MediaBundle\Provider\Pool
     */
    public function getPool()
    {
        return $this->pool;
    }
    /**
     * {@inheritdoc}
     */
    public function getObjectMetadata($object)
    {
        $provider = $this->pool->getProvider($object->getProviderName());
        $url = $provider->generatePublicUrl($object, $provider->getFormatName($object, 'admin'));

        return new Metadata($object->getName(), $object->getDescription(), $url);
    }
}