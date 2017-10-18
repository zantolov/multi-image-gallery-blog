<?php

namespace App\DataFixtures\ORM;

use App\Entity\Gallery;
use App\Entity\Image;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadImagesData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    const COUNT = 20;

    /** @var  ContainerInterface */
    private $container;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 1; $i <= self::COUNT; $i++) {
            $filename = $faker->word . '.jpg';
            $filepath = 'image' . $i . '.jpg';

            $image = new Image(Uuid::getFactory()->uuid4(), $filepath, $filename);

            $description = <<<MD
#{$faker->sentence()}

{$faker->realText()}
MD;

            $image->setDescription($description);

            if (rand() % 3 === 0) {
                /** @var Gallery $gallery */
                $gallery = $this->getReference('gallery' . (rand(1, LoadGalleriesData::COUNT)));
                $gallery->addImage($image);
            }

            $this->addReference('image' . $i, $image);
            $manager->persist($image);
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