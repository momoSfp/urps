<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Game;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker   = Factory::create('Fr-fr');

        for($i = 1; $i <= 10; $i++)
        {
            $game = new Game;

            $title       = $faker->sentence();
            $coverImage  = $faker->imageUrl(1000,400);
            $description = $faker->paragraph(2);
            $content     = $faker->paragraph(5);

            $game->setTitle($title)
                ->setCoverImage($coverImage)
                ->setDescription($description)
                ->setContent($content)
                ->setActive(true)
                ->setPublic(false)
                ->setLink("http://");

            for($j = 1; $j <= mt_rand(2, 4); $j++)
            {
                $image = new Image();

                $image->setUrl($faker->imageUrl(1000,400))
                      ->setCaption($faker->sentence())
                      ->setGame($game);

                $manager->persist($image);
            }

            $manager->persist($game);
        } 

        $manager->flush();
    }
}
