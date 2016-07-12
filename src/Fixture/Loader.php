<?php

namespace Magento\Bootstrap\Fixture;

use Magento\Bootstrap\Exception\FixtureNotFoundException;

class Loader
{
    /**
     * @param string $fixture
     *
     * @return string
     *
     * @throws FixtureNotFoundException
     */
    public function load($fixture)
    {
        $filename = __DIR__.'/../../app/fixtures/'.$fixture.'.json';

        if (!file_exists($filename)) {
            throw new FixtureNotFoundException($fixture);
        }

        return file_get_contents($filename);
    }
}
