<?php

namespace App\DataFixtures\ORM;

use App\Service\UserManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUsersData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    const COUNT = 30;

    /** @var  ContainerInterface */
    private $container;

    public function load(ObjectManager $manager)
    {
        /** @var UserManager $userManager */
        $userManager = $this->container->get(UserManager::class);

        for ($i = 1; $i <= self::COUNT; $i++) {
            $user = $userManager->createUser();
            $user->setEmail(sprintf('user%s@mailinator.com', $i));
            $user->setRoles(['ROLE_USER']);
            $user->setPlainPassword('123456');
            $userManager->update($user);
            $manager->persist($user);
            $this->addReference('user' . $i, $user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 100;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}