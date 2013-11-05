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

use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

use Sonata\AdminBundle\Admin\Pool;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;

/**
 * Dashboard Media Management block
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class MediaBlockService extends BaseBlockService
{
    /**
     * @var Pool
     */
    protected $pool;

    /**
     * @param string            $name
     * @param EngineInterface   $templating
     * @param Pool              $pool
     */
    public function __construct($name, EngineInterface $templating, Pool $pool)
    {
        parent::__construct($name, $templating);

        $this->pool = $pool;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Dashboard Media';
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $settings = array_merge($this->getDefaultSettings(), $blockContext->getSettings());

        return $this->renderResponse(
            'PrestaSonataAdminExtendedBundle:Block/Dashboard:block_media.html.twig',
            array(
                'block_context' => $blockContext,
                'block'         => $blockContext->getBlock(),
                'blockId'       => 'block-media',
                'mediaAdmin'    => $this->pool->getAdminByAdminCode('sonata.media.admin.media'),
                'galleryAdmin'  => $this->pool->getAdminByAdminCode('sonata.media.admin.gallery'),
                'settings'      => $settings
            ),
            $response
        );
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
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
    }
}
