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
use Website\Setup;

class Index extends ControllerAbstract
{
    public function index()
    {
        $viewInit = array(
            'title' => 'Startpage',
            'head1' => 'Welcome Guest!',
            'head2' => 'You are on the Startpage',
            'link1' => array('aText' => 'Stay in contact&hellip;', 'aHref' => $this->view->baseUrl() . 'contact'),
            'link2' => array('aText' => 'See the world&hellip;', 'aHref' => $this->view->baseUrl() . '?world=1'),
            'link3' => array('aText' => 'Browse the Albums&hellip;', 'aHref' => $this->view->baseUrl() . '?albums=1')
        );
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

}
