<?php
declare(strict_types=1);

namespace App\Controller;

use App\DTO\ShippingAddressDTO;
use App\Service\ShippingAddressService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ShippingAddressController
{
    /**
     * @var ShippingAddressService
     */
    private $shippingAddressService;

    /**
     * @param ShippingAddressService $shippingAddressService
     */
    public function __construct(ShippingAddressService $shippingAddressService)
    {
        $this->shippingAddressService = $shippingAddressService;
    }

    /**
     * @param Request $request
     * @param string $clientId
     * @return JsonResponse
     * @throws \Exception
     */
    public function add(Request $request, string $clientId)
    {
        $dto = ShippingAddressDTO::fromArray($request->request->all());

        return new JsonResponse([
            "response" => $this->shippingAddressService->add($dto, $clientId)
        ]);
    }

    /**
     * @param string $clientId
     * @return JsonResponse
     * @throws \Exception
     */
    public function list(string $clientId)
    {
        return new JsonResponse([
            "response" => $this->shippingAddressService->list($clientId)
        ]);
    }

    /**
     * @param Request $request
     * @param string $clientId
     * @param string $shippingAddressId
     * @return JsonResponse
     * @throws \Exception
     */
    public function edit(Request $request, string $clientId, string $shippingAddressId)
    {
        $dto = ShippingAddressDTO::fromArray($request->query->all());

        return new JsonResponse([
            "response" => $this->shippingAddressService->edit($dto, $clientId, $shippingAddressId)
        ]);
    }

    /**
     * @param string $clientId
     * @param string $shippingAddressId
     * @return JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function default(string $clientId, string $shippingAddressId)
    {
        return new JsonResponse([
            "response" => $this->shippingAddressService->default($clientId, $shippingAddressId)
        ]);
    }

    /**
     * @param string $clientId
     * @param string $shippingAddressId
     * @return JsonResponse
     * @throws \Exception
     */
    public function remove(string $clientId, string $shippingAddressId)
    {
        return new JsonResponse([
            "response" => $this->shippingAddressService->remove($clientId, $shippingAddressId)
        ]);
    }
}
