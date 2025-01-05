<?php

namespace console\service;

use yii\httpclient\Client;
use yii\httpclient\CurlTransport;

/**
 * Общение между gitlab'ом для добавления / включения ключей
 *
 * @property Client $httpClient
 * Class GitlabService
 * @package console\service
 */
class GitlabService
{
    /** @var integer id пользователя quartz в системе gitlab */
    const ID_USER_QUARTZ = 45;

    /** @var array репозиторий quartz'a, в который будет добавлен ключ*/
    const DEFAULT_REPOSITORY_QUARTZ = 'yii2-user';

    /** @var array все репозитории quartz'a используемые в проекте */
    const REPOSITORIES_QUARTZ = [
        'yii2-adminlte-theme',
        'yii2-debug',
        'yii2-gii',
        'yii2-image-behavior',
        'yii2-installer',
        'yii2-localization',
        'yii2-mailnotify',
        'yii2-menu',
        'yii2-metatag',
        'yii2-multiple-upload',
        'yii2-notification',
        'yii2-oauth2-server',
        'yii2-pages',
        'yii2-rbac',
        'yii2-settings-module',
        'yii2-tools',
        'yii2-user',
        'yii2-utilities',
        'yii2-fileapi-widget',
        'yii2-imperavi-widget',
        'yii2-reference',
    ];

    /** @var string $defaultRepository репозиторий, в который будет добавляться ключ */
    public $defaultRepository;

    /** @var array $repositories репозитории в которых необходимо будет включить ключи */
    public $repositories;

    /** @var int $idUser id пользователя, у которого будем искать проекты*/
    public $idUser;

    /** @var string $baseUrl */
    public $baseUrl = 'http://gitlab.freematiq.com/api/v4';

    /** @var Client */
    private $httpClient;

    /**
     * GitlabService constructor.
     * @param int $idUser
     * @param string $defaultRepository
     * @param array $repositories
     */
    public function __construct(
        $idUser = self::ID_USER_QUARTZ,
        $defaultRepository = self::DEFAULT_REPOSITORY_QUARTZ,
        array $repositories = self::REPOSITORIES_QUARTZ
    ) {
        $this->idUser = $idUser;
        $this->defaultRepository = $defaultRepository;
        $this->repositories = $repositories;
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        if (!is_object($this->httpClient)) {
            /** @phpstan-ignore-next-line */
            $this->httpClient = \Yii::createObject($this->defaultHttpClientConfig());
        }

        return $this->httpClient;
    }

    /**
     * Дефолтные настройки для HttpClient
     * @return array
     */
    protected function defaultHttpClientConfig(): array
    {
        return [
            'class' => Client::class,
            'baseUrl' => rtrim($this->baseUrl, '/'),
            'transport' => CurlTransport::class,
        ];
    }
}
