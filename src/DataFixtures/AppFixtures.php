<?php

namespace App\DataFixtures;

use Faker\Factory;
<<<<<<< HEAD
use App\Entity\Game;
use App\Entity\Image;
=======
use App\Entity\Image;
use App\Entity\Content;
>>>>>>> controller
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker   = Factory::create('Fr-fr');

        for($i = 1; $i <= 10; $i++)
        {
<<<<<<< HEAD
            $game = new Game;
=======
            $content = new Content;
>>>>>>> controller

            $title       = $faker->sentence();
            $coverImage  = $faker->imageUrl(1000,400);
            $description = $faker->paragraph(2);
<<<<<<< HEAD
            $content     = $faker->paragraph(5);

            $game->setTitle($title)
                ->setCoverImage($coverImage)
                ->setDescription($description)
                ->setContent($content)
=======
            $contentText = $faker->paragraph(5);
            $datetime    = $faker->dateTimeAD('now', 'Europe/Paris');

            $content->setTitle($title)
                ->setCoverImage($coverImage)
                ->setDescription($description)
                ->setContent($contentText)
>>>>>>> controller
                ->setActive(true)
                ->setPublic(false)
                ->setLink("http://");

            for($j = 1; $j <= mt_rand(2, 4); $j++)
            {
                $image = new Image();

                $image->setUrl($faker->imageUrl(1000,400))
                      ->setCaption($faker->sentence())
<<<<<<< HEAD
                      ->setGame($game);
=======
                      ->setContent($content);
>>>>>>> controller

                $manager->persist($image);
            }

<<<<<<< HEAD
            $manager->persist($game);
=======
            $manager->persist($content);
>>>>>>> controller
        } 

        $manager->flush();
    }
}
