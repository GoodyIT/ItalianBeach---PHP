<?php
namespace backend\controllers;

use common\models\LoginForm;
use common\models\SignupForm;
use common\models\User;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\ResetPasswordForm;
use frontend\models\AccountActivation;
use frontend\models\PasswordResetRequestForm;
use Yii;

/**
 * Site controller.
 * It is responsible for displaying static pages, and logging users in and out.
 */
class SiteController extends BaseController
{
    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'request-password-reset', 'reset-password', 'signup', 'activate-account'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => '@app/views/site/error.php'
            ],
        ];
    }

    /*public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $this->render('error', ['exception' => $exception]);
        }
    }*/
    /**
     * Displays the index (home) page.
     * Use it in case your home page contains static content.
     *
     * @return string
     */
    public function actionIndex()
    {
       return $this->redirect(Url::to(['dashboard/index', 'lang' =>  Yii::$app->language]));
    }

    /**
     * Logs in the user if his account is activated,
     * if not, displays standard error message.
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        User::deactiveExpiredUsers();

        if (!Yii::$app->user->isGuest) 
        {
            return $this->goHome();
        }

        // get setting value for 'Login With Email'
        $lwe = Yii::$app->params['lwe'];

        // if "login with email" is true we instantiate LoginForm in "lwe" scenario
        $lwe ? $model = new LoginForm(['scenario' => 'lwe']) : $model = new LoginForm() ;

        // everything went fine, log in the user
        if ($model->load(Yii::$app->request->post()) && $model->validate()) 
        {
            if ($model->notActivated()) {
                $messageType = "error";
                $message = Yii::t('messages', "Sorry, Your account is expired now.");
                Yii::$app->session->setFlash($messageType, $message);
                return $this->render('login', [
                    'model' => $model,
                ]);
            } else if ($model->login()) {
                $this->goBack();
            }
        } 
        // errors will be displayed
        else 
        {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the user.
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /*----------------*
 * PASSWORD RESET *
 *----------------*/

    /**
     * Sends email that contains link for password reset action.
     *
     * @return string|\yii\web\Response
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            if ($model->sendEmail())
            {
                Yii::$app->session->setFlash('success',
                    Yii::t('messages', 'Check your email for further instructions.'));

                return $this->redirect(Url::to(['/', 'lang' =>  Yii::$app->language]));
            }
            else
            {
                Yii::$app->session->setFlash('error',
                    Yii::t('messages', 'Sorry, we are unable to reset password for email provided.'));
            }
        }
        else
        {
            return $this->render('requestPasswordResetToken', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Resets password.
     *
     * @param  string $token Password reset token.
     * @return string|\yii\web\Response
     *
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try
        {
            $model = new ResetPasswordForm($token);
        }
        catch (InvalidParamException $e)
        {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post())
            && $model->validate() && $model->resetPassword())
        {
            Yii::$app->session->setFlash('success', Yii::t('messages', 'New password was saved.'));

            return $this->goHome();
        }
        else
        {
            return $this->render('resetPassword', [
                'model' => $model,
            ]);
        }
    }

//------------------------------------------------------------------------------------------------//
// SIGN UP / ACCOUNT ACTIVATION
//------------------------------------------------------------------------------------------------//

    /**
     * Signs up the user.
     * If user need to activate his account via email, we will display him
     * message with instructions and send him account activation email
     * ( with link containing account activation token ). If activation is not
     * necessary, we will log him in right after sign up process is complete.
     * NOTE: You can decide whether or not activation is necessary,
     * @see config/params.php
     *
     * @return string|\yii\web\Response
     */
    public function actionSignup()
    {
        // get setting value for 'Registration Needs Activation'
        $rna = Yii::$app->params['rna'];

       // if 'rna' value is 'true', we instantiate SignupForm in 'rna' scenario
        $model = $rna ? new SignupForm(['scenario' => 'rna']) : new SignupForm();


        // collect and validate user data
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            // try to save user data in database
            if ($user = $model->signup())
            {
                // if user is active he will be logged in automatically ( this will be first user )
                if ($user->status === User::STATUS_ACTIVE)
                {
                    Yii::$app->session->setFlash('success',
                    Yii::t('messages', 'Success! You can administrate the Sunticket.').'.<br>' . 
                    Yii::t('messages', 'Thank you ').Html::encode($user->username). Yii::t('messages', ' for joining us!'));

                    /*Yii::$app->mailer->compose()
                                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                                ->setTo([Yii::$app->params['adminEmail']])
                                ->setSubject(Yii::$app->name)
                                ->setTextBody('A new user ' . $user->username .' was successfully activated')
                                ->send();

                    Yii::$app->mailer->compose('welcome')
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                        ->setTo($email)
                        ->setSubject(Yii::$app->name)
                        ->send();

                    // to me
                    Yii::$app->mailer->compose()
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                        ->setTo("imobilegang@gmail.com")
                        ->setSubject(Yii::$app->name)
                        ->setTextBody('A new user ' . $user->username .' was successfully activated')
                        ->send();*/

                    if (Yii::$app->getUser()->login($user))
                    {
                        return $this->refresh();
                    }
                }
                // activation is needed, use signupWithActivation()
                else
                {
                    $this->signupWithActivation($model, $user);

                    Yii::$app->mailer->compose()
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                        ->setTo([Yii::$app->params['adminEmail']])
                        ->setSubject(Yii::$app->name)
                        ->setTextBody('A new user ' . $user->username .' is trying to sign up as a demo user')
                        ->send();

                    // to me
                    Yii::$app->mailer->compose()
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                        ->setTo("imobilegang@gmail.com")
                        ->setSubject(Yii::$app->name)
                        ->setTextBody('A new user ' . $user->username .' is trying to sign up as a demo user')
                        ->send();
                    return $this->refresh();
                }
            }
            // user could not be saved in database
            else
            {
                // display error message to user
                Yii::$app->session->setFlash('error',
                    Yii::t("messages", "We couldn't sign you up, please contact us."));

                // log this error, so we can debug possible problem easier.
                Yii::error( Yii::t('messages', 'Signup failed!') .
                 Yii::t('messages', 'User') . Html::encode($user->username). Yii::t('messages', ' could not sign up.') .'.<br>' . 
                    Yii::t('messages', 'Possible causes: something strange happened while saving user in database.'));

                return $this->refresh();
            }
        }


        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Sign up user with activation.
     * User will have to activate his account using activation link that we will
     * send him via email.
     *
     * @param $model 
     * @param $user
     */
    private function signupWithActivation($model, $user)
    {
        // try to send account activation email
        if ($model->sendAccountActivationEmail($user))
        {
            Yii::$app->session->setFlash('success',
                Yii::t('messages', 'Hello ').Html::encode($user->username).'.<br>' .  
                 Yii::t('messages', 'To be able to log in, you need to confirm your registration.'). '<br>'.
                 Yii::t('messages', 'Please check your email, we have sent you a message.'));
        }
        // email could not be sent
        else
        {
            // display error message to user
            Yii::$app->session->setFlash('error',
               Yii::t("messages",  "We couldn't send you account activation email, please contact us."));

            // log this error, so we can debug possible problem easier.
            Yii::error(Yii::t('messages', 'Signup failed!').'<br>'. 
                Yii::t('messages', 'User') .Html::encode($user->username).Yii::t('messages', ' could not sign up.') . '<br>'.
                Yii::t('messages', 'Possible causes: verification email could not be sent.'));
        }
    }

    /*--------------------*
     * ACCOUNT ACTIVATION *
     *--------------------*/

    /**
     * Activates the user account so he can log in into system.
     *
     * @param  string $token
     * @return \yii\web\Response
     *
     * @throws BadRequestHttpException
     */
    public function actionActivateAccount($token)
    {
        try
        {
            $email = User::findByAccountActivationToken($token)->email;

            $user = new AccountActivation($token);
        }
        catch (InvalidParamException $e)
        {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($user->activateAccount())
        {
            Yii::$app->session->setFlash('success',
                Yii::t('messages', 'Success! You can now log in.').'.<br>' . 
                Yii::t('messages', 'Thank you ').Html::encode($user->username). Yii::t('messages', ' for joining us!'));

            Yii::$app->mailer->compose()
                        ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                        ->setTo([Yii::$app->params['adminEmail']])
                        ->setSubject(Yii::$app->name)
                        ->setTextBody('A new user ' . $user->username .' was successfully activated')
                        ->send();

            Yii::$app->mailer->compose('welcome')
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                ->setTo($email)
                ->setSubject(Yii::$app->name)
                ->send();

            // to me
            Yii::$app->mailer->compose()
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                ->setTo("imobilegang@gmail.com")
                ->setSubject(Yii::$app->name)
                ->setTextBody('A new user ' . $user->username .' was successfully activated')
                ->send();
        }
        else
        {
            Yii::$app->session->setFlash('error',
                ''.Html::encode($user->username). Yii::t('messages', ' your account could not be activated,').'.<br>' . 
                Yii::t('messages', 'please contact us!'));
        }

        return $this->redirect(Url::to(['login', 'lang' => Yii::$app->language]));
    }
}
