<?php
/**
 * Formicula - the contact mailer for Zikula
 * -----------------------------------------
 *
 * @copyright  (c) Formicula Development Team
 * @link       https://github.com/landseer/Formicula
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author     Frank Schummertz <frank@zikula.org>
 * @package    Third_Party_Components
 * @subpackage Formicula
 */

class Formicula_Version extends Zikula_AbstractVersion
{
    public function getMetaData()
    {
        $meta = array();
        $meta['version'] = '3.0.0';
        $meta['oldnames']    = array('formicula');
        $meta['description'] = $this->__('Formicula forms module');
        $meta['displayname'] = $this->__('Formicula');
        //! module url should be in lowercase without spaces different to displayname
        $meta['url'] = $this->__('formicula');
        $meta['contact'] = 'Frank Schummertz <frank@zikula.org>';
        $meta['securityschema'] = array('Formicula::' => 'form_id:contact_id:' );
        return $meta;
    }
}