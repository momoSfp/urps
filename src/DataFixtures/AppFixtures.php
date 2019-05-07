<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Content;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker   = Factory::create('Fr-fr');

        for($i = 1; $i <= 10; $i++)
        {
            $content = new Content;

            $title       = $faker->sentence();
            $coverImage  = $faker->imageUrl(1000,400);
            $description = $faker->paragraph(2);
            $contentText = $faker->paragraph(5);
            $datetime    = $faker->dateTimeAD('now', 'Europe/Paris');

            $content->setTitle($title)
                ->setCoverImage($coverImage)
                ->setDescription($description)
                ->setContent($contentText)
                ->setActive(true)
                ->setPublic(false)
                ->setLink("http://");

            for($j = 1; $j <= mt_rand(2, 4); $j++)
            {
                $image = new Image();

                $image->setUrl($faker->imageUrl(1000,400))
                      ->setCaption($faker->sentence())
                      ->setContent($content);

                $manager->persist($image);
            }

            $manager->persist($content);
        } 

        $manager->flush();
    }
}
