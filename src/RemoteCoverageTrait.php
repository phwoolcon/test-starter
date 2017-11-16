<?php

namespace Phwoolcon\TestStarter;

trait RemoteCoverageTrait
{

    public function appendRemoteCoverage()
    {
        foreach ($this->getRemoteCoverageFiles() as $coverageFile) {
            $this->getTestResultObject()->getCodeCoverage()->append(include $coverageFile);
            unlink($coverageFile);
        }
    }

    public function generateRemoteCoverageFile()
    {
        return tempnam(storagePath('remote-coverage'), 'cov-' . time() . '-');
    }

    public function getRemoteCoverageFiles()
    {
        return glob(storagePath('remote-coverage/cov-*'));
    }

    public function writeRemoteCoverage()
    {
        $coverage = $this->getTestResultObject()->getCodeCoverage();
        $coverage->stop();
        $data = $coverage->getData(true);
        foreach ($data as $file => &$lines) {
            foreach ($lines as $line => &$executed) {
                $executed and $executed = 1;
            }
            unset($executed);
        }
        unset($lines);
        fileSaveArray($this->generateRemoteCoverageFile(), $data);
    }
}
