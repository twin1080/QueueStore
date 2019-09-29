<?php
declare(strict_types=1);

namespace App\Application\Actions\Store;

use App\Application\Actions\Action;
use App\Domain\Customer\CustomerRepositoryInterface;
use App\Domain\Store\StoreRepositoryInterface;
use DI\Container;
use DI\ContainerBuilder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Log\LoggerInterface;
use Slim\App;
use App\Domain\Store;
use Slim\Factory\AppFactory;

class StoreAction extends Action
{
    /**
     * @var StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @param LoggerInterface $logger
     * @param StoreRepositoryInterface $storeRepository
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(LoggerInterface $logger, StoreRepositoryInterface $storeRepository, CustomerRepositoryInterface $customerRepository)
    {
        parent::__construct($logger);
        $this->storeRepository = $storeRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $result = [];

        $app = AppFactory::create();
        $settings = $app->getContainer()->get('settings');

        $workFrom = $settings['workFrom'];
        $workTill = $settings['workTill'];

        // Магазин открывается
        $store = $this->storeRepository->create();
           // Посекундный отчет времени
            for ($second = $workFrom * 3600; $second <= 3600 * $workTill; $second++) {
                // Каждый час записываем состояние в ответ
                if ($second % 3600 === 0) {
                    $result[] = [
                        'time' => gmdate('H:i:s', $second),
                        'condition' => json_decode(json_encode($store)),
                    ];
                }

                $customer = $this->customerRepository->get();

                // Добавим посетителя если пришел
                if ($customer) {
                    $store->addCustomer($customer);
                }
                // Сделаем продвижение очередей
                $store->doWork();


            }

        $this->response->getBody()->write(json_encode($result, JSON_PRETTY_PRINT));

        return $this->response->withHeader('Content-Type', 'application/json');

    }
}