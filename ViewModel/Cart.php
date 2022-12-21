<?php


namespace Aimsinfosoft\AutoApplyCouponCode\ViewModel;

class Cart {

	/**
	 * @var \Magento\Quote\Api\CartRepositoryInterface
	 */
	private $quoteRepository;

	/**
	 * @var \Magento\Framework\Message\ManagerInterface
	 */
	private $messageManager;

	/**
	 * Constructor
	 *
	 * @param \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
	 * @param \Magento\Framework\Message\ManagerInterface $messageManager
	 */
	public function __construct(
		\Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
		\Magento\Framework\Message\ManagerInterface $messageManager
	) {
		$this->quoteRepository = $quoteRepository;
		$this->messageManager = $messageManager;
	}

	public function applyCoupon(\Magento\Quote\Model\Quote $quote, string $coupon): void {

		try {
			$quote->setCouponCode($coupon);
			$this->quoteRepository->save($quote->collectTotals());
		} catch (LocalizedException $e) {
			$this->messageManager->addError(
				__("Discount code <strong>%1</strong> couldn't be applied: %2",$coupon,$e->getMessage())
			);
		} catch (\Exception $e) {
			$this->messageManager->addError(
				__("Discount code <strong>%1</strong> couldn't be applied or is invalid",$coupon)
			);
		}

		if ($quote->getCouponCode() != $coupon) {
			$this->messageManager->addError(
				__("Discount code <strong>%1</strong> is invalid. Verify that it's correct and try again.",$coupon)
			);
		}
	}
}

