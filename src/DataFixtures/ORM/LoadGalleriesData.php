<?php

namespace App\DataFixtures\ORM;

use App\Entity\Gallery;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadGalleriesData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    const COUNT = 10;

    /** @var  ContainerInterface */
    private $container;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 1; $i <= self::COUNT; $i++) {
            $gallery = new Gallery(Uuid::getFactory()->uuid4());
            $gallery->setName($faker->sentence);

            $description = <<<MD
#{$faker->sentence()}

{$faker->realText()}
MD;

            $gallery->setDescription($description);
            $gallery->setUser($this->getReference('user' . (rand(1, LoadUsersData::COUNT))));
            $this->addReference('gallery' . $i, $gallery);
            $manager->persist($gallery);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 200;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}