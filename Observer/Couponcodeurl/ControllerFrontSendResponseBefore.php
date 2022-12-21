<?php

namespace Aimsinfosoft\AutoApplyCouponCode\Observer\Couponcodeurl;

class ControllerFrontSendResponseBefore implements \Magento\Framework\Event\ObserverInterface {

	/**
	 * @var \Aimsinfosoft\AutoApplyCouponCode\ViewModel\Config
	 */
	private $config;

	/**
	 * @var \Aimsinfosoft\AutoApplyCouponCode\ViewModel\Cookie
	 */
	private $cookieHelper;

	/**
	 * @var \Magento\Framework\Registry $registry
	 */
	private $registry;

	/**
	 * @var \Magento\Framework\Message\ManagerInterface
	 */
	private $messageManager;

	/**
	 * Constructor
	 *
	 * @param \Aimsinfosoft\AutoApplyCouponCode\ViewModel\Config $config
	 * @param \Aimsinfosoft\AutoApplyCouponCode\ViewModel\Cookie $cookieHelper
	 * @param \Magento\Framework\Registry $registry
	 * @param \Magento\Framework\Message\ManagerInterface $messageManager
	 */
	public function __construct(
		\Aimsinfosoft\AutoApplyCouponCode\ViewModel\Config $config,
		\Aimsinfosoft\AutoApplyCouponCode\ViewModel\Cookie $cookieHelper,
		\Magento\Framework\Registry $registry,
		\Magento\Framework\Message\ManagerInterface $messageManager
	) {
		$this->config = $config;
		$this->cookieHelper = $cookieHelper;
		$this->registry = $registry;
		$this->messageManager = $messageManager;
	}

	/**
	 * @param \Magento\Framework\Event\Observer $observer
	 *
	 * @return void
	 */
	public function execute(\Magento\Framework\Event\Observer $observer): void {

		if ($this->config->isEnabled()) {

			$coupon = $this->registry->registry('aimsinfosoft_autoapplycouponcode_coupon');
			$message = $this->registry->registry('aimsinfosoft_autoapplycouponcode_message');

			if ($coupon) {
				$this->cookieHelper->setCookie($coupon);
			}

			if ($message) {
				if ($message['error']) {
					$this->messageManager->addError($message['message']);
				} else {
					$this->messageManager->addSuccess($message['message']);
				}
			}
		}
	}
}

