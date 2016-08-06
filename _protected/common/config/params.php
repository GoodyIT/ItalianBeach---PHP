<?php

return [

//------------------------//
// SYSTEM SETTINGS
//------------------------//

    /**
     * Registration Needs Activation.
     *
     * If set to true users will have to activate their accounts using email account activation.
     */
    'rna' => false,

    /**
     * Login With Email.
     *
     * If set to true users will have to login using email/password combo.
     */
    'lwe' => false, 

    /**
     * Force Strong Password.
     *
     * If set to true users will have to use passwords with strength determined by StrengthValidator.
     */
    'fsp' => false,

    /**
     * Set the password reset token expiration time.
     */
    'user.passwordResetTokenExpire' => 3600,

//------------------------//
// EMAILS
//------------------------//

    /**
     * Email used in contact form.
     * Users will send you emails to this address.
     */
    'adminEmail' => 'imobilegang@gmail.com', 

    /**
     * Not used in template.
     * You can set support email here.
     */
    'supportEmail' => 'info@beachclubippocampo.it',

    'servicetype' => [1=>"1 day  ( max 2 people )", 2=>"7 days  ( max 4 people )", 3=>"31 days  ( max 4 people )", 4=>'All season  ( max 4 people )', 5 => 'Rooms'],
];
