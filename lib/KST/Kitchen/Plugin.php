<?php
/**
 * Class for managing plugin through KST
 *
 * @package     KitchenSinkHTML5Base
 * @subpackage  Core
 * @version     0.1
 * @since       0.1
 * @author      zoe somebody
 * @link        http://beingzoe.com/
 * @author      Scragz
 * @link        http://scragz.com/
 * @license		http://en.wikipedia.org/wiki/MIT_License The MIT License
*/


if (!class_exists('KST_Kitchen_Plugin')) {

/**
 *
 *
 *
*/
class KST_Kitchen_Plugin extends KST_Kitchen {

    /**
     * @since       0.1
     * @access      public
     * @uses        KST
     * @uses        KST::setIsCoreOnly()
    */
    public function __construct($settings, $preset=null) {
        $this->_type_of_kitchen = 'plugin';
        KST::setIsCoreOnly(FALSE); // Tell the core it is not alone
        parent::__construct($settings, $preset);
    }

}

}
