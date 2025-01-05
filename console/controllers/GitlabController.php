<?php

namespace console\controllers;

use quartz\tools\modules\errorHandler\exceptions\Exception;
use console\service\GitlabService;
use yii\console\Controller;
use yii\httpclient\Client;

/**
 * Консольный скрипт добавления и включения deploy ключей на гитлабе
 * Скрипт исплользуется при развертываниии проекта на новом сервере с новым ssh ключем
 *
 * @property Client $httpClient
 * Class GitlabController
 * @package console\controllers
 */
class GitlabController extends Controller
{
    /**
     * Активация ssh ключей в gitlab
     * @param string $nameKey            Название ключа
     * @param string $privateToken       Приватный токен пользователя quartz
     * @param string $ssh                SSH ключ, созданный на сервере ВНИМАНИЕ, ПЕРЕДАВАТЬ С КАВЫЧ. 'ssh-rsa AAAAB...'
     * @return void
     * @throws Exception
     */
    public function actionAddAndEnableKeys($nameKey, $privateToken, $ssh)
    {
        $gitlabService = new GitlabService();
        $httpClient = $gitlabService->getHttpClient();

        // Получаем список проектов (2 запроса, т.к. количество элементов максимальное 100)
        $url = 'users/' . $gitlabService->idUser . '/projects';
        $repositoriesOne = $httpClient->get($url, ['page' => 1, 'per_page' => 100])
            ->addHeaders(['Private-Token' => $privateToken])->send();
        $repositoriesTwo = $httpClient->get($url, ['page' => 2, 'per_page' => 100])
            ->addHeaders(['Private-Token' => $privateToken])->send();
        $repositories = array_merge(json_decode($repositoriesOne->content), json_decode($repositoriesTwo->content));

        // Получаем id дефолтного репозитория(где будет создаваться ключ) и остальных репозиториев quartq'a
        $defaultRepository = $repositoriesQuartz = [];

        foreach ($repositories as $repository) {
            if ($repository->name == $gitlabService->defaultRepository) {
                $defaultRepository = $repository;
            }

            if (in_array($repository->name, $gitlabService->repositories)) {
                $repositoriesQuartz[] = $repository;
            }
        }

        if (empty($defaultRepository)) {
            throw new Exception("id дефолтного репозитория не найден \n");
        }

        if (empty($repositoriesQuartz)) {
            throw new Exception("id репозиториев не найдены \n");
        }

        // Далее создаём ключ
        $body = [
            'title' => $nameKey,
            'key' => $ssh,
            'can_push' => false,
        ];
        /** @var string $jsonBody */
        $jsonBody = json_encode($body);
        $key = $httpClient->post('projects/' . $defaultRepository->id . '/deploy_keys', $jsonBody, [
            'Private-Token' => $privateToken, 'Content-Type' => 'application/json'
        ])->send();

        $key = json_decode($key->content);

        if (empty($key->id)) {
            throw new Exception('При создании ключа произошла ошибка: ' . $key->error);
        }

        /** @phpstan-ignore-next-line */
        echo "Создан/найден ключ " . $key->title ??  $key->message ?? ''. "\n";

        // Далее включаем ключ во всех репозиториях
        foreach ($repositoriesQuartz as $repository) {
            $keyEnabled = $httpClient
                ->post(
                    'projects/' . $repository->id . '/deploy_keys/' . $key->id . '/enable',
                    [],
                    ['Private-Token' => $privateToken]
                )
                ->send();
            $keyEnabled = json_decode($keyEnabled->content);

            if (isset($keyEnabled->id)) {
                echo 'Включен ключ для репозитория ' . $repository->name. "\n";
            } else {
                echo 'Не включен ключ для репозитория ' . $repository->name . "\n";
            }
        }
    }
}
