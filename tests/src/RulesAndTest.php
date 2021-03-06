<?php

/**
 * @file
 * Contains \Drupal\rules\Tests\RulesAndTest.
 */

namespace Drupal\rules\Tests;

use Drupal\rules\Plugin\RulesExpression\RulesAnd;

/**
 * Tests the rules AND condition plugin.
 */
class RulesAndTest extends RulesTestBase {

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => 'RulesAnd class tests',
      'description' => 'Test the RuleAnd class',
      'group' => 'Rules',
    ];
  }

  /**
   * Tests one condition.
   */
  public function testOneCondition() {
    // The method on the test condition must be called once.
    $this->trueCondition->expects($this->once())
      ->method('execute');

    // Create a test rule, we don't care about plugin information in the
    // constructor.
    $and = new RulesAnd([], 'test', []);
    $and->addCondition($this->trueCondition);
    $result = $and->execute();
    $this->assertTrue($result, 'Single condition returns TRUE.');
  }

  /**
   * Tests an empty AND.
   */
  public function testemptyAnd() {
    // Create a test rule, we don't care about plugin information in the
    // constructor.
    $and = new RulesAnd([], 'test', []);
    $result = $and->execute();
    $this->assertFalse($result, 'Empty AND returns FALSE.');
  }

  /**
   * Tests two true condition.
   */
  public function testTwoConditions() {
    // The method on the test condition must be called once.
    $this->trueCondition->expects($this->exactly(2))
      ->method('execute');

    // Create a test rule, we don't care about plugin information in the
    // constructor.
    $and = new RulesAnd([], 'test', []);
    $and->addCondition($this->trueCondition);
    $and->addCondition($this->trueCondition);
    $result = $and->execute();
    $this->assertTrue($result, 'Two conditions returns TRUE.');
  }

  /**
   * Tests two false conditions.
   */
  public function testTwoFalseConditions() {
    // The method on the test condition must be called once.
    $this->falseCondition->expects($this->once())
      ->method('execute');

    // Create a test rule, we don't care about plugin information in the
    // constructor.
    $and = new RulesAnd([], 'test', []);
    $and->addCondition($this->falseCondition);
    $and->addCondition($this->falseCondition);
    $result = $and->execute();
    $this->assertFalse($result, 'Two false conditions return FALSE.');
  }
}
