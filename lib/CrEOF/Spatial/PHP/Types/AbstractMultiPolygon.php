<?php
/**
 * Copyright (C) 2012 Derek J. Lambert
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CrEOF\Spatial\PHP\Types;

use CrEOF\Spatial\Exception\InvalidValueException;
use CrEOF\Spatial\PHP\Types\AbstractPoint;


/**
 * Abstract Polygon object for POLYGON spatial types
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
abstract class AbstractMultiPolygon extends AbstractGeometry
{
    /**
     * @var array[] $rings
     */
    protected $polygons = array();

    /**
     * @param AbstractLineString[]|array[] $rings
     * @param null|int                     $srid
     */
    public function __construct(array $polygons, $srid = null)
    {
        $this->setPolygons($polygons)
            ->setSrid($srid);
    }

    /**
     * @param AbstractLineString|array[] $polygon
     *
     * @return self
     */
    public function addPolygon($polygon)
    {
        $this->polygons[] = $this->validatePolygonValue($polygon);

        return $this;
    }

    /**
     * @return AbstractLineString[]
     */
    public function getPolygons()
    {
        $polygons = array();

        for ($i = 0; $i < count($this->polygons); $i++) {
            $polygons[] = $this->getPolygon($i);
        }

        return $polygons;
    }

    /**
     * @param int $index
     *
     * @return AbstractLineString
     */
    public function getPolygon($index)
    {
        if (-1 == $index) {
            $index = count($this->polygons) - 1;
        }

        $lineStringClass = $this->getNamespace() . '\Polygon';

        return new $lineStringClass($this->polygons[$index], $this->srid);
    }

    /**
     * @param AbstractLineString[] $rings
     *
     * @return self
     */
    public function setPolygons(array $polygons)
    {
        try
        {
        $this->polygons = $this->validateMultiPolygonValue($polygons);
        }
        catch(\Exception $e)
        {
            echo $e->getMessage();die;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::MULTIPOLYGON;
    }

    /**
     * @return array[]
     */
    public function toArray()
    {
        return $this->polygons;
    }
}