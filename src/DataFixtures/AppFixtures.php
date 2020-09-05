<?php

namespace App\DataFixtures;

use App\Entity\Content;
use App\Entity\ContentCategory;
use App\Entity\HealthInsurance;
use App\Entity\MediaCategory;
use App\Entity\Role;
use App\Entity\TimeTable;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder, ContainerInterface $container = null)
    {
        $this->encoder = $encoder;
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {


        $this->loadUsers($manager);
        $this->loadTimeTable($manager);

        $this->loadContent($manager);
        $this->loadHealthInsurances($manager);
        $this->loadMedias($manager);
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
        $quoteCategory->setName('quoteSectionContent');
        $manager->persist($quoteCategory);

        $quoteSectionContent = new Content;
        $quoteSectionContent->setTitle("Cédric Hinterlang - Opticien");
        $quoteSectionContent->setContent("<p>Bienvenue chez GRANDVILLARS OPTIQUE et AUDITION, depuis près de 20 ans nous sommes a votre disposition pour la réalisation de vos équipements visuels et auditifs , nous vous remercions pour cette confiance et soyez assuré qu avec toute mon équipe nous mettons toutes nos compétences et notre savoir faire a votre service pour que votre satisfaction soit maximum</p>");
        $quoteSectionContent->setContentCategory($quoteCategory);
        $manager->persist($quoteSectionContent);

        $serviceCategory = new ContentCategory;
        $serviceCategory->setName('serviceSection');
        $manager->persist($serviceCategory);

        $serviceSectionContent = new Content;
        $serviceSectionContent->setTitle("Nos services");
        $serviceSectionContent->setContent("<p>Mauris vel tincidunt nisi. Fusce vestibulum quam libero, eget lobortis enim consectetur vitae. Mauris tristique justo leo, eu mattis metus varius ut. Curabitur pretium mauris diam, sit amet placerat diam eleifend nec. Sed id faucibus turpis. Pellentesque hendrerit nulla vitae ligula pellentesque congue. Curabitur arcu est, rhoncus sollicitudin finibus ut, hendrerit id ligula. Nunc tincidunt purus quis diam congue pellentesque.</p>");
        $serviceSectionContent->setContentCategory($serviceCategory);
        $manager->persist($serviceSectionContent);


        $activisuCategory = new ContentCategory;
        $activisuCategory->setName('activisuSectionContent');
        $manager->persist($activisuCategory);

        $activisuSectionContent = new Content;
        $activisuSectionContent->setTitle("Dans notre magasin : Activisu");
        $activisuSectionContent->setContent("<p>Dans notre magasin, bénéficiez de l'Activisu avec la technologie Eyecode. C'est un appareil de prise de mesure automatique avec lequel vous pouvez prendre une photo et une séquence vidéo pour voir la monture de profil et de face sur votre visage.</p>");
        $activisuSectionContent->setContentCategory($activisuCategory);
        $manager->persist($activisuSectionContent);

        $jobItemCategory = new ContentCategory;
        $jobItemCategory->setName('jobItem');
        $manager->persist($jobItemCategory);

        $jobs = array("lunettes de vue" => 'mdi:glasses', "solaires" => 'mdi:sunglasses', "Audition" => 'mdi:ear-hearing', "Lentilles" => 'mdi:eye-circle-outline');

        foreach ($jobs as $jobTitle => $jobIcon) {
            $jobItemContent = new Content;
            $jobItemContent->setIcon($jobIcon);
            $jobItemContent->setTitle($jobTitle);
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
        $shopSectionContent->setContent("<p>Votre opticien Grandvillars Optique vous souhaite la bienvenue. Notre équipe de quatre personnes, dont trois opticiens lunetiers diplômés, vous accueille depuis 2002 au centre du village de Grandvillars, dans le Territoire de Belfort.</p>");
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
        $certificationSectionContent->setContent("<p style='text-align:center'><em>Notre magasin est engagé dans une démarche de certification de la qualité de service</em></p>");
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

    public function loadMedias(ObjectManager $manager){

        $categories = $this->container->getParameter('media.lockedCategories');
/*
        $categories = array(
            "marque",
            "mutuelle",
            "autre",
            "site",
            "cover",
            "quote",
            "activisu",
            "carousel",
            "certif1",
            "certif2",
            "time,",
            "time2",
            "time3",
            "time4"
        );
*/

        foreach ($categories as $categorie) {
            $mediaCategorie = new MediaCategory;
            $mediaCategorie->setName($categorie);
            $manager->persist($mediaCategorie);
        }

    }

    public function loadHealthInsurances(ObjectManager $manager)
    {

        $healthInsurances = array(
            'ABELA',
            'ACORIS MUTUELLES',
            'ACTIL',
            'ACTUARIAT CONSEIL ETUDES',
            'ADEP (ASSOCIATION DE PREVOYANCE)',
            'ADINAS',
            'ADREA MUTUELLE  UNILIA',
            'ADREA MUTUELLE ALPES DAUPHINÉ',
            'ADREA MUTUELLE BOURGOGNE',
            'ADREA MUTUELLE CENTRE AUVERGNE',
            'ADREA MUTUELLE FRANCHE COMTE',
            'ADREA MUTUELLE MUTI',
            'ADREA MUTUELLE PAYS DE L\'AIN',
            'ADREA MUTUELLE PAYS DE SAVOIE',
            'ADREA MUTUELLE SERVICE AIA',
            'AG2R LA MONDIALE',
            'AGEO',
            'AGGEMA',
            'AGILIS',
            'AGIP SANTE',
            'AGLAE GESTION',
            'AGMF PRÉVOYANCE',
            'AGRICA',
            'AIAC',
            'ALLIANZ',
            'ALLSECUR',
            'ALMERYS',
            'AMELLIS MUTUELLES',
            'AMIS',
            'AON',
            'APCI GESTION',
            'APEMME',
            'APGIS',
            'APIC',
            'APIVIA MUTUELLE',
            'APO PESENTI',
            'APREVA',
            'APRIA',
            'APRIA RSA',
            'APRIA RSA GESTION AGA',
            'APRIL ENTREPRISE',
            'APRIL ENTREPRISE CARAIBES',
            'APRIL ENTREPRISE SAVOIE',
            'APS PREVOYANCE (CHEVALIER GESTION)',
            'APS PREVOYANCE (KLESIA ACS)',
            'AREA CONSEIL',
            'AREAS ASSURANCES',
            'ARGENSON ET SMFEP',
            'ARPEGE PREVOYANCE',
            'ASCORE GESTION',
            'ASP BTP',
            'ASSURANCES MEDICALES',
            'ASSURASUD',
            'ASSUREMA',
            'ASSUREMA (POP SANTE)',
            'ASSUREO',
            'AUDIENS SANTE (PLEYEL SANTE)',
            'AUXIA',
            'AVENIR MUTUELLE',
            'AVILOG',
            'AVIVA',
            'AVIVA AMIS',
            'AXA',
            'B2V PREVOYANCE',
            'BALOO',
            'BANQUE POPULAIRE',
            'BCAC',
            'BPCE MUTUELLE',
            'BUREAU EUROPEEN DE PREVOYANCE (BEP)',
            'CAISSE D\'EPARGNE',
            'CAISSE DE PREVOYANCE MULHOUSIENNE (CPM)',
            'CAMIEG',
            'CAPAVES PREVOYANCE',
            'CAPREVAL',
            'CARDIF',
            'CARPS',
            'CARREFOUR ASSURANCES',
            'CARRES BLEUS',
            'CCMO CCN DU GOLF',
            'CCMO MUTUELLE',
            'CEGEMA',
            'CEGENORD',
            'CETIM',
            'CGAM',
            'CGRM',
            'CHORALIS MUTUELLE LE LIBRE CHOIX',
            'CIC',
            'CIPRES ASSURANCES',
            'CIPRES VIE',
            'CIPREV/MUTUELLE VICTOR HUGO',
            'CITRAM (MUTAERO)',
            'CLP MUTUELLE SANTE',
            'CMAV',
            'CMIP',
            'CMPI RADIANCE (ALENÇON)',
            'CMU',
            'CNM PREVOYANCE SANTE',
            'CNP',
            'COGEVIE',
            'COLLECTEAM',
            'COVEA RISK',
            'COVIMUT (COMMUNAUX VIENNE MUTUELLE)',
            'CPMS',
            'CPSP',
            'CREDIT MUTUEL',
            'CRIA PREVOYANCE',
            'DE CLARENS',
            'DEXIA INGÉNIERIE SOCIALE',
            'DYNALIS',
            'E.G.C.A FNAP AMAF',
            'ECA ASSURANCES',
            'EMOA MUTUELLE DU VAR',
            'ENERGIE MUTUELLE',
            'ENTIS MUTUELLES',
            'EOVI LA MIF CONTRATS UCANSS',
            'EOVI MUTUELLE',
            'EPAI',
            'FEDERATION FRANCAISE DES ASSURES',
            'FFA',
            'FILHET ALLARD',
            'FMA (POP SANTE)',
            'FMP',
            'FORCE SUD (EX MAT ALBI)',
            'FRANCE MUTUELLE',
            'FRATERNELLE DES TERRITORIAUX',
            'G2S',
            'GAN',
            'GEISTEL',
            'GENASSUR',
            'GENERALI',
            'GENERALI CARAIBES',
            'GENERATION',
            'GEREP',
            'GESTASSUR',
            'GESTINEO',
            'GESTION FORMATION PREVOYANCE',
            'GIE GROUPE NATION (MIP)',
            'GIEPS AGIPI',
            'GMC GESTION',
            'GMF',
            'GMI',
            'GPS (GESTION PRESTATION SERVICE)',
            'GRAS SAVOYE',
            'GRM',
            'GROUPAMA',
            'GROUPE ACCUEIL MEDICO CHIRURGICAL (FMP)',
            'GROUPE DIOT',
            'GROUPE MUTAERO',
            'GROUPE VICTOR HUGO',
            'GSP',
            'HARMONIE MUTUELLES',
            'HELIUM',
            'HEMOS SANTE',
            'HENNER GMC',
            'HOSP MAL CHIRURGIE TM (MA PETITE COMPLÉMENTAIRE)',
            'HUMANIS',
            'HUMANIS PREVOYANCE',
            'IBAMEO',
            'IDENTITES MUTUELLE',
            'IGA GESTION',
            'INPR',
            'INTERIALE MUTUELLE',
            'IPECA',
            'IPSEC',
            'ISANTE',
            'KEOLIS',
            'KLESIA',
            'KLESIA MUT\'',
            'KORELIO',
            'L\'ASSUREUR FRANCAIS',
            'LA BANQUE POSTALE ASSURANCES SANTE',
            'LA FRONTALIERE',
            'LA MONDIALE',
            'LA MUTUELLE CATALANE',
            'LA MUTUELLE DES SENIORS .COM',
            'LA MUTUELLE EN LIGNE.COM',
            'LA MUTUELLE GENERALE',
            'LA MUTUELLE OPTIQUE.COM',
            'LA PREVOYANCE',
            'LA PROTECTION MUTUELLE ET FAMILIALE (PMF)',
            'LA SAUVEGARDE',
            'LE RALLIEMENT (MUTUELLE DES COMPAGNONS DU DEVOIR)',
            'LE REFUGE MUTUALISTE AVEYRONNAIS',
            'LES MUTUELLES LIGERIENNES',
            'LES PREVOYANTS DE LA CCIMP',
            'LINEA',
            'LINEA (NEOLIANE GESTION)',
            'LMDE',
            'LMP (LES MENAGES PREVOYANT)',
            'LSN SFP',
            'M SANTE MUTUELLE FAMILIALE',
            'MA NOUVELLE MUTUELLE',
            'MAAF',
            'MACD',
            'MACIF MUTUALITE',
            'MAE MFP SERVICES',
            'MAGE',
            'MALAKOFF MEDERIC',
            'MALMASSON',
            'MAPA',
            'MASF',
            'MBA MUTUELLE',
            'MBA RADIANCE',
            'MCA (MUTUELLE COMPLÉMENTAIRE D\'ALSACE)',
            'MCCI',
            'MCDEF',
            'MCEN',
            'MCI MUTUELLE',
            'MCRN',
            'MEPSS',
            'MERCER',
            'MESE SCHNEIDER ELECTRIC',
            'MEUSREC',
            'MFA (MUTUELLE FAMILIALE D\'AQUITAINE MUTAMI)',
            'MFBCO (MUTUELLE DE FRANCE BRETAGNE CENTRE OCEANS)',
            'MFIF',
            'MFTSV',
            'MG CORSE',
            'MGAS',
            'MGC (MUTUELLE GENERALE DES CHEMINOTS)',
            'MGEC',
            'MGEFI',
            'MGEL',
            'MGEN',
            'MGEN EX MOCEN',
            'MGET MFP SERVICES',
            'MGP',
            'MGS',
            'MGTS (MUTAMI)',
            'MHV',
            'MICOM PREICOM',
            'MIEL MUTUELLE',
            'MIF PA (MUTAMI)',
            'MIPC',
            'MIPCF (MUTUELLE INTERENTREPRISES POLIET ET CIMENT FRANCAIS)',
            'MIRPOSS',
            'MLB MUTUELLE',
            'MMA',
            'MMC',
            'MNFCT',
            'MNH',
            'MNPAF (MUTUELLE NATIONALE DU PERSONNEL AIR FRANCE)',
            'MNSPF (MUTUELLE DES SAPEURS POMPIERS DE FRANCE)',
            'MNT',
            'MOCEN',
            'MPCL (MUTUELLE DES FONCTIONNAIRES TERRITORIAUX)',
            'MPOSS (MUTUELLE DE LA SÉCURITÉ SOCIALE MIDI PYRÉNÉES)',
            'MPPM',
            'MPST (MUTUELLE DES PERSONNELS DE SANTÉ TERRITORIAUX MUTAMI)',
            'MRPOSS',
            'MSAE',
            'MSI MICILS',
            'MTH',
            'MTRG',
            'MTRL',
            'MUFTI',
            'MUT EST',
            'MUT SANTE',
            'MUT SEINE',
            'MUTA SANTE',
            'MUTAME PROVENCE (MUTUELLE DES MUNICIPAUX)',
            'MUTAME SAVOIE MONT BLANC',
            'MUTAME TERRITOIRE DE BELFORT',
            'MUTAME VAL DE FRANCE',
            'MUTAMI',
            'MUTCAF',
            'MUTCOOP',
            'MUTI',
            'MUTLOR',
            'MUTOSIC',
            'MUTRALYON',
            'MUTUALIA',
            'MUTUALIA ALSACE GRAND EST',
            'MUTUALIA GRAND OUEST',
            'MUTUALIA NORD DE FRANCE',
            'MUTUALIA SANTE ATLANTIQUE',
            'MUTUALIA SANTE PREVOYANCE (MAS)',
            'MUTUALIA SANTE SUD EST',
            'MUTUALIA SUD OUEST',
            'MUTUELLE 04 05',
            'MUTUELLE 403',
            'MUTUELLE 525',
            'MUTUELLE 93',
            'MUTUELLE AERONAUTIQUE BOUGUENAIS (MAB)',
            'MUTUELLE ALSTOM RATEAU',
            'MUTUELLE AUDIENS PRESSE SPECTACLE COMMUNICATION',
            'MUTUELLE AVENIR SANTE',
            'MUTUELLE AVENIR SANTÉ',
            'MUTUELLE BEAUJOLAISE',
            'MUTUELLE CAP SANTE',
            'MUTUELLE CARCEPT PREV FMP',
            'MUTUELLE CHEMINOTS PICARDS',
            'MUTUELLE CHIRURICO DENTAIRE (MCD)',
            'MUTUELLE CPAMIF',
            'MUTUELLE D\'ENTREPRISE 341 (MUTUELLE UGINE)',
            'MUTUELLE DE BAGNEAUX',
            'MUTUELLE DE FRANCE (FMP)',
            'MUTUELLE DE FRANCE AUBEANE',
            'MUTUELLE DE FRANCE DES HOSPITALIERS',
            'MUTUELLE DE FRANCE PLUS',
            'MUTUELLE DE FRANCE ROANNE',
            'MUTUELLE DE L\'HOTELLERIE ET DE LA RESTAURATION',
            'MUTUELLE DE LA BOULANGERIE',
            'MUTUELLE DE LA CNAV',
            'MUTUELLE DE LA MEDITERRANEE',
            'MUTUELLE DE LA POLICE NATIONALE (MPN)',
            'MUTUELLE DE LA SOMME',
            'MUTUELLE DE NATIXIS',
            'MUTUELLE DE POITIERS ASSURANCE',
            'MUTUELLE DE PONTOISE',
            'MUTUELLE DE PREVOYANCE DES SALARIES',
            'MUTUELLE DES ASSURES SOCIAUX',
            'MUTUELLE DES CHEMINOTS DE CHAMPAGNE ARDENNE',
            'MUTUELLE DES CHEMINOTS DE LA REGION DE NANTES',
            'MUTUELLE DES CHEMINOTS DE NORMANDIE (MCN)',
            'MUTUELLE DES CHEMINOTS DU NORD (MCN)',
            'MUTUELLE DES HÔPITAUX DE LA VIENNE (MHV)',
            'MUTUELLE DES MENAGES PREVOYANTS',
            'MUTUELLE DES PAYS DE VAUCLUSE',
            'MUTUELLE DES PAYS DE VILAINE',
            'MUTUELLE DES SAPEURS POMPIERS DE PARIS (MSPP)',
            'MUTUELLE DES SERVICES PUBLICS',
            'MUTUELLE DES TABACS ET ALLUMETTES',
            'MUTUELLE DES TRAMWAYS DE NANCY',
            'MUTUELLE DES TRANSPORTS',
            'MUTUELLE DES USINES',
            'MUTUELLE DIJONNAISE',
            'MUTUELLE DU CHAMPAGNE',
            'MUTUELLE DU CHU ET HÔPITAUX DU PUY-DE-DÔME',
            'MUTUELLE DU GRAND PORT MARITIME DU HAVRE (FMP GROUPE 2528)',
            'MUTUELLE DU GROUPE BNP- PARIBAS',
            'MUTUELLE DU MEDECIN/MUTUELLE DU PROFESSIONEL DE SANTE',
            'MUTUELLE DU PERSONNEL BRED (FMP)',
            'MUTUELLE DU PERSONNEL DE LA BANQUE POPULAIRE DU SUD (MPBPS)',
            'MUTUELLE DU PERSONNEL DE LA CNAV',
            'MUTUELLE DU PERSONNEL DES COLLECTIVITES TERRITORIALES (MPCT)',
            'MUTUELLE DU PERSONNEL DU GROUPE MATRA HACHETTE',
            'MUTUELLE DU PERSONNEL ETERNIT',
            'MUTUELLE DU PERSONNEL HSBC FRANCE (FMP)',
            'MUTUELLE DU PERSONNEL MUTUALITE FRANCILIENNE (FMP)',
            'MUTUELLE DU PERSONNEL NAVIGUANT',
            'MUTUELLE DU REMPART',
            'MUTUELLE DU VAL DE SEVRE',
            'MUTUELLE ENTIS',
            'MUTUELLE ENTRAIDE LEA',
            'MUTUELLE ENTRAIDE PHOTO CINE',
            'MUTUELLE ENTRAIN',
            'MUTUELLE EPC (FMP)',
            'MUTUELLE EUROPE',
            'MUTUELLE FAMILIALE',
            'MUTUELLE FAMILIALE CENTRE AUVERGNE',
            'MUTUELLE FAMILIALE DE FRANCE',
            'MUTUELLE FAMILIALE DE HAUTE SAVOIE',
            'MUTUELLE FAMILIALE DE LA CORSE',
            'MUTUELLE FAMILIALE DE NORMANDIE',
            'MUTUELLE FAMILIALE DES ALPES (MFA)',
            'MUTUELLE FAMILIALE DES CHEMINOTS DE FRANCE (MFCF)',
            'MUTUELLE FAMILIALE DU LOIRET',
            'MUTUELLE FAMILIALE ET INTER ENTREPRISES',
            'MUTUELLE FAMILIALE TRAVAILLEURS DU GROUPE SAFRAN',
            'MUTUELLE FRANCE MARITIME',
            'MUTUELLE GENERALE DE L\'OISE',
            'MUTUELLE GENERALE DE LA DISTRIBUTION (MGD)',
            'MUTUELLE GENERALE DE LA SANTE',
            'MUTUELLE GENERALE LOIRE SUD (MGLS)',
            'MUTUELLE GENERALE SOGERMA (MUTAERO)',
            'MUTUELLE GEODIS',
            'MUTUELLE HUMANIS NATIONALE',
            'MUTUELLE INTERENTREPRISES DES CAVES',
            'MUTUELLE INTERGROUPE D\'ENTRAIDE (MIE)',
            'MUTUELLE INTERGROUPE D’ENTRAIDE',
            'MUTUELLE INTERNET',
            'MUTUELLE INTERNET.COM',
            'MUTUELLE JURASSIENNE',
            'MUTUELLE JUST',
            'MUTUELLE LA CHOLETAISE',
            'MUTUELLE LA FRANCE MARITIME',
            'MUTUELLE LA FRATERNELLE',
            'MUTUELLE LA PRUDENTE',
            'MUTUELLE MCLR',
            'MUTUELLE MGPA',
            'MUTUELLE MIASC',
            'MUTUELLE MIEUX ETRE (MUTUA GESTION)',
            'MUTUELLE MOAT',
            'MUTUELLE MONSOLS',
            'MUTUELLE MONTPERRIN',
            'MUTUELLE MOS',
            'MUTUELLE MSA',
            'MUTUELLE MVTE',
            'MUTUELLE NATIONALE DES METIERS',
            'MUTUELLE NATIONALE DU PERSONNEL DES ETABLISSEMENTS MICHELIN',
            'MUTUELLE NOVAMUT',
            'MUTUELLE OPALE',
            'MUTUELLE PREMIRIS',
            'MUTUELLE PREVIFRANCE',
            'MUTUELLE PREVOYANCE INTERPROFESSIONNELLE (MPI)',
            'MUTUELLE PREVOYANCE SANTE',
            'MUTUELLE PROVENCALE D\'AUBAGNE',
            'MUTUELLE PROVENCALE DE CASSIS ROQUEFORT LA BEDOULE',
            'MUTUELLE RENAULT',
            'MUTUELLE RHODIA BELLE ETOILE',
            'MUTUELLE SAINT GERMAIN (FMP)',
            'MUTUELLE SANTE',
            'MUTUELLE SANTE 08',
            'MUTUELLE SANTE EIFFAGE ENERGIE',
            'MUTUELLE SECURITE ASTURIENNE',
            'MUTUELLE SMEMA',
            'MUTUELLE SMPS',
            'MUTUELLE SMT',
            'MUTUELLE SOCIETE MARSEILLAISE DE CREDIT',
            'MUTUELLE SUD LYONNAIS',
            'MUTUELLE UDT',
            'MUTUELLE UMC AGENCE LYON',
            'MUTUELLE UNION DES TRAVAILLEURS',
            'MUTUELLE UNION DU COMMERCE ET DES SCOP',
            'MUTUELLE VALEO',
            'MUTUELLE VERTE',
            'MUTUELLES DU PAYS HAUT',
            'MUTUELLES DU SOLEIL',
            'MUTUELLES NICOLAS',
            'MVS MUTUELLE',
            'MYRIADE',
            'NEOLIANE EPSIL EQUITE',
            'NOVALIS PREVOYANCE',
            'OCIANE',
            'OMNILAND',
            'ONP (CABINET FABIAN)',
            'OSMOSE SYNEA',
            'OWLIANCE',
            'PACIFICA',
            'PACIFICA MSA',
            'PARTEO',
            'PAVILLON PREVOYANCE',
            'PERIGORD MUTUALITE',
            'PLANSANTE',
            'PRECOCIA',
            'PREDICA',
            'PREVADIES',
            'PREVOYANCE MUTUALISTE D\'ILE DE FRANCE',
            'PRO BTP',
            'RADIANCE ANGOULEME (UMTNS RADIANCE)',
            'RADIANCE BOURGOGNE',
            'RADIANCE GROUPE HUMANIS GRAND EST',
            'RADIANCE HUMANIS',
            'RADIANCE NORD PAS DE CALAIS',
            'RADIANCE PICARDIE',
            'RADIANCE RHÔNE ALPES',
            'RADIANCE SUD',
            'RAM GAMEX',
            'RCBF',
            'REPAM SANTE',
            'REUNICA',
            'RITCHAARD SANTE',
            'ROEDERER',
            'ROEDERER-SIMAX',
            'SACDROP ASSURANCES',
            'SAFIAG',
            'SAMBO',
            'SANTE GESTION PLUS',
            'SFP (SOCIÉTÉ FRANÇAISE DE PRÉVENTION)',
            'SG SANTE',
            'SIMAX',
            'SIMIRP',
            'SLPA',
            'SMAPRI (UNIASSUR UNIGEST)',
            'SMATIS',
            'SMEBA',
            'SMENO',
            'SMENO PRO',
            'SMEREP',
            'SMH',
            'SMI',
            'SMIE DES ORGANISMES SOCIAUX',
            'SOGAREP',
            'SOGECAP',
            'SOLI CAISSE',
            'SOLIDARITE MUTUALISTE',
            'SOLIMUT',
            'SOLIMUT MUTUELLE DE FRANCE',
            'SOLLY AZAR',
            'SOMUPOSS',
            'SORUAL',
            'SP Santé',
            'SPVIE',
            'SQUADRA GESTION',
            'STAM EC',
            'SUD OUEST MUTUALITE',
            'SURAVENIR ASSURANCES',
            'SWISS LIFE',
            'TAITBOUT',
            'TELERGOS',
            'TERRISANTE',
            'THELEM ASSURANCES',
            'TRANQUILLITE SANTE',
            'UCR',
            'UFR (UNITE FRATERNELLE DES REGIONS)',
            'UGIPS GESTION',
            'UGM HUMANIS',
            'UNEO',
            'UNI SANTE PREVOYANCE',
            'UNIASSUR UNIGEST',
            'UNIM',
            'UNIMIE',
            'UNIMUTUELLES',
            'USP',
            'VERSPIEREN',
            'VIAMEDIS',
            'VIASANTE',
            'VIASANTE (CARCASSONNE)',
            'VIGIE',
            'VITTAVI',
            'VIVENS',
            'VIVINTER',
            'VOTRE MUTUELLE MOINS CHERE.COM',
            'VOTRE MUTUELLE PAS CHERE.COM'
        );

        foreach ($healthInsurances as $healthInsuranceName) {
            $healthInsurance = new HealthInsurance;
            $healthInsurance->setName($healthInsuranceName);
            $healthInsurance->setStatus(1);
            $manager->persist($healthInsurance);
        }
    }
}
