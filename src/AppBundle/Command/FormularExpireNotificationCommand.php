<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class FormularExpireNotificationCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
          ->setName('notify:form-expire')
          ->setDescription('Send notification to users when forms expire.')
          ->addArgument('slug', InputArgument::OPTIONAL, 'What form/forms do you want to include? (slug/ slug1, slug2 / null = all)'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $formRepository = $container->get('doctrine')->getManager()->getRepository('AppBundle:Formular');
        $slug = $input->getArgument('slug');

        if ($slug) {
            $forms = [];
            foreach (explode(',', $slug) as $formSlug) {
                if ($formRepository->findOneBySlug(trim($formSlug))) {
                    $forms[] = $formRepository->findOneBySlug(trim($formSlug));
                }
            }
            if (empty($forms)) {

                $output->writeln('Argument ' . $slug . ' does not match any slug/slugs or no forms with the ' . $slug . '  Argument are about to expire.');

                return;
            }
        } else {
            $forms = $formRepository->findAll();

            if (empty($forms)) {

                $output->writeln('No forms found.');

                return;
            }
        }

        $stringOut = '';
        foreach ($forms as $form) {
            foreach (explode(',', $form->getNotifyDays()) as $notifyDays) {
                if (is_numeric($notifyDays)) {
                    $days = new \DateInterval('P' . trim($notifyDays) . 'D');
                    $formHelperGetFormTextMethod = 'getFormText' . str_replace("_", "", $form->getSlug());
                    foreach ($form->getFormularCreditsUsage() as $creditsUsage) {
                        $now = new \DateTime('');
                        if ($creditsUsage->getExpireDate()->diff($now->add($days))->days === 0) {
                            $formText = $container->get('app.formular_helper')->$formHelperGetFormTextMethod($creditsUsage->getFormConfig());

                            $container->get('app.mailer')->sendFormExpireNotificationMessage($creditsUsage, $formText);

                            $line = date("d-m-Y H:i:s", time()) . '--' . $container->get('translator')->trans('document-form.report.egd.notify-sent')
                              . $creditsUsage->getUser()->getName() . ', ' . $creditsUsage->getUser()->getEmail()
                              . $container->get('translator')->trans('document-form.report.egd.for-credit-usage-form') . ':'
                              . $creditsUsage->getId() . ' - ' . $creditsUsage->getFormular()->getName()
                              . $container->get('translator')->trans('document-form.report.egd.expires-on')
                              . $creditsUsage->getExpireDate()->format('d/m/Y');
                            $output->writeln($line);
                            $stringOut .= '<p>' . $line . '</p>';
                        }
                    }
                }
            }
        }
        if ($stringOut === '') {

            $output->writeln('No forms are about to expire.');

            return;
        }


        $container->get('app.mailer')->sendFormExpireReportMessage($stringOut);

        return;
    }

}