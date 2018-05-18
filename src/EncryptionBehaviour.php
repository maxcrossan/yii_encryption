<?php

/**
 * Class EncryptionBehaviour
 * @Author Max Crossan
 */
class EncryptionBehaviour extends CActiveRecordBehavior
{
    private $_owner;
    public $encryptedFields = array();

    public function __construct()
    {
        $this->_owner = $this->getOwner();
    }

    public function beforeSave($event) {
        foreach($this->encryptedFields as $encryptedField){
            //Protect against double encryption
            if ($this->owner->{$encryptedField} && @!Yii::app()->getSecurityManager()->decrypt(base64_decode($this->owner->{$encryptedField})))
                $this->owner->{$encryptedField} = base64_encode(Yii::app()->getSecurityManager()->encrypt($this->owner->{$encryptedField}));
        }
    }

    public function afterSave($event){
        foreach($this->encryptedFields as $encryptedField){
            if($this->owner->{$encryptedField})
                $this->owner->{$encryptedField} = Yii::app()->getSecurityManager()->decrypt(base64_decode($this->owner->{$encryptedField}));
        }
    }

    public function afterFind($event) {
        foreach($this->encryptedFields as $encryptedField){
            if ($this->owner->{$encryptedField} && @Yii::app()->getSecurityManager()->decrypt(base64_decode($this->owner->{$encryptedField})))
                $this->owner->{$encryptedField} = Yii::app()->getSecurityManager()->decrypt(base64_decode($this->owner->{$encryptedField}));
        }
    }
}