<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Set the layout and view params
     */
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
}

