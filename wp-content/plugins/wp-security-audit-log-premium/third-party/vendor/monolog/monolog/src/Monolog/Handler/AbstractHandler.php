<?php

declare (strict_types=1);
/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WSAL_Vendor\Monolog\Handler;

use WSAL_Vendor\Monolog\Logger;
use WSAL_Vendor\Monolog\ResettableInterface;
/**
 * Base Handler class providing basic level/bubble support
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
abstract class AbstractHandler extends \WSAL_Vendor\Monolog\Handler\Handler implements \WSAL_Vendor\Monolog\ResettableInterface
{
    protected $level = \WSAL_Vendor\Monolog\Logger::DEBUG;
    protected $bubble = \true;
    /**
     * @param int|string $level  The minimum logging level at which this handler will be triggered
     * @param bool       $bubble Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct($level = \WSAL_Vendor\Monolog\Logger::DEBUG, bool $bubble = \true)
    {
        $this->setLevel($level);
        $this->bubble = $bubble;
    }
    /**
     * {@inheritdoc}
     */
    public function isHandling(array $record) : bool
    {
        return $record['level'] >= $this->level;
    }
    /**
     * Sets minimum logging level at which this handler will be triggered.
     *
     * @param  int|string $level Level or level name
     * @return self
     */
    public function setLevel($level) : self
    {
        $this->level = \WSAL_Vendor\Monolog\Logger::toMonologLevel($level);
        return $this;
    }
    /**
     * Gets minimum logging level at which this handler will be triggered.
     *
     * @return int
     */
    public function getLevel() : int
    {
        return $this->level;
    }
    /**
     * Sets the bubbling behavior.
     *
     * @param  bool $bubble true means that this handler allows bubbling.
     *                      false means that bubbling is not permitted.
     * @return self
     */
    public function setBubble(bool $bubble) : self
    {
        $this->bubble = $bubble;
        return $this;
    }
    /**
     * Gets the bubbling behavior.
     *
     * @return bool true means that this handler allows bubbling.
     *              false means that bubbling is not permitted.
     */
    public function getBubble() : bool
    {
        return $this->bubble;
    }
    public function reset()
    {
    }
}
