<?php

namespace Aimsinfosoft\AutoApplyCouponCode\ViewModel;

class Cookie {

	/**
	 * @var \Magento\Framework\Stdlib\CookieManagerInterface
	 */
	private $cookieManager;

	/**
	 * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
	 */
	private $cookieMetadataFactory;

	/**
	 * @var \Magento\Framework\Session\SessionManagerInterface
	 */
	private $sessionManager;

	/**
	 * @var \Aimsinfosoft\AutoApplyCouponCode\ViewModel\Config
	 */
	private $config;

	public function __construct(
		\Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
		\Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
		\Magento\Framework\Session\SessionManagerInterface $sessionManager,
		\Aimsinfosoft\AutoApplyCouponCode\ViewModel\Config $config
	) {
		$this->cookieManager = $cookieManager;
		$this->cookieMetadataFactory = $cookieMetadataFactory;
		$this->sessionManager = $sessionManager;
		$this->config = $config;
	}

	/**
	 * Gets the session's currently applied coupon code or an empty .
	 *
	 * @return string|null
	 */
	public function getCookie(): ?string {

		$value = $this->cookieManager->getCookie($this->config->getCookieName());
		return $value ? $value : null;
	}

	/**
	 * Sets the coupon code cookie so we can remember it in the current session.
	 *
	 * @param $value
	 *
	 * @return void
	 */
	public function setCookie($value): void {

		$cookieLifetime = $this->config->getCookieLifetime();
		$metadata = $this->cookieMetadataFactory->createPublicCookieMetadata();

		$metadata->setPath($this->sessionManager->getCookiePath());
		$metadata->setDomain($this->sessionManager->getCookieDomain());
		$metadata->setHttpOnly(false);

		if ($cookieLifetime > 0) {
			$metadata->setDuration($cookieLifetime);
		}

		$this->cookieManager->setPublicCookie(
			$this->config->getCookieName(),
			$value,
			$metadata
		);
	}

	/**
	 * Deletes the coupon code cookie.
	 *
	 * @return void
	 */
	public function deleteCookie(): void {

		$metadata = $this->cookieMetadataFactory->createPublicCookieMetadata();

		$metadata->setPath($this->sessionManager->getCookiePath());
		$metadata->setDomain($this->sessionManager->getCookieDomain());
		$metadata->setHttpOnly(false);

		$this->cookieManager->deleteCookie($this->config->getCookieName(), $metadata);
	}
}

