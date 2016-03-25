<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Domain;

class LoadDomainData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager)
    {
        $domain = new Domain();
        $domain->setName('SSM');
        $domain->setBaseline('Securitate si Sanatate in Munca');
        $domain->setDescription('<ul><li>Obligatiile legale ale firmelor in domeniul SSM, in functie de activitate, numar de angajati</li><li>Legi, HG, Ordine, alte acte normative care reglementeaza activitatea SSM</li><li>Comentarii, interpretari, recomandari specifice activitatii SSM proprii</li><li>Modele dinamice de Evaluari de risc pentru posturile de lucru clasice; puteti sa le actualizati cu datele specifice firmei Dvs.</li><li>Documente dinamice care se personalizeaza utilizand meniuri de actualizare</li><ul><li>Plan de Prevenire si Protectie (PPP)</li><li>Instructiuni proprii</li><li>Tematici de Instruire</li><li>Program de instruire</li></ul><li>Decizii, convocatoare, modele de alte documente care se pot personaliza si edita online</li><li>Obligatii legate de medicina muncii</li><li>Explicatii/exemple/Indrumari despre:</li><ul><li>Echipamente individuale de protectie (EIP)</li><li>Semnalizare SSM</li><li>RSVTI</li><li>PRAM</li><li>Prim ajutor</li><li>CSSM</li></ul><li>Rapoarte care se pot actualiza/vizualiza/edita/arhiva online</li><li>Instruire Introductiv-Generala</li><li>Prezentari si fisiere PowerPoint</li><li>Filme pentru instruiri online</li><li>Teste pentru verificarea cunostintelor</li><li>Modele dinamice cu instructiuni pentru completarea fiselor de instruire individula SSM</li><li>Modele statice pentru vizualizarea anumitor formulare/fise de instruire/raportari/etc</li><li>Link-uri utile spre site-urile autoritatilor care reglementeaza domeniul SSM</li><li>Link-uri utile spre site-urile furnizorilor de servicii/echipamente pentru domeniul SSM</li><li>Orice document se poate imprima in forma personalizata</li></ul><p>Completeaza CHESTIONAR GRATUIT si vei primi un feedback personalizat, cu recomandari pentru firma ta!</p>');
        $domain->setDedicated(FALSE);
        $domain->addSubdomain($this->getReference('subdomain_ssm1'));
        $domain->addSubdomain($this->getReference('subdomain_ssm2'));
        $domain->addSubdomain($this->getReference('subdomain_ssm3'));
        $domain->addSubdomain($this->getReference('subdomain_ssm4'));
        $domain->addSubdomain($this->getReference('subdomain_ssm5'));
        $domain->addSubdomain($this->getReference('subdomain_ssm6'));
        $domain->addSubdomain($this->getReference('subdomain_ssm7'));
        $domain->addSubdomain($this->getReference('subdomain_ssm8'));
        $domain->addSubdomain($this->getReference('subdomain_ssm9'));
        $domain->addSubdomain($this->getReference('subdomain_ssm10'));
        $domain->addSubdomain($this->getReference('subdomain_ssm11'));
        $domain->addSubdomain($this->getReference('subdomain_ssm12'));
        $domain->addSubdomain($this->getReference('subdomain_ssm13'));
        $domain->addSubdomain($this->getReference('subdomain_ssm14'));

        $domain1 = new Domain();
        $domain1->setName('PSI/SU');
        $domain1->setBaseline('Paza contra incendiilor ');
        $domain1->setDescription('<ul><li>Obligatiile legale ale firmelor in domeniul PSI_SU, in functie de activitate, numar de angajati</li><li>Legi, HG, Ordine, alte acte normative care reglementeaza activitatea PSI_SU</li><li>Comentarii, interpretari, recomandari specifice activitatii PSI_SU proprii</li><li>Documente dinamice care se personalizeaza utilizand meniuri de actualizare</li><ul><li>Reglementari</li><li>Mijloace tehnice PSI</li><li>Semnalizare PSI</li><li>Tematici de instruire</li><li>Program de instruire</li></ul><li>PAAR</li><li>Planuri de interventie</li><li>Decizii, convocatoare, modele de alte documente care se pot personaliza si edita online</li><li>Explicatii/exemple/Indrumari despre completarea Planului de evacuare</li><li>Obligatii legate de numirea unui cadru tehnic PSI</li><li>Explicatii/exemple/Indrumari despre completarea Planului de evacuare</li><li>Instruire introductiv generala</li><li>Exemple/indrumari/explicatii pentru completarea Fiselor individuale de instruire PSI</li><li>Rapoarte care se pot actualiza/vizualiza/edita/arhiva online</li><li>Prezentari si fisiere PowerPoint</li><li>Filme pentru instruiri online</li><li>Teste pentru verificarea cunostintelor</li><li>Modele dinamice cu instructiuni pentru completarea anumitor formulare/fise de instruire/raportari/etc</li><li>Modele statice pentru vizualizarea anumitor formulare/fise de instruire/raportari/etc;</li><li>Link-uri utile spre site-urile autoritatilor care reglementeaza domeniul PSI</li><li>Link-uri utile spre site-urile furnizorilor de servicii/echipamente pentru domeniul PSI</li><li>Orice document se poate imprima in forma personalizata</li></ul><p>Completeaza CHESTIONAR GRATUIT si vei primi un feedback personalizat, cu recomandari pentru firma ta!</p>');
        $domain1->setDedicated(FALSE);
        $domain1->addSubdomain($this->getReference('subdomain_psi_su1'));
        $domain1->addSubdomain($this->getReference('subdomain_psi_su2'));
        $domain1->addSubdomain($this->getReference('subdomain_psi_su3'));
        $domain1->addSubdomain($this->getReference('subdomain_psi_su4'));
        $domain1->addSubdomain($this->getReference('subdomain_psi_su5'));
        $domain1->addSubdomain($this->getReference('subdomain_psi_su6'));

        $domain2 = new Domain();
        $domain2->setName('Mediu');
        $domain2->setBaseline('Mediu/Gestiunea deseurilor');
        $domain2->setDescription('<ul><li>Obligatiile legale ale firmelor in domeniul MEDIU, in functie de activitate, numar de angajati</li><li>Legi, HG, Ordine, alte acte normative care reglementeaza activitatea de MEDIU</li><li>Comentarii, interpretari, recomandari specifice activitatii de MEDIU proprii</li><li>Autorizatia de mediu</li><ul><li>Fisa de prezentare</li><li>Bilant de mediu nivel 0</li><li>Procedura de autorizare</li></ul><li>Gestiunea deseurilor</li><ul><li>Evidenta deseuri</li><li>Deseuri periculoase</li><li>Deseuri nepericuloase</li></ul><li>Administratia Fondului de Mediu</li><ul><li>Gestiune ambalaje</li><li>Preluarea responsabilitatii</li><li>Raportari AFM</li></ul><li>Garda de mediu</li><li>Sistemul de management al mediului ISO14001</li></ul><p>Completeaza CHESTIONAR GRATUIT si vei primi un feedback personalizat, cu recomandari pentru firma ta!</p>');
        $domain2->setDedicated(FALSE);
        $domain2->addSubdomain($this->getReference('subdomain_mediu1'));
        $domain2->addSubdomain($this->getReference('subdomain_mediu2'));
        $domain2->addSubdomain($this->getReference('subdomain_mediu3'));
        $domain2->addSubdomain($this->getReference('subdomain_mediu4'));
        $domain2->addSubdomain($this->getReference('subdomain_mediu5'));

        $domain3 = new Domain();
        $domain3->setName('HR');
        $domain3->setBaseline('Resurse Umane');
        $domain3->setDescription('<ul><li>Obligatiile legale ale firmelor in domeniul HR, in functie de activitate, numar de angajati</li><li>Legi, HG, Ordine, alte acte normative care reglementeaza activitatea HR</li><li>Comentarii, interpretari, recomandari specifice activitatii HR proprii</li><li>Exemple de Contracte de munca</li><li>Exemple de Fise de post</li><li>REVISAL</li><li>Raporturi de munca</li><li>Instruire personal</li><li>Motivare personal</li><li>Evaluare personal</li><li>Prezentari si fisiere PowerPoint</li><li>Filme pentru instruiri online</li><li>Modele statice pentru vizualizarea anumitor formulare/fise de instruire/raportari/etc</li><li>Link-uri utile spre site-urile autoritatilor care reglementeaza domeniul HR</li><li>Link-uri utile spre site-urile furnizorilor de servicii/echipamente pentru domeniul HR</li><li>Orice document se poate imprima in forma personalizata</li></ul><p>Completeaza CHESTIONAR GRATUIT si vei primi un feedback personalizat, cu recomandari pentru firma ta!</p>');
        $domain3->setDedicated(FALSE);
        $domain3->addSubdomain($this->getReference('subdomain_hr1'));
        $domain3->addSubdomain($this->getReference('subdomain_hr2'));
        $domain3->addSubdomain($this->getReference('subdomain_hr3'));
        $domain3->addSubdomain($this->getReference('subdomain_hr4'));
        $domain3->addSubdomain($this->getReference('subdomain_hr5'));
        $domain3->addSubdomain($this->getReference('subdomain_hr6'));
        $domain3->addSubdomain($this->getReference('subdomain_hr7'));
        $domain3->addSubdomain($this->getReference('subdomain_hr8'));

        $domain4 = new Domain();
        $domain4->setName('Management');
        $domain4->setBaseline('Sisteme de management');
        $domain4->setDescription('<ul><li>Organigrama</li><li>ROI</li><li>Sisteme de management</li><ul><li>ISO 9001</li><li>ISO 14001</li><li>OHSAS 18001</li></ul><li>Modele de Contracte</li><li>Plan de afaceri</li><li>Metode de finantare</li><li>Marketing</li><li>Publicitate</li></ul><p>Completeaza CHESTIONAR GRATUIT si vei primi un feedback personalizat, cu recomandari pentru firma ta!</p>');
        $domain4->setDedicated(FALSE);
        $domain4->addSubdomain($this->getReference('subdomain_mng1'));
        $domain4->addSubdomain($this->getReference('subdomain_mng2'));
        $domain4->addSubdomain($this->getReference('subdomain_mng3'));
        $domain4->addSubdomain($this->getReference('subdomain_mng4'));
        $domain4->addSubdomain($this->getReference('subdomain_mng5'));
        $domain4->addSubdomain($this->getReference('subdomain_mng6'));
        $domain4->addSubdomain($this->getReference('subdomain_mng7'));
        $domain4->addSubdomain($this->getReference('subdomain_mng8'));

        $domain5 = new Domain();
        $domain5->setName('SSM - E');
        $domain5->setBaseline('');
        $domain5->setDescription('<p>Aceasta sectiune este recomanda persoanelor abilitate sa presteze servicii de prevenire si protectie in domeniul SSM, cum sunt:</p><ul><li>Servicii externe SSM</li><li>Sef serviciu intern SSM</li><li>Lucrator desemnat SSM</li></ul><p>Aici gasiti un instrument util de concepere si editare a evaluarilor de risc si a Planului de prevenire si protectie.</p><p>Este usor de utilizat si va permite alegerea si editarea separata a diferitelor capitole din documente, urmand ca la sfarsit sa se genereze documentul final.</p><p>Puteti utiliza un model preexistent pe care sa-l personalizati conform situatiei concrete a postului pentru care lucrati, sau puteti sa generati un document nou.</p><p>De asemenea, aveti optiunea sa acordati permisiunea altor utilizatori sa foloseasca documentele generate de Dv ca model, caz in care veti beneficia de bonusuri (credite) suplimentare. Bineinteles ca in cazul in care documentul generat de Dv. se transforma in model, cei care il vor folosi nu vor avea acces la informatiile confidentiale din document (nume serviciu extern, nume client, alte informatii specific clientului sau locului de munca initial).</p><p>Completeaza CHESTIONAR GRATUIT si vei primi un feedback personalizat, cu recomandari pentru firma ta!</p>');
        $domain5->setDedicated(TRUE);
        $domain5->addSubdomain($this->getReference('subdomain_ssme1'));
        $domain5->addSubdomain($this->getReference('subdomain_ssme2'));

        $manager->persist($domain);
        $manager->persist($domain1);
        $manager->persist($domain2);
        $manager->persist($domain3);
        $manager->persist($domain4);
        $manager->persist($domain5);

        $manager->flush();

        $this->addReference('domain', $domain);
        $this->addReference('domain1', $domain1);
        $this->addReference('domain2', $domain2);
        $this->addReference('domain3', $domain3);
        $this->addReference('domain4', $domain4);
        $this->addReference('domain5', $domain5);
    }

    public function getOrder()
    {
        return 3;
    }
}