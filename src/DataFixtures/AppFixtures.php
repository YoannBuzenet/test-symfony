<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        
        //Here we handle Users

        $users = [];

        for( $i=1; $i<=10; $i++){
            $user = new User();
            $user->setFirstName($faker->firstName())
                 ->setLastName($faker->lastName())
                 ->setEmail($faker->email())
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                 ->setHash('password');

            $manager->persist($user);
            $users[] = $user;     
        }
        

        //Here we create ads
        for( $i=1; $i<=30; $i++){

            $title = $faker->sentence();

            $ad = new Ad();

            $user = $users[mt_rand(0, (count($users)-1))];

            $ad->setTitle($title)
            ->setCoverImage($faker->imageUrl(1000,350))
            ->setIntroduction($faker->paragraph(2))
            ->setContent('<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>')
            ->setPrice(mt_rand(40,200))
            ->setRooms(mt_rand(1,5))
            ->setAuthor($user);

            for($j=1 ; $j <= mt_rand(2,5) ; $j++){
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                      ->setCaption($faker->sentence())
                      ->setAd($ad);

                $manager->persist($image);      
            }

            $manager->persist($ad);
        }
        $manager->flush();
    }
}
