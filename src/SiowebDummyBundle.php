<?php

namespace Sioweb\DummyBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Sioweb\DummyBundle\DependencyInjection\Extension;

/**
 * @author Sascha Weidner <http://www.sioweb.de>
 */
class SiowebDummyBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new Extension();
    }

    public function exportRegistration()
    {
        die('ddd');
    }

    public function makeStorno(\Contao\DC_Table $dc)
    {
        $timeid = \Input::get('time_id');
        $registrationid = \Input::get('registration_id');

        $time = \Contao\System::getContainer()->get('sioweb_dummybundle.service.search')->addTimeCount($timeid, $registrationid);
        \Contao\Message::addInfo('<span style="padding: 20px 5px; display: inline-block;">Die Registration wurde storniert!</span>');
        \Contao\Controller::redirect('contao/main.php?do=NuvisanManageRegistration');
    }
}