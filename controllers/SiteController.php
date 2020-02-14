<?php

namespace app\controllers;


use BadMethodCallException;
use InvalidArgumentException;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\ValidationData;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\CryptTrait;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'accessControl' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['error'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['redirectedhere'],
                        'allow' => true,
                        'roles' => ['?', '*', '@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // Access token -> token_type, expires_in, access_token, refresh_token are stored in session and can be accessed by

        // below code will only give results when user is logged in via Oauth server

        // print_r(\Yii::$app->getSession()->get('app\components\OauthServerDaClient_oauthserver_token')->getParams()['access_token']);
        //print_r($_SESSION);

        // Way to get token's (JWT access_token) custom data here
        // $this->getTokenData(Yii::$app->getSession()->get('app\components\OauthServerDaClient_oauthserver_token')->getParams()['access_token']);
        // die;
        return $this->render('index');
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionRedirectedhere()
    {
        $client = new \yii\httpclient\Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl('http://localhost:7876/web/index.php?r=oauth/default/my-access-token')
            ->setData([
                'grant_type' => 'authorization_code',
                'client_id' => 1,
                'client_secret' => 'secrets65df65sd65f4s6d45f',
                'redirect_uri' => 'http://localhost:7878/web/index.php?r=site/redirectedhere',
                'code' => \Yii::$app->request->get('code'),
            ])
            ->send();

        echo "<pre>";
        if ($response->isOk) {
            print_r($response->content);
        } else {
            print_r($response);
            echo "else string";
        }
        echo "</pre>";
        die();

        // return $this->render('index');
    }

    protected function getTokenData($jwt)
    {
        $token = (new Parser())->parse($jwt);
        $publicKey = new CryptKey('TODO-Put file path here e.g. /var/www/html/oauthserver/pubblic.key');
        try {
            if ($token->verify(new Sha256(), $publicKey->getKeyPath()) === false) {
                throw OAuthServerException::accessDenied('Access token could not be verified');
            }
        } catch (BadMethodCallException $exception) {
            throw OAuthServerException::accessDenied('Access token is not signed', null, $exception);
        }

        // Ensure access token hasn't expired
        $data = new ValidationData();
        $data->setCurrentTime(time());

        if ($token->validate($data) === false) {
            throw OAuthServerException::accessDenied('Access token is invalid');
        }
        // print_r($token->getHeaders()); die; // you can get custom geta here
        return $token;
    }
}
