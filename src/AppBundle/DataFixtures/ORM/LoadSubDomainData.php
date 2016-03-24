<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\SubDomain;

class LoadSubDomainData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager)
    {
        $subdomain = new SubDomain();
        $subdomain->setName('Legislatie SSM');
        $subdomain->setDescription('Obligatiile legale ale firmelor in domeniul SSM, in functie de activitate, numar de angajati. Legi, HG, Ordine, alte acte normative care reglementeaza activitatea SSM. Comentarii, interpretari, recomandari specifice activitatii SSM proprii Modele dinamice de Evaluari de risc pentru posturile de lucru clasice; puteti sa le actualizati cu datele specifice firmei Dv. Documente dinamice care se personalizeaza utilizand meniuri de actualizare: Plan de Prevenire si Protectie (PPP)  Instructiuni proprii Tematici de Instruire Program de instruire Decizii, convocatoare, modele de alte documente care se pot personaliza si edita online. Obligatii legate de medicina muncii. Explicatii / exemple / Indrumari despre Echipamente individuale de  protectie (EIP) Semnalizare SSM RSVTI PRAM Prim ajutor CSSM Rapoarte care se pot actualiza/vizualiza/edita/arhiva online; Instruire Introductiv-Generala. Prezentari si fisiere Powerpoint; Filme pentru instruiri online; Teste pentru verificarea cunostintelor; Modele dinamice cu instructiuni pentru completarea fiselor de instruire individula SSM; Modele statice pentru vizualizarea anumitor formulare/fise de instruire / raportari / etc; Link-uri utile spre site-urile autoritatilor care reglementeaza domeniul SSM; Link-uri utile spre site-urile furnizorilor de servicii/echipamente pentru domeniul SSM; Orice document se poate imprima in forma personalizata.');

        $subdomain1 = new SubDomain();
        $subdomain1->setName('Evaluare de risc');
        $subdomain1->setDescription('Obligatiile legale ale firmelor in domeniul SSM, in functie de activitate, numar de angajati. Legi, HG, Ordine, alte acte normative care reglementeaza activitatea SSM. Comentarii, interpretari, recomandari specifice activitatii SSM proprii Modele dinamice de Evaluari de risc pentru posturile de lucru clasice; puteti sa le actualizati cu datele specifice firmei Dv. Documente dinamice care se personalizeaza utilizand meniuri de actualizare: Plan de Prevenire si Protectie (PPP) Instructiuni proprii Tematici de Instruire Program de instruire Decizii, convocatoare, modele de alte documente care se pot personaliza si edita online. Obligatii legate de medicina muncii. Explicatii / exemple / Indrumari despre');

        $subdomain2 = new SubDomain();
        $subdomain2->setName('Documentatie SSM');
        $subdomain2->setDescription('Obligatiile legale ale firmelor in domeniul SSM, in functie de activitate, numar de angajati. Legi, HG, Ordine, alte acte normative care reglementeaza activitatea SSM. Comentarii, interpretari, recomandari specifice activitatii SSM proprii Modele dinamice de Evaluari de risc pentru posturile de lucru clasice; puteti sa le actualizati cu datele specifice firmei Dv. Documente dinamice care se personalizeaza utilizand meniuri de actualizare:');

        $subdomain3 = new SubDomain();
        $subdomain3->setName('Responsabil SSM');
        $subdomain3->setDescription('Lorem Ipsum');

        $subdomain4 = new SubDomain();
        $subdomain4->setName('Instruiri');
        $subdomain4->setDescription('Lorem Ipsum');

        $subdomain5 = new SubDomain();
        $subdomain5->setName('Medicina muncii');
        $subdomain5->setDescription('Lorem Ipsum');

        $subdomain6 = new SubDomain();
        $subdomain6->setName('EIP');
        $subdomain6->setDescription('Lorem Ipsum');

        $subdomain7 = new SubDomain();
        $subdomain7->setName('RSVTI');
        $subdomain7->setDescription('Lorem Ipsum');

        $subdomain8 = new SubDomain();
        $subdomain8->setName('PRAM');
        $subdomain8->setDescription('Lorem Ipsum');

        $subdomain9 = new SubDomain();
        $subdomain9->setName('Semnalizare');
        $subdomain9->setDescription('Lorem Ipsum');

        $subdomain10 = new SubDomain();
        $subdomain10->setName('Testare SSM');
        $subdomain10->setDescription('Lorem Ipsum');

        $subdomain11 = new SubDomain();
        $subdomain11->setName('CSSM');
        $subdomain11->setDescription('Lorem Ipsum');

        $subdomain12 = new SubDomain();
        $subdomain12->setName('Prim Ajutor');
        $subdomain12->setDescription('Lorem Ipsum');

        $subdomain13 = new SubDomain();
        $subdomain13->setName('Accidente de munca');
        $subdomain13->setDescription('Lorem Ipsum');

        $subdomain14 = new SubDomain();
        $subdomain14->setName('Legislatie PSI');
        $subdomain14->setDescription('Obligatiile legale ale firmelor in domeniul SSM, in functie de activitate, numar de angajati. Legi, HG, Ordine, alte acte normative care reglementeaza activitatea SSM. Comentarii, interpretari, recomandari specifice activitatii SSM proprii Modele dinamice de Evaluari de risc pentru posturile de lucru clasice; puteti sa le actualizati cu datele specifice firmei Dv. Documente dinamice care se personalizeaza utilizand meniuri de actualizare: Plan de Prevenire si Protectie (PPP); Instructiuni proprii Tematici de Instruire Program de instruire Decizii, convocatoare, modele de alte documente care se pot personaliza si edita online. Obligatii legate de medicina muncii. Explicatii / exemple / Indrumari despre');

        $subdomain15 = new SubDomain();
        $subdomain15->setName('Legislatie SU');
        $subdomain15->setDescription('Obligatiile legale ale firmelor in domeniul SSM, in functie de activitate, numar de angajati. Legi, HG, Ordine, alte acte normative care reglementeaza activitatea SSM. Comentarii, interpretari, recomandari specifice activitatii SSM proprii Modele dinamice de Evaluari de risc pentru posturile de lucru clasice; puteti sa le actualizati cu datele specifice firmei Dv. Documente dinamice care se personalizeaza utilizand meniuri de actualizare: Decizii, convocatoare, modele de alte documente care se pot personaliza si edita online. Obligatii legate de medicina muncii. Explicatii / exemple / Indrumari despre');

        $subdomain16 = new SubDomain();
        $subdomain16->setName('Documentatie PSI');
        $subdomain16->setDescription('Lorem Ipsum');

        $subdomain17 = new SubDomain();
        $subdomain17->setName('Documentatie SU');
        $subdomain17->setDescription('Lorem Ipsum');

        $subdomain18 = new SubDomain();
        $subdomain18->setName('Responsabil PSI');
        $subdomain18->setDescription('Lorem Ipsum');

        $subdomain19 = new SubDomain();
        $subdomain19->setName('Instruiri');
        $subdomain19->setDescription('Lorem Ipsum');

        $subdomain20 = new SubDomain();
        $subdomain20->setName('Legislatie Mediu');
        $subdomain20->setDescription('Obligatiile legale ale firmelor in domeniul SSM, in functie de activitate, numar de angajati. Legi, HG, Ordine, alte acte normative care reglementeaza activitatea SSM. Comentarii, interpretari, recomandari specifice activitatii SSM proprii Modele dinamice de Evaluari de risc pentru posturile de lucru clasice; puteti sa le actualizati cu datele specifice firmei Dv. Documente dinamice care se personalizeaza utilizand meniuri de actualizare: Rapoarte care se pot actualiza/vizualiza/edita/arhiva online; Instruire Introductiv-Generala. Prezentari si fisiere Powerpoint; Filme pentru instruiri online; Teste pentru verificarea cunostintelor; Modele dinamice cu instructiuni pentru completarea fiselor de instruire individula SSM; Modele statice pentru vizualizarea anumitor formulare/fise de instruire / raportari / etc; Link-uri utile spre site-urile autoritatilor care reglementeaza domeniul SSM; Link-uri utile spre site-urile furnizorilor de servicii/echipamente pentru domeniul SSM; Orice document se poate imprima in forma personalizata.');

        $subdomain21 = new SubDomain();
        $subdomain21->setName('Autorizatia de Mediu');
        $subdomain21->setDescription('Obligatiile legale ale firmelor in domeniul SSM, in functie de activitate, numar de angajati. Legi, HG, Ordine, alte acte normative care reglementeaza activitatea SSM. Comentarii, interpretari, recomandari specifice activitatii SSM proprii Modele dinamice de Evaluari de risc pentru posturile de lucru clasice; puteti sa le actualizati cu datele specifice firmei Dv. Documente dinamice care se personalizeaza utilizand meniuri de actualizare:');

        $subdomain22 = new SubDomain();
        $subdomain22->setName('Deseuri');
        $subdomain22->setDescription('Lorem Ipsum');

        $subdomain23 = new SubDomain();
        $subdomain23->setName('Fondul de Mediu');
        $subdomain23->setDescription('Lorem Ipsum');

        $subdomain24 = new SubDomain();
        $subdomain24->setName('ISO 14001');
        $subdomain24->setDescription('Lorem Ipsum');

        $subdomain25 = new SubDomain();
        $subdomain25->setName('Legislatie HR');
        $subdomain25->setDescription('Rapoarte care se pot actualiza/vizualiza/edita/arhiva online; Instruire Introductiv-Generala. Prezentari si fisiere Powerpoint; Filme pentru instruiri online; Teste pentru verificarea cunostintelor; Modele dinamice cu instructiuni pentru completarea fiselor de instruire individula SSM; Modele statice pentru vizualizarea anumitor formulare/fise de instruire / raportari / etc; Link-uri utile spre site-urile autoritatilor care reglementeaza domeniul SSM; Link-uri utile spre site-urile furnizorilor de servicii/echipamente pentru domeniul SSM; Orice document se poate imprima in forma personalizata.');

        $subdomain26 = new SubDomain();
        $subdomain26->setName('Revisal');
        $subdomain26->setDescription('Obligatiile legale ale firmelor in domeniul SSM, in functie de activitate, numar de angajati. Legi, HG, Ordine, alte acte normative care reglementeaza activitatea SSM. Comentarii, interpretari, recomandari specifice activitatii SSM proprii Modele dinamice de Evaluari de risc pentru posturile de lucru clasice; puteti sa le actualizati cu datele specifice firmei Dv. Documente dinamice care se personalizeaza utilizand meniuri de actualizare: Decizii, convocatoare, modele de alte documente care se pot personaliza si edita online. Obligatii legate de medicina muncii. Explicatii / exemple / Indrumari despre');

        $subdomain27 = new SubDomain();
        $subdomain27->setName('Contracte de munca');
        $subdomain27->setDescription('Obligatiile legale ale firmelor in domeniul SSM, in functie de activitate, numar de angajati. Legi, HG, Ordine, alte acte normative care reglementeaza activitatea SSM. Comentarii, interpretari, recomandari specifice activitatii SSM proprii Modele dinamice de Evaluari de risc pentru posturile de lucru clasice; puteti sa le actualizati cu datele specifice firmei Dv. Documente dinamice care se personalizeaza utilizand meniuri de actualizare:');

        $subdomain28 = new SubDomain();
        $subdomain28->setName('Raporturi de munca');
        $subdomain28->setDescription('Lorem Ipsum');

        $subdomain29 = new SubDomain();
        $subdomain29->setName('Fisa de post');
        $subdomain29->setDescription('Lorem Ipsum');

        $subdomain30 = new SubDomain();
        $subdomain30->setName('Instruire personal');
        $subdomain30->setDescription('Lorem Ipsum');

        $subdomain31 = new SubDomain();
        $subdomain31->setName('Motivare personal');
        $subdomain31->setDescription('Lorem Ipsum');

        $subdomain32 = new SubDomain();
        $subdomain32->setName('Evaluare personal');
        $subdomain32->setDescription('Lorem Ipsum');

        $subdomain33 = new SubDomain();
        $subdomain33->setName('Organigrama');
        $subdomain33->setDescription('Obligatiile legale ale firmelor in domeniul SSM, in functie de activitate, numar de angajati. Legi, HG, Ordine, alte acte normative care reglementeaza activitatea SSM. Comentarii, interpretari, recomandari specifice activitatii SSM proprii Modele dinamice de Evaluari de risc pentru posturile de lucru clasice; puteti sa le actualizati cu datele specifice firmei Dv. Documente dinamice care se personalizeaza utilizand meniuri de actualizare:');

        $subdomain34 = new SubDomain();
        $subdomain34->setName('ROI');
        $subdomain34->setDescription('Obligatiile legale ale firmelor in domeniul SSM, in functie de activitate, numar de angajati. Legi, HG, Ordine, alte acte normative care reglementeaza activitatea SSM. Comentarii, interpretari, recomandari specifice activitatii SSM proprii');

        $subdomain35 = new SubDomain();
        $subdomain35->setName('Sisteme de management');
        $subdomain35->setDescription('Obligatiile legale ale firmelor in domeniul SSM, in functie de activitate, numar de angajati. Legi, HG, Ordine, alte acte normative care reglementeaza activitatea SSM. Comentarii, interpretari, recomandari specifice activitatii SSM proprii Modele dinamice de Evaluari de risc pentru posturile de lucru clasice; puteti sa le actualizati cu datele specifice firmei Dv. Documente dinamice care se personalizeaza utilizand meniuri de actualizare:');

        $subdomain36 = new SubDomain();
        $subdomain36->setName('Contracte');
        $subdomain36->setDescription('Lorem Ipsum');

        $subdomain37 = new SubDomain();
        $subdomain37->setName('Plan de afaceri');
        $subdomain37->setDescription('Lorem Ipsum');

        $subdomain38 = new SubDomain();
        $subdomain38->setName('Metode de finantare');
        $subdomain38->setDescription('Lorem Ipsum');

        $subdomain39 = new SubDomain();
        $subdomain39->setName('Marketing');
        $subdomain39->setDescription('Lorem Ipsum');

        $subdomain40 = new SubDomain();
        $subdomain40->setName('Publicitate');
        $subdomain40->setDescription('Lorem Ipsum');

        $subdomain41 = new SubDomain();
        $subdomain41->setName('Evaluari SSM');
        $subdomain41->setDescription('Lorem Ipsum');

        $subdomain42 = new SubDomain();
        $subdomain42->setName('PPP');
        $subdomain42->setDescription('Lorem Ipsum');

        $manager->persist($subdomain);
        $manager->persist($subdomain1);
        $manager->persist($subdomain2);
        $manager->persist($subdomain3);
        $manager->persist($subdomain4);
        $manager->persist($subdomain5);
        $manager->persist($subdomain6);
        $manager->persist($subdomain7);
        $manager->persist($subdomain8);
        $manager->persist($subdomain9);
        $manager->persist($subdomain10);
        $manager->persist($subdomain11);
        $manager->persist($subdomain12);
        $manager->persist($subdomain13);
        $manager->persist($subdomain14);
        $manager->persist($subdomain15);
        $manager->persist($subdomain16);
        $manager->persist($subdomain17);
        $manager->persist($subdomain18);
        $manager->persist($subdomain19);
        $manager->persist($subdomain20);
        $manager->persist($subdomain21);
        $manager->persist($subdomain22);
        $manager->persist($subdomain23);
        $manager->persist($subdomain24);
        $manager->persist($subdomain25);
        $manager->persist($subdomain26);
        $manager->persist($subdomain27);
        $manager->persist($subdomain28);
        $manager->persist($subdomain29);
        $manager->persist($subdomain30);
        $manager->persist($subdomain31);
        $manager->persist($subdomain32);
        $manager->persist($subdomain33);
        $manager->persist($subdomain34);
        $manager->persist($subdomain35);
        $manager->persist($subdomain36);
        $manager->persist($subdomain37);
        $manager->persist($subdomain38);
        $manager->persist($subdomain39);
        $manager->persist($subdomain40);
        $manager->persist($subdomain41);
        $manager->persist($subdomain42);

        $manager->flush();

        $this->addReference('subdomain_ssm1', $subdomain);
        $this->addReference('subdomain_ssm2', $subdomain1);
        $this->addReference('subdomain_ssm3', $subdomain2);
        $this->addReference('subdomain_ssm4', $subdomain3);
        $this->addReference('subdomain_ssm5', $subdomain4);
        $this->addReference('subdomain_ssm6', $subdomain5);
        $this->addReference('subdomain_ssm7', $subdomain6);
        $this->addReference('subdomain_ssm8', $subdomain7);
        $this->addReference('subdomain_ssm9', $subdomain8);
        $this->addReference('subdomain_ssm10', $subdomain9);
        $this->addReference('subdomain_ssm11', $subdomain10);
        $this->addReference('subdomain_ssm12', $subdomain11);
        $this->addReference('subdomain_ssm13', $subdomain12);
        $this->addReference('subdomain_ssm14', $subdomain13);
        $this->addReference('subdomain_psi_su1', $subdomain14);
        $this->addReference('subdomain_psi_su2', $subdomain15);
        $this->addReference('subdomain_psi_su3', $subdomain16);
        $this->addReference('subdomain_psi_su4', $subdomain17);
        $this->addReference('subdomain_psi_su5', $subdomain18);
        $this->addReference('subdomain_psi_su6', $subdomain19);
        $this->addReference('subdomain_mediu1', $subdomain20);
        $this->addReference('subdomain_mediu2', $subdomain21);
        $this->addReference('subdomain_mediu3', $subdomain22);
        $this->addReference('subdomain_mediu4', $subdomain23);
        $this->addReference('subdomain_mediu5', $subdomain24);
        $this->addReference('subdomain_hr1', $subdomain25);
        $this->addReference('subdomain_hr2', $subdomain26);
        $this->addReference('subdomain_hr3', $subdomain27);
        $this->addReference('subdomain_hr4', $subdomain28);
        $this->addReference('subdomain_hr5', $subdomain29);
        $this->addReference('subdomain_hr6', $subdomain30);
        $this->addReference('subdomain_hr7', $subdomain31);
        $this->addReference('subdomain_hr8', $subdomain32);
        $this->addReference('subdomain_mng1', $subdomain33);
        $this->addReference('subdomain_mng2', $subdomain34);
        $this->addReference('subdomain_mng3', $subdomain35);
        $this->addReference('subdomain_mng4', $subdomain36);
        $this->addReference('subdomain_mng5', $subdomain37);
        $this->addReference('subdomain_mng6', $subdomain38);
        $this->addReference('subdomain_mng7', $subdomain39);
        $this->addReference('subdomain_mng8', $subdomain40);
        $this->addReference('subdomain_ssme1', $subdomain41);
        $this->addReference('subdomain_ssme2', $subdomain42);
    }

    public function getOrder()
    {
        return 2;
    }

}