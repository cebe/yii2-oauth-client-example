<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace app\modules\usuariomod\controllers;

use Da\User\Contracts\AuthClientInterface;
use Da\User\Event\FormEvent;
use Da\User\Event\UserEvent;
use Da\User\Form\LoginForm;
use Da\User\Query\SocialNetworkAccountQuery;
use Da\User\Service\SocialNetworkAccountConnectService;
use Da\User\Service\SocialNetworkAuthenticateService;
use Da\User\Traits\ContainerAwareTrait;
use Da\User\Traits\ModuleAwareTrait;
use Yii;
use yii\authclient\AuthAction;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class SecurityController extends \Da\User\Controller\SecurityController
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::class,
                // if user is not logged in, will try to log him in, otherwise
                // will try to connect social account to user.
                'successCallback' => Yii::$app->user->isGuest
                    ? [$this, 'authenticate']
                    : [$this, 'connect'],
                'cancelCallback' => function(\yii\authclient\ClientInterface $client) {
                    $request = Yii::$app->getRequest();
                    Yii::$app->session->setFlash('warning', Yii::t('usuario',
                        $request->get('error') .' | ' .  $request->get('error_description') . ' | ' . $request->get('hint') . ' | ' . $request->get('message')
                    ));
                    return $this->redirect(['/user/security/login']);
                }
            ],
        ];
    }
}
