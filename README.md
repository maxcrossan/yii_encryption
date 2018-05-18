Encryption Behaviour for Yii
============================

A simple encryption behaviour for encrypting and decrypting database fields on YIi models 

## Installing the behaviour

Add the following to your composer file and run `composer update`

```json
    "repositories": [
        { "type": "vcs", "url": "https://github.com/maxcrossan/yii_encryption.git" }
    ],
    "require": {
        "maxcrossan/yii_encryption": "dev-master"
    },
```

Configure the Yii securityManger component in main.php

```php
    'components'=>array(
        'securityManager'=>array(
            'cryptAlgorithm' => 'rijndael-128',
            'encryptionKey' => 'your-encryption-key',
        ),
    )
```

## Usage

Simply add the following to your model behaviours along with the database columns you want to encrypt:

```php
    public function behaviors(){
        return array(
            'EncryptionBehaviour'=>array(
                'class'=>'application.vendor.maxcrossan.yii_encryption.src.EncryptionBehaviour',
                //Add the fields you wish to encrypt below
                'encryptedFields'=>array(
                    'addressLine1',
                    'addressLine2',
                    'lastName',
                    'city',
                    'postcode',
                    'emailAddress',
                    'phone',
                )
            )
        );
    }
```