<?php

namespace Aimsinfosoft\AutoApplyCouponCode\Observer\Couponcodeurl;

class CheckoutCartSaveAfter implements \Magento\Framework\Event\ObserverInterface {

	/**
	 * @var \Aimsinfosoft\AutoApplyCouponCode\ViewModel\Config
	 */
	private $config;

	/**
	 * @var \Aimsinfosoft\AutoApplyCouponCode\ViewModel\Cookie
	 */
	private $cookieViewModel;

	/**
	 * @var \Aimsinfosoft\AutoApplyCouponCode\ViewModel\Cart
	 */
	private $cartViewModel;

	/**
	 * Constructor
	 *
	 * @param \Aimsinfosoft\AutoApplyCouponCode\ViewModel\Config $config
	 * @param \Aimsinfosoft\AutoApplyCouponCode\ViewModel\Cookie $cookieViewModel
	 * @param \Aimsinfosoft\AutoApplyCouponCode\ViewModel\Cart $cartViewModel
	 */
	public function __construct(
		\Aimsinfosoft\AutoApplyCouponCode\ViewModel\Config $config,
		\Aimsinfosoft\AutoApplyCouponCode\ViewModel\Cookie $cookieViewModel,
		\Aimsinfosoft\AutoApplyCouponCode\ViewModel\Cart $cartViewModel
	) {
		$this->config = $config;
		$this->cookieViewModel = $cookieViewModel;
		$this->cartViewModel = $cartViewModel;
	}

	/**
	 * If a coupon code was set in the URL at any point during the session,
	 * apply it as soon as the cart is created and re-apply it every time it's
	 * updated to keep the total price current.
	 *
	 * @param \Magento\Framework\Event\Observer $observer
	 *
	 * @return void
	 */
	public function execute(\Magento\Framework\Event\Observer $observer): void {

		if ($this->config->isEnabled()) {

			$coupon = $this->cookieViewModel->getCookie();

			if ($coupon) {

				$cart = $observer->getData('cart');

				if ($cart) {
					$this->cartViewModel->applyCoupon($cart->getQuote(), $coupon);
				}
			}
		}
	}
}

