<?php
/**
 * This file is part of the PrestaSonataAdminExtendedBundle
 *
 * (c) PrestaConcept <www.prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\SonataAdminExtendedBundle\Fixture\Loader;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\Yaml\Parser;
use Doctrine\Common\Util\Inflector;

/**
 * @author Alain Flaus <aflaus@prestaconcept.net>
 */
abstract class AbstractYmlLoader extends AbstractFixture
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var string
     */
    protected $fileDir = null;

    /**
     * @var string
     */
    protected $class = null;

    /**
     * Create references
     *
     * @param  ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        if (($this->fileDir === null) || ($this->class === null)) {
            throw new \Exception('Invalid yml loader configuration');
        }

        $yaml           = new Parser();
        $datas          = $yaml->parse(file_get_contents($this->fileDir));
        $this->manager  = $manager;

        foreach ($datas as $code => $entityConfiguration) {
            $this->createEntity(
                $this->class,
                $code,
                $entityConfiguration
            );
        }
    }

    /**
     * Create entity
     *
     * @param string $class
     * @param string $code
     * @param array  $entityConfiguration
     */
    protected function createEntity($class, $code, $entityConfiguration)
    {
        $entity = new $class();

        foreach ($entityConfiguration as $fieldType => $values) {
            switch ($fieldType) {
                case 'associations':
                    $this->bindAssociations($entity, $values);
                    break;
                case 'fields':
                    $this->bind($entity, $values);
                    break;
                default:
                    //Translatable entity case
                    $this->bindTranslation($entity, $fieldType, $values);
                    break;
            }
        }

        $this->addReference($code, $entity);

        $this->manager->persist($entity);
        $this->manager->flush();
    }

    /**
     * @param $entity
     * @param $fields
     */
    protected function bind(&$entity, $fields)
    {
        foreach ($fields as $fieldName => $value) {
            $setterName = sprintf('set%s', Inflector::classify($fieldName));

            $entity->$setterName($value);
        }
    }

    /**
     * Add translation
     *
     * @param misc   $entity
     * @param string $locale
     * @param array  $fields
     */
    protected function bindTranslation(&$entity, $locale, $fields)
    {
        $entity->setLocale($locale);

        $this->bind($entity, $fields);

        $this->manager->persist($entity);
        $this->manager->flush();
    }

    /**
     * Add association to other entity
     *
     * @param misc  $entity
     * @param array $associations
     */
    protected function bindAssociations(&$entity, $associations)
    {
        foreach ($associations as $associationName => $associatedEntities) {
            $assocMethodName = $this->getAssocMethodName($entity, $associationName);

            foreach ($associatedEntities as $refCode) {
                $entity->$assocMethodName($this->getReference($refCode));
            }
        }

        $this->manager->persist($entity);
        $this->manager->flush();
    }

    /**
     * Generate and evaluate association method for field name
     *
     * @param  misc       $entity
     * @param  string     $associationName
     * @return string
     * @throws \Exception if all generate method name don't exist
     */
    protected function getAssocMethodName($entity, $associationName)
    {
        $reflexion = new \ReflectionClass(get_class($entity));

        $assocMethodName = sprintf('add%s', Inflector::classify($associationName));

        if (!$reflexion->hasMethod($assocMethodName)) {
            $assocMethodName = sprintf('set%s', Inflector::classify($associationName));

            if (!$reflexion->hasMethod($assocMethodName)) {
                throw new \Exception('Can\'t generate method name');
            }
        }

        return $assocMethodName;
    }
}
