Работа с новым модулем очередей (yiisoft/yii2-queue)
====================================================

Для того что создать очередь и начать работу с демонами нужно выполнить несколько простых шагов:
### Установить пакеты:
* "php-amqplib/php-amqplib": "*"
* "enqueue/amqp-lib": "*"
* "yiisoft/yii2-queue": "*"

### Создать компонент (в common/config/main.php), отвечающий за очередь, например:
```php
'components' => [
    ...
    'yourNewQueue' => [
        'class' => \yii\queue\amqp_interop\Queue::class,
        'port' => '5672',
        'vhost' => '/',
        'user' => 'guest',
        'password' => 'guest',
        'queueName' => 'base_app.your_new_queue',
        'driver' => yii\queue\amqp_interop\Queue::ENQUEUE_AMQP_LIB,
        'exchangeName' => 'base_app.your-new-exchange',
    ],
    ...
],
```

### Прописать в common/config/main.php следующие строки чтобы Yii начал работать с компонентом:
```php
'bootstrap' => [
    ...
    'yourNewQueue',
    ...
],
```
Также для видимости в IDE можно добавить в common/config/Yii.php
```php
* @property \yii\queue\amqp_interop\Queue $yourNewQueue
```

### Далее создаем обработчик сообщений который работает с интерфейсом yii\queue\JobInterface
```php
namespace common\components\amqp;

use yii\base\BaseObject;
use yii\queue\amqp_interop\Queue;
use yii\queue\JobInterface;
use Yii;

/**
 * Воркер для работы с сообщениям
 *
 * Class SendMailJob
 * @package common\components\amqp
 */
class YourNewJob extends BaseObject implements JobInterface
{
    public $var1;
    public $var2;
    public $lives = 10;

    /**
     * @param Queue $queue
     * @throws \Exception
     * @throws \Throwable
     */
    public function execute($queue)
    {
        try {
            // Ваш обработчик
            $this->>var1 + $this->var2;
        } catch (\Throwable $e) {
            // Перепостановка в очередь
            if ($this->lives > 0) {
                $this->lives--;
                $queueComponent = Yii::$app->getComponents(false)[Yii::$app->getModule('mailnotify')->queueComponent];
                Yii::$app->yourNewQueue->push($this);   
            }
        }
    }
}
```
здесь var1, var2 - переменные, которые устанавливаются при создании и с ними дальше работает обработчик

### Для постановки задачи используем комбинацию компонента и обработчика:
```php
    Yii::$app->yourNewQueue->push(new YourNewJob([
        'var1' => 100,
        'var2' => 200,
    ]));
```

Готово, ваш первый обработчик готов! Также можно исползовать lives, delay, и прочие штуки

### Важно помнить!!!
* При выпадании в exception, обработчик сам не ставит задачу назад
* Процесс убить можно только через kill -9 (живучий блин)

### По всем вопросам обращаться к Проскурин В.