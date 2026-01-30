<?php

namespace App\Services\Hotel;

class AvailabilityResult
{
    public function __construct(
        public string $status,
        public ?float $minPrice,
        public string $currency,
        public int $availableRoomsCount,
        public array $rooms
    ) {}
    
    /**
     * Check if hotel has available rooms
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->minPrice !== null && $this->minPrice > 0;
    }
    
    /**
     * Convert to array for JSON responses
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'amount' => $this->minPrice,
            'currency' => $this->currency,
            'available_rooms_count' => $this->availableRoomsCount,
        ];
    }
}
