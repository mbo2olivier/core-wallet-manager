<?php
namespace Mukadi\Wallet\Core\Test;

use Mukadi\Wallet\Core\Instruction;
use PHPUnit\Framework\TestCase;

class InstructionTest extends TestCase
{
    

    public function testIs() {
        $i = new Instruction();
        $i->set(Instruction::AMOUNT_COMPUTED);

        $this->assertTrue($i->is(Instruction::AMOUNT_COMPUTED));
    }

    public function testConstructor() {
        $i = new Instruction(1,'', '', '', '', '', '', Instruction::AMOUNT_COMPUTED|Instruction::LABEL_COMPUTED);

        $this->assertTrue($i->is(Instruction::AMOUNT_COMPUTED));
        $this->assertFalse($i->is(Instruction::CURRENCY_COMPUTED));
    }
}