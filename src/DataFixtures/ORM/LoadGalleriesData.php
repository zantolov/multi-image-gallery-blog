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

class LoadGalleriesData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{
    const COUNT = 20;

    /** @var  ContainerInterface */
    private $container;

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 1; $i <= self::COUNT; $i++) {
            $gallery = new Gallery(Uuid::getFactory()->uuid4());
            $gallery->setName($faker->sentence);

            $description = <<<MD
# {$faker->sentence()}

{$faker->realText()}
MD;

            $gallery->setDescription($description);
            $gallery->setUser($this->getReference('user' . (rand(1, LoadUsersData::COUNT))));
            $this->addReference('gallery' . $i, $gallery);

            for ($j = 1; $j <= rand(5, 10); $j++) {
                $filename = $faker->word . '.jpg';
                $image = $this->generateRandomImage($filename);

                $description = <<<MD
# {$faker->sentence()}

{$faker->realText()}
MD;

                $image->setDescription($description);
                $gallery->addImage($image);
                $manager->persist($image);
            }

            $manager->persist($gallery);
        }

        $manager->flush();
    }


    private function generateRandomImage($imageName)
    {
        $faker = Factory::create();

        $targetDirectory = $this->container->getParameter('kernel.project_dir') . '/var/uploads';
        $imageFilename = $faker->image($targetDirectory, 800, 600);
        $imageFilename = str_replace($targetDirectory . '/', '', $imageFilename);

        $image = new Image(
            Uuid::getFactory()->uuid4(),
            $imageName,
            $imageFilename
        );

        return $image;
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