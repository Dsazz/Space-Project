<?php

namespace EspaceMembers\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Knp\Menu\ItemInterface as MenuItemInterface;

class TagAdmin extends Admin
{
    /*
     * Конфигурация отображения записи
     *
     * @param  \Sonata\AdminBundle\Show\ShowMapper $showMapper
     * @return void
    */
    protected function configureShowField(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id', null, array('label' => 'ID'))
            ->add('title', null, array('label' => 'Title'))
        ;
    }

    /**
     * Конфигурация формы редактирования записи
     * @param  \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, array('label' => 'Title'))
            ->add('title', null, array('label' => 'Title'))
        ;
    }

    /**
     * Конфигурация списка записей
     *
     * @param  \Sonata\AdminBundle\Datagrid\ListMapper $listMapper
     * @return void
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('title', null, array('label' => 'Title'))
        ;
    }

    /**
     * Поля, по которым производится поиск в списке записей
     *
     * @param  \Sonata\AdminBundle\Datagrid\DatagridMapper $datagridMapper
     * @return void
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', null, array('label' => 'Title'))
        ;
    }

    /**
     * Конфигурация левого меню при отображении и редатировании записи
     *
     * @param \Knp\Menu\ItemInterface              $menu
     * @param $action
     * @param null|\Sonata\AdminBundle\Admin\Admin $childAdmin
     *
     * @return void
     */
    //protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    //{
        //if (!$childAdmin && !in_array($action, array('edit', 'show'))) {
            //return;
        //}
        //$admin = $this->isChild() ? $this->getParent() : $this;
        //$id = $admin->getRequest()->get('id');

        //$menu->addChild(
            //$action == 'edit' ? 'Просмотр новости' : 'Редактирование новости',
            //array('uri' => $this->generateUrl(
                //$action == 'edit' ? 'show' : 'edit', array('id' => $id)))
        //);
    //}

}