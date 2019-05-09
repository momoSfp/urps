<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Content;
use App\Entity\ParticipateContent;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker   = Factory::create('Fr-fr');

        // make user
        $adminRole = new Role();
        $adminRole->setTitle("ROLE_ADMIN");

        $manager->persist($adminRole);

        $adminUser = new User();
            
        $password = $this->encoder->encodePassword($adminUser, 'admin');

        $adminUser->setFirstname("admin")
             ->setLastname("admin")
             ->setEmail("admin@admin.fr")
             ->setPassword($password)
             ->addUserRole($adminRole);
        
        $manager->persist($adminUser);

        $users = [];

        for($i = 1; $i <= 5; $i++)
        {
            $user = new User();
            
            $password = $this->encoder->encodePassword($user, 'pass_1234');

            $user->setFirstname($faker->firstname)
                 ->setLastname($faker->lastname)
                 ->setEmail($faker->email)
                 ->setPassword($password);
            
            $manager->persist($user);

            $users[] = $user;
        }
        
        // make contents 

        $contens = [];

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
                ->setLink("https:")
                ->setFileName("test");

            for($j = 1; $j <= mt_rand(2, 4); $j++)
            {
                $image = new Image();

                $image->setUrl($faker->imageUrl(1000,400))
                      ->setCaption($faker->sentence())
                      ->setContent($content);

                $manager->persist($image);
            }

            $manager->persist($content);

            $contents[] = $content;
        } 

        // make participate Content

        for($i = 1; $i <= 10; $i++)
        {
            $participateContent = new ParticipateContent;
            $user = $users[mt_rand(0, count($users) -1)];
            $content = $contents[mt_rand(0, count($contents) -1)];

            $participateContent->setUser($user)         
                               ->setContent($content)
                               ->setResult(["score" => 70, "time" => 12345, "attemps" => 3]);

            $manager->persist($participateContent);
        }

        $manager->flush();
    }
}
