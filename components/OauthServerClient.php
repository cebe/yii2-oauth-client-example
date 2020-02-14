<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components;

use yii\authclient\OAuth2;

/**
 * GitHub allows authentication via GitHub OAuth.
 *
 * In order to use GitHub OAuth you must register your application at <https://github.com/settings/applications/new>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'github' => [
 *                 'class' => 'yii\authclient\clients\GitHub',
 *                 'clientId' => 'github_client_id',
 *                 'clientSecret' => 'github_client_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * @see http://developer.github.com/v3/oauth/
 * @see https://github.com/settings/applications/new
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class OauthServerClient extends OAuth2
{
    /**
     * {@inheritdoc}
     */
    public $authUrl = 'http://localhost:7876/web/index.php?r=oauth/default/authorize';
    /**
     * {@inheritdoc}
     */
    public $tokenUrl = 'http://localhost:7876/web/index.php?r=oauth/default/my-access-token';
    /**
     * {@inheritdoc}
     */
    public $apiBaseUrl = 'http://localhost:7876/web/index.php?r=api';


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = 'email'; // default scope is 'email'
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function initUserAttributes()
    {

        $attributes = $this->api('default/user', 'GET');
        // var_dump($attributes); die;
        return $attributes;
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultName()
    {
        return 'oauthserver';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultTitle()
    {
        return 'Oauth Server';
    }

    /**
     * {@inheritdoc}
     */
    public function applyAccessTokenToRequest($request, $accessToken)
    {
        $request->getHeaders()->set('Authorization', 'Bearer '. $accessToken->getToken());
    }

    protected function defaultNormalizeUserAttributeMap()
    {
        return [
            // 'id' => 'id',
            'email' => 'email',
            'username' => 'username',
        ];
    }
}
