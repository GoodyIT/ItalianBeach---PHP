<?php

namespace backend\components;
class ClassWorker
{
    public function setUp()
    {
        # Set up environment for this job
    }

    public function perform()
    {

        echo "Hello World";
        # Run task
    }

    public function tearDown()
    {
        # Remove environment for this job
    }
}