<?php

namespace backend\modules\api;

use Yii;
use yii\base\Module;

/**
 * api module definition class
 */
class ModuleAPI extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\api\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::$app->user->enableSession = false;
        // custom initialization code goes here
    }
}
