<?php

namespace BlogBundle\DataFixtures\ORM;

use BlogBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class UserFixtures extends AbstractFixture implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $passwordEncoder = $this->container->get('security.password_encoder');

        $janeAdmin = new User();
        $janeAdmin->setFullName('Jane Doe');
        $janeAdmin->setUsername('jane_admin');
        $janeAdmin->setEmail('jane_admin@symfony.com');
        $janeAdmin->setRoles(['ROLE_ADMIN']);
        $encodedPassword = $passwordEncoder->encodePassword($janeAdmin, 'kitten');
        $janeAdmin->setPassword($encodedPassword);
        $manager->persist($janeAdmin);
        // In case if fixture objects have relations to other fixtures, adds a reference
        // to that object by name and later reference it to form a relation.
        // See https://symfony.com/doc/current/bundles/DoctrineFixturesBundle/index.html#sharing-objects-between-fixtures
        $this->addReference('jane-admin', $janeAdmin);

        $johnUser = new User();
        $johnUser->setFullName('John Doe');
        $johnUser->setUsername('john_user');
        $johnUser->setEmail('john_user@symfony.com');
        $encodedPassword = $passwordEncoder->encodePassword($johnUser, 'kitten');
        $johnUser->setPassword($encodedPassword);
        $manager->persist($johnUser);
        $this->addReference('john-user', $johnUser);

        $manager->flush();
    }
}
