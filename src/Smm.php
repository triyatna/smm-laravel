<?php

namespace Triyatna\SmmLaravel;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class Smm
{
    protected PendingRequest $client;

    public function __construct(
        protected ?string $apiUrl,
        protected ?string $apiId,
        protected ?string $apiKey
    ) {
        if (!$this->apiUrl || !$this->apiId || !$this->apiKey) {
            throw new \InvalidArgumentException('SMM API URL, ID, and Key must be configured.');
        }

        $this->client = Http::baseUrl($this->apiUrl)->asForm();
    }

    /**
     * Helper untuk mengirim request ke API.
     */
    private function sendRequest(string $endpoint, array $data = []): array
    {
        $payload = array_merge([
            'api_id' => $this->apiId,
            'api_key' => $this->apiKey,
        ], $data);

        $response = $this->client->post($endpoint, $payload);

        return $response->json();
    }

    /**
     * Cek Saldo & Profil.
     */
    public function profile(): array
    {
        return $this->sendRequest('profile');
    }

    /**
     * Dapatkan semua layanan.
     */
    public function services(): array
    {
        return $this->sendRequest('services');
    }

    /**
     * Buat pesanan baru.
     * @param int $serviceId ID Layanan.
     * @param string $target Target pesanan (username/link).
     * @param array $options Opsi tambahan sesuai tipe layanan (quantity, comments, dll).
     */
    public function order(int $serviceId, string $target, array $options = []): array
    {
        $data = array_merge([
            'service' => $serviceId,
            'target' => $target,
        ], $options);

        return $this->sendRequest('order', $data);
    }

    /**
     * Cek status pesanan.
     * @param string|array $orderIds ID pesanan tunggal atau array ID (maksimal 5).
     */
    public function status(string|array $orderIds): array
    {
        $idString = is_array($orderIds) ? implode(',', $orderIds) : $orderIds;

        return $this->sendRequest('status', ['id' => $idString]);
    }

    /**
     * Minta refill untuk pesanan.
     */
    public function refill(int $orderId): array
    {
        return $this->sendRequest('refill', ['id' => $orderId]);
    }

    /**
     * Cek status refill.
     */
    public function refillStatus(int $refillId): array
    {
        return $this->sendRequest('refill/status', ['id' => $refillId]);
    }
}
