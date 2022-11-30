<?php
/**
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version //autogentag//
 * @filesource
 * @package Url
 * @subpackage Tests
 */

include_once( 'data/delayed_init_configuration.php' );

/**
 * @package Url
 * @subpackage Tests
 */
class ezcUrlConfigurationTest extends ezcTestCase
{
    public function testPropertiesGet()
    {
        $urlCfg = new ezcUrlConfiguration();
        $this->assertEquals( null, $urlCfg->basedir );
        $this->assertEquals( null, $urlCfg->script );
        $this->assertEquals( array(), $urlCfg->orderedParameters );
        $this->assertEquals( array(), $urlCfg->unorderedParameters );
        $this->assertEquals( array( '(', ')' ), $urlCfg->unorderedDelimiters );
    }

    public function testPropertiesGetInvalid()
    {
        $urlCfg = new ezcUrlConfiguration();
        try
        {
            $urlCfg->no_such_property;
            $this->fail( 'Expected exception was not thrown' );
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            $expected = "No such property name 'no_such_property'.";
            $this->assertEquals( $expected, $e->getMessage() );
        }
    }

    public function testPropertiesSet()
    {
        $urlCfg = new ezcUrlConfiguration();
        $urlCfg->basedir = '/mydir/shop';
        $urlCfg->script = 'index.php';
        $urlCfg->unorderedDelimiters = array( '_', '_' );
        $urlCfg->addOrderedParameter( 'section' );
        $urlCfg->addOrderedParameter( 'module' );
        $urlCfg->addOrderedParameter( 'view' );
        $urlCfg->addOrderedParameter( 'branch' );
        $urlCfg->addUnorderedParameter( 'file' );

        $this->assertEquals( '/mydir/shop', $urlCfg->basedir );
        $this->assertEquals( 'index.php', $urlCfg->script );
        $this->assertEquals( array( 'section' => 0, 'module' => 1, 'view' => 2, 'branch' => 3 ),
                             $urlCfg->orderedParameters );
        $this->assertEquals( array( 'file' => 1 ), $urlCfg->unorderedParameters );
        $this->assertEquals( array( '_', '_' ), $urlCfg->unorderedDelimiters );
    }

    public function testPropertiesSetInvalid()
    {
        $urlCfg = new ezcUrlConfiguration();
        try
        {
            $urlCfg->no_such_property = 'some value';
            $this->fail( 'Expected exception was not thrown' );
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            $expected = "No such property name 'no_such_property'.";
            $this->assertEquals( $expected, $e->getMessage() );
        }
    }

    public function testAddOrderedParameter()
    {
        $urlCfg = new ezcUrlConfiguration();
        $urlCfg->addOrderedParameter( 'folder' );
    }

    public function testAddUnorderedParameter()
    {
        $urlCfg = new ezcUrlConfiguration();
        $urlCfg->addUnorderedParameter( 'folder' );
    }

    public function testIsSet()
    {
        $urlCfg = new ezcUrlConfiguration();
        $this->assertEquals( false, isset( $urlCfg->basedir ) );
        $this->assertEquals( false, isset( $urlCfg->script ) );
        $this->assertEquals( true, isset( $urlCfg->unorderedDelimiters ) );
        $this->assertEquals( true, isset( $urlCfg->orderedParameters ) );
        $this->assertEquals( true, isset( $urlCfg->unorderedParameters ) );
        $this->assertEquals( false, isset( $urlCfg->no_such_property ) );
    }

    public function testDelayedInit()
    {
        ezcBaseInit::setCallback( 'ezcUrlConfiguration', 'testDelayedInitUrlConfiguration' );
        $urlCfg = ezcUrlConfiguration::getInstance();
        $this->assertEquals( array( 'section' => 0 ), $urlCfg->orderedParameters );
        $this->assertEquals( array( 'article' => 1 ), $urlCfg->unorderedParameters );
    }

    public static function suite()
    {
         return new PHPUnit\Framework\TestSuite( "ezcUrlConfigurationTest" );
    }
}
?>
