<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Bundle\CoreBundle\Validator\Constraints;

use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

final class OrderPaymentMethodEligibilityValidator extends ConstraintValidator
{
    /**
     * @throws \InvalidArgumentException
     */
    public function validate($order, Constraint $constraint): void
    {
        /** @var OrderInterface $order */
        Assert::isInstanceOf($order, OrderInterface::class);

        /** @var OrderPaymentMethodEligibility $constraint */
        Assert::isInstanceOf($constraint, OrderPaymentMethodEligibility::class);

        $payments = $order->getPayments();

        foreach ($payments as $payment) {
            if (!$payment->getMethod()->isEnabled()) {
                $this->context->addViolation(
                    $constraint->message,
                    ['%paymentMethodName%' => $payment->getMethod()->getName()]
                );
            }
        }
    }
}
