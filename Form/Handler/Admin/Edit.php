<?php

/*
 * This file is part of the Formicula package.
 *
 * Copyright Formicula Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Formicula_Form_Handler_Admin_Edit extends Zikula_Form_AbstractHandler
{
    public $cid;

    function initialize(Zikula_Form_View $view)
    {
        $this->cid = (int)FormUtil::getPassedValue('cid', -1, 'GETPOST');

        $view->caching = false;
        $view->add_core_data();

        if ($this->cid == -1) {
            $mode = 'create';
            $contact = [
                'cid'      => -1,
                'name'     => '',
                'email'    => '',
                'public'   => 1,
                'semail'   => '',
                'sname'    => '',
                'ssubject' => ''
            ];
        } else {
            $mode = 'edit';
            $contact = ModUtil::apiFunc('Formicula', 'admin', 'getContact', ['cid' => $this->cid]);
            if (false === $contact) {
                return LogUtil::registerError($this->__('Unknown Contact'), null, ModUtil::url('Formicula', 'admin', 'main'));
            }
        }

        $view->assign('mode', $mode)
             ->assign('contact', $contact);

        return true;
    }


    function handleCommand(Zikula_Form_View $view, &$args)
    {
        // Security check
        if (!SecurityUtil::checkPermission('Formicula::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError(ModUtil::url('Formicula', 'admin', 'main'));
        }

        if ($args['commandName'] == 'submit') {
            $ok = $view->isValid();

            $data = $view->getValues();
            $data['cid'] = $this->cid;
            $data['public'] = (int)$data['public'];

            // copy cname to name for updating the db
            $data['name'] = $data['cname'];

            // no deletion, further checks needed
            if (empty($data['cname'])) {
                $ifield = & $view->getPluginById('cname');
                $ifield->setError(DataUtil::formatForDisplay($this->__('Error! No contact name.')));
                $ok = false;
            }
            if (empty($data['email'])) {
                $ifield = & $view->getPluginById('email');
                $ifield->setError(DataUtil::formatForDisplay($this->__('Error! No email address supplied.')));
                $ok = false;
            } else {
                // email addresses can be a comma seperated string, split and check seperately.
                $data['email'] = preg_replace('/\s*/m', '', $data['email']); // remove spaces
                $aMail = explode(',', $data['email']);
                for ($i = 0; $i < count($aMail); $i++) {
                    if (!System::varValidate($aMail[$i], 'email')) {
                        $ifield = & $view->getPluginById('email');
                        $ifield->setError(DataUtil::formatForDisplay($this->__f('Error! Incorrect email address [%s] supplied.', $aMail[$i])));
                        $ok = false;
                        break;
                    }
                }
            }
            if (!empty($data['semail']) && !System::varValidate($data['semail'], 'email')) {
                $ifield = & $view->getPluginById('semail');
                $ifield->setError(DataUtil::formatForDisplay($this->__('Error! Incorrect email address supplied.')));
                $ok = false;
            }

            if (!$ok) {
                return false;
            }

            // The API function is called
            if ($data['cid'] == -1) {
                if (false !== ModUtil::apiFunc('Formicula', 'admin', 'createContact', $data)) {
                    // Success
                    LogUtil::registerStatus($this->__('Contact created'));
                } else {
                    LogUtil::registerError($this->__('Error creating contact!'));
                }
            } else {
                if (false !== ModUtil::apiFunc('Formicula', 'admin', 'updateContact', $data)) {
                    // Success
                    LogUtil::registerStatus($this->__('Contact info has been updated'));
                } else {
                    LogUtil::registerError($this->__('Error updating contact!'));
                }
            }
        }

        return System::redirect(ModUtil::url('Formicula', 'contact', 'view'));
    }
}
