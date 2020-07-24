<?php

namespace App\DataFixtures;

use App\Entity\Content;
use App\Entity\ContentCategory;
use App\Entity\Role;
use App\Entity\User;
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

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $staffRole = new Role();
        $staffRole->setTitle('ROLE_STAFF');
        $manager->persist($staffRole);


        $adminUser = new User();
        $adminUser->setPseudo('Grandvillars-optique')
            ->setEmail('contact@grandvillars-optique.fr')
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->addUserRole($adminRole);

        $manager->persist($adminUser);

        $category = new ContentCategory;
        $category->setName('HORAIRES');
        $manager->persist($category);

        $week = array(
            'MON_AM' => "FERME",
            'MON_PM' => "14h-19h",
            'THU_AM' => "9h-12h",
            'THU_PM' => "14h-19h",
            'WED_AM' => "9h-12h",
            'WED_PM' => "14h-19h",
            'THU_AM' => "9h-12h",
            'THU_PM' => "14h-19h",
            'FRI_AM' => "9h-12h",
            'FRI_PM' => "14h-19h",
            'SAT_AM' => "9h-12h",
            'SAT_PM' => "14h-19h",
            'SUN_AM' => "FERME",
            'SUN_PM' => "FERME",
        );

        foreach ($week as $day => $value) {
            $content = new Content;
            $content->setTitle($day);
            $content->setContent($value);
            $content->setContentCategory($category);
            $manager->persist($content);
        }



        $manager->flush();
    }
}
