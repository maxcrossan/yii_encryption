<?php

/**
 * Class EncryptionBehaviour
 * @Author Max Crossan
 */
class EncryptionBehaviour extends CActiveRecordBehavior
{
    public $encryptedFields = array();

    private $_security;
    private $_owner;
    private $_preCheck = 'XN%_'; //Used when checking if string is already encrypted

    public function __construct()
    {
        $this->_security = Yii::app()->getSecurityManager();
        $this->_owner = $this->getOwner();
    }

    /**
     * @param CModelEvent $event
     * @throws CException
     */
    public function beforeSave($event) {
        foreach($this->encryptedFields as $encryptedField){
            $value = $this->owner->{$encryptedField};
            //Protect against double encryption
            if ($value && !$this->_isEncrypted($value))
                $this->owner->{$encryptedField} = $this->_encrypt($value);
        }
    }

    /**
     * @param CEvent $event
     * @throws CException
     */
    public function afterSave($event){
        foreach($this->encryptedFields as $encryptedField){
            $value = $this->owner->{$encryptedField};
            if($value)
                $this->owner->{$encryptedField} = $this->_decrypt($value);
        }
    }

    /**
     * @param CEvent $event
     * @throws CException
     */
    public function afterFind($event) {
        foreach($this->encryptedFields as $encryptedField){
            $value = $this->owner->{$encryptedField};
            if ($value && $this->_isEncrypted($value))
                $this->owner->{$encryptedField} = $this->_decrypt($value);
        }
    }

    /**
     * @param $field
     * @return bool
     */
    private function _isEncrypted($field){
        $decrypted = $this->_decrypt($field, true);
        if (substr($decrypted, 0, strlen($this->_preCheck)) === $this->_preCheck)
            return true;
        else return false;
    }

    /**
     * @param $field
     * @param bool $keepPreCheck
     * @return bool|string
     */
    private function _decrypt($field, $keepPreCheck=false){
        if($keepPreCheck)
            return $this->_security->decrypt(base64_decode($field));
        else return substr($this->_security->decrypt(base64_decode($field)), strlen($this->_preCheck));
    }

    /**
     * @param $field
     * @return string
     */
    private function _encrypt($field){
        return base64_encode($this->_security->encrypt($this->_preCheck . $field));
    }

}