<?php
/**
 * This file is part of the mukadi/core-wallet-manager
 * (c) 2019 Genius Conception
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Mukadi\Wallet\Core\Expression;
/**
 * Class Proxy.
 * 
 * @author Olivier M. Mukadi <olivier.m@geniusconception.com>
 */
class Proxy  
{
    /** @var object */
    private $subject;

    public function __construct($subject) {
        $this->subject = $subject;
    }

    public  function __get($name) {
        $getter = "get".ucfirst($name);
        if(method_exists($this->subject, $getter)) {
            return $this->subject->$getter();
        }
        else
            throw new \BadMethodCallException(
                "Undefined method '$getter'"
            );
    }

    public function __set($name, $value) {
        $setter = "set".ucfirst($name);
        if(method_exists($this->subject, $setter)) {
            $this->subject->$setter($value);
        }
        else
            throw new \BadMethodCallException(
                "Undefined method '$setter'"
            );
    }
}
