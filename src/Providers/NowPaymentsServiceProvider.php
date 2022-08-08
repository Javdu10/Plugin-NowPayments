<?php

namespace Azuriom\Plugin\NowPayments\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Plugin\NowPayments\NowPaymentsMethod;

class NowPaymentsServiceProvider extends BasePluginServiceProvider
{
    /**
     * Bootstrap any plugin services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViews();

        $this->loadTranslations();

        payment_manager()->registerPaymentMethod('nowpayments', NowPaymentsMethod::class);
    }
}
