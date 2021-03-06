<?php
namespace DluTwBootstrap\View\Helper\Navigation;

use Zend\View\Helper\Navigation\AbstractHelper as AbstractZfNavigationHelper;

/**
 * Abstract Helper
 * @package DluTwBootstrap
 * @copyright David Lukas (c) - http://www.zfdaily.com
 * @license http://www.zfdaily.com/code/license New BSD License
 * @link http://www.zfdaily.com
 * @link https://bitbucket.org/dlu/dlutwbootstrap
 */
abstract class AbstractHelper extends AbstractZfNavigationHelper
{
    const ALIGN_LEFT    = 'left';

    const ALIGN_RIGHT   = 'right';

    /* *********************** METHODS *************************** */

    protected function renderContainer($container,
                                       $renderIcons = true,
                                       $activeIconInverse = true,
                                       array $options = array()) {
        $pages  = $container->getPages();
        $html   = '';
        foreach ($pages as $page) {
            /* @var $page \Zend\Navigation\Page\AbstractPage */
            if($this->hasVisibleChildren($page)) {
                //Dropdown menu
                $html   .= "\n" . $this->renderDropdown($page, $renderIcons, $activeIconInverse, $options);
            } else {
                $html   .= "\n" . $this->renderItem($page, $renderIcons, $activeIconInverse, false, $options);
            }
        }
        $html   = $this->decorateContainer($html, $container, $renderIcons, $activeIconInverse, $options);
        return $html;
    }
    
    protected function hasVisibleChildren(\Zend\Navigation\Page\AbstractPage $page) {
        if(!$page->hasChildren()) {
             return false;
        }

        $page->rewind();
        foreach ($page as $child) {
            if($child->isVisible()) {
                return true;
            }
        }
        return false;
    }

    abstract protected function decorateContainer($content,
                                                  $container,
                                                  $renderIcons = true,
                                                  $activeIconInverse = true,
                                                  array $options = array());

    protected function renderItem(\Zend\Navigation\Page\AbstractPage $page,
                                  $renderIcons = true,
                                  $activeIconInverse = true,
                                  $renderInDropdown = false,
                                  array $options = array()) {
        if(!$this->accept($page)) {
            return '';
        }
        if($page->navHeader) {
            //Nav Header
            if($renderInDropdown) {
                $itemHtml   = $this->renderNavHeaderInDropdown($page, $renderIcons, $activeIconInverse, $options);
                $html       = $this->decorateNavHeaderInDropdown($itemHtml, $page, $renderIcons, $activeIconInverse, $options);
            } else {
                $itemHtml   = $this->renderNavHeader($page, $renderIcons, $activeIconInverse, $options);
                $html       = $this->decorateNavHeader($itemHtml, $page, $renderIcons, $activeIconInverse, $options);
            }
        } elseif($page->divider) {
            //Divider
            if($renderInDropdown) {
                $itemHtml   = $this->renderDividerInDropdown($page, $options);
                $html       = $this->decorateDividerInDropdown($itemHtml, $page, $options);
            } else {
                $itemHtml   = $this->renderDivider($page, $options);
                $html       = $this->decorateDivider($itemHtml, $page, $options);
            }
        } else {
            //Nav link
            if($renderInDropdown) {
                $itemHtml   = $this->renderLinkInDropdown($page, $renderIcons, $activeIconInverse, $options);
                $html       = $this->decorateLinkInDropdown($itemHtml, $page, $renderIcons, $activeIconInverse, $options);
            } else {
                $itemHtml   = $this->renderLink($page, $renderIcons, $activeIconInverse, $options);
                $html       = $this->decorateLink($itemHtml, $page, $renderIcons, $activeIconInverse, $options);
            }
        }
        return $html;
    }

    protected function renderNavHeader(\Zend\Navigation\Page\AbstractPage $item,
                                       $renderIcons = true,
                                       $activeIconInverse = true,
                                       array $options = array()) {
        $icon   = $this->htmlifyIcon($item, $renderIcons, $activeIconInverse);
        $label  = $this->translate($item->getLabel());
        $html   = $icon . $this->getView()->escapeHtml($label);
        return $html;
    }

    protected function renderNavHeaderInDropdown(\Zend\Navigation\Page\AbstractPage $item,
                                                 $renderIcons = true,
                                                 $activeIconInverse = true,
                                                 array $options = array()) {
        $html   = $this->renderNavHeader($item, $renderIcons, $activeIconInverse, $options);
        return $html;
    }

    abstract protected function decorateNavHeader($content,
                                                  \Zend\Navigation\Page\AbstractPage $item,
                                                  $renderIcons = true,
                                                  $activeIconInverse = true,
                                                  array $options = array());

    protected function decorateNavHeaderInDropdown($content,
                                                   \Zend\Navigation\Page\AbstractPage $item,
                                                   $renderIcons = true,
                                                   $activeIconInverse = true,
                                                   array $options = array()) {
        $html   = '<li class="nav-header">' . $content . '</li>';
        return $html;
    }



    protected function renderDivider(\Zend\Navigation\Page\AbstractPage $item,
                                     array $options = array()) {
        return '';
    }

    protected function renderDividerInDropdown(\Zend\Navigation\Page\AbstractPage $item,
                                               array $options = array()) {
        $html   = $this->renderDivider($item, $options);
        return $html;
    }

    abstract protected function decorateDivider($content,
                                                \Zend\Navigation\Page\AbstractPage $item,
                                                array $options = array());

    protected function decorateDividerInDropdown($content,
                                                 \Zend\Navigation\Page\AbstractPage $item,
                                                 array $options = array()) {
        $html   = '<li class="divider">' . $content . '</li>';
        return $html;
    }

    protected function renderLink(\Zend\Navigation\Page\AbstractPage $page,
                                  $renderIcons = true,
                                  $activeIconInverse = true,
                                  array $options = array()) {
        //Assemble html
        $html   = $this->htmlifyA($page, $renderIcons, $activeIconInverse);
        return $html;
    }

    protected function renderLinkInDropdown(\Zend\Navigation\Page\AbstractPage $page,
                                            $renderIcons = true,
                                            $activeIconInverse = true,
                                            array $options = array()) {
        $html   = $this->renderLink($page, $renderIcons, $activeIconInverse, $options);
        return $html;
    }

    abstract protected function decorateLink($content,
                                             \Zend\Navigation\Page\AbstractPage $page,
                                             $renderIcons = true,
                                             $activeIconInverse = true,
                                             array $options = array());

    protected function decorateLinkInDropdown($content,
                                              \Zend\Navigation\Page\AbstractPage $page,
                                              $renderIcons = true,
                                              $activeIconInverse = true,
                                              array $options = array()) {
        //Active
        if($page->isActive(true)) {
            $liClass    = ' class="active"';
        } else {
            $liClass    = '';
        }
        $html   = '<li' . $liClass . '>' . $content . '</li>';
        return $html;
    }

    protected function renderDropdown(\Zend\Navigation\Page\AbstractPage $page,
                                      $renderIcons = true,
                                      $activeIconInverse = true,
                                      array $options = array()) {
        if(!$this->accept($page)) {
            return '';
        }
        
        //Get label and title
        $label      = $this->translate($page->getLabel());
        $title      = $this->translate($page->getTitle());
        $escaper    = $this->view->plugin('escapeHtml');
        //Get attribs
        $class      = $page->getClass();
        $this->addWord('dropdown-toggle', $class);
        $aAttribs   = array(
            'title'         => $title,
            'class'         => $class,
            'data-toggle'   => 'dropdown',
            'href'          => '#',
        );
        if($renderIcons) {
            $iconHtml   = $this->htmlifyIcon($page, $activeIconInverse);
        } else {
            $iconHtml   = '';
        }
        $html   = '<a' . $this->htmlAttribs($aAttribs) . '>'
            . $iconHtml . $escaper($label) . ' <b class="caret"></b></a>';
        $html   .= "\n" . '<ul class="dropdown-menu">';
        $pages  = $page->getPages();
        foreach($pages as $dropdownPage) {
            /* @var $dropdownPage \Zend\Navigation\Page\AbstractPage */
                $html   .= "\n" . $this->renderItem($dropdownPage, $renderIcons, $activeIconInverse, true, $options);
        }
        $html   .= "\n</ul>";
        $html   = $this->decorateDropdown($html, $page, $renderIcons, $activeIconInverse, $options);
        return $html;
    }

    abstract protected function decorateDropdown($content,
                                                 \Zend\Navigation\Page\AbstractPage $page,
                                                 $renderIcons = true,
                                                 $activeIconInverse = true,
                                                 array $options = array());



    /**
     * Returns an HTML string containing an 'a' element for the given page
     * @param \Zend\Navigation\Page\AbstractPage $page
     * @param bool $renderIcons
     * @param bool $activeIconInverse
     * @return string
     */
    public function htmlifyA(\Zend\Navigation\Page\AbstractPage $page, $renderIcons = true, $activeIconInverse = true) {
        // get label and title for translating
        $label      = $this->translate($page->getLabel());
        $title      = $this->translate($page->getTitle());
        $escaper    = $this->view->plugin('escapeHtml');
        //Get attribs for anchor element
        $attribs = array(
            'id'     => $page->getId(),
            'title'  => $title,
            'class'  => $page->getClass(),
            'href'   => $page->getHref(),
            'target' => $page->getTarget()
        );
        if($renderIcons) {
            $iconHtml   = $this->htmlifyIcon($page, $activeIconInverse);
        } else {
            $iconHtml   = '';
        }
        $html       = '<a' . $this->htmlAttribs($attribs) . '>'
                      . $iconHtml . $escaper($label)
                      . '</a>';
        return $html;
    }

    protected function htmlifyIcon(\Zend\Navigation\Page\AbstractPage $item, $activeIconInverse = true) {
        if($item->icon) {
            $iClass     = $item->icon;
            if($activeIconInverse && $item->isActive(true)) {
                $classes    = explode(' ', $iClass);
                $iconWhiteClassKey  = array_search('icon-white', $classes);
                if($iconWhiteClassKey === false) {
                    //icon-white class not found
                    $iClass .= ' icon-white';
                } else {
                    //icon-white class found
                    unset($classes[$iconWhiteClassKey]);
                    $iClass = implode(' ', $classes);
                }
            }
            $icon   = '<i class="' . $iClass . '"></i> ';
        } else {
            $icon   = '';
        }
        return $icon;
    }

    /**
     * View helper entry point:
     * Retrieves helper and optionally sets container to operate on
     * @param  \Zend\Navigation\Navigation $container [optional] container to operate on
     * @return TwbNavbar    fluent interface, returns self
     */
    public function __invoke(\Zend\Navigation\Navigation $container = null) {
        if (null !== $container) {
            $this->setContainer($container);
        }
        return $this;
    }

    protected function translate($text) {
        $t = $this->getTranslator();
        if ($this->isTranslatorEnabled()
            && $t
            && is_string($text)
            && !empty($text)) {
                $text = $t->translate($text);
        }
        return $text;
    }

    /**
     * If missing in the text, adds the space separated word to the text
     * @param string $word
     * @param string $text
     */
    protected function addWord($word, &$text) {
        $text   = trim($text);
        if(!$text) {
            $wordsLower  = array();
            $words       = array();
        } else {
            $wordsLower  = explode(' ', strtolower($text));
            $words       = explode(' ', $text);
        }
        if(!in_array(strtolower($word), $wordsLower)) {
            $words[]     = $word;
            $text   = implode(' ', $words);
        }
    }
}