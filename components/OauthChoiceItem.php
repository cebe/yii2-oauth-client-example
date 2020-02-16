<?php

namespace app\components;

use yii\authclient\OAuth2;
use yii\helpers\Html;

class OauthChoiceItem extends \yii\authclient\widgets\AuthChoiceItem
{
    public function run()
    {
        $htmlOptions = [];

        $text = Html::tag('span', 'Login/Signup with Oauth Server', ['class' => ' ' . $this->client->getName()]); // auth-icon class is not added because scroll bar gets visible if it is added

        $htmlOptions['class'] = $this->client->getName();
        $htmlOptions['title'] = $this->client->getTitle();

        Html::addCssClass($htmlOptions, ['widget' => 'auth-link']);

        if ($this->authChoice->popupMode) {
            $htmlOptions['data-popup-width'] = 860;
            $htmlOptions['data-popup-height'] = 480;
        }

        return Html::a($text, $this->authChoice->createClientUrl($this->client), $htmlOptions);
    }
}
