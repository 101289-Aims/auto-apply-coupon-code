<?php

namespace Aimsinfosoft\AutoApplyCouponCode\Observer\Couponcodeurl;

class SalesOrderPlaceAfter implements \Magento\Framework\Event\ObserverInterface {

	/**
	 * @var \Aimsinfosoft\AutoApplyCouponCode\ViewModel\Config
	 */
	private $config;

	/**
	 * @var \Aimsinfosoft\AutoApplyCouponCode\ViewModel\Cookie
	 */
	private $cookieViewModel;

	/**
	 * Constructor
	 *
	 * @param \Aimsinfosoft\AutoApplyCouponCode\ViewModel\Cookie $cookieViewModel
	 */
	public function __construct(
		\Aimsinfosoft\AutoApplyCouponCode\ViewModel\Config $config,
		\Aimsinfosoft\AutoApplyCouponCode\ViewModel\Cookie $cookieViewModel
	) {
		$this->config = $config;
		$this->cookieViewModel = $cookieViewModel;
	}

	/**
	 * If a coupon code was set in the URL at any point during the session
	 * an an order was successfully placed, we should remove it to avoid
	 * having it get automatically applied a secon time (user will have to
	 * either enter the code manually again or browse once more to the
	 * coupon-specific URL.)
	 *
	 * @param \Magento\Framework\Event\Observer $observer
	 *
	 * @return void
	 */
	public function execute(\Magento\Framework\Event\Observer $observer): void {

		// Once we've placed an order, we should delete the coupon cookie so
		// that the user will have to add one again if they wish to place
		// another order
		if ($this->config->isEnabled()) {
			$this->cookieViewModel->deleteCookie();
		}
	}
}

