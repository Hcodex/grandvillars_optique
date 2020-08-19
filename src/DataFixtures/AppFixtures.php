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

        $jobs = array("lunettes de vue" => 'mdi:glasses', "solaires" => 'mdi:sunglasses', "Audition" => 'mdi:ear-hearing', "Lentilles" => 'mdi:eye-circle-outline');

        foreach ($jobs as $jobTitle => $jobIcon) {
            $jobItemContent = new Content;
            $jobItemContent->setTitle($jobIcon);
            $jobItemContent->setContent($jobTitle);
            $jobItemContent->setContentCategory($jobItemCategory);
            $manager->persist($jobItemContent);
        }

        $serviceItemCategory = new ContentCategory;
        $serviceItemCategory->setName('serviceItem');
        $manager->persist($serviceItemCategory);

        $services = array(
            "Verres ophtalmiques" => '<p>Nous disposons d\'une gamme de verre ophtalmiques apportant une réponse aux pathologies simples comme complexes</p><ul><li>unifocaux, double foyers</li><li>progressifs</li><li>traitements des verres : anti buée, anti reflet, photochromique, ...</li></ul>',
            "Prothèses audio" => '<p>Grandvillars Optique dispose d\'un audioprothésiste Diplômé d\'Etat dans son équipé pour réaliser votre équipement auditif :</p><ul><li>Conception</li><li>Réalisation</li><li>Positionnement</li><li>Réglages</li></ul>',
            "Atelier" => '<p>Notre équipe dispose des compétences et outils pour la création ou la réparation de vos lunettes et équipements auditifs</p><ul><li>Réparation</li><li>montage</li><li>Réglage</li></ul>',
            "Dépistages" => '<p>Notre magasin dispose de tout l\'équiment nécessaire pour effectuer les dépistages et évaluer votre besoin en équipement visuel ou auditif</p><div class="row"><div class="col-12 col-md-6"><ul><li>Bilans visuels</li><li>Mesures d\'accuité</li><li>Depistage gratuit</li></ul>'
        );

        foreach ($services as $serviceTitle => $serviceContent) {
            $serviceItemContent = new Content;
            $serviceItemContent->setTitle($serviceTitle);
            $serviceItemContent->setContent($serviceContent);
            $serviceItemContent->setContentCategory($serviceItemCategory);
            $manager->persist($serviceItemContent);
        }

        $shopCategory = new ContentCategory;
        $shopCategory->setName('shopSection');
        $manager->persist($shopCategory);

        $shopSectionContent = new Content;
        $shopSectionContent->setTitle("Les + Grandvillars Optique");
        $shopSectionContent->setContent("Votre opticien Grandvillars Optique vous souhaite la bienvenue. Notre équipe de quatre personnes, dont trois opticiens lunetiers diplômés, vous accueille depuis 2002 au centre du village de Grandvillars, dans le Territoire de Belfort.");
        $shopSectionContent->setContentCategory($shopCategory);
        $manager->persist($shopSectionContent);

        $shopItemCategory = new ContentCategory;
        $shopItemCategory->setName('shopItem');
        $manager->persist($shopItemCategory);

        $shops = array(
            "Satisfaction client" => '<ul><li>Vaste choix de montures</li><li>Offre Qualissime Essilor</li><li>Délais de réalisation très courts</li><li>Montage rapide sur place</li></ul>',
            "Prendre soin de votre bugdet" => '<ul><li>La 2ème paire à 1€</li><li>Paiement en 4x sans frais</li><li>Tiers payant mutuelle</li></ul>',
            "Engagements" => '<ul><li>Garantie anti-casse 2 ans</li><li>Service après vente</li></ul>',
            "Espace enfants" => '<ul><li>Vaste choix de montures adaptées</li><li>Divertissements</li></ul>'
        );

        foreach ($shops as $shopTitle => $shopContent) {
            $shopItemContent = new Content;
            $shopItemContent->setTitle($shopTitle);
            $shopItemContent->setContent($shopContent);
            $shopItemContent->setContentCategory($shopItemCategory);
            $manager->persist($shopItemContent);
        }

        $certificationCategory = new ContentCategory;
        $certificationCategory->setName('certificationSection');
        $manager->persist($certificationCategory);

        $certificationSectionContent = new Content;
        $certificationSectionContent->setTitle("Notre savoir faire est reconnu ");
        $certificationSectionContent->setContent("blabla");
        $certificationSectionContent->setContentCategory($certificationCategory);
        $manager->persist($certificationSectionContent);


        $certificationItemCategory = new ContentCategory;
        $certificationItemCategory->setName('certificationItem');
        $manager->persist($certificationItemCategory);

        $certifications = array(
            "Opticien partenaire mutuelles" => 'emojione-v1:left-check-mark',
            "Partenaire Essilor : opticien engagé" => 'emojione-v1:left-check-mark',
            "Certifié AFNOR qualité service" => 'emojione-v1:left-check-mark',
        );

        foreach ($certifications as $certificationContent => $certificationIcon) {
            $certificationItemContent = new Content;
            $certificationItemContent->setTitle($certificationIcon);
            $certificationItemContent->setContent($certificationContent);
            $certificationItemContent->setContentCategory($certificationItemCategory);
            $manager->persist($certificationItemContent);
        }
    }
}
