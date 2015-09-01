<?php
/**
 * ViewContainer.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * ViewContainer
 * @package SlickFW\Mvc\Model
 */

namespace SlickFW\Mvc\Model;

use SlickFW\Mvc\View;

class ViewContainer extends ModelAbstract
{
    /**
     * @var View
     */
    protected $_view;

    /**
     * {@inheritdoc}
     * @param View $view
     * @param array $data
     */
    public function __construct(View $view, array $data = array())
    {
        $this->_view = $view;
        parent::__construct();
        if (!empty($data)) {
            $this->setData($data);
        }
    }

    /**
     * assign all public members of the storage-object either to a (*default*) placeholder
     * or to a new member of the view
     * @param string $toPlaceholder
     * @return View
     */
    public function assignViewVars($toPlaceholder = 'content')
    {
        $placeholder = $this->_view->getPlaceholder();
        if ($placeholder !== $toPlaceholder) {
            $this->_view->setDefaultPlaceholder($toPlaceholder);
        }
        $this->_view->{$toPlaceholder} = $this->getData();
        return $this->_view;
    }

}
