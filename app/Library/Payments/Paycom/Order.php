<?php

namespace App\Library\Payments\Paycom;

use App\Models\Order as ModelsOrder;
use Illuminate\Support\Facades\Auth;

/**
 * Class Order
 *
 * Example MySQL table might look like to the following:
 *
 * CREATE TABLE orders
 * (
 *     id          INT AUTO_INCREMENT PRIMARY KEY,
 *     product_ids VARCHAR(255)   NOT NULL,
 *     amount      DECIMAL(18, 2) NOT NULL,
 *     state       TINYINT(1)     NOT NULL,
 *     user_id     INT            NOT NULL,
 *     phone       VARCHAR(15)    NOT NULL
 * ) ENGINE = InnoDB;
 *
 */
class Order
{
    /** Order is available for sell, anyone can buy it. */
    const STATE_AVAILABLE = 0;

    /** Pay in progress, order must not be changed. */
    const STATE_WAITING_PAY = 'unpaid';

    /** Order completed and not available for sell. */
    const STATE_PAY_ACCEPTED = 'paid';

    /** Order is cancelled. */
    const STATE_CANCELLED = 'canceled';

    public $request_id;
    public $params;

    // todo: Adjust Order specific fields for your needs

    /**
     * Order ID
     */
    public $id;

    /**
     * IDs of the selected products/services
     */
    public $product_ids;

    /**
     * Total price of the selected products/services
     */
    public $amount;

    /**
     * State of the order
     */
    public $state;

    /**
     * ID of the customer created the order
     */
    public $user_id;

    /**
     * Phone number of the user
     */
    public $phone;

    public function __construct($request_id)
    {
        $this->request_id = $request_id;
    }

    /**
     * Validates amount and account values.
     * @param array $params amount and account parameters to validate.
     * @return bool true - if validation passes
     * @throws PaycomException - if validation fails
     */
    public function validate(array $params)
    {
        // todo: Validate amount, if failed throw error
        // for example, check amount is numeric
        if (!is_numeric($params['amount'])) {
            throw new PaycomException(
                $this->request_id,
                'Incorrect amount.',
                PaycomException::ERROR_INVALID_AMOUNT
            );
        }

        // todo: Validate account, if failed throw error
        // assume, we should have order_id
        if (!isset($params['account']['order_id']) || !$params['account']['order_id']) {
            throw new PaycomException(
                $this->request_id,
                PaycomException::message(
                    'Неверный код заказа.',
                    'Harid kodida xatolik.',
                    'Incorrect order code.'
                ),
                PaycomException::ERROR_INVALID_ACCOUNT,
                'order_id'
            );
        }

        // todo: Check is order available

        // assume, after find() $this will be populated with Order data
        $order = $this->find($params['account']);

        // Check, is order found by specified order_id
        if (!$order || !$order->id) {
            throw new PaycomException(
                $this->request_id,
                PaycomException::message(
                    'Неверный код заказа.',
                    'Harid kodida xatolik.',
                    'Incorrect order code.'
                ),
                PaycomException::ERROR_INVALID_ACCOUNT,
                'order_id'
            );
        }

        // validate amount
        // convert $this->amount to coins
        // $params['amount'] already in coins
        if ((100 * $this->amount) != (1 * $params['amount'])) {
            throw new PaycomException(
                $this->request_id,
                'Incorrect amount.',
                PaycomException::ERROR_INVALID_AMOUNT
            );
        }
        
        // for example, order state before payment should be 'waiting pay'
        if ($this->state != self::STATE_WAITING_PAY) {
            throw new PaycomException(
                $this->request_id,
                'Order state is invalid.',
                PaycomException::ERROR_COULD_NOT_PERFORM
            );
        }

        // keep params for further use
        $this->params = $params;

        return true;
    }

    /**
     * Find order by given parameters.
     * @param mixed $params parameters.
     * @return Order|Order[] found order or array of orders.
     */
    public function find($params)
    {
        // todo: Implement searching order(s) by given parameters, populate current instance with data

        // Example implementation to load order by id
        if (isset($params['order_id'])) {

            // $sql        = "select * from orders where id=:orderId";
            // $sth        = self::db()->prepare($sql);
            // $is_success = $sth->execute([':orderId' => $params['order_id']]);
            $order = ModelsOrder::find($params['order_id']);

            if ($order) {
                $this->id          = $order->id;
                $this->amount      = $order->grand_total;
                $this->product_ids = $order->orderDetails->pluck('id');
                $this->state       = $order->payment_status;
                $this->user_id     = $order->user_id;
                $this->phone       = $order->user->phone;

                return $this;
            }

        }

        return null;
    }

    /**
     * Change order's state to specified one.
     * @param int $state new state of the order
     * @return void
     */
    public function changeState($state)
    {
        // todo: Implement changing order state (reserve order after create transaction or free order after cancel)
        // Example implementation
        $this->state = $state;

        $this->save();
    }

    /**
     * Check, whether order can be cancelled or not.
     * @return bool true - order is cancellable, otherwise false.
     */
    public function allowCancel()
    {
        // todo: Implement order cancelling allowance check

        // Example implementation
        return false; // do not allow cancellation
    }

    /**
     * Saves this order.
     * @throws PaycomException
     */
    public function save()
    {
        if (!$this->id) {

            // If new order, set its state to waiting
            $this->state = self::STATE_WAITING_PAY;

            // todo: Set customer ID
            // $this->user_id = 1 * SomeSessionManager::get('user_id');
            $is_success = ModelsOrder::create([
                'first_last' => Auth::id() ? Auth::user()->lastname . ' ' . Auth::user()->firstname : 'unknown',
                'product_ids' => $this->product_ids,
                'status' => $this->state,
                'amount' => $this->amount,
                'payment_type' => 'payme',
                'user_id' => $this->user_id ?? null,
                'phone' => $this->phone,
            ]);

            if ($is_success) {
                $this->id = $is_success->id;
            }
        } else {
            $is_success = ModelsOrder::find($this->id)->update(['payment_status' => $this->state]);
        }

        if (!$is_success) {
            throw new PaycomException($this->request_id, 'Could not save order.', PaycomException::ERROR_INTERNAL_SYSTEM);
        }
    }
}