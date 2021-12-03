<?php
/**
 * Copyright © O2TI. All rights reserved.
 *
 * @author    Bruno Elisei <brunoelisei@o2ti.com>
 * See COPYING.txt for license details.
 */

namespace O2TI\AdvancedFieldsCheckout\Plugin;

use Magento\Checkout\Block\Checkout\LayoutProcessor;
use O2TI\AdvancedFieldsCheckout\Helper\Config;
use O2TI\AdvancedFieldsCheckout\Model\AddtionalClasses;
use O2TI\AdvancedFieldsCheckout\Model\Autocomplete;
use O2TI\AdvancedFieldsCheckout\Model\Placeholder;

/**
 *  CheckoutAdvancedFieldsCheckout - Change Components.
 */
class CheckoutAdvancedFieldsCheckout
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var AddtionalClasses
     */
    protected $addtionalClasses;

    /**
     * @var Autocomplete
     */
    protected $autocomplete;

    /**
     * @var Placeholder
     */
    protected $placeholder;

    /**
     * @param Config           $config
     * @param AddtionalClasses $addtionalClasses
     * @param Autocomplete     $autocomplete
     * @param Placeholder      $placeholder
     */
    public function __construct(
        Config $config,
        AddtionalClasses $addtionalClasses,
        Autocomplete $autocomplete,
        Placeholder $placeholder
    ) {
        $this->config = $config;
        $this->addtionalClasses = $addtionalClasses;
        $this->autocomplete = $autocomplete;
        $this->placeholder = $placeholder;
    }

    /**
     * Change Components in Create Account.
     *
     * @param array $jsLayout
     *
     * @return array
     */
    public function changeCreateAccount(array $jsLayout): array
    {
        // phpcs:ignore
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['identification-step']['children']['identification']['children']['createAccount']['children']['create-account-fieldset']['children'])) {
            // phpcs:ignore
            $createAccountFields = &$jsLayout['components']['checkout']['children']['steps']['children']['identification-step']['children']['identification']['children']['createAccount']['children']['create-account-fieldset']['children'];
            if ($this->config->isEnabledClasses()) {
                $createAccountFields = $this->addtionalClasses->setAddtionalClasses($createAccountFields);
            }
            if ($this->config->isEnabledAutocomplete()) {
                $createAccountFields = $this->autocomplete->setAutocomplete($createAccountFields);
            }
            if ($this->config->isEnabledPlaceholder()) {
                $createAccountFields = $this->placeholder->setPlaceholder($createAccountFields);
            }
        }

        return $jsLayout;
    }

    /**
     * Change Components in Shipping.
     *
     * @param array $jsLayout
     *
     * @return array
     */
    public function changeShippingFields(array $jsLayout): array
    {
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step'])) {
            // phpcs:ignore
            $shippingFields = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];
            if ($this->config->isEnabledClasses()) {
                $shippingFields = $this->addtionalClasses->setAddtionalClasses($shippingFields);
            }
            if ($this->config->isEnabledAutocomplete()) {
                $shippingFields = $this->autocomplete->setAutocomplete($shippingFields);
            }
            if ($this->config->isEnabledPlaceholder()) {
                $shippingFields = $this->placeholder->setPlaceholder($shippingFields);
            }
        }

        return $jsLayout;
    }

    /**
     * Change Components in Billing.
     *
     * @param array $jsLayout
     *
     * @return array
     */
    public function changeBillingFields(array $jsLayout): array
    {
        // phpcs:ignore
        foreach ($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'] as &$payment) {
            if (isset($payment['children']['form-fields'])) {
                $billingFields = &$payment['children']['form-fields']['children'];
                if ($this->config->isEnabledClasses()) {
                    $billingFields = $this->addtionalClasses->setAddtionalClasses($billingFields);
                }
                if ($this->config->isEnabledAutocomplete()) {
                    $billingFields = $this->autocomplete->setAutocomplete($billingFields);
                }
                if ($this->config->isEnabledPlaceholder()) {
                    $billingFields = $this->placeholder->setPlaceholder($billingFields);
                }
            }
        }
        // phpcs:ignore
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['afterMethods']['children']['billing-address-form'])) {
            // phpcs:ignore
            $billingAddressOnPage = &$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['afterMethods']['children']['billing-address-form']['children']['form-fields']['children'];
            if ($this->config->isEnabledClasses()) {
                $billingAddressOnPage = $this->addtionalClasses->setAddtionalClasses($billingAddressOnPage);
            }
            if ($this->config->isEnabledAutocomplete()) {
                $billingAddressOnPage = $this->autocomplete->setAutocomplete($billingAddressOnPage);
            }
            if ($this->config->isEnabledPlaceholder()) {
                $billingAddressOnPage = $this->placeholder->setPlaceholder($billingAddressOnPage);
            }
        }
        // phpcs:ignore
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['beforeMethods']['children']['billing-address-form'])) {
            // phpcs:ignore
            $billingAddressOnPage = &$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['beforeMethods']['children']['billing-address-form']['children']['form-fields']['children'];
            if ($this->config->isEnabledClasses()) {
                $billingAddressOnPage = $this->addtionalClasses->setAddtionalClasses($billingAddressOnPage);
            }
            if ($this->config->isEnabledAutocomplete()) {
                $billingAddressOnPage = $this->autocomplete->setAutocomplete($billingAddressOnPage);
            }
            if ($this->config->isEnabledPlaceholder()) {
                $billingAddressOnPage = $this->placeholder->setPlaceholder($billingAddressOnPage);
            }
        }

        return $jsLayout;
    }

    /**
     * Select Components for Change.
     *
     * @param LayoutProcessor $layoutProcessor
     * @param callable        $proceed
     * @param array           $args
     *
     * @return array
     */
    public function aroundProcess(LayoutProcessor $layoutProcessor, callable $proceed, array $args): array
    {
        $jsLayout = $proceed($args);
        if ($this->config->isEnabled()) {
            $jsLayout = $this->changeCreateAccount($jsLayout);
            $jsLayout = $this->changeShippingFields($jsLayout);
            $jsLayout = $this->changeBillingFields($jsLayout);
        }

        return $jsLayout;
    }
}
