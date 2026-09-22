<?php

declare(strict_types=1);

namespace Metricool\Http\Endpoints\Responses;

class ConnectedNetworksResponse extends Response
{
    /**
     * Response from Metricool /v2/settings/brands/{blogId}
     * @var array $brandSettings
     */
    protected array $brandSettings = [];

    public function __construct(array $brandSettings)
    {
        $this->brandSettings = $brandSettings;
    }

    /**
     * Parse the brandSettings response and extract just the connected networks
     */
    protected function parse(): array
    {
        $networks = [];

        if (!isset($this->brandSettings['networksData'])) {
            return $networks;
        }

        foreach ($this->brandSettings['networksData'] as $network => $networkData) {
            $networkName = str_replace('Data', '', $network);
            $networks[$networkName] = $networkData;
        }

        if (!isset($networks['web']['url'])) {
            // Hardcode the "web" connection. This prevents an inactive WordPress connection after onboarding.
            $networks['web']['url'] = home_url();
        }

        return $networks;
    }

    /**
     * @inheritDoc
     */
    public function body(): array
    {
        return $this->parse();
    }
}
