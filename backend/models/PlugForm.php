<?php

namespace backend\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;

require_once(Yii::getAlias('@root') . '/vendor/quartz/yii2-utilities/plug-site.php');
require_once(Yii::getAlias('@root') . '/vendor/quartz/yii2-utilities/cloudflare-api.php');

use root\plugsite\PlugSite;
use root\cloudflareapi\CFApi;

class PlugForm extends Model
{
    /**
     * Статус сайта
     * @var boolean
     */
    public $plug_status;

    /**
     * Время выполнения технических работ
     * @var string
     */
    public $plug_interval;

    /**
     * Примерное время окончания технических работ
     * @var string
     */
    public $plug_end;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['plug_status'], 'boolean'],
            [['plug_interval', 'plug_end'], 'required'],
            [['plug_interval', 'plug_end'], 'string'],
            [['plug_interval', 'plug_end'], 'filter', 'filter' => 'trim'],
            [['plug_interval', 'plug_end'], 'filter', 'filter' => 'strip_tags'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'plug_status' => 'Закрыть сайт',
            'plug_interval' => 'Время выполнения технических работ',
            'plug_end' => 'Примерное время окончания технических работ',
        ];
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function save()
    {
        $params = array();
        if ($this->plug_status) {
            $params['s'] = 'true';
        } else {
            $params['s'] = 'false';
        }
        $params['i'] = $this->plug_interval;
        $params['e'] = $this->plug_end;
        try {
            PlugSite::run($params);
        } catch (Exception $e) {
            return false;
        }
        if (YII_ENV == 'prod') {
            try {
                CFApi::run(array('c' => 'clear_cache'));
            } catch (Exception $e) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return $this
     */
    public function get()
    {
        $this->plug_status = defined('APP_PLUG_STATUS') ? APP_PLUG_STATUS : false;
        $this->plug_interval = defined('APP_PLUG_INTERVAL') ? APP_PLUG_INTERVAL : '';
        $this->plug_end = defined('APP_PLUG_END') ? APP_PLUG_END : '';
        return $this;
    }
}
