<?php
/**
 * This file is part of the PrestaSonataAdminExtendedBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\SonataAdminExtendedBundle\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController;

/**
 * Base controller for administration
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class BaseCRUDController extends CRUDController
{
    /**
     * {@inheritdoc}
     */
    public function createAction()
    {
        //If creation right is dynamic : for exemple with creationg relation entyties
        //it's usefull if user click on click and add in th form
        if (false === $this->admin->isGranted('CREATE')) {
            return $this->listAction();
        }

        return parent::createAction();
    }
}
