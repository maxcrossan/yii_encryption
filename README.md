Encryption Behaviour for Yii
============================

A simple encryption behaviour for encrypting and decrypting database fields on Yii models.
After implementation fields configured for encryption will be encrypted the next time the model is saved.

## Installation

Add the following to your composer file and run `php composer.phar update`

```json
    "require": {
        "maxcrossan/yii_encryption": "dev-master"
    },
```

Configure the Yii securityManger component in protected/config/main.php

```php
    'components'=>array(
        'securityManager'=>array(
            'cryptAlgorithm' => 'rijndael-128',
            'encryptionKey' => 'your-encryption-key'
        )
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
                    'phone'
                )
            )
        )
    }
```

Any encrypted database columns are best setup with the BLOB db type


## Troubleshooting

If you have problems loading the class inside your models you may need to setup an alias for vendor in main.php, something like ` Yii::setPathOfAlias('vendor',dirname(__FILE__).'/../../vendor/');` and then reference the EncryptionBehaviour as `vendor.maxcrossan.yii_encryption.src.EncryptionBehaviour`