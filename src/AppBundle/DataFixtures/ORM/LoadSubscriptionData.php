<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Subscription;

class LoadSubscriptionData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager)
    {
        $subscription = new Subscription();
        $subscription->setName('Start');
        $subscription->setIntro('Abonamentul START va permite accesul la unul din domeniile de mai jos.<br/> Va rugam sa bifati domeniul ales');
        $subscription->setDescription('Este recomandat firmelor mici cu pana la 9 angajati, care implica un numar de maxim 2 posturi, in special in domeniul serviciilor sau comertului. Permite rezolvarea integrala si generarea documentelor necesare in domeniul ales.');
        $subscription->setCredit(25);
        $subscription->setPrice(300);
        $subscription->setValability(1);
        $subscription->setDomainAmount(1);
        $subscription->addDomain($this->getReference('domain'))
                ->addDomain($this->getReference('domain1'))
                ->addDomain($this->getReference('domain2'))
                ->addDomain($this->getReference('domain3'))
                ->addDomain($this->getReference('domain4'));

        $subscription1 = new Subscription();
        $subscription1->setName('Office');
        $subscription1->setIntro('Abonamentul OFFICE va permite accesul la 2 din domeniile de mai jos.<br/> Va rugam sa bifati domeniul ales');
        $subscription1->setDescription('Este recomandat firmelor mici cu pana la 9 angajati, care implica un numar mai mare de 3 posturi, in domeniul serviciilor, comertului sau microproductiei. Permite rezolvarea integrala si generarea documentelor necesare in domeniile alese.');
        $subscription1->setCredit(56);
        $subscription1->setPrice(600);
        $subscription1->setValability(1);
        $subscription1->setDomainAmount(2);
        $subscription1->addDomain($this->getReference('domain'))
                ->addDomain($this->getReference('domain1'))
                ->addDomain($this->getReference('domain2'))
                ->addDomain($this->getReference('domain3'))
                ->addDomain($this->getReference('domain4'));

        $subscription2 = new Subscription();
        $subscription2->setName('Enterprise');
        $subscription2->setIntro('Abonamentul ENTERPRISE va permite accesul la 3 din domeniile de mai jos.<br/> Va rugam sa bifati domeniul ales');
        $subscription2->setDescription('Este recomandat firmelor cu 10-49 angajati, care implica un numar mai mare de 3 posturi, in domeniul productiei, serviciilor si comertului. Permite rezolvarea integrala si generarea documentelor necesare in domeniile alese.');
        $subscription2->setCredit(79);
        $subscription2->setPrice(800);
        $subscription2->setValability(1);
        $subscription2->setDomainAmount(3);
        $subscription2->addDomain($this->getReference('domain'))
                ->addDomain($this->getReference('domain1'))
                ->addDomain($this->getReference('domain2'))
                ->addDomain($this->getReference('domain3'))
                ->addDomain($this->getReference('domain4'));

        $subscription3 = new Subscription();
        $subscription3->setName('Business');
        $subscription3->setIntro('Abonamentul BUSINESS va permite accesul la toate cele 5 domenii de mai jos.');
        $subscription3->setDescription('Este recomandat firmelor mici cu pana la 9 angajati, care implica un numar mai mare de 3 posturi, in domeniul serviciilor, comertului sau microproductiei. Permite rezolvarea integrala si generarea documentelor necesare in domeniile alese.');
        $subscription3->setCredit(112);
        $subscription3->setPrice(1000);
        $subscription3->setValability(1);
        $subscription3->setDomainAmount(5);
        $subscription3->addDomain($this->getReference('domain'))
                ->addDomain($this->getReference('domain1'))
                ->addDomain($this->getReference('domain2'))
                ->addDomain($this->getReference('domain3'))
                ->addDomain($this->getReference('domain4'));

        $subscription4 = new Subscription();
        $subscription4->setName('Expert');
        $subscription4->setIntro('<p>Abonamentul EXPERT va permite accesul la domeniul SSM - E</p><p>Este recomandat pentru :</p><ul class="list-check forspacing"><li><i class="fa fa-check"></i> Firme acreditate ca si Serviciu Extern SSM</li><li><i class="fa fa-check"></i> Firme cu peste 50 de angajati care au Serviciu Intern SSM</li><li><i class="fa fa-check"></i> Firme cu pana la 50 de angajati care au Lucrator Desemnat SSM</li></ul><p>Pentru achizitia abonamentului, va rugam sa completati datele persoanei abilitata SSM</p><p>Acest modul va permite sa generati propriile Evaluari de risc si Planul de prevenire si protectie aferent; utilizand template-uri realizate de Dv. sau de alti abonati. In acest mod munca de rutina este preluata de soft, iar Dv. trebuie doar sa personalizati documentele cu elementele specifice Firmei pentru care intocmiti documentatia.</p>');
        $subscription4->setDescription('Permite generarea de Evaluari de Risc pentru orice tip de post, indiferent de domeniul de activitate: productie, constructii, comert, servicii.');
        $subscription4->setCredit(50);
        $subscription4->setPrice(500);
        $subscription4->setValability(1);
        $subscription4->setDomainAmount(1);
        $subscription4->addDomain($this->getReference('domain5'));

        $manager->persist($subscription);
        $manager->persist($subscription1);
        $manager->persist($subscription2);
        $manager->persist($subscription3);
        $manager->persist($subscription4);

        $manager->flush();

    }

    public function getOrder()
    {
        return 4;
    }
}