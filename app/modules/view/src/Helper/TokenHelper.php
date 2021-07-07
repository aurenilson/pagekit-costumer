<?php

namespace Pagekit\View\Helper;

use Pagekit\Session\Csrf\Provider\CsrfProviderInterface;

class TokenHelper extends Helper
{
    protected \Pagekit\Session\Csrf\Provider\CsrfProviderInterface $provider;

    /**
     * Constructor.
     *
     * @param CsrfProviderInterface $provider
     */
    public function __construct(CsrfProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Displays a hidden token field to reduce the risk of CSRF exploits.
     *
     * @param string $name
     */
    public function get($name = '_csrf'): void
    {
        printf('<input type="hidden" name="%s" value="%s">', $name, $this->provider->generate());
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'token';
    }
}
