<?php
/**
 * Index.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * Website\Controller\Index
 *
 * @package   Website\Controller
 * @category  module
 */

namespace Website\Controller;

use SlickFW\Mvc\Controller\ControllerAbstract;
use Website\Model\View\Container;

class Index extends ControllerAbstract
{
    public function index()
    {
        $baseUrl = $this->view->baseUrl();
        $viewInit = [
            'title' => 'Startpage',
            'head1' => 'Welcome Guest!',
            'head2' => 'You are on the Startpage',
            'link1' => ['aText' => 'Stay in contact&hellip;', 'aHref' => $baseUrl . 'contact'],
            'link2' => ['aText' => 'See the world&hellip;', 'aHref' => $baseUrl . '?world=1'],
            'link3' => ['aText' => 'Browse the Albums&hellip;', 'aHref' => $baseUrl . '?albums=1']
        ];
        $this->_model = new Container($this->view, $viewInit);
        if ($this->_request->getQuery()->count() > 0
            && $this->_request->getQuery()->offsetExists('world')
            && $this->_request->getQuery()->offsetGet('world') == 1
        ) {
            $this->_model->getCountries();
        }
        if ($this->_request->getQuery()->count() > 0
            && $this->_request->getQuery()->offsetExists('albums')
            && $this->_request->getQuery()->offsetGet('albums') == 1
        ) {
            $this->_model->getAlbums();
        }
        $this->_model->assignViewVars();
    }

    public function contact()
    {
        $this->view->noLayout();
        if ($this->_request->isAjax()) {
            $this->view->text = 'Please enter your e-mail address to register for our daily newsletter&hellip;';
        }
    }

    public function imprint()
    {
        $viewInit = [
            'title' => 'About Us',
            'head1' => 'Imprint',
            'head2' => 'You are looking at the imprint of the website',
        ];
        $this->_model = new Container($this->view, $viewInit);

        $this->_model->assignViewVars();
    }

    public function nlconfirmation()
    {
        if ($this->_request->isAjax()) {
            if (!empty($post = $this->_request->getPost())) {
                if (isset($post['nl-email'])) {
                    $this->view->success = true;
                    $this->view->responseText = 'Deine Anmeldung zum Newsletter war erfogreich!';
                }
            }
        } else {
            return $this->index();
        }
    }

}
