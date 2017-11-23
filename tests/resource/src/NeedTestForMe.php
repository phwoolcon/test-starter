<?php

namespace Phwoolcon\TestStarter\Foo;

use Phwoolcon\Queue\Adapter\Beanstalkd\Job;

class NeedTestForMe extends Job implements SkipInterface
{
    use SkipTrait;

    public function __construct()
    {
    }

    protected function invisibleMethodWillBeSkip()
    {
        if (0) {
            $this->invisibleMethodWillBeSkip();
        }
    }

    public function visibleMethod()
    {
        return 'Please generate test case for me';
    }
}
