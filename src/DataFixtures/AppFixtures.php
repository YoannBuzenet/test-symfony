<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        //Handling  Roles
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        //special admin user
        $adminUser = new User();
        $adminUser->setFirstName('Yoann')
                  ->setLastName('Buzenet')
                  ->setEmail('yoann.buzenet@gmail.com')
                  ->setHash($this->encoder->encodePassword($adminUser,'password'))
                  ->setPicture('https://avatars.io/twitter/yoann_buzenet')
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                  ->addUserRole($adminRole);
        
        $manager->persist($adminUser);
        
        //Here we handle Users

        $users = [];
        $genders = ['male','female'];

        for( $i=1; $i<=10; $i++){
            $user = new User();

            $gender = $faker->randomElement($genders);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            if($gender == 'male'){
                $picture = $picture . 'men/' . $pictureId;
            }
            else if ($gender == 'female'){
                $picture = $picture . 'women/' . $pictureId;
            }

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName())
                 ->setLastName($faker->lastName())
                 ->setEmail($faker->email())
                 ->setIntroduction($faker->sentence())
                 ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                 ->setHash($hash)
                 ->setPicture($picture);

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
