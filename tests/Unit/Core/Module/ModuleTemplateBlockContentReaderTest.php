<?php
/**
 * This file is part of OXID eShop Community Edition.
 *
 * OXID eShop Community Edition is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OXID eShop Community Edition is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @link      http://www.oxid-esales.com
 * @copyright (C) OXID eSales AG 2003-2016
 * @version   OXID eShop CE
 */
namespace Unit\Core\Module;

use oxException;
use OxidEsales\TestingLibrary\UnitTestCase;
use OxidEsales\Eshop\Core\Module\ModuleTemplateBlockContentReader;
use OxidEsales\Eshop\Core\Module\ModuleTemplateBlockPathFormatter;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

class ModuleTemplateBlockContentReaderTest extends UnitTestCase
{
    public function testCanCreateClass()
    {
        oxNew(ModuleTemplateBlockContentReader::class);
    }

    public function testGetContentThrowExceptionWithoutPathFormatter()
    {
        $this->setExpectedException(oxException::class);

        $content = oxNew(ModuleTemplateBlockContentReader::class);
        $content->getContent(null);
    }

    public function testGetContentDoesNotThrowExceptionWhenValidArgumentProvided()
    {
        $pathFormatter = $this->mockFile();

        $content = oxNew(ModuleTemplateBlockContentReader::class);
        $content->getContent($pathFormatter);
    }

    public function providerGetContentReturnContentFromFileWhichWasProvided()
    {
        return [
            ['pathToFile', 'some content'],
            ['pathToFile', 'some other content'],
        ];
    }

    /**
     * @param $filePath
     * @param $content
     *
     * @dataProvider providerGetContentReturnContentFromFileWhichWasProvided
     */
    public function testGetContentReturnContentFromFileWhichWasProvided($filePath, $content)
    {
        $pathFormatter = $this->mockFile($filePath, $content);

        $contentGetter = oxNew(ModuleTemplateBlockContentReader::class);

        $this->assertSame($content, $contentGetter->getContent($pathFormatter));
    }

    public function testGetContentThrowsExceptionWhenFileDoesNotExist()
    {
        $vfsStreamWrapper = $this->getVfsStreamWrapper();

        $filePath = $vfsStreamWrapper->getRootPath() . DIRECTORY_SEPARATOR . 'someFile';

        $exceptionMessage = "Template block file (%s) was not found.";
        $this->setExpectedException(oxException::class, sprintf($exceptionMessage, $filePath));

        $content = oxNew(ModuleTemplateBlockContentReader::class);
        $content->getContent($filePath);
    }

    public function testGetContentThrowsExceptionWhenFileIsNotReadable()
    {
        $notReadableMode = 000;
        $filePath = $this->mockFile();
        chmod($filePath, $notReadableMode);

        $exceptionMessage = "Template block file (%s) is not readable.";
        $this->setExpectedException(oxException::class, sprintf($exceptionMessage, $filePath));

        $content = oxNew(ModuleTemplateBlockContentReader::class);
        $content->getContent($filePath);
    }

    /**
     * @param string $filePath
     * @param string $content that will be in that file
     *
     * @return string full path
     */
    private function mockFile($filePath = 'pathToFile', $content = 'someContent')
    {
        $vfsStreamWrapper = $this->getVfsStreamWrapper();
        $filePath = $vfsStreamWrapper->createFile($filePath, $content);

        return $filePath;
    }
}
