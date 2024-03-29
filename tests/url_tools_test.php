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

/**
 * @package Url
 * @subpackage Tests
 */
class ezcUrlToolsTest extends ezcTestCase
{
    protected static $queriesParseStr = array( // original URL, parse result, http_build_query() result

        array( '',                              array(),                                                     '' ),
        array( 'foo',                           array( 'foo'    => null ),                                    'foo=' ),

        array( 'foo=bar',                       array( 'foo'    => 'bar' ),                                   'foo=bar' ),
        array( 'foo[]=bar',                     array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'foo[][]=bar',                   array( 'foo'    => array( array( 'bar' ) ) ),                 'foo[0][0]=bar' ),
        array( 'foo[][][]=bar',                 array( 'foo'    => array( array( array( 'bar' ) ) ) ),        'foo[0][0][0]=bar' ),

        array( 'foo[][]=bar&foo=baz',           array( 'foo'    => 'baz' ),                                   'foo=baz' ),
        array( 'foo[][]=bar&foo[]=baz',         array( 'foo'    => array( array( 'bar' ), 'baz' ) ),          'foo[0][0]=bar&foo[1]=baz' ),
        array( 'foo[]=bar&foo[][]=baz',         array( 'foo'    => array( 'bar', array( 'baz' ) ) ),          'foo[0]=bar&foo[1][0]=baz' ),
        array( 'foo[][]=bar&foo[][]=baz',       array( 'foo'    => array( array( 'bar' ), array( 'baz' ) ) ), 'foo[0][0]=bar&foo[1][0]=baz' ),
        array( 'foo=bar&answer=42',             array( 'foo'    => 'bar', 'answer' => '42' ),                 'foo=bar&answer=42' ),
        array( 'foo[]=bar&answer=42',           array( 'foo'    => array( 'bar' ), 'answer' => '42' ),        'foo[0]=bar&answer=42' ),
        array( 'foo[]=bar&answer=42&foo[]=baz', array( 'foo'    => array( 'bar', 'baz' ), 'answer' => '42' ), 'foo[0]=bar&foo[1]=baz&answer=42' ),

        array( 'foo=bar&amp;answer=42',         array( 'foo'    => 'bar', 'amp;answer' => '42' ),             'foo=bar&amp;answer=42' ),

        array( 'foo[0]=bar',                    array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'foo[1]=bar',                    array( 'foo'    => array( 1 => 'bar' ) ),                     'foo[1]=bar' ),
        array( 'foo[0]=bar&foo[0]=baz',         array( 'foo'    => array( 'baz' ) ),                          'foo[0]=baz' ),
        array( 'foo[0][0]=bar&foo[0]=baz',      array( 'foo'    => array( 'baz' ) ),                          'foo[0]=baz' ),

        array( 'foo=ba+r',                      array( 'foo'    => 'ba r' ),                                  'foo=ba r' ),
        array( 'foo=ba%20r',                    array( 'foo'    => 'ba r' ),                                  'foo=ba r' ),
        array( 'foo=ba r',                      array( 'foo'    => 'ba r' ),                                  'foo=ba r' ),
        array( 'foo=ba.r',                      array( 'foo'    => 'ba.r' ),                                  'foo=ba.r' ),

        array( 'fo.o=bar',                      array( 'fo_o'   => 'bar' ),                                   'fo_o=bar' ),
        array( 'fo.o[]=bar',                    array( 'fo_o'   => array( 'bar' ) ),                          'fo_o[0]=bar' ),
        array( 'fo_o=bar',                      array( 'fo_o'   => 'bar' ),                                   'fo_o=bar' ),
        array( 'f._o=bar',                      array( 'f__o'   => 'bar' ),                                   'f__o=bar' ),
        array( 'fo_o[]=bar',                    array( 'fo_o'   => array( 'bar' ) ),                          'fo_o[0]=bar' ),
        array( 'fo:o=bar',                      array( 'fo:o'   => 'bar' ),                                   'fo:o=bar' ),
        array( 'fo;o=bar',                      array( 'fo;o'   => 'bar' ),                                   'fo;o=bar' ),
        array( 'foo()=bar',                     array( 'foo()'  => 'bar' ),                                   'foo()=bar' ),
        array( 'foo{}=bar',                     array( 'foo{}'  => 'bar' ),                                   'foo{}=bar' ),

        array( 'fo.o=bar&answer=42',            array( 'fo_o'   => 'bar', 'answer' => 42 ),                   'fo_o=bar&answer=42' ),

        array( 'foo[=bar',                      array( 'foo_'   => 'bar' ),                                   'foo_=bar' ),
        array( 'foo[[=bar',                     array( 'foo__'  => 'bar' ),                                   'foo__=bar' ),
        array( 'foo]=bar',                      array( 'foo]'   => 'bar' ),                                   'foo]=bar' ),
        array( 'foo]]=bar',                     array( 'foo]]'  => 'bar' ),                                   'foo]]=bar' ),
        array( 'foo][=bar',                     array( 'foo]_'  => 'bar' ),                                   'foo]_=bar' ),
        array( 'foo[[]=bar',                    array( 'foo'    => array( '[' => 'bar' ) ),                   'foo[[]=bar' ),
        array( 'foo][]=bar',                    array( 'foo]'   => array( 'bar' ) ),                          'foo][0]=bar' ),
        array( 'foo[][=bar',                    array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'foo[]]=bar',                    array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'foo][[=bar',                    array( 'foo]__' => 'bar' ),                                   'foo]__=bar' ),

        array( 'fo[o=bar',                      array( 'fo_o'   => 'bar' ),                                   'fo_o=bar' ),
        array( 'fo[[o=bar',                     array( 'fo__o'  => 'bar' ),                                   'fo__o=bar' ),
        array( 'fo]o=bar',                      array( 'fo]o'   => 'bar' ),                                   'fo]o=bar' ),
        array( 'fo]]o=bar',                     array( 'fo]]o'  => 'bar' ),                                   'fo]]o=bar' ),
        array( 'fo][o=bar',                     array( 'fo]_o'  => 'bar' ),                                   'fo]_o=bar' ),
        array( 'foo[[]o=bar',                   array( 'foo'    => array( '[' => 'bar' ) ),                   'foo[[]=bar' ),
        array( 'foo][]o=bar',                   array( 'foo]'   => array( 'bar' ) ),                          'foo][0]=bar' ),
        array( 'foo[][o=bar',                   array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'foo[]]o=bar',                   array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'fo[]o=bar',                     array( 'fo'     => array( 'bar' ) ),                          'fo[0]=bar' ),
        array( 'fo][[o=bar',                    array( 'fo]__o' => 'bar' ),                                   'fo]__o=bar' ),

        array( 'foo[[0]o=bar',                  array( 'foo'    => array( '[0' => 'bar' ) ),                  'foo[[0]=bar' ),
        array( 'foo][0]o=bar',                  array( 'foo]'   => array( 'bar' ) ),                          'foo][0]=bar' ),
        array( 'foo[0][o=bar',                  array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'foo[0]]o=bar',                  array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'fo[0]o=bar',                    array( 'fo'     => array( 'bar' ) ),                          'fo[0]=bar' ),
        );

    protected static $queriesParseQueryString = array( // original URL, parse result, http_build_query() result

        array( '',                              array(),                                                     '' ),
        array( 'foo',                           array( 'foo'    => null ),                                    'foo=' ),

        array( 'foo=bar',                       array( 'foo'    => 'bar' ),                                   'foo=bar' ),
        array( 'foo[]=bar',                     array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'foo[][]=bar',                   array( 'foo'    => array( array( 'bar' ) ) ),                 'foo[0][0]=bar' ),
        array( 'foo[][][]=bar',                 array( 'foo'    => array( array( array( 'bar' ) ) ) ),        'foo[0][0][0]=bar' ),

        array( 'foo[][]=bar&foo=baz',           array( 'foo'    => 'baz' ),                                   'foo=baz' ),
        array( 'foo[][]=bar&foo[]=baz',         array( 'foo'    => array( array( 'bar' ), 'baz' ) ),          'foo[0][0]=bar&foo[1]=baz' ),
        array( 'foo[]=bar&foo[][]=baz',         array( 'foo'    => array( 'bar', array( 'baz' ) ) ),          'foo[0]=bar&foo[1][0]=baz' ),
        array( 'foo[][]=bar&foo[][]=baz',       array( 'foo'    => array( array( 'bar' ), array( 'baz' ) ) ), 'foo[0][0]=bar&foo[1][0]=baz' ),
        array( 'foo=bar&answer=42',             array( 'foo'    => 'bar', 'answer' => '42' ),                 'foo=bar&answer=42' ),
        array( 'foo[]=bar&answer=42',           array( 'foo'    => array( 'bar' ), 'answer' => '42' ),        'foo[0]=bar&answer=42' ),
        array( 'foo[]=bar&answer=42&foo[]=baz', array( 'foo'    => array( 'bar', 'baz' ), 'answer' => '42' ), 'foo[0]=bar&foo[1]=baz&answer=42' ),

        array( 'foo=bar&amp;answer=42',         array( 'foo'    => 'bar', 'amp;answer' => '42' ),             'foo=bar&amp;answer=42' ),

        array( 'foo[0]=bar',                    array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'foo[1]=bar',                    array( 'foo'    => array( 1 => 'bar' ) ),                     'foo[1]=bar' ),
        array( 'foo[0]=bar&foo[0]=baz',         array( 'foo'    => array( 'baz' ) ),                          'foo[0]=baz' ),
        array( 'foo[0][0]=bar&foo[0]=baz',      array( 'foo'    => array( 'baz' ) ),                          'foo[0]=baz' ),

        array( 'foo=ba+r',                      array( 'foo'    => 'ba r' ),                                  'foo=ba r' ),
        array( 'foo=ba%20r',                    array( 'foo'    => 'ba r' ),                                  'foo=ba r' ),
        array( 'foo=ba r',                      array( 'foo'    => 'ba r' ),                                  'foo=ba r' ),
        array( 'foo=ba.r',                      array( 'foo'    => 'ba.r' ),                                  'foo=ba.r' ),

        array( 'fo.o=bar',                      array( 'fo.o'   => 'bar' ),                                   'fo.o=bar' ),
        array( 'fo.o[]=bar',                    array( 'fo.o'   => array( 'bar' ) ),                          'fo.o[0]=bar' ),
        array( 'fo_o=bar',                      array( 'fo_o'   => 'bar' ),                                   'fo_o=bar' ),
        array( 'f._o=bar',                      array( 'f._o'   => 'bar' ),                                   'f._o=bar' ),
        array( 'fo_o[]=bar',                    array( 'fo_o'   => array( 'bar' ) ),                          'fo_o[0]=bar' ),
        array( 'fo:o=bar',                      array( 'fo:o'   => 'bar' ),                                   'fo:o=bar' ),
        array( 'fo;o=bar',                      array( 'fo;o'   => 'bar' ),                                   'fo;o=bar' ),
        array( 'foo()=bar',                     array( 'foo()'  => 'bar' ),                                   'foo()=bar' ),
        array( 'foo{}=bar',                     array( 'foo{}'  => 'bar' ),                                   'foo{}=bar' ),

        array( 'fo.o=bar&answer=42',            array( 'fo.o'   => 'bar', 'answer' => 42 ),                   'fo.o=bar&answer=42' ),

        array( 'foo[=bar',                      array( 'foo_'   => 'bar' ),                                   'foo_=bar' ),
        array( 'foo[[=bar',                     array( 'foo__'  => 'bar' ),                                   'foo__=bar' ),
        array( 'foo]=bar',                      array( 'foo]'   => 'bar' ),                                   'foo]=bar' ),
        array( 'foo]]=bar',                     array( 'foo]]'  => 'bar' ),                                   'foo]]=bar' ),
        array( 'foo][=bar',                     array( 'foo]_'  => 'bar' ),                                   'foo]_=bar' ),
        array( 'foo[[]=bar',                    array( 'foo'    => array( '[' => 'bar' ) ),                   'foo[[]=bar' ),
        array( 'foo][]=bar',                    array( 'foo]'   => array( 'bar' ) ),                          'foo][0]=bar' ),
        array( 'foo[][=bar',                    array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'foo[]]=bar',                    array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'foo][[=bar',                    array( 'foo]__' => 'bar' ),                                   'foo]__=bar' ),

        array( 'fo[o=bar',                      array( 'fo_o'   => 'bar' ),                                   'fo_o=bar' ),
        array( 'fo[[o=bar',                     array( 'fo__o'  => 'bar' ),                                   'fo__o=bar' ),
        array( 'fo]o=bar',                      array( 'fo]o'   => 'bar' ),                                   'fo]o=bar' ),
        array( 'fo]]o=bar',                     array( 'fo]]o'  => 'bar' ),                                   'fo]]o=bar' ),
        array( 'fo][o=bar',                     array( 'fo]_o'  => 'bar' ),                                   'fo]_o=bar' ),
        array( 'foo[[]o=bar',                   array( 'foo'    => array( '[' => 'bar' ) ),                   'foo[[]=bar' ),
        array( 'foo][]o=bar',                   array( 'foo]'   => array( 'bar' ) ),                          'foo][0]=bar' ),
        array( 'foo[][o=bar',                   array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'foo[]]o=bar',                   array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'fo[]o=bar',                     array( 'fo'     => array( 'bar' ) ),                          'fo[0]=bar' ),
        array( 'fo][[o=bar',                    array( 'fo]__o' => 'bar' ),                                   'fo]__o=bar' ),

        array( 'foo[[0]o=bar',                  array( 'foo'    => array( '[0' => 'bar' ) ),                  'foo[[0]=bar' ),
        array( 'foo][0]o=bar',                  array( 'foo]'   => array( 'bar' ) ),                          'foo][0]=bar' ),
        array( 'foo[0][o=bar',                  array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'foo[0]]o=bar',                  array( 'foo'    => array( 'bar' ) ),                          'foo[0]=bar' ),
        array( 'fo[0]o=bar',                    array( 'fo'     => array( 'bar' ) ),                          'fo[0]=bar' ),
        );

    protected static $serverValues = array( // HTTPS, SERVER_NAME, SERVER_PORT, REQUEST_URI, constructed URL
        array( array( null, 'www.example.com', 80,   '/index.php',               'http://www.example.com/index.php' ) ),
        array( array( '1',  'www.example.com', 80,   '/index.php',               'https://www.example.com/index.php' ) ),
        array( array( 'on', 'www.example.com', 80,   '/index.php',               'https://www.example.com/index.php' ) ),

        array( array( null, 'www.example.com', 443,  '/index.php',               'http://www.example.com:443/index.php' ) ),
        array( array( '1',  'www.example.com', 443,  '/index.php',               'https://www.example.com:443/index.php' ) ),
        array( array( 'on', 'www.example.com', 443,  '/index.php',               'https://www.example.com:443/index.php' ) ),

        array( array( null, 'www.example.com', 80,   '',                         'http://www.example.com' ) ),
        array( array( null, 'www.example.com', 80,   '/',                        'http://www.example.com/' ) ),
        array( array( null, 'www.example.com', 80,   '/mydir/index.php',         'http://www.example.com/mydir/index.php' ) ),
        array( array( null, 'www.example.com', 80,   '/mydir/index.php/content', 'http://www.example.com/mydir/index.php/content' ) ),

        array( array( null, 'www.example.com', 80,   '/index.php?',              'http://www.example.com/index.php?' ) ),
        array( array( null, 'www.example.com', 80,   '/index.php?foo=bar',       'http://www.example.com/index.php?foo=bar' ) ),
        array( array( null, 'www.example.com', 80,   '/index.php?foo=bar#p1',    'http://www.example.com/index.php?foo=bar#p1' ) ),

        array( array( null, null,              null, null,                       'http://' ) ),
        array( array( 'on', null,              null, null,                       'https://' ) ),
        array( array( null, 'www.example.com', null, null,                       'http://www.example.com' ) ),
        array( array( null, 'www.example.com', 81,   null,                       'http://www.example.com:81' ) ),
        array( array( null, null,              81,   null,                       'http://:81' ) ),
        array( array( null, null,              81,   '/',                        'http://:81/' ) ),
        array( array( null, null,              null, '/',                        'http:///' ) ),
        array( array( null, null,              80,   '/',                        'http:///' ) ),
        array( array( true, null,              80,   '/',                        'http:///' ) ),
        );

    // the order of fields in self::$serverValues
    protected static $serverMapping = array( 'HTTPS', 'SERVER_NAME', 'SERVER_PORT', 'REQUEST_URI' );

    public static function suite()
    {
        return new PHPUnit\Framework\TestSuite( __CLASS__ );
    }

    public static function getQueriesParseStr()
    {
        return self::$queriesParseStr;
    }

    public static function getQueriesParseQueryString()
    {
        return self::$queriesParseQueryString;
    }

    public static function getServerValues()
    {
        return self::$serverValues;
    }

    /**
     * @dataProvider getQueriesParseStr
     */
    public function testParseStr( $query0, $query1, $query2 )
    {
        if ( PHP_VERSION_ID < 80000 )
        {
            $this->markTestSkipped( "PHP < 8.0 has different behaviour" );
        }

        parse_str( $query0, $params );

        $this->assertEquals( $query1, $params, "Failed parsing '{$query0}'" );
        $this->assertEquals( $query2, urldecode( http_build_query( $params ) ), "Failed building back the query '{$query0}' to '{$query2}'" );
    }

    /**
     * @dataProvider getQueriesParseQueryString
     */
    public function testParseQueryString( $query0, $query1, $query2 )
    {
        if ( PHP_VERSION_ID < 80000 )
        {
            $this->markTestSkipped( "PHP < 8.0 has different behaviour" );
        }

        $params = ezcUrlTools::parseQueryString( $query0 );

        $this->assertEquals( $query1, $params, "Failed parsing '{$query0}'" );
        $this->assertEquals( $query2, urldecode( http_build_query( $params ) ), "Failed building back the query '{$query0}' to '{$query2}'" );
    }

    /**
     * @dataProvider getServerValues
     */
    public function testGetCurrentUrlServer( $data )
    {
        $oldSERVER = $_SERVER;
        $_SERVER = array();

        foreach ( self::$serverMapping as $key => $mapping )
        {
            if ( $data[$key] !== null )
            {
                $_SERVER[$mapping] = $data[$key];
            }
        }

        $expected = $data[4];
        $returned = ezcUrlTools::getCurrentUrl();
        $_SERVER = $oldSERVER;
        $this->assertEquals( $expected, $returned, "Failed building URL " . $data[4] );
    }

    /**
     * @dataProvider getServerValues
     */
    public function testGetCurrentUrlOtherSource( $data )
    {
        $source = array();

        foreach ( self::$serverMapping as $key => $mapping )
        {
            if ( $data[$key] !== null )
            {
                $source[$mapping] = $data[$key];
            }
        }

        $expected = $data[4];

        $this->assertEquals( $expected, ezcUrlTools::getCurrentUrl( $source ), "Failed building URL " . $data[4] );
    }
}
?>
