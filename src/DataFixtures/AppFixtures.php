<?php

namespace App\DataFixtures;

use App\Entity\Content;
use App\Entity\ContentCategory;
use App\Entity\Role;
use App\Entity\TimeTable;
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


        $this->loadUsers($manager);
        $this->loadTimeTable($manager);

        $this->loadContent($manager);

        /*
        foreach ($week as $day => $value) {
            $content = new Content;
            $content->setTitle($day);
            $content->setContent($value);
            $content->setContentCategory($category);
            $manager->persist($content);
        }
*/


        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
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

        $staffUser = new User();
        $staffUser->setPseudo('Grandvillars-optique')
            ->setEmail('staff@grandvillars-optique.fr')
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->addUserRole($staffRole);

        $manager->persist($adminUser);
        $manager->persist($staffUser);

    }

    public function loadTimeTable(ObjectManager $manager)
    {
        $timeTable = new TimeTable;
        $timeTable->setMonAm('Fermé');
        $timeTable->setMonPm('14h-19h');
        $timeTable->setTueAm('8h-12h');
        $timeTable->setTuePm('14h-19h');
        $timeTable->setWedAm('8h-12h');
        $timeTable->setWedPm('14h-19h');
        $timeTable->setThuAm('8h-12h');
        $timeTable->setThuPm('14h-19h');
        $timeTable->setFriAm('8h-12h');
        $timeTable->setFriPm('14h-19h');
        $timeTable->setSatAm('8h-12h');
        $timeTable->setSatPm('14h-18h');
        $timeTable->setSunAm('Fermé');
        $timeTable->setSunPm('Fermé');
        $manager->persist($timeTable);
    }

    public function loadContent(ObjectManager $manager)
    {
        $quoteCategory = new ContentCategory;
        $quoteCategory->setName('quoteSection');
        $manager->persist($quoteCategory);

        $quoteSectionContent = new Content;
        $quoteSectionContent->setTitle("Cédric Hinterlang - Opticien");
        $quoteSectionContent->setContent("Bienvenue chez GRANDVILLARS OPTIQUE et AUDITION, depuis près de 20 ans nous sommes a votre disposition pour la réalisation de vos équipements visuels et auditifs , nous vous remercions pour cette confiance et soyez assuré qu avec toute mon équipe nous mettons toutes nos compétences et notre savoir faire a votre service pour que votre satisfaction soit maximum");
        $quoteSectionContent->setContentCategory($quoteCategory);
        $manager->persist($quoteSectionContent);

        $serviceCategory = new ContentCategory;
        $serviceCategory->setName('serviceSection');
        $manager->persist($serviceCategory);

        $serviceSectionContent = new Content;
        $serviceSectionContent->setTitle("Nos services");
        $serviceSectionContent->setContent("Mauris vel tincidunt nisi. Fusce vestibulum quam libero, eget lobortis enim consectetur vitae. Mauris tristique justo leo, eu mattis metus varius ut. Curabitur pretium mauris diam, sit amet placerat diam eleifend nec. Sed id faucibus turpis. Pellentesque hendrerit nulla vitae ligula pellentesque congue. Curabitur arcu est, rhoncus sollicitudin finibus ut, hendrerit id ligula. Nunc tincidunt purus quis diam congue pellentesque.");
        $serviceSectionContent->setContentCategory($serviceCategory);
        $manager->persist($serviceSectionContent);


        $activisuCategory = new ContentCategory;
        $activisuCategory->setName('activisuSection');
        $manager->persist($activisuCategory);

        $activisuSectionContent = new Content;
        $activisuSectionContent->setTitle("Dans notre magasin : Activisu");
        $activisuSectionContent->setContent("Dans notre magasin, bénéficiez de l'Activisu avec la technologie Eyecode. C'est un appareil de prise de mesure automatique avec lequel vous pouvez prendre une photo et une séquence vidéo pour voir la monture de profil et de face sur votre visage.");
        $activisuSectionContent->setContentCategory($activisuCategory);
        $manager->persist($activisuSectionContent);

        $jobItemCategory = new ContentCategory;
        $jobItemCategory->setName('jobItem');
        $manager->persist($jobItemCategory);

        $jobs = ["lunettes de vue", "solaires", "Audition", "Lentilles"];
        foreach($jobs as $job){
        $jobItemContent = new Content;
        $jobItemContent->setTitle("Test");
        $jobItemContent->setContent($job);
        $jobItemContent->setContentCategory($jobItemCategory);
        $manager->persist($jobItemContent);
        }

    }
}
