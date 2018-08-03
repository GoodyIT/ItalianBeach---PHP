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
    'rna' => true,

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
    'supportEmail' => 'imobilegang@gmail.com',

    'service' => [1 => 1, 2 => 7, 3 => 31, 4 => 100, 5 => 0],

    'servicetype' => [1=>"1 day", 2=>"7 days", 3=>"31 days", 4=>'All season', 5 => 'Rooms'],
    'servicetype_it' => [1=>"1 giorno", 2=>"7 giorni", 3=>"31 giorni", 4=>'Intera Stagione', 5 => 'Stanze'],
    'intervals' => ['1' => '0 day', '2' =>'6 days', '3' => '30 days', '4' => '99 days']
];
