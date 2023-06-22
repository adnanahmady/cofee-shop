<?php

namespace App\Api;

class Exchanges implements ApiGetInterface
{
    private null|array $response = null;

    /**
     * Attention: in a real world project this class
     * is responsible to receive the exchange rates
     * from a reliable source, usually this source is
     * an exchange http source. but for the sake of
     * not making the project complex, no external api
     * gets called and only a sample array gets returned.
     */
    public function get(): array
    {
        if (null === $this->response) {
            $this->response = $this->getResponse();
        }

        return $this->response;
    }

    /**
     * Finds and returns the rate specified for the given
     * currency code.
     *
     * Attention: Since this class is not a real world
     * instance, if a currency does not exist a default
     * currency will return.
     *
     * @param string $code currency Code
     */
    public function getRate(string $code): float
    {
        return $this->get()['rates'][$code] ?? 1.20;
    }

    public function getBaseRate(): float
    {
        $base = $this->getBase();

        return $this->getRate($base);
    }

    public function getBase(): string
    {
        return $this->get()['base'];
    }

    private function getResponse(): array
    {
        return [
            'base' => 'USD',
            'rates' => [
                'USD' => 1,
                'IRR' => 0.000024,
                'CAD' => 1.260046,
                'CHF' => 0.933058,
                'EUR' => 0.806942,
                'GBP' => 0.719154,
            ],
        ];
    }
}
