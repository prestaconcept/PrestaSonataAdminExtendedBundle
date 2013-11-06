<?php
/**
 * This file is part of the PrestaSonataAdminExtendedBundle
 *
 * (c) PrestaConcept <http://www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\SonataAdminExtendedBundle\Block\Dashboard;

use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\BaseBlockService;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class UserBlockService extends BaseBlockService
{
    /**
     * @var Pool
     */
    protected $pool;

    /**
     * @param string          $name
     * @param EngineInterface $templating
     * @param Pool            $pool
     */
    public function __construct($name, EngineInterface $templating, Pool $pool)
    {
        parent::__construct($name, $templating);

        $this->pool = $pool;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        if (!$response) {
            $response = new Response;
        }
        //------------------
        // check role
        $security = $this->pool->getContainer()->get('security.context');
        if (!$security->isGranted('ROLE_ADMIN_USER')) {
            return $response;
        }
        //------------------

        $settings = array_merge($this->getDefaultSettings(), $blockContext->getSettings());

        return $this->renderResponse(
            'PrestaSonataAdminExtendedBundle:Block/Dashboard:block_user.html.twig',
            array(
                'block_context' => $blockContext,
                'block'         => $blockContext->getBlock(),
                'blockId'       => 'block-user',
                'userAdmin'     => $this->pool->getAdminByAdminCode('sonata.user.admin.user'),
                'groupAdmin'    => $this->pool->getAdminByAdminCode('sonata.user.admin.group'),
                'settings'      => $settings
            ),
            $response
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Dashboard Users';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $form, BlockInterface $block)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }
}
