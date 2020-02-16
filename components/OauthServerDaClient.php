<?php

/*
 * This file is part of the 2amigos/yii2-usuario project.
 *
 * (c) 2amigOS! <http://2amigos.us/>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace app\components;

use Da\User\Contracts\AuthClientInterface;
use app\components\OauhtServerClient;

class OauthServerDaClient extends \app\components\OauthServerClient implements AuthClientInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['email'])
            ? $this->getUserAttributes()['email']
            : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['username'])
            ? $this->getUserAttributes()['username']
            : null;
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultViewOptions()
    {
        return [
            'widget' => [
                'class' => \app\components\OauthChoiceItem::class,
            ]
        ];
    }
}
