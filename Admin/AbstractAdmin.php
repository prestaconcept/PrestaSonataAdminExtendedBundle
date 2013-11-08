<?php
/**
 * This file is part of the PrestaSonataAdminExtendedBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\SonataAdminExtendedBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
abstract class AbstractAdmin extends Admin
{
    /**
     * The translation domain to be used to translate messages
     * 'admin' is easier and used for every application bundle
     *
     * @var string
     */
    protected $translationDomain = 'admin';

    /**
     * {@inheritdoc}
     */
    public function getLabelTranslatorStrategy()
    {
        return $this->getConfigurationPool()->getContainer()->get('sonata.admin.label.strategy.underscore');
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->getConfigurationPool()->getContainer();
    }

    /**
     * {@inheritdoc}
     *
     * Better to remove all so each admin adds only what it needs
     */
    public function getExportFormats()
    {
        return array();
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return ($this->isUserHasRole('ROLE_ADMIN') || $this->isUserHasRole('ROLE_SUPER_ADMIN'));
    }
}
