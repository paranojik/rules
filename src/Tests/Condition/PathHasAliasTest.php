<?php

/**
 * @file
 * Contains \Drupal\rules\Tests\Condition\PathHasAliasTest.
 */

namespace Drupal\rules\Tests\Condition;

use Drupal\Core\Path;
use Drupal\Core\Database\Database;
use Drupal\system\Tests\Path\PathUnitTestBase;

/**
 * Tests the 'Path has alias' condition.
 */
class PathHasAliasTest extends PathUnitTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['system', 'rules', 'path'];

  /**
   * The condition manager.
   *
   * @var \Drupal\Core\Condition\ConditionManager
   */
  protected $conditionManager;

  /**
   * The alias manager.
   *
   * @var \Drupal\Core\Path\AliasStorageInterface;
   */
  protected $aliasStorage;

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => 'Path has alias condition test',
      'description' => 'Tests the condition.',
      'group' => 'Rules conditions',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->conditionManager = $this->container->get('plugin.manager.condition', $this->container->get('container.namespaces'));
    $this->aliasStorage = $this->container->get('path.alias_storage');
    $this->fixtures->createTables(Database::getConnection());
  }

  /**
   * Tests evaluating the condition.
   */
  public function testConditionEvaluation() {
    $this->aliasStorage->save('original', 'alias');
    $this->aliasStorage->save('english-original', 'english-alias', 'en');

    //
    $condition = $this->conditionManager->createInstance('rules_path_has_alias')
      ->setContextValue('path', 'notexisting');
    $this->assertFalse($condition->execute());

    //
    $condition = $this->conditionManager->createInstance('rules_path_has_alias')
      ->setContextValue('path', 'english-notexisting')
      ->setContextValue('language', 'en');
    $this->assertFalse($condition->execute());

    //
    $condition = $this->conditionManager->createInstance('rules_path_has_alias')
      ->setContextValue('path', 'original');
    $this->assertTrue($condition->execute());

    //
    $condition = $this->conditionManager->createInstance('rules_path_has_alias')
      ->setContextValue('path', 'english-original')
      ->setContextValue('language', 'en');
    $this->assertTrue($condition->execute());
  }

}
