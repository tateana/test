<?php
namespace DluTwBootstrap\View\Helper\Navigation;
use DluTwBootstrap\View\Helper\Navigation\Exception\UnsupportedElementTypeException;

/**
 * TwbNavbar
 * @package DluTwBootstrap
 * @copyright David Lukas (c) - http://www.zfdaily.com
 * @license http://www.zfdaily.com/code/license New BSD License
 * @link http://www.zfdaily.com
 * @link https://bitbucket.org/dlu/dlutwbootstrap
 */
class TwbNavbar extends AbstractNavHelper {

    protected $isRightAlign = false;

    /* *********************** METHODS *************************** */

    /**
     * Renders helper
     * @param  string|\Zend\Navigation\AbstractContainer $container [optional] container to render.
     *                                         Default is null, which indicates
     *                                         that the helper should render
     *                                         the container returned by {@link
     *                                         getContainer()}.
     * @return string helper output
     * @throws \Zend\View\Exception\ExceptionInterface if unable to render
     */
    public function render($container = null) {
        return $this->renderNavbar($container);
    }

    public function renderNavbar($container = null) {
        if (null === $container) {
            $container = $this->getContainer();
        }

        $html = '';

        //Primary container
        $options = array(
            'align' => null,
            'ulClass' => ($this->isRightAlign)? 'nav navbar-nav navbar-right': 'nav navbar-nav'
        );
        $html .= "\n" . $this->renderContainer($container, true, true, $options);

        return $html;
    }

    public function setIsRightAlign($isRightAlign) {
        $this->isRightAlign = $isRightAlign;
        return $this;
    }

}
